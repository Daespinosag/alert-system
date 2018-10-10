<style>
        div.show-filters {
            height: 90px;
            width: 23px;
            position: absolute;
            left: 0px;
            background-color: white;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            line-height: 90px;
            top: 50%;
            cursor: pointer;
            margin-top: -45px;
            z-index: 9;
            text-align: center;
        }
</style>

<template>
    <div id="app-layout">
        <div class="show-filters" v-show="!showFilters" v-on:click="toggleShowFilters()">
            <img src="images/grey-right.svg"/>
        </div>

        <navigation></navigation>

        <error-notification></error-notification>

        <router-view></router-view>

        <filters></filters>
    </div>
</template>

<script>
    import { EventBus } from '../event-bus.js';

    import Navigation from '../components/global/Navigation.vue';
    import Filters from '../components/global/Filters.vue';
    import ErrorNotification from '../components/global/ErrorNotification.vue';

    export default {
        props: { user: null },
        components: {Navigation,Filters,ErrorNotification},
        created(){
            // se pregunta si es la primera vez que carga el usuario
            if (this.$store.userLoadStatus !== 2 && this.user !== null){

                this.$store.commit('setUser', this.user);

                this.$store.dispatch('loadTypeStation').then(
                    response => {
                        this.$store.dispatch('loadAlerts',{ permissions : this.$store.getters.getAlertPermissions}).then(
                            response => {
                                this.$store.dispatch('loadStations',{ alerts : this.$store.getters.getAlerts }).then(
                                    response => { this.$router.push({ name: 'stations' }); },
                                    error    => { this.sendEvenError('No fue posible cargar la información referente a las estaciones. ',false); }
                                );
                            },
                            error    => { this.sendEvenError('No fue posible cargar la información referente a los tipos de alertas. ',false); }
                        );
                    },
                    error    => { this.sendEvenError('No fue posible cargar la información referente a los tipos de estación. ',false); }
                );
            }
        },
        computed: {
            showFilters(){
                return this.$store.getters.getShowFilters;
            }
        },
        methods: {
            toggleShowFilters(){
                this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
            },
            sendEvenError(notification,collapsible){
                EventBus.$emit('show-error', { notification : notification, collapsible : collapsible });
            }
        },
    }
</script>
