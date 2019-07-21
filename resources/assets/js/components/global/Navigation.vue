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
            <!--<ul class="socialLinks hidden-xs">
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
            </ul> -->
            <div class="navbar-default">
                <nav id="profiles">
                    <ul class="nav navbar-nav dropdown-menu">
                        <li class="item_Egresados #>"><router-link :to="{ name: 'stations' }"><span class=""> Sistemas de Alerta Temprana</span></router-link></li>
                        <li class="item_Egresados #>" v-show="true" ><router-link :to="{ name: 'ConsultAlert' }" v-show="$route.name != 'ConsultAlert'"><span class="">Consultar Alertas</span></router-link></li>
                        <li class="item_Egresados #>"><a href="http://satman.manizales.unal.edu.co/logout">Cerrar Sesión</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {},
        methods: {
            setShowPopOut(){
                this.$store.dispatch( 'toggleShowPopOut', { showPopOut: true } );
            },
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