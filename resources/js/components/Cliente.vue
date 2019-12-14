<template>
 <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/EuropeaWeb/public/main">Escritorio</a></li>
            </ol>
            
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Clientes
                        <button type="button" @click="abrirModal('cliente','registrar')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="cliente_nombre">Nombre</option>
                                      <option value="cliente_nit">Nit</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarCliente(1,buscar,criterio)" class="form-control" placeholder="Ingrese el nombre/nit del cliente">
                                    <button type="submit" @click="listarCliente(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Dirección</th>
                                    <th>Nit</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cliente in arrayCliente" :key="cliente.clienteid">
                                    <td>
                                        <button type="button" @click="abrirModal('cliente','actualizar', cliente)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;

                                        <template v-if="cliente.cliente_estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCliente(cliente.clienteid)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>

                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarCliente(cliente.clienteid)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                    </td>
                                    <td v-text="cliente.cliente_nombre"></td>
                                    <td v-text="cliente.cliente_apellido"></td>
                                    <td v-text="cliente.cliente_direccion"></td>
                                    <td v-text="cliente.cliente_nit"></td>
                                    <td>
                                        <div v-if="cliente.cliente_estado">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade"  tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="cliente_nombre" class="form-control" placeholder="Nombre del cliente">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellido</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="cliente_apellido" class="form-control" placeholder="Apellido del cliente">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="cliente_direccion" class="form-control" placeholder="Dirección del cliente">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nit</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="cliente_nit" class="form-control" placeholder="Nit del cliente">
                                    </div>
                                </div>

                                <div v-show="errorCliente" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCliente" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion == 1" @click="registrarCliente()" class="btn btn-primary">Guardar</button>
                            <button type="button" v-if="tipoAccion == 2" @click="actualizarCliente()" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
     
        </main>
</template>

<script>
    export default {
        props: ['ruta'],
        data(){
            return{
                clienteid : 0,
                cliente_nombre : '',
                cliente_apellido : '',
                cliente_direccion : '',
                cliente_nit : '',
                arrayCliente : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCliente : 0,
                errorMostrarMsjCliente : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },

        computed :{
            isActived : function(){
                return this.pagination.current_page;
            },

            pagesNumber : function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page)
                {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            },

        },
        methods : {
            listarCliente(page, buscar, criterio){
                let me = this;
                var url = this.ruta + '/cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayCliente = respuesta.clientes.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error.response.data);
                });
            },

            cambiarPagina(page, buscar, criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarCliente(page, buscar, criterio);

            },

            registrarCliente()
            {

                if(this.validarCliente()){
                    return;
                }

                let me = this;
                axios.post(this.ruta + '/cliente/registrar',{
                    'cliente_nombre' : this.cliente_nombre,
                    'cliente_apellido' : this.cliente_apellido,
                    'cliente_direccion' : this.cliente_direccion,
                    'cliente_nit' : this.cliente_nit
                }).then(function (response){
                    me.cerrarModal();
                    me.listarCliente(1,'','nombre');
                }).catch(function (error){
                    console.log(error.response.data);
                });

            },

            actualizarCliente(){
                 if(this.validarCliente()){
                    return;
                }

                let me = this;
                axios.put(this.ruta + '/cliente/actualizar',{
                    'clienteid' : this.clienteid,
                    'cliente_nombre' : this.cliente_nombre,
                    'cliente_apellido' : this.cliente_apellido,
                    'cliente_direccion' : this.cliente_direccion,
                    'cliente_nit' : this.cliente_nit
                }).then(function (response){
                    me.cerrarModal();
                    me.listarCliente(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },

            desactivarCliente(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de desactivar este cliente?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put(this.ruta + '/cliente/desactivar',{
                        'clienteid' : id
                    }).then(function (response){
                        me.listarCliente(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'El cliente ha sido desactivado correctamente.',
                        )
                    }).catch(function (error){
                        console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },




             activarCliente(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de restaurar el cliente?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put(this.ruta + '/cliente/activar',{
                        'clienteid' : id
                    }).then(function (response){
                        me.listarCliente(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'El cliente ha sido restaurado con éxito',
                        )
                    }).catch(function (error){
                        console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },

            validarCliente(){
                this.errorCliente = 0;
                this.errorMostrarMsjCliente = [];

                if(!this.cliente_nombre) this.errorMostrarMsjCliente.push("El nombre del cliente no debe estar vacío.");
                if(!this.cliente_apellido) this.errorMostrarMsjCliente.push("El apellido del cliente no debe estar vacío.");
                if(!this.cliente_direccion) this.errorMostrarMsjCliente.push("La dirección del cliente no debe estar vacío.");
                if(!this.cliente_nit) this.errorMostrarMsjCliente.push("El nit debe ser necesario y no debe estar vacío.");


                if(this.errorMostrarMsjCliente.length){
                    this.errorCliente = 1;
                }

                return this.errorCliente;
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.cliente_nombre = '';
                this.cliente_apellido = '';
                this.cliente_direccion = '';
                this.cliente_nit = '';
            },

            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "cliente":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar nuevo cliente';
                                this.cliente_nombre = '';
                                this.cliente_apellido = '';
                                this.cliente_direccion = '';
                                this.cliente_nit = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tipoAccion = 2;
                                this.tituloModal = 'Actualizar datos cliente';
                                this.clienteid = data['clienteid'];
                                this.cliente_nombre = data['cliente_nombre'];
                                this.cliente_apellido = data['cliente_apellido'];
                                this.cliente_direccion = data['cliente_direccion'];
                                this.cliente_nit = data['cliente_nit'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarCliente(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width : 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color : red !important;
        font-weight: bold;
    }
</style>
