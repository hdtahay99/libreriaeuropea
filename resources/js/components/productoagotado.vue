<template>
 <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Productos
                        <button type="button" @click="abrirModal('producto','registrar')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="cargarPdf()" class="btn btn-info" >
                            <i class="icon-doc"></i>&nbsp;Imprimir Reporte
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarProducto(1,buscar,criterio)" class="form-control" placeholder="Ingrese el nombre del producto">
                                    <button type="submit" @click="listarProducto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código de barra</th>    
                                    <th>Categoría</th>
                                    <th>Nombre producto</th>
                                    <th>Existencia</th>
                                    <th>Precio compra</th>
                                    <th>Precio venta</th>
                                    <th>Visualización</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in arrayProducto" :key="producto.productoid">
                                    <td>
                                        <button type="button" @click="abrirModal('producto','actualizar',producto)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;

                                        <template v-if="producto.producto_estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarProducto(producto.productoid)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>

                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarProducto(producto.productoid)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                    </td>
                                    <td v-text="producto.producto_barra"></td>
                                    <td v-text="producto.categoria_nombre"></td>
                                    <td v-text="producto.producto_nombre"></td>
                                    <td v-text="producto.producto_existencia"></td>
                                    <td v-text="producto.producto_pcompra"></td>
                                    <td v-text="producto.producto_pventa"></td>
                                    <td><img width="100" height="100" v-bind:src="'/uploads/'+producto.producto_imagen" /></td>
                                    <td>
                                        <div v-if="producto.producto_estado">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="categoriaid">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="categoria in arrayCategoria" :key="categoria.categoriaid" :value="categoria.categoriaid" v-text="categoria.categoria_nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código de barras</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="producto_barra" class="form-control" placeholder="Codigo de barras">
                                        <barcode :value="producto_barra" :options=" { format : 'EAB-13' } " >
                                            Generando código de barras.
                                        </barcode>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Producto</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="producto_nombre" class="form-control" placeholder="Nombre de producto">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="producto_existencia" class="form-control" placeholder="Existencia de producto">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio compra</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="producto_pcompra" class="form-control" placeholder="Precio de compra">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio venta</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="producto_pventa" class="form-control" placeholder="Precio de venta">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Imagen</label>
                                    <div class="col-md-9">
                                        <input type="file" id="image" class="form-control" placeholder="Cargar imagen del producto" name="image" @change="getImage" accept="image/*">
                                    </div>
                                </div>

                                <div v-show="errorProducto" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion == 1" @click="registrarProducto()" class="btn btn-primary">Guardar</button>
                            <button type="button" v-if="tipoAccion == 2" @click="actualizarProducto()" class="btn btn-primary">Actualizar</button>
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
    import VueBarcode from 'vue-barcode';
    export default {
        data(){
            return{
                productoid : 0,
                categoriaid :0,
                producto_nombre : '',
                categoria_nombre :'',
                producto_existencia :0,
                producto_barra : 0,
                producto_pcompra : 0,
                producto_pventa : 0,
                producto_imagen : null,
                arrayProducto : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorProducto : 0,
                errorMostrarMsjProducto : [],
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
                buscar : '',
                arrayCategoria : []
            }
        },
        components: {
            'barcode': VueBarcode
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
            getImage(event){
                //Asignamos la imagen a  nuestra data
                this.producto_imagen = event.target.files[0];
            },
            listarProducto(page, buscar, criterio){
                if(criterio == 'nombre')
                {
                    criterio = 'producto_nombre';
                }
                let me = this;
                var url = '/producto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProducto = respuesta.productos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cargarPdf(){
                window.open('http://127.0.0.1:8000/producto/listarPdf','_blank');
            },

            selectCategoria(){
                let me = this;
                var url = '/categoria/selectCategoria';
                axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayCategoria = respuesta.categorias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           
            cambiarPagina(page, buscar, criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarProducto(page, buscar, criterio);
            },

            registrarProducto()
            {
                if(this.validarProducto()){
                   return;
                }
                var data = new FormData();
                data.append('categoriaid',this.categoriaid);
                data.append('producto_nombre',this.producto_nombre);
                data.append('producto_existencia',this.producto_existencia);
                data.append('producto_barra',this.producto_barra);
                data.append('producto_pcompra',this.producto_pcompra);
                data.append('producto_pventa',this.producto_pventa);
                data.append('producto_imagen',this.producto_imagen);
                let me = this;
                axios.post('/producto/registrar',data).then(function (response){
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                })

            },

            actualizarProducto(){
                 if(this.validarProducto()){
                    return;
                }
                var data = new FormData();
                data.append('productoid', this.productoid);
                data.append('categoriaid',this.categoriaid);
                data.append('producto_nombre',this.producto_nombre);
                data.append('producto_existencia',this.producto_existencia);
                data.append('producto_barra',this.producto_barra);
                data.append('producto_pcompra',this.producto_pcompra);
                data.append('producto_pventa',this.producto_pventa);
                data.append('producto_imagen',this.producto_imagen);
                data.append('_method', 'PUT');
                let me = this;
                axios.post('/producto/actualizar',data).then(function (response){
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');
                }).catch(function (error){
                    console.log(error.response);
                });
            },

            desactivarProducto(id){
                
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de desactivar este producto?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put('/producto/desactivar',{
                        'productoid' : id
                    }).then(function (response){
                        me.listarProducto(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'El producto ha sido desactivado',
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

             activarProducto(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de restaurar este producto?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put('/producto/activar',{
                        'productoid' : id
                    }).then(function (response){
                        me.listarProducto(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'El producto ha sido restaurado',
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

            validarProducto(){
                this.errorProducto = 0;
                this.errorMostrarMsjProducto = [];

                if(this.categoriaid == 0) this.errorMostrarMsjProducto.push("Seleccione una categoría.");
                if(!this.producto_nombre) this.errorMostrarMsjProducto.push("El nombre del producto no debe de estar vacío.");
                if(!this.producto_existencia) this.errorMostrarMsjProducto.push("El valor del stock debe de se un número y no debe estar vacío.");
                if(!this.producto_pcompra) this.errorMostrarMsjProducto.push("Es necesario ingresar el precio de compra.");
                if(!this.producto_pventa) this.errorMostrarMsjProducto.push("Es necesario ingresar el precio de venta.");
                

                if(this.errorMostrarMsjProducto.length){
                    this.errorProducto = 1;
                }

                return this.errorProducto;
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.categoria_nombre = '';
                this.categoriaid = 0;
                this.producto_nombre = '';
                this.producto_existencia ='';
                this.producto_barra = '';
                this.producto_pcompra = '';
                this.producto_pventa = '';
                this.producto_imagen = null;
                this.errorProducto = 0;
                document.getElementById("image").value = "";
            },

            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "producto":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Insertar nuevo producto';
                                this.producto_nombre = '';
                                this.categoriaid = 0;
                                this.producto_nombre = '';
                                this.producto_existencia ='';
                                this.producto_barra = '';
                                this.producto_pcompra = '';
                                this.producto_pventa = '';
                                this.producto_imagen = null;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tipoAccion = 2;
                                this.tituloModal = 'Actualizar Producto';
                                this.productoid = data['productoid'];
                                this.categoriaid = data['categoriaid'];
                                this.producto_nombre = data['producto_nombre'];
                                this.producto_existencia = data['producto_existencia'];
                                this.producto_barra = data['producto_barra'];
                                this.producto_pcompra = data['producto_pcompra'];
                                this.producto_pventa = data['producto_pventa'];
                                this.producto_imagen = null;
                                break;
                            }
                        }
                    }
                }
                this.selectCategoria();
            }
        },
        mounted() {
            this.listarProducto(1,this.buscar,this.criterio);
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
