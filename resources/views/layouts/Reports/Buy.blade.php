@extends('layouts.master',['title'=>'Report Products - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Product
    </div>
    <div class="card-body">
        <div class="col-md-12 table-responsive px-2">
            <div class="col-md-12 row mb-3">
                <form action="/report/buy" method="post" class="col-md-12 px-3">
                    {{ csrf_field() }}
                    <div class="col-md-3 float-left px-1 mb-2">
                        <label for="date" class="font-weight-bold">From</label>
                        <input type="date" name="fromDate" class="form-control" required>
                    </div>
                    <div class="col-md-3 float-left px-1 mb-2">
                        <label for="date" class="font-weight-bold">To</label>
                        <input type="date" name="toDate" class="form-control" required>
                    </div>
                    <div class="col-md-6 float-left px1 mb-2 pt-4">
                        <button type="submit" class="btn btn-info mt-2 rounded-0 float-left mr-1">Cari <i
                                class="fa fa-search"></i></button>
                        <button type="reset" class="btn btn-danger rounded-0 mt-2 float-left mr-1">Clear <i
                                class="fa fa-eraser"></i></button>
                        <a href="/report/sell/excel" target="_blank">
                            <button class="btn btn-success rounded-0 mt-2 float-right">Export Excel
                                <i class="fa fa-file-excel-o"></i>
                            </button>
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-md-12 mb-3">
                <div class="col-md-7 px-1 float-left">
                    <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Show Entries
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @for ($i = 1; $i <= sizeof($getSizeData); $i++) <a href="/report/buy/paginate/{{$i}}/"
                            class="dropdown-item"> {{$i}}</a>
                            @endfor
                    </div>
                </div>
                <div class="col-md-3 px-1 py-0 float-right">
                    <form action="/report/buy/search" method="get" class="px-0 py-0 input-group">
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
                <table class="mt-3 tableData table table-light px-1 border border-dark">
                    <tr class="bg-dark text-white font-weight-bold text-capitalize text-center">
                        <td colspan="5">Total Sell Price : {{number_format($totalBuy[0],2)}}</td>
                    </tr>
                    <tr>
                        <th>Reff ID</th>
                        <th>Branch Name</th>
                        <th>Created At</th>
                        @if (Auth::user()->roles[0] == "Master")
                        <th>Action</th>
                        @endif
                    </tr>
                    @if (count($data))
                    @foreach ($data as $item)
                    <tr>
                        <td>{{$item->ReffID}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->CreatedAt}}</td>
                        @if (Auth::user()->roles[0] == "Master")
                        <td>
                            <a href="/product/edit/{{$item->slug}}">
                                <button class="btn rounded-0 btn-sm btn-warning" title="Edit"><i
                                        class="fa fa-pencil-square-o"></i></button>
                            </a>
                        </td>
                        @endif
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