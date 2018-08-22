<?php

namespace App\Http\Controllers;

use App\Clientes;
use App\enderecos;
use App\Http\Requests\EnderecosRequest;
use Illuminate\Http\Request;

class EnderecosController extends Controller
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
     * @param  \App\Http\Requests\EnderecosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EnderecosRequest $request)
    {
        $endereco = new Enderecos($request->endereco);
        Clientes::find($request->cliente)->enderecos()->save($endereco);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Enderecos  $enderecos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Enderecos::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\enderecos  $enderecos
     * @return \Illuminate\Http\Response
     */
    public function edit(enderecos $enderecos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\enderecos  $enderecos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Enderecos::find($id)->update($request->endereco);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\enderecos  $enderecos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Enderecos::find($id)->delete();
    }
}
