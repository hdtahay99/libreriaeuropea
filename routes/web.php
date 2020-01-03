<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function(){

    Route::post('/logout','Auth\LoginController@logout')->name('logout');

    Route::post('/notification/get', 'NotificationController@get');


    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');
    
    Route::group(['middleware' => ['Vendedor']], function(){
        Route::get('/producto', 'ProductoController@index');
        Route::get('/productoCat', 'ProductoController@buscarProductoCategoria');
        Route::get('/producto/buscarProductoVenta', 'ProductoController@buscarProductoVenta');
        Route::get('/producto/listarProductoVenta', 'ProductoController@listarProductoVenta');
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        Route::get('/cliente/buscarCliente', 'ClienteController@buscarCliente');
        Route::get('/factura', 'FacturaController@index');
        Route::post('/factura/registrar', 'FacturaController@store');
        Route::put('/factura/desactivar', 'FacturaController@desactivar');
        Route::get('/factura/obtenerCabecera', 'FacturaController@obtenerCabecera');
        Route::get('/factura/obtenerDetalles', 'FacturaController@obtenerDetalles');
    });

    Route::group(['middleware' => ['Administrador']], function(){
        Route::get('/categoria', 'CategoriaController@index');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
    
        Route::get('/producto', 'ProductoController@index');
        Route::post('/producto/registrar', 'ProductoController@store');
        Route::put('/producto/actualizar', 'ProductoController@update');
        Route::put('/producto/desactivar', 'ProductoController@desactivar');
        Route::put('/producto/activar', 'ProductoController@activar');
        Route::get('/producto/buscarProductoVenta', 'ProductoController@buscarProductoVenta');
        Route::get('/producto/listarProductoVenta', 'ProductoController@listarProductoVenta');
        Route::get('/producto/listarPdf', 'ProductoController@listarPdf')->name('productos_pdf');

    
        Route::get('/empleado', 'EmpleadoController@index');
        Route::post('/empleado/registrar', 'EmpleadoController@store');
        Route::put('/empleado/actualizar', 'EmpleadoController@update');
        Route::put('/empleado/desactivar', 'EmpleadoController@desactivar');
        Route::put('/empleado/activar', 'EmpleadoController@activar');
    
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::put('/cliente/desactivar', 'ClienteController@desactivar');
        Route::put('/cliente/activar', 'ClienteController@activar');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        Route::get('/cliente/buscarCliente', 'ClienteController@buscarCliente');

    
        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');
    
        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');

        Route::get('/factura', 'FacturaController@index');
        Route::post('/factura/registrar', 'FacturaController@store');
        Route::post('/factura/registrar2', 'FacturaController@store2');
        Route::put('/factura/desactivar', 'FacturaController@desactivar');
        Route::get('/factura/obtenerCabecera', 'FacturaController@obtenerCabecera');
        Route::get('/factura/obtenerDetalles', 'FacturaController@obtenerDetalles');
        Route::get('/factura/pdf/{facturaid}', 'FacturaController@pdf')->name('pdf');
        Route::get('/dashboard', 'DashboardController');

    });    
    
});


//Route::get('/home', 'HomeController@index')->name('home');
