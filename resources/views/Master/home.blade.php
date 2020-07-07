{{-- Extends --}}

@extends('Admin.layout.master')

{{-- end Extends --}}



{{-- Section --}}

@section('content')
<h4 class="text-info mb-3">Dashboard <i class="fa fa-dashboard"></i></h4>
<div class="col-md-12">
    @if (Auth::user()->roles[0]=="Master")
        <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardUser">
            <div class="card-header">Users</div>
            <div class="card-body text-center">
                {{$data['User']->count()}}
                <i class="fa fa-user"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1 ">
        <div class="card col-md-12 dashboardProduct">
            <div class="card-header">Products</div>
            <div class="card-body text-center">
                {{$data['Stock']->count()}}
                <i class="fa fa-shopping-cart"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardBranch">
            <div class="card-header">Branch</div>
            <div class="card-body text-center">
                {{$data['Branch']->count()}}
                <i class="fa fa-code-fork"></i>
            </div>
        </div>
    </div>
    @else
    <div class="col-6 dashboardDatabase float-left px-1 ">
        <div class="card col-md-12 dashboardProduct">
            <div class="card-header">Products</div>
            <div class="card-body text-center">
                {{$data['Stock']->count()}}
                <i class="fa fa-shopping-cart"></i>
            </div>
        </div>
    </div>
    <div class="col-6 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardBranch">
            <div class="card-header">History</div>
            <div class="card-body text-center">
                {{$data['historySells']->count()}}
                <i class="fa fa-history"></i>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="col-md-12">
    <div class="col-md-6 px-2 pt-3 float-left">
        <span class="text-info"><i class="fa fa-star"></i> New Product At This Day !</span>
        @if (count($data['productThisDay']))
        @else
        <div class="mt-3 alert alert-info">
            There are no products here
        </div>
        @endif
        <table class="tableData mt-3 table-hover table table-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Sell Price</th>
                <th scope="col">Status</th>
                <th>See More</th>
            </tr>
            @foreach ($data['productThisDay'] as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{Str::limit($item->name,20)}}</td>
                <td>{{$item->sell_price}}</td>
                <td>{{$item->status}}</td>
                <td><a href="/product/{{$item->slug}}"><i class="fa fa-search"></i></a></td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="col-md-6 px-2 pt-3 float-left">
        <span class="text-info"><i class="fa fa-star"></i> History</span>
        @if (count($data['historySells']))
        @else
        <div class="mt-3 alert alert-info">
            There are no History here
        </div>
        @endif
        <table class="tableData mt-3 table-hover table table-light">
            <tr>
                <th scope="col">Product ID</th>
                <th scope="col">Quantity</th>
                <th scope="col">Sell Price</th>
            </tr>
            @foreach ($data['historyThisDay'] as $item)
            <tr>
                <td>{{$item->branch_code}}</td>
                <td>{{$item->qty}}</td>
                <td>{{$item->sell_price}}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@endsection

@section('linkCss')

@endsection

@section('script')

@endsection

@section('title','Home')

{{-- End Section --}}
