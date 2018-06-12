<style>

</style>

<template>
    <div id="stations" class="page">
        <div class="grid-x">
            <div class="large-9 medium-9 small-12 cell">
                <station-map></station-map>
            </div>
            <div class="large-3 medium-3 small-12 cell">

            </div>
        </div>
    </div>
</template>

<script>
    import StationMap from '../components/stations/StationMap.vue';

    export default {
        components: {StationMap}
    }
</script>