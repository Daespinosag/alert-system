<template>
    <div id="landslide-alert-list" class="landslide-alert-list">
        <landslide-alert-card v-for="landslideAlert in landslideAlerts" :key="landslideAlert.code" :alert="landslideAlert"></landslide-alert-card>
    </div>
</template>

<script>
    import landslideAlert from "../../../store/models/alerts/landslide/landslideAlert";
    import LandslideAlertCard from "./LandslideAlertCard";

    export default {
        name: "landslide-alert-list",
        components: {LandslideAlertCard },
        computed: {
            landslideAlerts(){
                return landslideAlert.all()
            }
        },
    }
</script>

<style scoped>

</style>