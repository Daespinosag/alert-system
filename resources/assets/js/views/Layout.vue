<template>
    <div class="layout" id="layout">
        <Navigation></Navigation>
        <float-button></float-button>
        <filters></filters>
        <load-initial-information-component :user_id="1"> </load-initial-information-component>
        <update-information-in-real-time></update-information-in-real-time>
        <router-view></router-view>
    </div>
</template>

<script>
    // @ is an alias to /src
    import LoadInitialInformationComponent from "../components/LoadInitialInformationComponent";
    import Navigation from "../components/global/Navigation";
    import Filters from "../components/global/Filters";
    import FloatButton from '../components/global/FloatButton';
    import StatusBar from "../components/alerts/StatusBar";
    import UpdateInformationInRealTime from "../components/UpdateInformationInRealTime";

    export default {
        name: 'layout',
        components: {
            StatusBar,
            LoadInitialInformationComponent,
            Navigation,
            FloatButton,
            Filters,
            UpdateInformationInRealTime
        },
        created(){

        }
    }
</script>