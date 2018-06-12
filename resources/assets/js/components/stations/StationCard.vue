<template>
    <li class="list-group-item">
        <router-link :to="{ name: 'station', params: { id: station.id} }">
            {{ station.name }}
        </router-link>
    </li>
</template>

<script>
    export default {
        props: ['station']
    }
</script>

<style>

</style>