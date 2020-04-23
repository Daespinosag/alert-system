<template>
    <div id="Load-initial-information" class="Load-initial-information"> </div>
</template>

<script>
    import User from "@alert-system-vue/store/models/users/User";

    export default {
        name: "Load-initial-information",
        props: { msg: String, user_id: Number},
        data() {
            return {
                redirectRouteName: 'printAlerts',
            };
        },
        created() {
            if (User.getters('getLoadStatus') !== 2) {
                this.initLoadInformation();
                //this.$router.push({ name: 'printAlerts'});
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
                    .catch(() => { console.log('ahora si tenemos un error')})
            },
            initLoadUserInformation(){
                return this.$store.dispatch('initUserInformation',{ id : this.user_id })
                    .then((response) => response)
                    .catch(() => { })
            },
            initLoadFloodInformation(){
                return this.$store.dispatch('initFloodInformation')
                    .then(() => { console.log(' load : Se cargaron las flood') })
                    .catch(() => { })
            },
            initLoadLandslideInformation(){
                return this.$store.dispatch('initLandslideInformation')
                    .then(() => { console.log(' load : Se cargaron las landslide')  })
                    .catch(() => { })
            },
            initLoadAlertsInformation(){
                return Promise.all([ this.initLoadFloodInformation(), this.initLoadLandslideInformation() ])
                    .then(()=>{
                        console.log(this.$router);
                        this.$router.push({ name: 'printalerts'});
                        console.log('termine de terminar terminar')
                    })
                    .catch(()=>{ console.log('no te lo creo, no termine por un error')})
            }
        }
    }
</script>

<style scoped>

</style>