<?php
/*
 * SPDX-FileCopyrightText: Copyright (C) 2024 Orange
 *
 * This software is confidential and proprietary information of Orange.
 * You shall not disclose such Confidential Information and shall not copy, use or distribute it
 * in whole or in part without executing an agreement with Orange
 */

namespace App\Helper;

use Aws\S3\S3Client;
use Exception;

/**
 *
 */
class S3FileManager
{
    /**
     * @var S3Client
     */
    private S3Client $s3Client;
    /**
     * @var string
     */
    private string $bucket;

    /**
     * @param string $endpoint
     * @param string $region
     * @param string $accessKey
     * @param string $secretAccessKey
     * @param string $bucket
     */
    public function __construct(
        string $endpoint,
        string $region,
        string $accessKey,
        string $secretAccessKey,
        string $bucket
    ) {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'endpoint' => $endpoint,
            'http' => [
                'verify' => false
            ],
            'credentials' => [
                'key' => $accessKey,
                'secret' => $secretAccessKey
            ]
        ]);
        $this->bucket = $bucket;
    }

    /**
     * Retrieves and returns the contents of an object on the bucket
     *
     * @param string $fileName
     *
     * @return mixed
     */
    public function getContentFile(string $fileName): mixed
    {
        $result = $this->s3Client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $fileName
        ]);

        return $result['Body']->getContents();
    }

    /**
     * Download an object from the bucket as a file
     *
     * @param string $fileName
     * @param string $saveAs
     *
     * @return void
     */
    public function downloadFile(string $fileName, string $saveAs): void
    {
        $this->s3Client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $fileName,
            'SaveAs' => $saveAs
        ]);
    }

    /**
     * Upload an object from content to the bucket
     *
     * @param string          $fileName
     * @param string|resource $fileContent
     *
     * @return void
     */
    public function uploadFile(string $fileName, mixed $fileContent): void
    {
        $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $fileName,
            'Body' => $fileContent
        ]);
    }

    /**
     * Return a list of objects present on a bucket or a folder of the bucket
     *
     * @return mixed|null
     */
    public function listFiles(?string $folder): mixed
    {
        if ($folder === null) {
            $result = $this->s3Client->listObjects([
                'Bucket' => $this->bucket
            ]);
        } else {
            $result = $this->s3Client->listObjects([
                'Bucket' => $this->bucket,
                'Prefix' => $folder
            ]);
        }

        return $result['Contents'];
    }

    /**
     * Retrieves a pre-signed URL for access to a bucket object for 10 minutes
     *
     * @param string $fileName
     *
     * @return string
     */
    public function getPreSignedUrl(string $fileName): string
    {
        $cmd = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $fileName
        ]);

        $request = $this->s3Client->createPresignedRequest($cmd, '+10 minutes');
        return (string)$request->getUri();
    }

    /**
     * Delete an object from its key
     *
     * @param string $fileName
     *
     * @return bool
     */
    public function deleteFile(string $fileName): bool
    {
        try {
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $fileName
            ]);
            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Moves an object to another location in the same bucket
     *
     * @param string $sourceKeyName
     * @param string $targetKeyName
     *
     * @return bool
     */
    public function moveObject(string $sourceKeyName, string $targetKeyName): bool
    {
        try {
            // Copy the file to the new folder
            $this->s3Client->copyObject([
                'Bucket' => $this->bucket,
                'CopySource' => "{$this->bucket}/{$sourceKeyName}",
                'Key' => $targetKeyName,
            ]);

            // Deleting the old file
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $sourceKeyName,
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
