<?php

namespace App\Http\Controllers;

use App\ciclista;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\ciclistasRquest;

class CiclistasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('index');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ciclistasRquest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'remember_token' => str_random(10),
        ]);
        $ciclista = new Ciclista;
        $ciclista->cpf = $request->cpf;
        $user->ciclista()->save($ciclista);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ciclista  $criclistas
     * @return \Illuminate\Http\Response
     */
    public function show(ciclista $criclistas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ciclista  $criclistas
     * @return \Illuminate\Http\Response
     */
    public function edit(ciclista $criclistas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ciclista  $criclistas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ciclista $criclistas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ciclista  $criclistas
     * @return \Illuminate\Http\Response
     */
    public function destroy(ciclista $criclistas)
    {
        //
    }
}
