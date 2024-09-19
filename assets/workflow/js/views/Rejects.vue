<template>
    <div class="container-fluid mt-2">
        <nav aria-label="breadcrumb row">
            <ol class="breadcrumb col">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item">Workflow</li>
                <li class="breadcrumb-item active" aria-current="page">Rejets</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <h1 class="col">Rejets</h1>
        <WorkflowTemplate pageTitle="Rejets" apiUrl="/api/rejects" :columnsByTab="columnsByTab" :options="options" isLocatedPath="/rejects" :classMap="classMap" />
    </div>
</template>

<script>
import WorkflowTemplate from "../components/WorkflowTemplate.vue"

export default {
    name: "Rejects",
    components: { WorkflowTemplate },
    data() {
        return {
            columnsByTab: {
                "rejets-42c": ["Base", "Date du rejet", "CAA / Site", "Mise à jour"],
                "rejets-42l": ["Dr", "DateMvt", "Centre", "Nd", "Ne", "Code Mouvement", "Repartiteur", "Code adresse rattachement", "Code operateur concurrent", "Type portabilité", "Indicateur blocage", "Infos1", "Infos2", "A054", "Code situation"]
            },
            options: [
                { value: "rejets-42c", text: "Rejets 42L/42C" },
                { value: "rejets-42l", text: "Rejets 42C/42L" }
            ],
            classMap: {
                "rejets-42c": "Rejects42C",
                "rejets-42l": "Rejects42L"
            }
        };
    }
};
</script>