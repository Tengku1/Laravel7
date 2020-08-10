@extends('layouts.master',['title'=>'Report Products - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Products
    </div>
    <div class="card-body">
        <div class="col-md-12 px-3">
            <div class="float-left mr-1 mb-1">
                <a href="{{route('excelReport','product')}}" target="_blank">
                    <button class="btn btn-success rounded-0 float-left">Export Excel
                        <i class="fa fa-file-excel-o"></i>
                    </button>
                </a>
            </div>
            @if (Auth::user()->roles[0] == "Master")
            <div class="dropdown float-left mr-1 mb-1">
                <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Branch List
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @for ($i = 0; $i < sizeof($branch); $i++) <a href="/report/products/{{$branch[$i]->slug}}/"
                        class="dropdown-item">
                        {{$branch[$i]->name}}
                        </a>
                        @endfor
                </div>
            </div>
            @endif
            @if (sizeof($data) != 1)
            <div class="dropdown float-left mr-1 mb-1">
                <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Show Entries
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @for ($i = 1; $i <= sizeof($data); $i++) <a href="/report/buy/paginate/{{$i}}/"
                        class="dropdown-item">
                        {{$i}}
                        </a>
                        @endfor
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-12 table-responsive px-2">
            <div class="col-md-12 row">
                <table class="mt-3 tableData table table-light px-2 border-bottom border-dark">
                    @if (count($BranchName))
                    <tr class="bg-dark text-white">
                        <th colspan="3" class="font-weight-bold">
                            {{$BranchName[0]->name}}
                        </th>
                    </tr>
                    <tr>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </tr>
                    @else
                    <tr class="bg-dark text-white">
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </tr>
                    @endif

                    @if (count($data))
                    @foreach ($data as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>
                            <a href="/report/product/{{$item->BranchCode}}/{{$item->ProductID}}">
                                <button class="btn rounded-0 btn-sm btn-info" title="Edit">
                                    Details <i class="fa fa-eye"></i>
                                </button>
                            </a>
                        </td>
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
        @if (count($data))
        @include('layouts.paginationTable')

        @endif
        {{-- == Pagination == --}}
    </div>

    <div class="card-footer text-muted">
    </div>
</div>
@endsection