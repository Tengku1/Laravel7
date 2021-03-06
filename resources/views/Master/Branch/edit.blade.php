@extends('layouts.master',['title'=>'Edit - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Edit Data
    </div>
    <div class="card-body">
        <form action="/branch/{{$branch->code}}/edit" method="post">
            @method('patch')
            @csrf
            <input type="hidden" name="id" value="{{$branch->code}}">
            <div class="form-group row">
                <div class="col-md-12 px-1">
                    <label for="name" class="font-weight-bold">Branch Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Product Name ..." required value="{{ $branch->name}}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 px-1">
                    <label for="address" class="font-weight-bold">Address</label>
                    <textarea name="address_name" required class="form-control" id="address" cols="30" rows="10">{{$branch->address_name}}</textarea>
                </div>
            </div>
            <div class="col-md-12 btn-group mt-3">
                <button type="submit" class="btn btn-primary">Update <i class="fa fa-pencil-square-o"></i></button>
                <a href="{{URL::previous()}}">
                    <button type="button" class="btn btn-primary rounded-0">Back</button>
                </a>
            </div>
        </form>
    </div>
</div>

@endsection