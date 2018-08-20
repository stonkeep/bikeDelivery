<?php

namespace App\Http\Controllers;

use App\Clientes;
use App\Http\Requests\ClientesRequest;
use App\User;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ClientesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientesRequest $request)
    {
        Clientes::create([
            'cnpj' => $request->cnpj
        ]);
    }

    public function gravaUsuarioParaCliente(Request $request){
        //TODO validar usuário
        $cliente = Clientes::find($request->clienteId);
        $user = new User($request->user);
        $cliente->users()->save($user);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Clientes::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function edit(Clientes $clientes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Clientes::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Clientes::find($id)->delete();
    }
}
