<template>
    <button type="button" :class="class" @click="() => {
        showModal();
        if (this.type === 'display') {
            triggerEvent();
        }
    }">{{ buttonOpenModal }}</button>

    <div class="modal fade" aria-hidden="true" ref="modal">
        <template v-if="type === 'display'">
            <div class="modal-dialog modal-fullscreen ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Close">
                            <span class="visually-hidden">Close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <document-viewer ref="DocumentViewer" :fileContent="file.keyName" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </template>

        <template v-if="type === 'rights'">
            <div class="modal-dialog modal-content">
                <div class="modal-header">
                    <h1 class="modal-title h5">Modification des droits</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Close">
                        <span class="visually-hidden">Close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select class="form-select d-flex justify-content-between" @change="rightsSelected">
                        <option v-for="role in roles" :value="role" :selected="role === this.file.rights">{{ role }} <span v-if="role === file.rights" id="displaycheck">&#10003;</span></option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" :disabled="!canRightsUpdate" @click="updateRights(file.id)">Save changes</button>
                </div>
            </div>
        </template>

        <template v-if="type === 'documentation'">
            <div class="modal-dialog modal-content modal-lg">
                <div class="modal-header">
                    <h1 class="modal-title h5">Documentation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Close">
                        <span class="visually-hidden">Close</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Actuel</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" :disabled="true">Historique V</button>
                </div>
                <div v-if="canDocUpdate">
                    <div class="modal-body">
                        <p>{{ (this.programDocumentation.documentation) }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="replaceUpdates()">Edit</button>
                    </div>
                </div>
                <div v-else>
                    <div class="modal-body">
                        <Editor :programDocumentation="this.programDocumentation.documentation" :canDocUpdate="this.canDocUpdate" />
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="replaceUpdates(); saveChanges(this.programDocumentation.id);">Save changes</button>
                    </div>
                </div>

            </div>
        </template>
    </div>
</template>

<script>
import DocumentViewer from "./DocumentViewer.vue";
import Editor from "./Editor.vue";
import axios from "axios";

export default {
    name: 'OperatingModeModal',
    components: { DocumentViewer, Editor },
    props: {
        type: {
            type: String,
            default: 'display'
        },
        file: {
            type: Object,
            Required: true
        },
        fetchFiles: {
            type: Function,
        },
    },
    data() {
        let buttonClass;
        let buttonLabel;
        if (this.type !== 'display') {
            buttonClass = this.type === 'documentation' ? 'btn btn-primary' : 'btn btn-secondary';
        } else {
            buttonClass = 'btn btn-primary';
        }
        if (this.type !== 'display') {
            buttonLabel = this.type === 'documentation' ? 'Documentation' : 'Modifier les droits';
        } else {
            buttonLabel = 'Afficher';
        }

        return {
            roles: ["SAME", "GESTIONNAIRE", "VISITEUR"],
            class: buttonClass,
            buttonOpenModal: buttonLabel,
            canRightsUpdate: false,
            canDocUpdate: true,
            selectedRight: this.file.rights,
            fileContent: "",
            programDocumentation: [],
        }
    },
    mounted() {
        if (this.type === 'documentation') {
            axios.get("/api/programdocumentation/" + this.file.id).then((response) => {
                this.programDocumentation = response.data;
            });
        }
    },
    methods: {
        /**
         *  A method that shows the modal instance
         */
        showModal() {
            $boosted.Modal.getOrCreateInstance(this.$refs.modal).show();
        },
        /**
         * A method that updates the selected right and the ability to update it or not
         * @param {Event} event 
         */
        rightsSelected(event) {
            this.canRightsUpdate = event.target.value !== this.file.rights;
            this.selectedRight = event.target.value;
        },
        /**
         * A method that calls an API to update the selected right in the database
         * @param {Int} id
         */
        updateRights(id) {
            if ((window.location.pathname).includes("documentation")) {
                axios.patch("/api/fileupload/" + id, { droits: this.selectedRight });
            } else {
                axios.patch("/api/programupload/" + id, { droits: this.selectedRight });
            }

            this.fetchFiles();
            this.canRightsUpdate = !this.canRightsUpdate;
        },
        /**
         *  A method that triggers the API call to display the document 
         */
        triggerEvent() {
            this.$refs.DocumentViewer.fetchData();
        },

        replaceUpdates() {
            if (this.canDocUpdate === true) {
                this.canDocUpdate = false
            } else {
                this.canDocUpdate = true
            }
        },

        saveChanges(id) {
            axios.patch("/api/programdocumentation/" + id, { documentation: tinymce.get("mytextarea").getContent() });
        },
    },

}
</script>