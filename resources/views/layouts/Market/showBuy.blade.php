@extends('layouts.master',['title'=>'Show Data - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Detail History Buy
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="mt-3 tableData table table-light">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Buy Price</th>
                    <th>Sub Total</th>
                </tr>
                @if (count($data))
                @foreach ($data as $item)
                <tr>
                    <td>{{$item->ProductName}}</td>
                    <td>{{$item->qty}}</td>
                    <td>{{$item->buy_price}}</td>
                    <td>{{$item->buy_price * $item->qty}}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5" class="bg-dark text-white text-bold">No Data Available in this Table</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{URL::previous()}}">
            <button class="btn btn-primary">Back</button>
        </a>
    </div>
</div>
@endsection