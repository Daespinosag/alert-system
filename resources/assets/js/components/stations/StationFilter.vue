<style lang="scss">
    span.show-filters{
        display: block;
        margin: auto;
        color: #3e8f3e;
        font-family: 'Josefin Sans', sans-serif;
        cursor: pointer;
        font-size: 14px;
    }
</style>

<template>
    <div id="station-filter">
        <div class="col-md-10 col-md-offset-1" >
            <div class="card">
                <div class="card-head">
                    <div class="">
                        <span class="show-filters" v-on:click="show = !show">{{ show ? 'Ocultar Filtros ↑' : 'Mostrar Filtros ↓' }}</span>
                    </div>
                </div>
                <div class="card-body" v-show="show">
                    <h2 class="card-title">Filtrar Estaciones</h2>
                    <div class="form-group">
                        <label>Buscar:</label>
                        <input type="text" v-model="textSearch" placeholder="Buscar Estaciones"/>
                    </div>
                    <div class="">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
        data(){
            return {
                show: true,
                textSearch: '',
                typeAlerts: []
            }
        },
        watch: {
            textSearch(){
                this.updateFilterDisplay();
            },
        },
        methods: {
            updateFilterDisplay() {
                EventBus.$emit('filters-updated', {text: this.textSearch});
            }
        }
    }
</script>