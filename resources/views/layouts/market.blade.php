{{-- Extends --}}

@extends('layouts.master',['title' => 'Market - Crux'])

{{-- ======= --}}

{{-- Section --}}

@section('content')
@php
$url = request()->url();
@endphp

<div class="col-12 mt-4 px-1">
    <div class="col-6 market float-left px-1">
        <div class="card col-md-12">
            <div class="card-header">Buy Product</div>
            <div class="card-body text-center">
                <i class="fa fa-money"></i>
            </div>
            <a href="/market/buy">
                <div class="card-footer">
                    Buy Now
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 market float-left px-1">
        <div class="card col-md-12">
            <div class="card-header">Sell Product</div>
            <div class="card-body text-center">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="/market/sell">
                <div class="card-footer">
                    Sell Now
                </div>
            </a>
        </div>
    </div>
</div>

@endsection

{{-- end section --}}