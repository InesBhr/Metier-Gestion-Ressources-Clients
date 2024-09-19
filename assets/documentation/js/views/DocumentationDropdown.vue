<template>
    <div class="container bg-dark" style="width:500px; padding: 20px">
        <div class="row">
            <div class="col">
                <h3>Documentation</h3>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <router-link v-if="isLocated" :to="{ name: 'documentation_modop' }" class="nav-link"><i class="fa-solid fa-book me-2"></i>Modop & Formation</router-link>
                <a href="/documentation/modop-formation" v-else class="nav-link"><i class="fa-solid fa-book me-2"></i>Modop & Formation</a>
            </div>
            <div class="col-6">
                <router-link v-if="isLocated" :to="{ name: 'documentation_consignes' }" class="nav-link"><i class="fa-solid fa-wrench me-2"></i>Consignes & Process</router-link>
                <a href="/documentation/consignes-process" v-else class="nav-link"><i class="fa-solid fa-wrench me-2"></i>Consignes & Process</a>
            </div>
        </div>
    </div>
</template>
  
<script>
export default {
    name: "DocumentationDropdown",
    computed: {
        /**
        * A method that returns if the window pathname includes "/documentaion" or no 
        */
        isLocated() {
            return (window.location.pathname.startsWith('/documentation'));
        }
    }
}
</script>

  