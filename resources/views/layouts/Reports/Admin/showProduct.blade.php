@extends('layouts.master',['title'=>'Report Detail Products - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Detail Products
    </div>
    <div class="card-body">
        <div class="col-md-12 table-responsive px-2">
            <div class="col-md-12 px-1 py-1 mb-4">
                <span>Products Name : <span class="font-weight-bold">{{count($data) ? $data[0]->name : '-'}}</span>
                <span class="float-right">Branch Name : <span class="font-weight-bold">{{count($data) ? $data[0]->BranchName : '-'}}</span></span>
            </div>
            @if (count($data))
                <div class="col-md-12 mb-3">
                <div class="col-md-7 px-1 float-left btn-group">
                    <form action="{{route('excelShowReport','product')}}" class="px-0 py-0" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="slug" value="{{$data[0]->slug}}">
                        <input type="hidden" name="BranchSlug" value="{{$data[0]->BranchSlug}}">
                        <button type="submit" class="btn btn-success rounded-0 float-left mr-1 mb-2"{{count($data) ? '' : ' disabled'}}>Export Excel
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </form>
                    <div class="dropdown">
                        <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Show Entries
                        </button>
                        @if (count($data))
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @for ($i = 1; $i <= sizeof($data); $i++) 
                            <a href="/report/product/{{$data[0]->BranchSlug}}/{{$data[0]->slug}}/paginate/{{$i}}/" class="dropdown-item">
                                {{$i}}
                            </a>
                            @endfor
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 px-1 py-0 float-right">
                    <form action="/report/product/{{$data[0]->BranchSlug}}/{{$data[0]->slug}}" method="get" class="px-0 py-1 input-group">
                        <input class="form-control py-2 float-left" type="search" value="" placeholder="Search By Qty, Buy Price" id="searchdata" name="by"{{count($data) ? '' : ' disabled'}}>
                        <span class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit"{{count($data) ? '' : ' disabled'}}>
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </form>
                </div>
            </div>
            @endif
            <div class="col-md-12 row">
                <table class="mt-3 tableData table table-light px-2 border-bottom border-dark">
                    <tr class="bg-dark text-white">
                        <td colspan="5">Total Quantity : {{number_format($TotalQty[0]->Total)}}</td>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>Quantity</th>
                        <th>Buy Price</th>
                        <th>Created At</th>
                    </tr>
                    @if (count($data))
                    @php
                        $no=1;
                    @endphp
                    @foreach ($data as $item)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$item->qty}}</td>
                        <td>{{$item->buy_price}}</td>
                        <td>{{$item->created_at->diffForHumans()}}</td>
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
        <a href="/report/products">
            <button class="btn btn-info">Back</button>
        </a>
    </div>
</div>
@endsection