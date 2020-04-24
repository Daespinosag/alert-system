<template>
    <div id="print-alerts-component" class="print-alerts-component">
        <alert-map id="alert-map-container" class="alert-map-container"> </alert-map>
    </div>
</template>

<script>
    import AlertMap from "@alert-system-vue/components/map/AlertMap"

    export default {
        name: "print-alerts-component",
        components: { AlertMap },
    }
</script>

<style lang="scss">
    div#print-alerts-component{
        position: absolute;
        top: 5px;
        left: 0px;
        right: 0px;
        bottom: 0px;
        z-index:0;

        div#alert-map-container{
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
        }
    }
</style>