@extends('layouts.master',['title'=>'Report Products - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Product
    </div>
    <div class="card-body">
        <div class="col-md-12 table-responsive px-2">
            <div class="col-md-12 mb-3">
                <div class="col-md-7 px-1 float-left btn-group">
                    <a href="/report/sell/excel" target="_blank">
                        <button class="btn btn-success rounded-0 float-left mr-1 mb-2">Export Excel
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Show Entries
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @for ($i = 1; $i <= sizeof($data); $i++) 
                            <a href="/report/buy/paginate/{{$i}}/" class="dropdown-item">
                                {{$i}}
                            </a>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="col-md-3 px-1 py-0 float-right">
                    <form action="/report/buy/search" method="get" class="px-0 py-1 input-group">
                        <input class="form-control py-2 float-left" type="search" value="" placeholder="Search ..."
                            id="searchdata" name="by">
                        <span class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </form>
                </div>
            </div>
            <div class="col-md-12 row">
                <table class="mt-3 tableData table table-light px-2 border-bottom border-dark">
                    <tr class="bg-dark text-white">
                        <td colspan="3">Total Quantity : {{number_format($TotalQty)}}</td>
                    </tr>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Buy Price</th>
                    </tr>
                    @if (count($data))
                    @foreach ($data as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>{{$item->buy_price}}</td>
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
        {{-- Pagination --}}
        @include('layouts.paginationTable')
        {{-- == Pagination == --}}
    </div>

    <div class="card-footer text-muted">
    </div>
</div>
@endsection