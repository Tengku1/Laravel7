{{-- Extends --}}

@extends('layouts.master')

{{-- ======= --}}

{{-- Section --}}
@php
    $url = request()->url();
@endphp
@section('content')
<div class="card">
    <div class="card-header">
        Markets
    </div>
    <div class="card-body">
        <form action="/market/marketex" class="px-0" method="post">
            @method('patch')
            @csrf
            <table class="col-md-12 mt-3 table table-hover table-light">
                <tr>
                    <td>Product Name</td>
                    <td>:</td>
                    <td>
                        <select name="ProductName" id="" class="form-control">
                            <option value="">Select Product ---</option>
                            @foreach ($data['product'] as $product)
                            <option value="{{$product->name}}">{{$product->name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @if (strstr($url,"buy"))
                <tr>
                    <td>Buy Quantity</td>
                    <td>:</td>
                    <td><input type="text" name="buyqty" placeholder="How many ?" class="form-control"></td>
                </tr>
                @else
                <tr>
                    <td>Sell Quantity</td>
                    <td>:</td>
                    <td><input type="text" name="sellqty" placeholder="How many ?" class="form-control"></td>
                </tr>
                @endif
            </table>
            <div class="col-md-12 btn-group mt-3">
                <button type="submit" class="btn btn-primary">Buy <i class="fa fa-money"></i></button>
                <button type="reset" class="btn btn-danger">Clear <i class="fa fa-trash"></i></button>
                <a href="{{URL::previous()}}">
                    <button type="button" class="btn btn-primary rounded-0">Back</button>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- ======= --}}