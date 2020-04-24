<template>
    <div class="layout" id="layout">
        <Navigation></Navigation>

        <load-initial-information-component :user_id="1"> </load-initial-information-component>

        <router-view></router-view>
    </div>
</template>

<script>
    // @ is an alias to /src
    import LoadInitialInformationComponent from "@alert-system-vue/components/LoadInitialInformationComponent";
    import Navigation from "../components/global/Navigation"

    export default {
        name: 'layout',
        components: { LoadInitialInformationComponent, Navigation },
        created(){

        }
    }
</script>