<template>
    <div class="container-fluid mt-2">
        <nav aria-label="breadcrumb row">
            <ol class="breadcrumb col">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item">Workflow</li>
                <li class="breadcrumb-item active" aria-current="page">Anomalies</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <h1 class="col">Anomalies</h1>
        <WorkflowTemplate pageTitle="Anomalies" apiUrl="/api/anomalies" :columnsByTab="columnsByTab" :options="options" isLocatedPath="/anomalies" :classMap="classMap" />
    </div>

</template>


<script>
import WorkflowTemplate from '../components/WorkflowTemplate.vue';

export default {
    name: "Anomalies",
    components: { WorkflowTemplate },
    data() {
        return {
            columnsByTab: {
                "anomalies-spn-dispo": ["Upr", "CodeServeur42L", "Nd", "TypePorta", "Crn", "Z0bpq", "DatePorta"],
                "anomalies-spn-interdit": ["Upr", "CodeServeur42L", "Nd", "TypePorta", "Crn", "Z0bpq", "DatePorta"],
                "anomalies-ban": ["Upr", "CodeBan", "Code42C", "Nra", "Sgtqs", "Nd", "TypePorta", "Crn", "Op", "DatePorta"]
            },
            options: [
                { value: "anomalies-spn-dispo", text: "Anomalies SPN Dispo" },
                { value: "anomalies-spn-interdit", text: "Anomalies SPN Interdit" },
                { value: "anomalies-ban", text: "Anomalies BAN" }
            ],
            classMap: {
                "anomalies-spn-dispo": "AnomaliesSPN",
                "anomalies-spn-interdit": "AnomaliesSPN",
                "anomalies-ban": "AnomaliesBAN"
            }
        };
    }
};
</script>