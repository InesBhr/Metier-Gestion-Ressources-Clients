<template>
  <input type="file" class="filepond" name="filepond" multiple data-allow-reorder="true" data-max-file-size="10MB" data-max-files="3" />
</template>

<script>
import axios from "axios";
import * as FilePond from "filepond";
import { registerPlugin } from "filepond";
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileEncode from "filepond-plugin-file-encode";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation";
import FilePondPluginImageResize from "filepond-plugin-image-resize";

registerPlugin(
  FilePondPluginFileEncode,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview,
  FilePondPluginImageExifOrientation,
  FilePondPluginImageResize,
  FilePondPluginFileValidateType
);

export default {
  name: "FileUploader",
  props: {
    fetchFiles: {
      type: Function,
    },
  },
  mounted() {
    if ((window.location.pathname).includes("outils")) {
      FilePond.create(document.querySelector("input"), {
        acceptedFileTypes: ["application/x-zip-compressed", "application/x-rar-compressed", "application/x-7z-compressed"],
        fileValidateTypeDetectType: (source, type) =>
          Promise.resolve(type)
      })
    } else {
      FilePond.create(document.querySelector("input"), {
        labelIdle: `Faites glisser et déposez votre fichier ou <span class="filepond--label-action">Parcourez</span>`,
      });
    }

    FilePond.setOptions({
      server: {
        process: {
          url: (window.location.pathname).includes("documentation") ? "/api/fileupload" : "/api/programupload",
          method: "POST",
          // headers: {
          //   "Content-Type": "multipart/form-data",
          // },
          withCredentials: false,
          headers: {},
          timeout: 7000,
          forceRevert: true,
        },

        revert: async (uniqueFileId, load) => {
          const fileupload = JSON.parse(uniqueFileId);
          if ((window.location.pathname).includes("documentation")) {
            await axios.delete(`/api/fileupload?fileId=${fileupload.id}`);
          } else {
            await axios.delete(`/api/programupload?fileId=${fileupload.id}`)
          }
          this.fetchFiles();
          load();
        },
      },
      onprocessfile: () => {
        this.fetchFiles();
      },
    });
  },
};
</script>

<style lang="css">
@import "filepond/dist/filepond.min.css";
@import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";

.filepond--credits {
  display: none;
}

.filepond--root {
  width: 40%;
}
</style>
