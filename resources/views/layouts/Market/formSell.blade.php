@extends('layouts.master',['title' => 'Detail Buy - Crux'])


@section('content')
<form action="/market/storeSell" method="post" class="px-0 py-0">
    {{ csrf_field() }}
    <div class="card mb-3">
        <div class="card-header">
            Input Product
        </div>
        <div class="card-body">
            <div class="col-md-6 float-left px-1">
                <label for="id" class="mt-1 mr-2 font-weight-bolder">Product Name</label>
                <select name="product_id" id="id" class="form-control" required>
                    <option value=""></option>
                    @foreach ($product as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="branch_code" value="{{$branch[0]->code}}">
            <input type="hidden" name="id" value="{{$historyId}}">
            <div class="col-md-6 float-left px-1">
                <label for="" class="mt-1 mr-2 font-weight-bolder">Quantity</label>
                <input type="number" name="qty" min="0" class="form-control" required>
            </div>
        </div>
        <div class="card-footer text-muted">
            <button type="button" class="btn border">Cancel</button>
            <button type="submit" class="btn btn-info float-right">Save</button>
        </div>
    </div>
</form>
<div class="card">
    <div class="card-header">
        List Product
    </div>
    <div class="card-body">
        <form action="{{route('SellExecution')}}" method="post" class="px-0 py-0" id="listDetailProduct">

            {{ csrf_field() }}
            @foreach ($data as $item)
            <input type="hidden" name="id" value="{{$data[0]->sellId}}">
            <input type="hidden" name="branch_code" value="{{$branch[0]->code}}">
            @endforeach
            <div class="col-md-12">
                <div class="col-md-6 float-left px-1">
                    <span class="text-bold">Reff ID : {{$historyId}}</span>
                </div>

                <div class="col-md-6 float-left px-1">
                    <span class="text-bold">Total Price : {{$total[0]}}</span>
                    <button type="{{count($data) ? 'submit' : 'button'}}" class="btn btn-success float-right text-white"
                        {{count($data) ? '' : 'disabled'}}>Finish</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12 table-responsive px-4">
        <table class="mt-3 tableData table table-light">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Sell Price</th>
                <th>Sub Total</th>
            </tr>
            @if (count($data))
            @foreach ($data as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->qty}}</td>
                <td>{{$item->buy_price}}</td>
                <td>{{$item->sell_price * $item->qty}}</td>
            </tr>
            <!-- Modal -->
            <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="/market/deleteSell" method="post">
                            {{method_field('delete')}}
                            {{ csrf_field() }}
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                You Want You Sure Delete This Record?
                                <input type="hidden" name="id" id="id" value="{{$item->HistoryProductID}}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect"
                                    data-dismiss="modal">Close</button>
                                <button type="submit"
                                    class="btn btn-danger waves-effect remove-data-from-delete-form">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- End Modal --}}
            @endforeach
            @else
            <tr>
                <td colspan="4" class="bg-dark text-white text-bold">No Data Available in this Table</td>
            </tr>
            @endif
        </table>
    </div>
    <div class="card-footer text-muted">
    </div>
</div>
@endsection