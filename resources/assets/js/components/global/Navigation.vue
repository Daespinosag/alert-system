<template>
    <div id="unalTop">
        <div class="logo">
            <a href="http://unal.edu.co">
                <svg width="93%" height="93%">
                    <image xlink:href="images/escudoUnal.svg" width="100%" height="100%" class="hidden-print"></image>
                </svg>
            </a>
        </div>

        <div class="firstMenu">
            <ul class="socialLinks hidden-xs">
                <li>
                    <a href="https://www.facebook.com/UNColombia" target="_blank" class="facebook" title="Página oficial en Facebook"></a>
                </li>
                <li>
                    <a href="https://twitter.com/UNColombia" target="_blank" class="twitter" title="Cuenta oficial en Twitter"></a>
                </li>
                <li>
                    <a href="https://www.youtube.com/channel/UCnE6Zj2llVxcvL5I38B0Ceg" target="_blank" class="youtube" title="Canal oficial de Youtube"></a>
                </li>
                <li>
                    <a href="http://agenciadenoticias.unal.edu.co/nc/sus/type/rss2.html" target="_blank" class="rss" title="Suscripción a canales de información RSS"></a>
                </li>
            </ul>
            <div class="navbar-default">
                <nav id="profiles">
                    <ul class="nav navbar-nav dropdown-menu">
                        <li class="item_Aspirantes #>"><a href="index.html#">Aspirantes</a></li>
                        <li class="item_Estudiantes #>"><a href="index.html#">Estudiantes</a></li>
                        <li class="item_Egresados #>"><a href="index.html#">Egresados</a></li>
                        <li class="item_Docentes #>"><a href="index.html#">Docentes</a></li>
                        <li class="item_Administrativos #>"><a href="index.html#">Administrativos</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div id="bs-navbar" class="navbar-collapse collapse navigation"><br>
            <div class="site-url"><a href=""> Sistema de Alerta Temprana</a></div>
            <div class="col-md-7">
                <div class="col-md-11">
                    <div class="btn btn-group">
                        <router-link :to="{ name: 'stations' }" class="btn btn-default"><span class="">Stations</span></router-link>
                    </div>
                    <div class="col-md-8">
                        <a class="filters" v-bind:class="{'active': showFilters}" v-on:click="toggleShowFilters()">
                            <img class="chevron" src="images/chevron-right.svg"/>
                            <img class="chevron-active" src="images/chevron-right-active.svg"/> Filtros
                        </a>
                    </div>
                </div>
                <div class="col-md-1">
                    <img class="hidden-print" alt="Escudo de la República de Colombia" src="images/sealColombia.png" width="50" height="50" />
                    <img class="visible-print margin-img" alt="Escudo de la República de Colombia" src="images/sealColombia_black.png" width="50" height="50" />
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
        computed: {
            showFilters(){
                return this.$store.getters.getShowFilters;
            }
        },
        methods: {
            toggleShowFilters(){
                this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
            },
            setShowPopOut(){
                this.$store.dispatch( 'toggleShowPopOut', { showPopOut: true } );
            },
            clearFilters(){
                EventBus.$emit('clear-filters');
            }
        }
    }
</script>

<style lang="scss">

    @import '~@/abstracts/_variables.scss';

    nav.top-navigation{
        background-color: $white;
        height: 50px;
        border-bottom: 2px solid $dark-color;

        span.logo{
            border-right: 1px solid $dark-color;
            display: block;
            float: left;
            height: 50px;
            line-height: 50px;
            padding: 0px 20px 0px 20px;
            font-family: 'Josefin Sans', sans-serif;
            font-weight: bold;
            color: #000000;

            &:hover{
                color: white;
                background-color: #000000;
            }
        }

        ul.links{
            display: block;
            float: left;

            li{
                display: inline-block;
                list-style-type: none;
                line-height: 50px;
                a{
                    font-family: 'Lato', sans-serif;
                    font-weight: bold;
                    color: #000000;

                &:hover{color: #3e8f3e;}
                }
            }
        }

        div.right{
            float: right;
                img.avatar{
                width: 40px;
                height: 40px;
                border-radius: 40px;
                margin-top: 5px;
                margin-right: 10px;
            }
        }
    }

    a.filters {
        cursor: pointer;
        color: black;
        width: 140px;
        height: 45px;
        border: 2px solid #000000;
        border-radius: 3px;
        text-transform: uppercase;
        display: block;
        float: left;
        text-align: center;
        line-height: 41px;
        margin-top: 15px;
        margin-left: 20px;
        font-family: "Lato", sans-serif;
        font-weight: bold;
        font-size: 16px;

        img{
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
            height: 13px;
        }
        img.chevron-active{
            display: none;
        }
        &.active{
            background-color: #000000;
            color: white;
            img.chevron{
                display: none;
            }
            img.chevron-active{
                display: inline-block;
            }
        }
    }

    span.clear-filters{
        font-size: 16px;
        color: #FFFFFF;
        font-family: "Lato", sans-serif;
        cursor: pointer;
        margin-left: 25px;
        display: block;
        float: left;
        margin-top: 25px;
        img{
            margin-right: 10px;
            float: left;
            margin-top: 6px;
        }
    }

</style>