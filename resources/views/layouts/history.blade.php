{{-- extends --}}
@extends('layouts.master',['title' => 'Product - Crux'])
{{-- ======= --}}
{{-- section --}}
@section('content')
@if ($data->count())
<div class="col-md-12">
    <div class="col-md-8 float-left">
        <h4 class="text-info mb-3">Histories Sells <i class="fa fa-shopping-cart"></i></h4>
    </div>
</div>
<div class="table-responsive px-1">
    <table class="mt-3 tableData table-hover table table-light">
        <tr>
            <th scope="col">Product Name </th>
            <th scope="col">Branch Name</th>
            <th scope="col">Quantity Total</th>
            <th scope="col">Buy Price</th>
            <th scope="col">Sell Price</th>
        </tr>
        @foreach ($data as $item)
        <td scope="col">{{$item->ProductName}}</td>
        <td scope="col">{{$item->BranchName}}</td>
        <td scope="col">{{$item->qty}}</td>
        <td scope="col">{{$item->buy_price}}</td>
        <td scope="col">{{$item->sell_price}}</td>
        </tr>
        @endforeach
    </table>
</div>

{{-- For Pagination --}}
<div class="btn-group">
    <span class="pt-2 mr-3 text-info">
        Showing {{$data->firstItem()}} to {{$data->lastItem()}} of {{$data->total()}} Entries
    </span>
    {{$data->links()}}
</div>
{{-- EndPagination --}}

@else
<div class="row mt-3 alert alert-info">
    There are no products here
</div>
@endif
@endsection
{{-- ======= --}}
