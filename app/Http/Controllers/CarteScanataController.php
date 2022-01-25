<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use App\Models\CarteScanata;


class CarteScanataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_titlu = \Request::get('search_titlu');
        $search_autor = \Request::get('search_autor');
        $search_inventar = \Request::get('search_inventar');

        $carti_scanate = CarteScanata::with('utilizator')
            ->when($search_titlu, function ($query, $search_titlu) {
                return $query->where('titlu', 'like', '%' . $search_titlu . '%');
            })
            ->when($search_autor, function ($query, $search_autor) {
                return $query->where('autor', 'like', '%' . $search_autor . '%');
            })
            ->when($search_inventar, function ($query, $search_inventar) {
                return $query->where('inventar', $search_inventar);
            })
            ->latest()
            ->simplePaginate(25);
// dd($carti_scanate->first());

        return view('carti_scanate.index', compact('carti_scanate', 'search_titlu', 'search_autor', 'search_inventar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('carti_scanate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['user_id' => $request->user()->id]);
        $carte_scanata = CarteScanata::create($this->validateRequest($request));

        if (count($carti_scanate = CarteScanata::where('titlu', $carte_scanata->titlu)->get()) > 1){
            return redirect('/carti-scanate')->with('warning', 'Cartea „' . ($carte_scanata->titlu ?? '') . '” a fost adăugată cu succes,
                dar aveți în vedere că sunt mai multe cărți introduse în baza de date cu acest titlu!');
        } else {
            return redirect('/carti-scanate')->with('status', 'Cartea „' . ($carte_scanata->titlu ?? '') . '” a fost adăugată cu succes!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CarteScanata  $carte_scanata
     * @return \Illuminate\Http\Response
     */
    public function show(CarteScanata $carte_scanata)
    {
        return view('carti_scanate.show', compact('carte_scanata'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CarteScanata  $carte_scanata
     * @return \Illuminate\Http\Response
     */
    public function edit(CarteScanata $carte_scanata)
    {
        if (Gate::denies('modifica-carte-scanata', $carte_scanata)) {
            abort(403);
        }

        return view('carti_scanate.edit', compact('carte_scanata'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CarteScanata  $carte_scanata
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarteScanata $carte_scanata)
    {
        if (Gate::denies('modifica-carte-scanata', $carte_scanata)) {
            abort(403);
        }

        $request->request->add(['user_id' => $request->user()->id]);
        $carte_scanata->update($this->validateRequest($request, $carte_scanata));


        if (count($carti_scanate = CarteScanata::where('titlu', $carte_scanata->titlu)->get()) > 1){
            return redirect('/carti-scanate')->with('warning', 'Cartea „' . ($carte_scanata->titlu ?? '') . '” a fost modificată cu succes,
                dar aveți în vedere că sunt mai multe cărți introduse în baza de date cu acest titlu!');
        } else {
            return redirect('/carti-scanate')->with('status', 'Cartea „' . ($carte_scanata->titlu ?? '') . '” a fost modificată cu succes!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CarteScanata  $carte_scanata
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarteScanata $carte_scanata)
    {
        if (Gate::denies('modifica-carte-scanata', $carte_scanata)) {
            abort(403);
        }

        $carte_scanata->delete();

        return redirect('/carti-scanate')->with('status', 'Cartea „' . ($carte_scanata->titlu ?? '') . '” a fost ștearsă cu succes!');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request, $carte_scanate = null)
    {
        return $request->validate(
            [
                'titlu' => 'required|max:500',
                'autor' => 'nullable|max:500',
                'inventar' => [
                    'required', 'max:500',
                    Rule::unique('carti_scanate')->ignore($carte_scanate->id ?? ''),
                ],
                'editura' => 'nullable|max:500',
                'anul' => 'nullable|max:500',
                'nr_pagini' => 'required|numeric|integer|max:9999',
                'user_id' => 'required',
            ],
            [

            ]
        );
    }
}
