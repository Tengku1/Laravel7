@extends('layouts.master',['title'=>'Show Data - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Detail History Buy
    </div>

    <div class="card-body">
        <span>Reff ID : {{$data[0]->ReffID}}</span>
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
                    <td>{{number_format($item->qty)}}</td>
                    <td>Rp. {{number_format($item->buy_price,2)}}</td>
                    <td>Rp. {{number_format($item->buy_price * $item->qty,2)}}</td>
                </tr>
                @endforeach
                <tr class="bg-success text-white text-bold text-center">
                    <td colspan="5">Total : {{$total[0]->TotalBuy}}</td>
                </tr>
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