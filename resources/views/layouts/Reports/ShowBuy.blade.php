@extends('layouts.master',['title'=>'Report Detail Buys - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Detail Buys
    </div>
    <div class="card-body">
        <div class="col-md-12 table-responsive px-2">
            <div class="col-md-12 clearfix">
                <span class="float-left font-weight-bold">Reff ID : {{count($data) ? $data[0]->ReffID : '-'}}</span>
                <span class="float-right font-weight-bold">Branch : {{count($data) ? $data[0]->BranchName : '-'}}</span>
            </div>
            @if (count($data) && Auth::user()->roles[0] == "Master")
            <form action="/report/buy/Reff/{{$data[0]->ReffID}}" method="get" class="p-0 col-md-12 clearfix">
                <div class="col-md-12 float-left mt-1">
                    <div class="col-md-4 float-left pr-1 mb-2">
                        <div class="col-md-12"><label for="" class="font-weight-bold">From</label></div>

                        <div class="col-md-12">
                            <input type="date" name="fromDate" id="" class="form-control" value="{{$fromDate != null && $toDate != null ? date("Y-m-d") : ''}}">
                        </div>
                    </div>
                    <div class="col-md-6 float-left pr-1 mb-2">
                        <div class="col-md-12"><label for="" class="font-weight-bold">To</label></div>
                        <div class="col-md-8 float-left mb-2">
                            <input type="date" name="toDate" id="" class="form-control" value="{{$fromDate != null && $toDate != null ? date("Y-m-d") : ''}}">
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
            @if (count($data))
            <div class="col-md-12 clearfix">
                <div class="col-md-7 px-1 float-left btn-group">
                    <form action="{{route('excelShowReport','buy')}}" class="px-0 py-0" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="reffid" value="{{$data[0]->ReffID}}">
                        @if ($fromDate != null && $toDate != null)
                        <input type="hidden" name="fromDate" value="{{$fromDate[0]}}">
                        <input type="hidden" name="toDate" value="{{$toDate[0]}}">
                        @endif
                        <button type="submit" class="btn btn-success rounded-0 float-left mr-1 mb-2"
                            {{count($data) ? '' : ' disabled'}}>Export Excel
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
                            @for ($i = 1; $i <= sizeof($data); $i++) <a
                                href="/report/buy/Reff/{{$data[0]->ReffID}}/paginate/{{$i}}/" class="dropdown-item">
                                {{$i}}
                                </a>
                                @endfor
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-12 clearfix">
                <table class="mt-3 tableData table table-light px-2 border-bottom border-dark">
                    <tr class="bg-dark text-white">
                        <td colspan="5">Total Price : {{number_format($total[0])}}</td>
                    </tr>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Buy Price</th>
                        <th>Sub Total</th>
                    </tr>
                    @if (count($data))
                    @php
                    $no=1;
                    @endphp
                    @foreach ($data as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{number_format($item->qty)}}</td>
                        <td>{{number_format($item->buy_price)}}</td>
                        <td>{{number_format($item->qty * $item->buy_price,2)}}</td>
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
        <a href="/report/buy">
            <button class="btn btn-info">Back</button>
        </a>
    </div>
</div>
@endsection