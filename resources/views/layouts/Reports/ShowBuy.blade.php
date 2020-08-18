@extends('layouts.master',['title'=>'Report Detail Buys - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Detail Buys
    </div>
    <div class="card-body">
        <div class="col-md-12 table-responsive px-2">
            <div class="col-md-12 clearfix mb-2">
                <span class="float-left font-weight-bold">Reff ID : {{count($data) ? $data[0]->ReffID : '-'}}</span>
                <span class="float-right font-weight-bold">Branch : {{count($data) ? $data[0]->BranchName : '-'}}</span>
            </div>
            @if (count($data))
            <div class="col-md-12 clearfix">
                <div class="col-md-7 px-1 float-left btn-group">
                    <form action="{{route('excelShowReport','buy')}}" class="px-0 py-0" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="reffid" value="{{$data[0]->ReffID}}">
                        <button type="submit" class="btn btn-success rounded-0 float-left mr-1 mb-2"
                            {{count($data) ? '' : ' disabled'}}>Export Excel
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </form>
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