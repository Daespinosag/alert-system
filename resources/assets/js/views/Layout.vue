<template>
    <div class="layout" id="layout">
        <Navigation></Navigation>
        <float-button></float-button>
        <filters></filters>
        <load-initial-information-component :user_id="1"> </load-initial-information-component>

        <router-view></router-view>
    </div>
</template>

<script>
    // @ is an alias to /src
    import LoadInitialInformationComponent from "../components/LoadInitialInformationComponent";
    import Navigation from "../components/global/Navigation";
    import Filters from "../components/global/Filters";
    import FloatButton from '../components/global/FloatButton'

    export default {
        name: 'layout',
        components: { LoadInitialInformationComponent, Navigation, FloatButton, Filters },
        created(){

        }
    }
</script>