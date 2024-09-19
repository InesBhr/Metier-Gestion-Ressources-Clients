<template>
    <div class="d-flex justify-content-center mt-5" style="width: 100%">
        <div class="card d-flex me-5" style="width: 35%">
            <img src="../../../../public/images/anomalies.jpg" alt="anomalies capture" />
            <div class="card-body">
                <h5 class="card-title">Anomalies</h5>
                <p>Anomalies BAN / Anomalies SPN Dispo et Interdit</p>
                <a href="/workflow/anomalies" class="btn btn-primary">Accéder</a>
            </div>
        </div>

        <div class="card" style="width: 35%">
            <img src="../../../../public/images/rejects.jpg" alt="rejects capture" />

            <div class="card-body">
                <h5 class="card-title">Rejets</h5>
                <p>Rejets 42C et 42L</p>
                <a href="/workflow/rejects" class="btn btn-primary">Accéder</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "HomeCards.vue"
}
</script>