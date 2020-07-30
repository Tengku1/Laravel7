@extends('layouts.master',['title'=>'Report Products - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Report Product
    </div>
    <div class="card-body">
        <div class="col-md-12 table-responsive px-2">
            <table class="mt-3 tableData table table-light">
                <tr>
                    <th>Product Name</th>
                    <th>Sell Price</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                @if (count($data))
                @foreach ($data as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>Rp. {{number_format($item->sell_price,2)}}</td>
                    <td>{{$item->status}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        <button type="button" class="btn rounded-0 btn-sm btn-danger delete" data-toggle="modal"
                            data-id="{{$item->HistoryProductID}}" data-target="#delete" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
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

    <div class="card-footer text-muted">
    </div>
</div>
@endsection