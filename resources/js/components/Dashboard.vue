<template>
<main class="main">
    
    <div class="container-fluid">
        <div class="card">
            
            <div class="card-header">

            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Productos Ingresados</h4>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="ingresos">

                                </canvas>
                            </div>
                        </div>
                        <div class="cart-footer">
                            <p>Productos ingresados de los últimos meses</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ventas</h4>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="ventas">

                                </canvas>
                            </div>
                        </div>
                        <div class="card-content">
                            <p>Ventas de los últimos meses</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
</template>

<script>
export default {
    props: ['ruta'],
    data(){
        return {
            varIngreso:null,
            charIngreso:null,
            ingresos:[],
            varTotalIngreso:[],
            varMesIngreso:[],


            varVenta:null,
            charVenta:null,
            ventas:[],
            varTotalVenta:[],
            varMesVenta:[],
            months:{1: "Enero", 2: "Febrero", 
                    3: "Marzo", 4: "Abril", 
                    5: "Mayo", 6: "Junio", 
                    7: "Julio", 8: "Agosto", 
                    9: "Septiembre", 10: "Octubre",
                    11: "Noviembre", 12: "Diciembre"}
        }
    },
    methods:{
        getIngresos(){
            let me=this;
            var url = this.ruta + '/dashboard';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.ingresos = respuesta.ingresos;
                me.loadIngresos();
            })
            .catch(function (error) {
                console.log(error.response);
            });
        },

        getVentas(){
            let me=this;
            var url = this.ruta + '/dashboard';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.ventas = respuesta.ventas;
                me.loadVentas();
            })
            .catch(function (error) {
                console.log(error.response);
            });
        },

        loadVentas(){
            let me=this;
            me.ventas.map(function (x){
                me.varMesVenta.push(me.months[x.mes]);
                me.varTotalVenta.push(x.total);
            });
            me.varVenta=document.getElementById('ventas').getContext('2d');

            me.charVenta = new Chart(me.varVenta, {
                type: 'bar',
                data: {
                    labels: me.varMesVenta,
                    datasets: [{
                        label: 'Ventas',
                        data: me.varTotalVenta,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',

                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });            
        },

        loadIngresos(){
            let me=this;
            me.ingresos.map(function (x){
                me.varMesIngreso.push(me.months[x.mes]);
                me.varTotalIngreso.push(x.total);
            });
            me.varIngreso=document.getElementById('ingresos').getContext('2d');

            me.charIngreso = new Chart(me.varIngreso, {
                type: 'bar',
                data: {
                    labels: me.varMesIngreso,
                    datasets: [{
                        label: 'Ingresos',
                        data: me.varTotalIngreso,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',

                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    },
    mounted(){
        this.getIngresos();
        this.getVentas();
    }
}
</script>