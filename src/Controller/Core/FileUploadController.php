<?php

namespace App\Controller\Core;

use Aws\S3\S3Client;
use App\Entity\Core\FileUpload;
use Aws\Credentials\Credentials;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileUploadController extends AbstractController
{
    private S3Client $s3Client;
    private $bucket;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    /**
     * Undocumented function
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface $params
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $params,
    ) {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => 'PARIS',
            'endpoint' => $params->get('oss118')['host'],
            'credentials' => new Credentials(
                $params->get('oss118')['access_key_id'],
                $params->get('oss118')['secret_access_key']
            ),
            'http' => ['verify' => false]
        ]);
        $this->bucket = $params->get('oss118')['bucket'];
    }

    /**
     * A function that collects the file uploaded and creates an entry for it in the DB and in the S3
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request)
    {
        if ($request->files->get('file') != null) {
            $requestFile = $request->files->get('file');
            $type = "image"; //for text editor
        } else {
            $requestFile = $request->files->get('filepond');
            $type = "fileupload"; //for file uploads
        }
        $fileName = hash("sha256", $requestFile->getClientOriginalName()) . '.'
            . $requestFile->getClientOriginalExtension();
        $file = new FileUpload();
        $file->setName($requestFile->getClientOriginalName());
        $file->setSize($requestFile->getSize());
        $file->setKeyName($fileName);
        $file->setType($type);
        $file->setRights("SAME");
        if (
            $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $fileName,
                'SourceFile'   => $requestFile->getPathname(),
            ])['@metadata']['statusCode'] === 200
        ) {
            $this->entityManager->persist($file);
            $this->entityManager->flush();
            $jsonContent = $this->serializer->serialize($file, 'json');
            return new JsonResponse($jsonContent, 200, [], true);
        } else {
            return new JsonResponse(null, 400, [], false);
        }
    }

    /**
     * A function that deletes the file uploaded from the DB and S3
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $fileuploadRepo = $this->entityManager->getRepository(FileUpload::class);
        $file = $fileuploadRepo->findOneBy(['id' => $request->get('fileId')]);
        if (
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $file->getKeyName(),
            ])['@metadata']['statusCode'] === 204
        ) {
            $jsonContent = $this->serializer->serialize($file, 'json');
            $this->entityManager->remove($file);
            $this->entityManager->flush();
            return new JsonResponse($jsonContent, 200, [], true);
        } else {
            return new JsonResponse(null, 400, [], false);
        }
    }

    /**
     * A function that deletes the file displayed on the list throught the api call from the DB and S3
     *
     * @param FileUpload $file
     * @return JsonResponse
     */
    public function deletion(FileUpload $file): JsonResponse
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $file->getKeyName(),
        ]);
        $jsonContent = $this->serializer->serialize($file, 'json');
        $this->entityManager->remove($file);
        $this->entityManager->flush();
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * A function that returns all the files in the DB
     *
     * @return JsonResponse
     */
    public function getFiles(): JsonResponse
    {
        $fileuploadRepo = $this->entityManager->getRepository(FileUpload::class);
        $jsonContent = $this->serializer->serialize($fileuploadRepo->findAll(), 'json');
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * A function that returns the S3 Object to display it on the front
     *
     * @return Response
     */
    public function display($fileKey)
    {
        $object = $this->s3Client->getObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $fileKey,
        ));
        $fileuploadRepo = $this->entityManager->getRepository(FileUpload::class);
        $fileName = ($fileuploadRepo->findOneBy(['keyName' => $fileKey]))->getName();
        $fileExtension = substr(strrchr($fileName, '.'), 1);
        if ($fileExtension === "pdf") {
            $contentType = "application/pdf";
        } elseif ($fileExtension === "png" || $fileExtension === "jpeg" || $fileExtension === "jpg") {
            $contentType = "image/png";
        } elseif ($fileExtension === "mp4") {
            $contentType = "video/mp4";
        } elseif ($fileExtension === "ppt") {
            $contentType = "application/vnd.ms-powerpoint";
        } elseif ($fileExtension === "pptx") {
            $contentType = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
        } elseif ($fileExtension === "doc") {
            $contentType = "application/msword";
        } elseif ($fileExtension === "docx") {
            $contentType = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
        }
        return new Response($object->get('Body'), 200, [
            'Content-type' => $contentType,
            'Content-Disposition' => "filename=$fileName"
        ]);
    }

    /**
     * A function that updates the rights for a specific file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $id = ($request->attributes->get('_route_params'))["file"];
        $droits = json_decode($request->getContent())->droits;
        $fileuploadRepo = $this->entityManager->getRepository(FileUpload::class);
        $file = ($fileuploadRepo->findOneBy(['id' => $id]));
        $file->setRights($droits);
        $this->entityManager->persist($file);
        $this->entityManager->flush();
        $jsonContent = $this->serializer->serialize($file, 'json');
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * A function that retrieves the file from S3 and downloads it to the client
     *
     * @return Response
     */
    public function download($fileKey)
    {
        $object = $this->s3Client->getObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $fileKey,
        ));
        $fileuploadRepo = $this->entityManager->getRepository(FileUpload::class);
        $fileName = ($fileuploadRepo->findOneBy(['keyName' => $fileKey]))->getName();
        return new Response($object->get('Body'), 200, ['Content-Disposition' => "filename=$fileName"]);
    }
}
