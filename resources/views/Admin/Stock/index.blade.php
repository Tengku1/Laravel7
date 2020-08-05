{{-- extends --}}
@extends('layouts.master',['title' => 'Stock - Crux'])
{{-- ======= --}}

{{-- section --}}
@section('content')
@php
$no = 1;
@endphp
<h4 class="text-info mb-3">Stock Product <i class="fa fa-shopping-cart"></i></h4>

{{-- If the data is not empty --}}
@if ($data->count())
<div class="col-md-12 py-2">
    <div class="col-md-8 float-left mb-2">
        <div class="dropdown float-left mb-2">
            <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Show Entries
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @for ($i = 1; $i <= sizeof($getSizeData); $i++) 
                    <a href="/stock/{{$data[0]->branch_slug}}/paginate{{$i}}/" class="dropdown-item">{{$i}}</a>
                @endfor
            </div>
        </div>
    </div>
    <div class="col-md-4 float-left mb-2">
        <form action="/stock/search" method="get" class="px-0 py-0 input-group">
            <input class="form-control py-2 float-left" type="search" value="" placeholder="Search ..." id="searchdata" name="by">
            <span class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </form>
    </div>
</div>
<div class="table-responsive px-1 py-2">
    <table class="tableData table table-light">
        <tr class="bg-dark text-white">
            <th>No</th>
            <th>Product Name</th>
            <th>Branch Name</th>
            <th>Buy Price</th>
            <th>Quantity</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{$no++}}</td>
            <td>{{$item->ProductName}}</td>
            <td>{{$item->BranchName}}</td>
            <td>Rp. {{number_format($item->buy_price)}}</td>
            <td>{{number_format($item->qty)}}</td>
        </tr>
        @endforeach
    </table>
</div>

{{-- Pagination --}}
@include('layouts.paginationTable')
{{-- == Pagination == --}}


@else
{{-- If Data is Empty --}}
<div class="row mt-3 alert alert-info">
    No Data Available
</div>
{{-- End If --}}
@endif
@endsection