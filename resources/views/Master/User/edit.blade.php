@extends('layouts.master',['title'=>'Edit - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Update Data
    </div>
    <div class="card-body">

        <form action="/user/{{$data[0]->email}}/update" method="post">
            @method('patch')
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$data[0]->id}}">
            <div class="form-group row">
                <div class="col-md-6 px-1">
                    <label for="name" class="font-weight-bold">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="" placeholder="{{$data[0]->name}}" required>
                </div>
                <div class="col-md-6 px-1">
                    <label for="name" class="font-weight-bold">Full Name</label>
                    <input type="text" class="form-control" name="fullname" id="name" value="" placeholder="{{$data[0]->full_name != '' ? $data[0]->full_name : 'not set'}}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6 px-1">
                    <label for="sell_price" class="font-weight-bold">Email</label>
                    <input type="email" class="form-control" name="email" id="sell_price"
                        placeholder="{{$data[0]->email}}" required>
                </div>
                <div class="col-md-6 px-1">
                    <label for="sell_price" class="font-weight-bold">Branch Name</label>
                    <select name="branch" id="" class="form-control">
                        <option value="default">Default</option>
                        @foreach ($branch as $item)
                            <option value="{{$item->name}}">{{$item->name}}</option>
                        @endforeach
                    </select>
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
