@extends('layouts.master',['title'=>'Report Detail Products - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Detail Products
    </div>
    <div class="card-body">
        <div class="col-md-12 px-2">
            <div class="col-md-12 table-responsive px-2 pt-3">
                @if (count($data))
                <div class="col-md-12">
                    <div class="col-md-6 px-1 float-left btn-group">
                        <form action="{{route('excelShowReport','product')}}" class="px-0 py-0" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$data[0]->ProductID}}">
                            <input type="hidden" name="code" value="{{$data[0]->BranchCode}}">
                            <button type="submit" class="btn btn-success rounded-0 float-left mr-1 mb-2"
                                {{count($data) ? '' : ' disabled'}}>Export Excel
                                <i class="fa fa-file-excel-o"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 px-1 py-0 float-right">
                        <div class="col-md-11 px-1 py-2">
                            <span class="font-weight-bold float-right border-bottom border-dark" title="Branch Name">{{count($data) ? $data[0]->BranchName : '-'}} </span>
                            <span class="font-weight-bold float-right mx-1"> | </span>
                            <span class="font-weight-bold float-right border-bottom border-dark" title="Product Name">
                                {{count($data) ? $data[0]->name : '-'}}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-md-12 row">
                    <table class="mt-3 tableData table table-light px-2 border-bottom border-dark">
                        <tr class="bg-dark text-white">
                            <td colspan="5">Total Quantity : {{count($data) ? number_format($TotalQty[0]->Total) : '0'}}
                            </td>
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
                            <td>{{$item->created_at}}</td>
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