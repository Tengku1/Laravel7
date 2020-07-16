{{-- extends --}}
@extends('layouts.master',['title' => 'Product - Crux'])
{{-- ======= --}}
{{-- section --}}
@section('content')
<h4 class="text-info mb-3">Histories Sells <i class="fa fa-shopping-cart"></i></h4>
@if ($data->count())
<div class="col-md-12">
    <div class="col-md-8 float-left">
        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <a class="dropdown-item"><i class="fa fa-print"></i> Print</a>
            <a class="dropdown-item" target="_blank" href="/product/excel"><i class="fa fa-file-excel-o"></i> Export
                Excel</a>
        </div>
    </div>
    <div class="col-md-4 float-left">
        <div class="input-group col-md-12">
            <input class="form-control py-2" type="search" value="" placeholder="Search ..." id="searchdata">
            <span class="input-group-append">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </div>
</div>
<div class="table-responsive px-1">
    <table class="mt-3 tableData table-hover table table-light">
        <tr>
            <th><i class="fa fa-check"></i></th>
            <th scope="col">Product Name </th>
            <th scope="col">Branch Name</th>
            <th scope="col">Quantity Total</th>
            <th scope="col">Buy Price</th>
            <th scope="col">Sell Price</th>
        </tr>
        @foreach ($data as $item)
        <td scope="col"><input type="checkbox" name="list[]" id=""></td>
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
