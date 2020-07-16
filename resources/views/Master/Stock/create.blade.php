@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-header">
        Add Data
    </div>
    <div class="card-body">
        <form action="/stock/store/{{$data['code']}}" method="post">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="name" class="font-weight-bold">Product Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Product Name ..." required>
                    @error('name')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="sell_price" class="font-weight-bold">Sell Price</label>
                    <input type="text" class="form-control" name="sell_price" id="sell_price" placeholder="Rp ..." required>
                    @error('sell_price')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-12 btn-group mt-3">
                <button type="submit" class="btn btn-primary">Add Data <i class="fa fa-plus"></i></button>
                <a href="/product">
                    <button type="button" class="btn btn-primary rounded-0">Back</button>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

