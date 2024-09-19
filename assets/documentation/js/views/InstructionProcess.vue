<template>
    <div class="row justify-content-center mx-auto">
        <h1 class="col fs-3 text-center">- Consignes & Process -</h1>
    </div>
    <div class="row justify-content-center mx-auto">
        <FileUploader :fetchFiles="this.fetchFiles" />
    </div>
    <div class="row p-2 p-0 w-25 mx-auto">
        <div class="col">
            <input type="search" class="form-control" placeholder="Rechercher par nom" v-model="search" />
        </div>
    </div>
    <div class="row mb-2 mt-2 justify-content-center">
        <div class="col">
            <table class="table table-striped" aria-describedby="files list table">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th scope="col">Nom du fichier</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center" v-for="file in searchFilter" :key="file.id">
                        <td>{{ file.name }}</td>
                        <td class="d-flex justify-content-center">
                            <OperationModeModal type="display" :file="file" />
                            <a :href="'/download/' + file.keyName" target="_blank" rel="noopener" download class="btn btn-secondary">Télécharger</a>
                            <button v-if="userPermissions.same" class="btn btn-danger" @click="deletion(file.id, $event)">Supprimer</button>
                            <OperationModeModal v-if="userPermissions.same" type="rights" :file="file" :fetchFiles="this.fetchFiles" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import FileUploader from "/assets/documentation/js/components/FileUploader.vue";
import OperationModeModal from "/assets/documentation/js/components/OperationModeModal.vue";

export default {
    name: "OperatingMode",
    components: {
        FileUploader,
        OperationModeModal
    },
    props: {
        userPermissions: Array,
    },
    data() {
        return {
            files: [],
            search: "",
            selectedDroit: "",
        };
    },
    mounted() {
        this.fetchFiles();
        console.log(userPermissions);
    },
    computed: {
        /**
         * Filter data
         * @return {array}
         */
        searchFilter() {
            return this.files.filter((file) => file.name.toLowerCase().includes(this.search));
        },
    },
    methods: {
        // A function that fetchs files from the DB
        fetchFiles() {
            axios.get("/api/fileupload").then((response) => {
                this.files = response.data;
            });
        },

        /**
         * A function that deletes the file displayed on the list throught an api call
         * @param {integer} $id 
         * @param {PointerEvent} event 
         */
        deletion($id, event) {
            if (confirm("Are you sure you want to delete this file?")) {
                event.target.innerHTML = `<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>`;
                axios.delete("/api/fileupload/" + $id).then((response) => {
                    this.files = this.files.filter(
                        (file) => file.id !== response.data.id
                    );
                });
            }
        },

    },
};
</script>
  
<style>
.model-dialog {
    width: 100%;
}
</style>
  