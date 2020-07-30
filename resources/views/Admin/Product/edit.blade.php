@extends('layouts.master',['title'=>'Edit - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Update Data
    </div>
    <div class="card-body">
        <form action="/product/{{$data[0]->id}}" method="post">
            @method('patch')
            @csrf
            <input type="hidden" name="id" value="{{$data[0]->id}}">
            <div class="form-group row">
                <div class="col-md-12 px-1">
                    <label for="name" class="font-weight-bold">Product Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="" placeholder="values : {{ $data[0]->name}}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 px-1">
                    <label for="sell_price" class="font-weight-bold">Sell Price</label>
                    <input type="text" class="form-control" name="sell_price" id="sell_price"
                        placeholder="Rp. {{ number_format($data[0]->sell_price) }}" required>
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
