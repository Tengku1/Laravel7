@extends('layouts.master',['title'=>'Report Sells - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Sells
    </div>
    <div class="card-body">
        <div class="col-md-12 table-responsive px-2">
            <div class="col-md-12 mb-3">
                <div class="col-md-12 px-1 py-1 float-left btn-group">
                    <a href="{{route('excelReport','sell')}}" target="_blank">
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
                            @for ($i = 1; $i <= sizeof($data); $i++) <a href="/report/buy/paginate/{{$i}}/"
                                class="dropdown-item">
                                {{$i}}
                                </a>
                                @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 row">
                <table class="mt-3 tableData table table-light px-2 border-bottom border-dark">
                    <tr class="bg-dark text-white">
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
                        <td colspan="5" class="bg-dark text-white text-bold text-capitalize">there are no reports today
                        </td>
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