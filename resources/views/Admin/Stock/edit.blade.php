@extends('layouts.master',['title'=>'Edit - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Add Data
    </div>
    <div class="card-body">
        <form action="/stock/{{$stocks['id']}}/update" method="post">
            @method('patch')
            @csrf
            <input type="hidden" name="id" value="{{$stocks['id']}}">
            <div class="form-group row">
                <div class="col-md-6 px-1">
                    <label for="name" class="font-weight-bold">Product Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Product Name ..." value="{{ $stocks['name']}}" required>
                </div>
                <div class="col-md-6 px-1">
                    <label for="code">Branch Code</label>
                    <select name="code" id="code" class="custom-select">
                        <option value="{{$stocks['branch_code']}}">{{$stocks['branch_code']}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6 px-1">
                    <label for="sell_price" class="font-weight-bold">Sell Price</label>
                    <input type="text" class="form-control" name="sell_price" id="sell_price"
                        placeholder="Product Name ..." value="{{ $stocks['sell_price']}}" required>
                </div>
                <div class="col-md-6 px-1">
                    <label for="buyprice" class="font-weight-bold">Buy Price</label>
                    <input type="text" class="form-control" name="buyprice" id="buyprice" placeholder="Product Name ..."
                        value="{{ $stocks['buy_price']}}" required>
                </div>

            </div>
            <div class="col-md-12 btn-group mt-3">
                <button type="submit" class="btn btn-primary">Update <i class="fa fa-pencil-square-o"></i></button>
                <button type="reset" class="btn btn-danger">Clear <i class="fa fa-trash"></i></button>
                <a href="{{URL::previous()}}">
                    <button type="button" class="btn btn-primary rounded-0">Back</button>
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
