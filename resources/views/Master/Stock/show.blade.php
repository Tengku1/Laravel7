@extends('layouts.master',['title'=>'Show Data - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Detail Data
    </div>

    <div class="card-body">
        Added From {{$product['created_at']->diffForHumans()}}
        @if ($product['qty'] < 20 && $product['qty'] > 0)
            <div class="row mt-3 alert alert-warning">
                The Product Running Low ! Increase Now !
            </div>
        @elseif ($product['qty'] <= 0)
            <div class="row mt-3 alert alert-danger">
                Out Of Product ! Add Now !
            </div>
        @endif
        <div class="table-responsive">
            <table class="col-md-12 mt-3 table table-hover table-light">
                <tr>
                    <td>ID</td>
                    <td>:</td>
                    <td>{{$product['id']}}</td>
                </tr>
                <tr>
                    <td>Title</td>
                    <td>:</td>
                    <td>{{$product['name']}}</td>
                </tr>
                <tr>
                    <td>Slug</td>
                    <td>:</td>
                    <td>{{$product['slug']}}</td>
                </tr>
                <tr>
                    <td>Quantity</td>
                    <td>:</td>
                    <td>{{$product['qty']}}</td>
                </tr>
                <tr>
                    <td>Created At</td>
                    <td>:</td>
                    <td>{{$product['created_at']}}</td>
                </tr>
                <tr>
                    <td>Updated At</td>
                    <td>:</td>
                    <td>{{$product['modified_at']}}</td>
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