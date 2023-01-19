@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Statistica</div>

                <div class="card-body">
                    @include ('errors')

                    @php
                        // $cartiScanate->sortBy('created_at' 'asc')->first();
                        // dd($cartiScanate->sortBy('created_at')->first()->created_at);
                        $primulAn = \Carbon\Carbon::parse($cartiScanate->sortBy('created_at')->first()->created_at)->year;
                    @endphp
                    @for ($an = $primulAn; $an <= \Carbon\Carbon::today()->year; $an++)
                        @php
                            $cartiScanateInAcestAn = $cartiScanate->where('created_at', '>', \Carbon\Carbon::createFromDate($an, 1, 1))
                                                                        ->where('created_at', '<', \Carbon\Carbon::createFromDate($an, 12, 31));
                        @endphp
                        <b>{{ $an }}</b>
                        <br>
                        Cărți scanate: {{ $cartiScanateInAcestAn->count() }}
                        <br>
                        Pagini scanate: {{ $cartiScanateInAcestAn->sum('nr_pagini') }}
                        <br><br>
                    @endfor
                    <br><br><br>
                    <b>Toti anii</b>
                    <br>
                    Total cărți scanate: {{ $cartiScanate->count() }}
                    <br>
                    Total pagini scanate: {{ $cartiScanate->sum('nr_pagini') }}
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
