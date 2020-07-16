@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-header">
        Add Data
    </div>
    <div class="card-body">
        <form action="/branch/store" method="post">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="name" class="font-weight-bold">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Branch Name ..." required>
                    @error('name')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="address" class="font-weight-bold">Address</label>
                    <textarea name="address" class="form-control" id="address" cols="30" rows="10" placeholder="Address ..." required></textarea>
                    @error('address')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-12 btn-group mt-3">
                <button type="submit" class="btn btn-primary">Add Data <i class="fa fa-plus"></i></button>
                <a href="{{URL::previous()}}">
                    <button type="button" class="btn btn-primary rounded-0">Back</button>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

