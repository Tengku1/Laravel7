@extends('layouts.master',['title'=>'Show Data - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Detail History Sell
    </div>

    <div class="card-body">
        <span>Reff ID : {{$data[0]->ReffID}}</span>
        <div class="table-responsive">
            <table class="mt-3 tableData table table-light">
                <tr class="bg-dark text-white text-bold text-center">
                    <td colspan="5" class="px-3">
                        <span class="float-left">Branch : {{$data[0]->BranchName}}</span>
                        <span class="float-right">Total : {{$total[0]->TotalSell}}</span> 
                    </td>
                </tr>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Sell Price</th>
                    <th>Sub Total</th>
                </tr>
                @if (count($data))
                @foreach ($data as $item)
                <tr>
                    <td>{{$item->ProductName}}</td>
                    <td>{{number_format($item->qty)}}</td>
                    <td>Rp. {{number_format($item->sell_price,2)}}</td>
                    <td>Rp. {{number_format($item->sell_price * $item->qty,2)}}</td>
                </tr>
                @endforeach
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