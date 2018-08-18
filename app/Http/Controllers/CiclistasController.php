<?php

namespace App\Http\Controllers;

use App\Ciclista;
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
        return response()->json(Ciclista::all()->toArray());
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
     * @param  \App\Ciclista  $ciclista
     * @return \Illuminate\Http\Response
     */
    public function show(Ciclista $ciclista)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ciclista  $ciclista
     * @return \Illuminate\Http\Response
     */
    public function edit(Ciclista $ciclista)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ciclista  $ciclista
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Ciclista::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ciclista  $ciclista
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Ciclista::find($id)->delete();
        } catch (\Exception $e) {
            dd($e);
//            if ($e->getCode() == "23000") { //23000 is sql code for integrity constraint violation
//                flash('Resgistro tem dependência, Favor verificar as ligaçõe')->error();
//            } else {
//                flash('Erro '.$e->getCode().' ocorreu. Favor verificar com a administração do sistema')->error();
//            }
        }
    }
}
