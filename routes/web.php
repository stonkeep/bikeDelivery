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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Rotas para ciclistas
Route::resource('ciclistas', 'CiclistasController');

//Rotas para clientes
Route::resource('clientes', 'ClientesController');
//Rota para o cliente cadastrar um usuário
//TODO fazer a calidação de permissão
Route::post('clientes/usuario', 'ClientesController@gravaUsuarioParaCliente')->name('clientes.usuario');
Route::resource('enderecos', 'EnderecosController');

//Rotas para pedidos
Route::resource('pedidos', 'PedidosController');
