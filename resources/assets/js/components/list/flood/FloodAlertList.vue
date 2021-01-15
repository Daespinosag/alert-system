<template>
    <div id="flood-alert-list" class="flood-alert-list">
        <flood-alert-card v-for="floodAlert in floodAlerts" :key="floodAlert.code" :alert="floodAlert"></flood-alert-card>
    </div>
</template>

<script>
    import FloodAlert from "../../../store/models/alerts/flood/floodAlert";
    import FloodAlertCard from "./FloodAlertCard";

    export default {
        name: "flood-alert-list",
        components: { FloodAlertCard },
        computed: {
            floodAlerts(){
                return FloodAlert.all()
            }
        },
    }
</script>

<style scoped>

</style>