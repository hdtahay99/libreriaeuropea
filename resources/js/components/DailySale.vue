<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/EuropeaWeb/public/main">Escritorio</a></li>
        </ol>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Reportes Venta
                </div>
                
                <div class="card-body">
                    <div class="form-group">
                        <h4>Reporte Venta Diaria</h4>
                        <hr />
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="form-group">
                                    <label class="form-control-label">Fecha Venta</label>
                                    <datepicker :bootstrap-styling="true" placeholder="Seleccione fecha" :language="es" :format="customFormatter" v-model="fecha"></datepicker>
                                </div>
                                <button @click="generarReporte()" type="submit" class="btn btn-info btn-download">
                                    <i class="fa fa-file"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />

            </div>
        </div>
    
    </main>
</template>

<script>
    import moment from 'moment';
    import Datepicker from 'vuejs-datepicker';
    import { es } from 'vuejs-datepicker/dist/locale';

    export default {
        props: ['ruta'],
        data(){
            return{
                es: es,
                fecha: '',
            }
        },
        computed :{
        },
        components: {
            Datepicker
        },
        methods : {
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
            generarReporte(){
                let me = this;

                if(me.fecha == '' || me.fecha == null || me.fecha == 'INVALID_DATE')
                {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Seleccione una fecha para generar el reporte de venta diaria',
                        })
                }
                else 
                {
                    window.open(me.ruta + '/reporte/ventas/daily/'+me.customFormatter(me.fecha)+','+'_blank');
                }
            }
        },

        mounted() {
        }
    }
</script>
<style>
    .btn-download{
        margin: 29px 0px 0px 0px;
        height: 35px;
    }
    .card-body{
        height: 27rem;
    }
</style>
