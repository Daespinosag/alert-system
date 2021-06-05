<template>
    <div id="Load-initial-information" class="Load-initial-information"> </div>
</template>

<script>
    import User from "@alert-system-vue/store/models/users/User";
    import { EventBus } from "../event-bus";

    export default {
        name: "Load-initial-information",
        props: { msg: String, user_id: Number},
        data() {
            return {
                redirectRouteName: 'PrintAlerts',
            };
        },
        created() {
            if (User.getters('getLoadStatus') !== 2) {
                this.initLoadInformation();
            }
        },
        computed: {

        },
        methods: {
            initLoadInformation(){
                Promise.all([ this.initLoadUserInformation() ])
                    .then(() => {
                        this.initLoadAlertsInformation()
                    })
                    .catch(() => { EventBus.$emit("message-logged", {error: true, message: "ahora si tenemos un error"})
                        console.log('ahora si tenemos un error')
                    })
            },
            initLoadUserInformation(){
                return this.$store.dispatch('initUserInformation',{ id : this.user_id })
                    .then((response) => response)
                    .catch(() => { })
            },
            initLoadFloodInformation(){
                return this.$store.dispatch('initFloodInformation')
                    .then(() => { EventBus.$emit("message-logged", {error: false, message: "Se cargaron las flood"})
                        console.log(' load : Se cargaron las flood') })
                    .catch(() => { })
            },
            initLoadLandslideInformation(){
                return this.$store.dispatch('initLandslideInformation')
                    .then(() => { EventBus.$emit("message-logged", {error: false, message: "Se cargaron las landslide"})
                        console.log(' load : Se cargaron las landslide')  })
                    .catch(() => { })
            },
            initLoadAlertsInformation(){
                return Promise.all([ this.initLoadFloodInformation(), this.initLoadLandslideInformation() ])
                    .then(()=>{
                        this.$router.push({ name: /*this.redirectRouteName*/ 'PrintAlerts' });
                        EventBus.$emit("message-logged", {error: false, message: "Toda la información cargada correctamente"})
                        console.log('termine de terminar terminar')
                    })
                    .catch(()=>{ EventBus.$emit("message-logged", {error: true, message: "No se termió de cargar informacion"})
                        console.log('no te lo creo, no termine por un error')})
            }
        }
    }
</script>

<style scoped>

</style>