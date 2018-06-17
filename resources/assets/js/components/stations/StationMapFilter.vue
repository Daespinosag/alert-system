<style lang="scss">
    div#station-map-filter{
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
        padding: 5px;
        z-index: 999999;
        position: absolute;
        right: 45px;
        top: 50px;
        width: 25%;
    }
</style>

<template>
    <div id="station-map-filter">
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
                textSearch: ''
            }
        },
        watch: {
            textSearch(){
                this.updateFilterDisplay();
            }
        },
        methods: {
            updateFilterDisplay(){
                EventBus.$emit('filters-updated', {
                    text: this.textSearch
                });
            }
        }
    }
</script>