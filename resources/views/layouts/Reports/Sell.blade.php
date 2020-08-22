@extends('layouts.master',['title'=>'Report Sells - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Sells
    </div>
    <div class="card-body">
        <div class="col-md-12 table-responsive px-2">
            @if (Auth::user()->roles[0] == "Master")
            @if ($branchSelected != null)
            <form action="/report/sell/{{$branchSelected[0]}}" method="get"
                class="p-0 col-md-12 clearfix">
                @else
                <form action="/report/sell" method="get" class="p-0 col-md-12 clearfix">
                    @endif
                    <div class="col-md-12 float-left mt-1">
                        <div class="col-md-4 float-left pr-1 mb-2">
                            <div class="col-md-12"><label for="" class="font-weight-bold">From</label></div>
                            <div class="col-md-12">
                                <input type="date" name="fromDate" id="" class="form-control" value="{{$fromDate[0]}}">
                            </div>
                        </div>
                        <div class="col-md-6 float-left pr-1 mb-2">
                            <div class="col-md-12"><label for="" class="font-weight-bold">To</label></div>
                            <div class="col-md-8 float-left mb-2">
                                <input type="date" name="toDate" id="" class="form-control" value="{{$toDate[0]}}">
                            </div>
                            <div class="col-md-4 float-left pl-1 btn-group">
                                <button type="submit" class="btn bg-info text-white rounded-0 mr-1 float-left">Search <i
                                        class="fa fa-search"></i></button>
                                <button type="clear" class="btn bg-danger text-white rounded-0 mr-1 float-left">Clear <i
                                        class="fa fa-eraser"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                @endif
                <div class="col-md-12 mb-3">
                    <a href="{{route('excelReport','sell')}}" target="_blank">
                        <button class="btn btn-success rounded-0 float-left mr-1 mb-2">Export Excel
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </a>
                    @if (Auth::user()->roles[0] == "Master")
                    <div class="dropdown float-left mr-1 mb-1">
                        <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Branch List
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @for ($i = 0; $i < sizeof($branch); $i++) <a href="/report/sell/{{$branch[$i]->slug}}/"
                                class="dropdown-item">
                                {{$branch[$i]->name}}
                                </a>
                                @endfor
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-12 row">
                    <table class="mt-3 tableData table table-light px-2 border-bottom border-dark">
                        <tr class="bg-dark text-white">
                            <td colspan="3">Branch : {{$branchSelected == null ? "All Branches" : $branchSelected[0]}}
                            </td>
                        </tr>
                        <tr>
                            <th>Reff ID</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                        @if (count($data))
                        @foreach ($data as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{number_format($item->TotalQty)}}</td>
                            <td>
                                <a href="/report/sell/Reff/{{$item->id}}">
                                    <button class="btn rounded-0 btn-sm btn-info" title="Edit">
                                        Details <i class="fa fa-eye"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="bg-dark text-white text-bold text-capitalize">there are no reports
                                today
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </form>
        </div>
        {{-- Pagination --}}
        @include('layouts.paginationTable')
        {{-- == Pagination == --}}
    </div>

    <div class="card-footer text-muted">
    </div>
</div>
@endsection