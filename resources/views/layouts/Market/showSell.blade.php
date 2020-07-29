@extends('layouts.master',['title'=>'Show Data - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Detail History Sell
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="mt-3 tableData table table-light">
                <tr>
                    <th>Reff ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Sell Price</th>
                    <th>Sub Total</th>
                </tr>
                @if (count($data))
                @foreach ($data as $item)
                <tr>
                    <td>{{$item->ReffID}}</td>
                    <td>{{$item->ProductName}}</td>
                    <td>{{$item->qty}}</td>
                    <td>{{$item->sell_price}}</td>
                    <td>{{$item->sell_price * $item->qty}}</td>
                </tr>
                @endforeach
                <tr class="bg-success text-white text-bold text-center">
                    <td colspan="5">Total : {{$total[0]->TotalSell}}</td>
                </tr>
                @else
                <tr>
                    <td colspan="4" class="bg-dark text-white text-bold">No Data Available in this Table</td>
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