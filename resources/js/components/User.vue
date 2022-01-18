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
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="abrirModal('empleado','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="telefono">Teléfono</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEmpleado(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEmpleado(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Rol</th>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="empleado in arrayEmpleado" :key="empleado.empleadoid">
                                    <td>
                                        <button type="button" @click="abrirModal('empleado','actualizar',empleado)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>&nbsp;

                                        <template v-if="empleado.estado==1">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(empleado.empleadoid)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>

                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarUsuario(empleado.empleadoid)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                    </td>
                                    <td v-text="empleado.rol_nombre"></td>
                                    <td v-text="empleado.usuario"></td>
                                    <td v-text="empleado.empleado_nombre"></td>
                                    <td v-text="empleado.empleado_apellido"></td>
                                    <td v-text="empleado.empleado_telefono"></td>
                                    <td v-text="empleado.empleado_direccion"></td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">x</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="empleado_nombre" class="form-control" placeholder="Nombre de la persona">                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellido (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="empleado_apellido" class="form-control" placeholder="Apellido de la persona">                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Teléfono (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="empleado_telefono" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Dirección (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="empleado_direccion" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Rol (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="rolid">
                                            <option value="0">Seleccione un rol</option>
                                            <option v-for="rol in arrayRol" :key="rol.rolid" :value="rol.rolid" v-text="rol.rol_nombre">

                                            </option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Usuario (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="usuario" class="form-control" placeholder="Nombre de usuario">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Password (*)</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Password de acceso">
                                    </div>
                                </div>

                                <div v-show="errorEmpleado" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEmpleado" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEmpleado()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEmpleado()">Actualizar</button>
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
        data (){
            return {
                empleadoid: 0,
                empleado_nombre : '',
                empleado_apellido : '',
                empleado_telefono : '',
                empleado_direccion : '',
                rolid : 0,
                usuario : '',
                password : '',
                arrayEmpleado : [],
                arrayRol : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEmpleado : 0,
                errorMostrarMsjEmpleado : [],
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
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarEmpleado (page,buscar,criterio){
                if(criterio == 'nombre'){
                    criterio = 'empleado_nombre';
                }
                if(criterio == 'telefono'){
                    criterio = 'empleado_telefono';
                }
                let me=this;
                var url= this.ruta + '/user?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEmpleado = respuesta.empleados.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRol(){
                let me=this;
                var url= this.ruta + '/rol/selectRol';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRol = respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error.response);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPersona(page,buscar,criterio);
            },
            registrarEmpleado(){
                
                if (this.validarEmpleado()){
                    return;
                }
                
                let me = this;

                axios.post(this.ruta + '/user/registrar',{
                    'empleado_nombre': this.empleado_nombre,
                    'empleado_apellido': this.empleado_apellido,
                    'empleado_telefono': this.empleado_telefono,
                    'empleado_direccion': this.empleado_direccion,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rolid': this.rolid
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEmpleado(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarEmpleado(){

               if (this.validarEmpleado()){
                    return;
                }
                
                let me = this;

                axios.put(this.ruta + '/user/actualizar',{
                    'empleado_nombre': this.empleado_nombre,
                    'empleado_apellido': this.empleado_apellido,
                    'empleado_telefono': this.empleado_telefono,
                    'empleado_direccion': this.empleado_direccion,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rolid': this.rolid,
                    'empleadoid': this.empleadoid
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEmpleado(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },            
            validarEmpleado(){
                this.errorEmpleado=0;
                this.errorMostrarMsjEmpleado =[];

                if (!this.empleado_nombre) this.errorMostrarMsjEmpleado.push("El nombre de la Persona no puede estar vacío.");
                if (!this.empleado_apellido) this.errorMostrarMsjEmpleado.push("El apellido de la Persona no puede estar vacío.");
                if (!this.empleado_telefono) this.errorMostrarMsjEmpleado.push("El teléfono de la Persona no puede estar vacío.");
                if (!this.empleado_direccion) this.errorMostrarMsjEmpleado.push("La dirección de la Persona no puede estar vacío.");
                if (!this.usuario) this.errorMostrarMsjEmpleado.push("El nombre de usuario no puede estar vacío.");
                if (!this.password) this.errorMostrarMsjEmpleado.push("El password no puede estar vacío.");
                if (this.rolid==0) this.errorMostrarMsjEmpleado.push("Debes seleccionar un rol para el usuario.");

                if (this.errorMostrarMsjEmpleado.length) this.errorEmpleado = 1;
                return this.errorEmpleado;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.empleado_nombre = '';
                this.empleado_apellido = '';
                this.empleado_telefono = '';
                this.empleado_direccion = '';
                this.usuario='';
                this.password='';
                this.rolid=0;
                this.errorEmpleado=0;
            },
            abrirModal(modelo, accion, data = []){
                this.selectRol();
                switch(modelo){
                    case "empleado":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Usuario';
                                this.empleado_nombre = '';
                                this.empleado_apellido = '';
                                this.empleado_telefono = '';
                                this.empleado_direccion = '';
                                this.usuario='';
                                this.password='';
                                this.rolid=0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Usuario';
                                this.tipoAccion=2;
                                this.empleadoid=data['empleadoid'];
                                this.empleado_nombre = data['empleado_nombre'];
                                this.empleado_apellido = data['empleado_apellido'];
                                this.empleado_telefono = data['empleado_telefono'];
                                this.empleado_direccion = data['empleado_direccion'];
                                this.usuario= data['usuario'];
                                this.password= data['password'];
                                this.rolid= data['rolid'];
                                break;
                            }
                        }
                    }
                }
            },
            desactivarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de desactivar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put(this.ruta + '/user/desactivar',{
                        'userid' : id
                    }).then(function (response){
                        me.listarEmpleado(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'El usuario ha sido desactivado',
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

             activarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: '¿Está seguro de activar a este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.put(this.ruta + '/user/activar',{
                        'userid' : id
                    }).then(function (response){
                        me.listarEmpleado(1,'','nombre');
                                            
                        swalWithBootstrapButtons.fire(
                        'El usuario ha sido activado',
                        )
                    }).catch(function (error){
                        console.log(error.response);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
        },
        mounted() {
            this.listarEmpleado(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
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
        color: red !important;
        font-weight: bold;
    }
</style>
