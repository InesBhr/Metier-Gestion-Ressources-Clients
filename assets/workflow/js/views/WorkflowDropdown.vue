<template>
    <div class="container bg-dark" style="width:300px; padding: 20px">
        <div class="row">
            <div class="col">
                <h3>Workflow</h3>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <router-link v-if="isLocated" :to="{ name: 'anomalies' }" class="nav-link"><i class="fa-solid fa-circle-exclamation me-2"></i>Anomalies</router-link>
                <a href="/workflow/anomalies" v-else class="nav-link"><i class="fa-solid fa-book me-2"></i>Anomalies</a>
            </div>
            <div class="col-6">
                <router-link v-if="isLocated" :to="{ name: 'rejects' }" class="nav-link"><i class="fa-solid fa-wrench me-2"></i>Rejets</router-link>
                <a href="/workflow/rejects" v-else class="nav-link"><i class="fa-solid fa-wrench me-2"></i>Rejets</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "WorkflowDropdown",
    computed: {
        /**
        * A method that returns if the window pathname includes "/workflow" or no 
        */
        isLocated() {
            return (window.location.pathname.startsWith('/workflow'));
        }
    }
}
</script>