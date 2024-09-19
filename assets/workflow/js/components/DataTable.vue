<template>
    <div class="table-responsive">
        <table class="table table-sm table-hover has-checkbox" aria-describedby="anomalies list table" style="width:100%">
            <thead>
                <tr>
                    <th scope="col" v-if="state === 'ATraiter'">
                        <div class="form-check mb-0">
                            <input class="form-check-input p-2" type="checkbox" id="selectAllCheckbox" v-model="selectAllChecked" @change="selectAllChanged">
                            <label class="form-check-label" for="selectAllCheckbox">
                                <span class="visually-hidden">Select all</span>
                            </label>
                        </div>
                    </th>
                    <th scope="col" :style="defineWidth(column)" v-for=" column in columns" :key="column" class="text-center" id="widthDefine">{{ column }}</th>

                    <div id="selectBar" class="border border-secondary p-2 border-light d-flex justify-content-center align-items-center" v-if="state === 'ATraiter' && display === true">
                        <div class="me-3">{{ count }} Sélectionnés</div>
                        <button @click="changeStateDone(selectedItems)" type="button" class="btn btn-success btn-sm me-2">
                            <i class="fa-solid fa-check me-2"></i>Traités
                        </button>

                        <button @click="changeStateUndo(selectedItems)" type="button" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-xmark me-2"></i>Ne pas traiter
                        </button>
                    </div>
                </tr>

                <tr>
                    <th scope="col" v-if="state === 'ATraiter'"></th>
                    <th scope="col" v-for="column in columns" :key="column" class="p-1 ">
                        <input v-model="search[checkColumnName(column)]" type="search" class="form-control p-0 text-center" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" :placeholder="'...'">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in paginatedData" :key="item.id" :id="item.nd">
                    <td v-if="state === 'ATraiter'">
                        <div class="form-check mb-0">
                            <input class="form-check-input p-2" type="checkbox" :id="'checkboxElem_' + index" v-model="selectedItems" :value="item.id" @change="itemCheckboxChanged">
                            <label class="form-check-label" :for="'checkboxElem_' + index">
                                <span class="visually-hidden">Select row {{ index + 1 }}</span>
                            </label>
                        </div>
                    </td>
                    <td v-for="column in columns" :key="column" class="text-center">{{ item[checkColumnName(column)] }}</td>
                </tr>
            </tbody>
        </table>


    </div>
    <nav v-if="shouldPaginate" aria-label="Page navigation example">
        <ul class="pagination mb-2">
            <li class="page-item" :class="{ disabled: currentPage === 1 }"><a class=" page-link" href="#" @click="prevPage" aria-label="Previous"></a></li>
            <li v-for="page in pagesToShow" :key="page" :class="['page-item', { active: page === currentPage, disabled: page === '...' }]">
                <a v-if="page !== '...'" class="page-link" href="#" @click.prevent="goToPage(page)">{{ page }}</a>
                <span v-else class="page-link" id="dots">{{ page }}</span>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }"><a class="page-link" href="#" @click="nextPage" aria-label="Next"></a></li>

        </ul>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            search: {},
            selectAllChecked: false,
            selectedItems: [],
            display: false,
            count: 0,
            currentPage: 1,
            perPage: 10,
        };
    },
    props: {
        columns: Array,
        data: Array,
        state: String,
        moveData: Function,
    },
    mounted() {
        this.$nextTick(() => {
            this.checkDuplicates();
        });
    },
    watch: {
        currentPage() {
            this.$nextTick(() => {
                this.checkDuplicates();
            });
        },
        paginatedData() {
            this.$nextTick(() => {
                this.checkDuplicates();
            });
        }
    },
    computed: {
        searchFilter() {
            this.filteredData = this.data.filter(dataElem => {
                for (const column in this.search) {
                    if (column == 'Nd') {
                        const searchString = dataElem[column];
                        if (!searchString.includes(this.search[column])) {
                            return false;
                        }
                    } else {
                        const searchString = String(dataElem[column]).toLowerCase();
                        if (!searchString.includes(this.search[column].toLowerCase())) {
                            return false;
                        }
                    }
                }
                return true;
            });
            this.currentPage = 1;
            return this.filteredData;
        },
        shouldPaginate() {
            return this.data.length > this.perPage;
        },
        paginatedData() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.searchFilter.slice(start, end);
        },
        totalPages() {
            return Math.ceil(this.searchFilter.length / this.perPage);
        },
        pagesToShow() {
            const pages = [];
            const totalPages = this.totalPages;
            const currentPage = this.currentPage;

            if (totalPages <= 5) {
                for (let i = 1; i <= totalPages; i++) {
                    pages.push(i);
                }
            } else {
                pages.push(1);
                if (currentPage > 3) {
                    pages.push('...');
                }
                if (currentPage > 2) {
                    pages.push(currentPage - 1);
                }
                if (currentPage !== 1 && currentPage !== totalPages) {
                    pages.push(currentPage);
                }
                if (currentPage < totalPages - 1) {
                    pages.push(currentPage + 1);
                }
                if (currentPage < totalPages - 2) {
                    pages.push('...');
                }
                pages.push(totalPages);
            }

            return pages;
        }
    },
    methods: {
        changeStateDone(IdList) {
            this.moveData("done", IdList);
        },
        changeStateUndo(IdList) {
            this.moveData("undo", IdList);
        },
        checkDuplicates() {
            this.$nextTick(() => {
                const valeursVues = new Set();
                for (const Elem in this.data) {
                    const valeurColonneND = this.data[Elem]['nd'];
                    if (valeursVues.has(valeurColonneND)) {
                        const rowElement = document.getElementById(this.data[Elem]['nd']);
                        if (rowElement) {
                            rowElement.style.color = 'red';
                        }
                    } else {
                        valeursVues.add(valeurColonneND);
                    }
                }
            });
        },
        checkColumnName(column) {
            if ((column.charAt(0).toLowerCase() + column.slice(1)) === "datePorta") {
                return 'datePortaFormatted';
            }
            if ((column.charAt(0).toLowerCase() + column.slice(1)) === "dateTraitement") {
                return 'dateTraitementFormatted';
            }
            if ((column.charAt(0).toLowerCase() + column.slice(1)) === "date du rejet") {
                return 'dateRejetFormatted';
            }
            if (column === "CAA / Site") {
                return 'infosSite';
            }
            if (column === "Mise à jour") {
                return 'operation';
            }
            if (column === "Code adresse rattachement") {
                return 'codeAdresseRattachement';
            }
            if (column === "Code operateur concurrent") {
                return 'codeOperateurConcurrent';
            }
            if (column === "Type portabilité") {
                return 'typePortabilite';
            }
            if (column === "Indicateur blocage") {
                return 'indicateurBlocage';
            }
            if (column === "Code Mouvement") {
                return 'codeMouvement';
            }
            if (column === "Infos1") {
                return 'info1';
            }
            if (column === "Infos2") {
                return 'info2';
            }
            if (column === "A054") {
                return 'a054';
            }
            if (column === "Code situation") {
                return 'codeSituation';
            }
            return (column.charAt(0).toLowerCase() + column.slice(1));
        },
        selectAllChanged() {
            if (this.selectAllChecked) {
                this.display = true;
                this.selectedItems = this.paginatedData.map(item => item.id);
            } else {
                this.display = false;
                this.selectedItems = [];
            }
            this.count = this.paginatedData.length;
        },
        itemCheckboxChanged() {
            if (this.selectedItems) {
                this.display = true;
                this.selectedItems = this.searchFilter.filter(item => this.selectedItems.includes(item.id)).map(item => item.id);
                if (this.selectedItems.length === 0) {
                    this.display = false;
                }
            }
            this.count = this.selectedItems.length;
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.$nextTick(() => {
                    this.checkDuplicates();
                });
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.$nextTick(() => {
                    this.checkDuplicates();
                });
            }
        },
        goToPage(page) {
            this.currentPage = page;
            this.$nextTick(() => {
                this.checkDuplicates();
            });
        },
        defineWidth(column) {
            if (column === 'Mise à jour') {
                return 'width:50rem;'
            }
            if (column === 'Infos1' || column === 'Infos2') {
                return 'width:20rem;'
            }
            if (column === 'Ne') {
                return 'width:7rem;'
            }
        }
    }
};
</script>



<style>
#selectBar {
    position: fixed;
    bottom: 15vh;
    right: 0;
    z-index: 999;
    width: 30%;
    height: 60px;
    left: 50%;
    transform: translate(-50%, 0);
    box-shadow: 7px 5px 5px rgba(155, 153, 153, 0.582);
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
}

#dots {
    border: none;
    background-color: rgb(255, 255, 255);
}
</style>
