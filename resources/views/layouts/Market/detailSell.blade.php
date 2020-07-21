@extends('layouts.master',['title' => 'Detail Sell - Crux'])


@section('content')
<form action="#" method="post" class="px-0 py-0">
    <div class="card mb-3">
        <div class="card-header">
            Input Product
        </div>
        <div class="card-body">
            {{ csrf_field() }}
            <div class="col-md-6 float-left px-1">
                <label for="name" class="mt-1 mr-2 font-weight-bolder">Product Name</label>
                <select name="name" id="name" class="form-control" required>
                    <option value=""></option>
                    @foreach ($data as $item)
                    <option value="{{$item->name}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 float-left px-1">
                <label for="" class="mt-1 mr-2 font-weight-bolder">Quantity</label>
                <input type="number" min="0" class="form-control" required>
            </div>
        </div>
        <div class="card-footer text-muted">
            <button type="button" class="btn border">Cancel</button>
            <button type="submit" class="btn btn-info float-right">Save</button>
        </div>
    </div>
</form>
<form action="#" method="post" class="px-0 py-0">

    <div class="card">
        <div class="card-header">
            List Detail Product
        </div>
        <div class="card-body">
            {{ csrf_field() }}
            <div class="col-md-12">
                <div class="col-md-6 float-left px-1">
                    <span class="text-bold">Reff ID : </span>
                </div>
                <div class="col-md-6 float-left px-1">
                    <span class="text-bold">Total Price : </span>
                    <button type="submit" class="btn btn-success float-right text-white">Finish</button>
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <div class="col-md-6 float-left">
                    <div class="dropdown float-left mb-2">
                        <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Show Entries
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            {{-- @for ($i = 1; $i <= sizeof($limit); $i++) <a href="/market/paginate/{{$i}}/"
                            class="dropdown-item">{{$i}}</a>
                            @endfor --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 btn-group float-left">
                    <form action="/detailSell/search" method="get" class="px-0 py-0 input-group">
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
                    <th>Price</th>
                    <th>Sub Total</th>
                    <th>Action</th>
                </tr>
                <tr>
                    {{-- @if ()
                    @else
                    @endif --}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="btn-group border">
                            <a href="#">
                                <button class="btn rounded-0 btn-sm btn-info" title="Edit"><i
                                        class="fa fa-eye"></i></button>
                            </a>
                            <a href="#">
                                <button class="btn rounded-0 btn-sm btn-warning" title="Edit"><i
                                        class="fa fa-pencil-square-o"></i></button>
                            </a>
                            <button type="submit" class="btn rounded-0 btn-sm btn-danger delete" data-toggle="modal"
                                data-target="#delete" data-id="#" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-muted">
        </div>
    </div>
</form>
@endsection