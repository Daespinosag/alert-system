<template>
    <div class="home">
        <load-initial-information-component :user_id="1"> </load-initial-information-component>
    </div>
</template>

<script>
    // @ is an alias to /src
    import LoadInitialInformationComponent from "@alert-system-vue/components/LoadInitialInformationComponent";

    export default {
        name: 'home',
        components: { LoadInitialInformationComponent },
        created(){
            console.log(this.$router)
            this.$router.push({ name: 'about'});
        }
    }
</script>