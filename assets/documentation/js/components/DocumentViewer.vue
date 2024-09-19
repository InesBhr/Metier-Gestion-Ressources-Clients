<template>
  <iframe :src="fileSrc" :type="contentType" width="100%" height="100%" class="center" title="document viewer iframe">
  </iframe>
</template>

<script>
import axios from "axios";
export default {
  name: "DocumentViewer",
  props: {
    fileContent: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      fileSrc: "",
      contentType: "",
    }
  },
  methods: {
    /**
    * A method that calls an API to fetch the file throught the iframe
    */
    fetchData() {
      axios.get("/api/fileupload/" + this.fileContent).then((response) => {
        this.fileSrc = "/api/fileupload/" + this.fileContent;
        this.contentType = response.headers['content-type'];
      });

    }
  },
}
</script>