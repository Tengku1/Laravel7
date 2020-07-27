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
        <form action="/market/stockSell/" method="post" class="px-0 py-0" id="listDetailProduct">

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
        <div class="col-md-12 mt-5">
            <div class="col-md-6 float-left">
                <div class="dropdown float-left mb-2">
                    <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        {{count($data)  ? '' : ' disabled'}}>
                        Show Entries
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @for ($i = 1; $i <= sizeof($data); $i++) <a href="/market/detail/sell/{{$i}}"
                            class="dropdown-item">{{$i}}</a>
                            @endfor
                    </div>
                </div>
            </div>
            <div class="col-md-6 btn-group float-left">
                <form action="/market/detail/sell" method="get" class="px-0 py-0 input-group">
                    <input class="form-control rounded-0 py-2 col-md-10 py-2 px-2 float-left" type="search" value=""
                        {{count($data) ? '' : 'disabled'}} placeholder="Search ..." id="searchdata" name="by">
                    <button class="btn btn-info rounded-0" {{count($data) ? '' : 'disabled'}} type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
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