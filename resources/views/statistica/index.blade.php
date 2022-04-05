@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Statistica</div>

                <div class="card-body">
                    @include ('errors')


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
