@extends('layouts.master',['title' => 'Detail Buy - Crux'])


@section('content')
<form action="/market/storeBuy" method="post" class="px-0 py-0">
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
                        @foreach ($data as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                </select>
            </div>
            <input type="hidden" name="buyId" value="{{$historyBuy[0]->id}}">
            <div class="col-md-6 float-left px-1">
                <label for="" class="mt-1 mr-2 font-weight-bolder">Buy Price</label>
                <input type="number" name="buy_price" min="0" class="form-control" required>
            </div>
            <div class="col-md-12 float-left px-1">
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

<form action="/market/stockBuy/" method="post" class="px-0 py-0" id="listDetailProduct">

    <div class="card">
        <div class="card-header">
            List Detail Product
        </div>
        <div class="card-body">
            {{ csrf_field() }}
            <div class="col-md-12">
                <div class="col-md-6 float-left px-1">
                    <span class="text-bold">Reff ID : {{$historyBuy[0]->id}}</span>
                </div>
                <div class="col-md-6 float-left px-1">
                    <span class="text-bold">Total Price : {{$total[0]}}</span>
                    <button type="{{count($history) ? 'submit' : 'button'}}" class="btn btn-success float-right text-white" {{count($history) ? '' : 'disabled'}}>Finish</button>
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <div class="col-md-6 float-left">
                    <div class="dropdown float-left mb-2">
                        <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            {{count($history)  ? '' : ' disabled'}}>
                            Show Entries
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @for ($i = 1; $i <= sizeof($history); $i++) <a href="/market/detail/buy/{{$i}}"
                                class="dropdown-item">{{$i}}</a>
                                @endfor
                        </div>
                    </div>
                </div>
                <div class="col-md-6 btn-group float-left">
                    <form action="/market/detail/search" method="get" class="px-0 py-0 input-group">
                        <input class="form-control rounded-0 py-2 col-md-10 py-2 px-2 float-left" type="search" value=""
                            placeholder="Search ..." id="searchdata" name="by">
                        <button class="btn btn-info rounded-0" type="submit">
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
                    <th>Buy Price</th>
                    <th>Sub Total</th>
                </tr>
                @if (count($history))
                @foreach ($history as $item)
                <input type="hidden" name="id" value="{{$item->buyId}}">
                <input type="hidden" name="branch_code" value="{{$branch[0]->code}}">    
                <tr>
                    <td>{{$item->name}}</td>
                    <td>{{$item->qty}}</td>
                    <td>{{$item->buy_price}}</td>
                    <td>{{$item->buy_price * $item->qty}}</td>
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
</form>
@endsection