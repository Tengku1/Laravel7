@extends('layouts.master',['title'=>'Show Data - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Detail Product
    </div>

    <div class="card-body">
        @if ($qty <= 0)
            <div class="row mt-3 alert alert-danger">
                Status : Out Of Product ! Add Now !
            </div>
        @endif
        <div class="table-responsive">
            <table class="col-md-12 mt-3 table table-hover table-light">
                <tr>
                    <td>Name</td>
                    <td>:</td>
                    <td>{{$data[0]->name}}</td>
                </tr>
                <tr>
                    <td>Sell Price</td>
                    <td>:</td>
                    <td>{{$data[0]->sell_price}}</td>
                </tr>
                <tr>
                    <td>Created At</td>
                    <td>:</td>
                    <td>{{$data[0]->created_at->diffForHumans()}}</td>
                </tr>
                <tr>
                    <td>Modified At</td>
                    <td>:</td>
                    <td>{{$data[0]->modified_at->diffForHumans()}}</td>
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