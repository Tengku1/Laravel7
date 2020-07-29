@extends('layouts.master',['title' => 'Add User - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Add User
    </div>
    <div class="card-body">
        <form action="/user/store" method="post" autocomplete="off">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-6 px-1">
                    <label for="email" class="font-weight-bold">Email</label>
                    <input type="email" name="email" id="email" placeholder="example@crux.com" class="form-control" required value="" autocomplete="new-email">
                    @error('email')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="col-md-6 px-1">
                    <label for="full_name" class="font-weight-bold">Full Name</label>
                    <input type="text" class="form-control" name="full_name" id="full_name"
                        placeholder="Product Name ..." required autocomplete="new-email" value="">
                    @error('full_name')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6 px-1">
                    <label for="name" class="font-weight-bold">Username</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Product Name ..." required value="" autocomplete="new-username">
                    @error('name')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6 px-1">
                    <label for="password" class="font-weight-bold">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required value="" autocomplete="new-password" min="8">
                    @error('password')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 px-1">
                    <label for="roles" class="font-weight-bold">Roles</label>
                    <select name="roles" id="roles" class="form-control">
                        <option value="master">Master</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('roles')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6 px-1">
                    <label for="branch" class="font-weight-bold">Branch</label>
                    <select name="branch_code" id="branch_code" class="form-control">
                        @foreach ($branch as $item)
                        <option value="{{$item->code}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('branch_code')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-12 btn-group mt-3">
                <button type="submit" class="btn btn-success">Add Data <i class="fa fa-plus"></i></button>
                <a href="{{URL::previous()}}">
                    <button type="button" class="btn btn-primary rounded-0">Back</button>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection