<template>
    <div id="print-alerts" class="print-alerts">
        <div class="grid-x">
            <div class="large-9 medium-9 small-12 cell">
                <print-alerts-component> </print-alerts-component>
            </div>
            <div class="large-3 medium-3 small-12 cell">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import PrintAlertsComponent from "../components/PrintAlertsComponent";

    export default {
        name: "print-alerts",
        components: { PrintAlertsComponent },
    }
</script>

<style scoped>

</style>