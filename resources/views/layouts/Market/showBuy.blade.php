@extends('layouts.master',['title'=>'Show Data - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Detail History Buy
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="col-md-12 mt-3 table table-hover table-light">
                <tr>
                    <td>Product Name</td>
                    <td>:</td>
                    <td>{{$data[0]->ProductName}}</td>
                </tr>
                <tr>
                    <td>Branch Name</td>
                    <td>:</td>
                    <td>{{$data[0]->BranchName}}</td>
                </tr>
                <tr>
                    <td>Qty</td>
                    <td>:</td>
                    <td>{{$data[0]->qty}}</td>
                </tr>
                <tr>
                    <td>Buy Price</td>
                    <td>:</td>
                    <td>{{$data[0]->buy_price}}</td>
                </tr>
                <tr>
                    <td>Modified User</td>
                    <td>:</td>
                    <td>{{$data[0]->modified_user}}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{URL::previous()}}">
            <button class="btn btn-primary">Back</button>
        </a>
    </div>
</div>
@endsection