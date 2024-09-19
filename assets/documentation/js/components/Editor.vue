<template>
  <form method="post">
    <textarea id="mytextarea" placeholder="">{{ this.programDocumentation }}</textarea>
  </form>
</template>

<script>
import tinymce from "tinymce/tinymce.min.js";
export default {
  name: "Editor",
  props: {
    programDocumentation: String,
    canDocUpdate: Boolean,
  },
  mounted() {
    this.tinymceInit();
  },

  methods:
  {
    tinymceInit() {
      tinymce.init({
        selector: "textarea",
        plugins: ["image", "preview"],
        statusbar: false,
        toolbar:
          "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | image | preview",
        // enable automatic uploads of images represented by blob or data URIs
        automatic_uploads: true,
        // add custom filepicker only to Image dialog
        file_picker_types: "file image media",
        file_picker_callback: function (cb, value, meta) {
          // To display the image in the dialog
          let input = document.createElement("input");
          console.log("im mounted");
          input.setAttribute("type", "file");
          input.setAttribute("accept", "image/*");
          input.onchange = function () {
            let file = this.files[0];
            let reader = new FileReader();
            reader.onload = function () {
              let id = "blobid" + new Date().getTime();
              let blobCache = tinymce.activeEditor.editorUpload.blobCache;
              let base64 = reader.result.split(",")[1];
              let blobInfo = blobCache.create(id, file, base64);
              blobCache.add(blobInfo);
              console.log(blobInfo);
              // document
              //   .querySelector("[title=Save]")
              //   .addEventListener("click", function () {
              //     console.log("Save clicked");
              // 
              //     axios.post("/api/programDocUpload", formData, {
              //       
              //     });
              //   });
              // call the callback and populate the Title field with the file name
              cb(blobInfo.blobUri(), { title: file.name });
              console.log(cb(blobInfo.blobUri(), { title: file.name }));
            };

            reader.readAsDataURL(file);
          };
          input.click();
        },
      });
    }
  }
};
</script>

<style lang="scss">
@import "../../scss/tinymce-boosted.scss";
</style>
