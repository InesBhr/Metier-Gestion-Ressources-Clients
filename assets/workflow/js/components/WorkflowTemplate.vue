<template>
    <div>
        <div v-if="isLocated" class="row">
            <div class="col">
                <select class="form-select form-select-lg mb-3" @change="switchTab" v-model="activeTab">
                    <option v-for="option in options" :key="option.value" :value="option.value">{{ option.text }}</option>
                </select>
            </div>
        </div>
        <ul class="nav nav-tabs px-2">
            <li class="nav-item">
                <button class="nav-link" :class="{ active: activeState === 'ATraiter' }, 'text-orange-500'" @click="switchState('ATraiter')">
                    <i class="fa-solid fa-hourglass-end me-2"></i>A traiter
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" :class="{ active: activeState === 'Traité' }, 'text-green-500'" @click="switchState('Traité')">
                    <i class="fa-solid fa-check me-2"></i>Traités
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" :class="{ active: activeState === 'NonTraité' }, 'text-red-500'" @click="switchState('NonTraité')">
                    <i class="fa-solid fa-xmark me-2"></i>Ne pas traiter
                </button>
            </li>
        </ul>
        <data-table v-if="loaded" :columns="activeColumns" :data="data" :state="activeState" :moveData="moveData" />
        <div v-if="!loaded" class="d-flex justify-content-center m-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import DataTable from './DataTable.vue';
import { toast } from "vue3-toastify";
import 'vue3-toastify/dist/index.css';

export default {
    props: {
        pageTitle: {
            type: String,
            required: true
        },
        apiUrl: {
            type: String,
            required: true
        },
        columnsByTab: {
            type: Object,
            required: true
        },
        options: {
            type: Array,
            required: true
        },
        isLocatedPath: {
            type: String,
            required: true
        },
        classMap: {
            type: Object,
            required: true
        }
    },
    components: {
        DataTable
    },
    data() {
        return {
            activeColumns: [],
            activeTab: "",
            activeState: "ATraiter",
            data: [],
            loaded: false
        };
    },
    mounted() {
        this.activeTab = this.options[0].value;
        this.switchTab();
    },
    computed: {
        isLocated() {
            return window.location.pathname.includes(this.isLocatedPath);
        }
    },
    methods: {
        switchTab() {
            this.loaded = false;
            this.activeColumns = this.columnsByTab[this.activeTab];
            if (this.activeState != "ATraiter") {
                if (!this.activeColumns.includes("DateTraitement")) {
                    this.activeColumns.push("DateTraitement");
                }
            } else if (this.activeColumns.includes("DateTraitement")) {
                this.activeColumns.pop();
            }
            this.fetchData();
        },
        switchState(activeState) {
            this.loaded = false;
            this.activeState = activeState;
            if (this.activeState != "ATraiter") {
                if (!this.activeColumns.includes("DateTraitement")) {
                    this.activeColumns.push("DateTraitement");
                }
            } else if (this.activeColumns.includes("DateTraitement")) {
                this.activeColumns.pop();
            }
            this.fetchData();
        },
        async fetchData() {
            await axios.get(this.apiUrl, {
                params: {
                    state: this.activeState,
                    type: this.activeTab.includes('dispo') ? 'Dispo' : 'Interdit',
                    class: this.classMap[this.activeTab]
                }
            }).then((response) => {
                this.data = JSON.parse(response.data);
                this.loaded = true;
            });
        },
        moveData(action, IdList) {
            this.loaded = false;
            this.activeTabInfo = this.activeTab.includes("spn") ? 'anomalies-spn' : this.activeTab;
            axios.patch('/api/' + `${this.activeTabInfo}`, { state: action === "done" ? 2 : 3, idList: IdList })
                .then(() => {
                    this.fetchData();
                    toast.success("Opération réussie!", {
                        "theme": "auto",
                        "type": "success",
                        "autoClose": 2000,
                        "dangerouslyHTMLString": true
                    });
                });
        }
    }
}
</script>
