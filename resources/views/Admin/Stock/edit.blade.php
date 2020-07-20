@extends('layouts.master',['title'=>'Edit - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Add Data
    </div>
    <div class="card-body">
        <form action="/stock/{{$stocks[0]->id}}/update" method="post">
            @method('patch')
            @csrf
            <input type="hidden" name="id" value="{{$stocks[0]->id}}">
            <div class="form-group row">
                <div class="col-md-12 px-1">
                    <label for="name" class="font-weight-bold">Product Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="{{ $stocks[0]->name}}" value="" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6 px-1">
                    <label for="sell_price" class="font-weight-bold">Sell Price</label>
                    <input type="text" class="form-control" name="sell_price" id="sell_price"
                        placeholder="{{ $stocks[0]->sell_price}}" value="" required>
                </div>
                <div class="col-md-6 px-1">
                    <label for="buyprice" class="font-weight-bold">Buy Price</label>
                    <input type="text" class="form-control" name="buyprice" id="buyprice" placeholder="{{ $stocks[0]->buy_price}}"
                        value="" required>
                </div>

            </div>
            <div class="col-md-12 btn-group mt-3">
                <button type="submit" class="btn btn-success">Update <i class="fa fa-pencil-square-o"></i></button>
                <a href="{{URL::previous()}}">
                    <button type="button" class="btn btn-primary rounded-0">Back</button>
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
