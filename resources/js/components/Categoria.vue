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
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" @click="abrirModal('categoria','registrar')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarCategoria(1,buscar,criterio)" class="form-control" placeholder="Ingrese el nombre de la categoría">
                                    <button type="submit" @click="listarCategoria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in arrayCategoria" :key="categoria.categoriaid">
                                    <td>
                                        <button type="button" @click="abrirModal('categoria','actualizar',categoria)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;

                                        <template v-if="categoria.categoria_estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.categoriaid)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>

                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria.categoriaid)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                    </td>
                                    <td v-text="categoria.categoria_nombre"></td>
                                    <td>
                                        <div v-if="categoria.categoria_estado">
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
                                        <input type="text" v-model="categoria_nombre" class="form-control" placeholder="Nombre de categoría">
                                    </div>
                                </div>

                                <div v-show="errorCategoria" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion == 1" @click="registrarCategoria()" class="btn btn-primary">Guardar</button>
                            <button type="button" v-if="tipoAccion == 2" @click="actualizarCategoria()" class="btn btn-primary">Actualizar</button>
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
                categoriaid : 0,
                categoria_nombre : '',
                arrayCategoria : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCategoria : 0,
                errorMostrarMsjCategoria : [],
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
            listarCategoria(page, buscar, criterio){
                let me = this;
                var url = this.ruta + '/categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayCategoria = respuesta.categorias.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page, buscar, criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarCategoria(page, buscar, criterio);

            },

            registrarCategoria()
            {

                if(this.validarCategoria()){
                    return;
                }

                let me = this;
                axios.post(this.ruta + '/categoria/registrar',{
                    'categoria_nombre' : this.categoria_nombre
                }).then(function (response){
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });

            },

            actualizarCategoria(){
                 if(this.validarCategoria()){
                    return;
                }

                let me = this;
                axios.put(this.ruta + '/categoria/actualizar',{
                    'categoriaid' : this.categoriaid,
                    'categoria_nombre' : this.categoria_nombre
                }).then(function (response){
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },

            desactivarCategoria(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de desactivar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put(this.ruta + '/categoria/desactivar',{
                        'categoriaid' : id
                    }).then(function (response){
                        me.listarCategoria(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'La categoría ha sido desactivado',
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




             activarCategoria(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de activar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put(this.ruta + '/categoria/activar',{
                        'categoriaid' : id
                    }).then(function (response){
                        me.listarCategoria(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'La categoría ha sido activado',
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

            validarCategoria(){
                this.errorCategoria = 0;
                this.errorMostrarMsjCategoria = [];

                if(!this.categoria_nombre){
                    this.errorMostrarMsjCategoria.push("El nombre de la categoría no debe estar vacía.");
                }

                if(this.errorMostrarMsjCategoria.length){
                    this.errorCategoria = 1;
                }

                return this.errorCategoria;
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.categoria_nombre = '';
            },

            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "categoria":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Categoría';
                                this.categoria_nombre = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tipoAccion = 2;
                                this.tituloModal = 'Actualizar Categoría';
                                this.categoriaid = data['categoriaid'];
                                this.categoria_nombre = data['categoria_nombre'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarCategoria(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width : 100% !important;
        position: fixed !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
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
