<template>
    <div id="print-alerts-component" class="print-alerts-component">
        <alert-map> </alert-map>
    </div>
</template>

<script>
    import AlertMap from "@alert-system-vue/components/map/AlertMap"

    export default {
        name: "print-alerts-component",
        components: { AlertMap },
    }
</script>

<style scoped>

</style>