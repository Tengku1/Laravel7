@extends('Admin.layout.master')

{{-- Section --}}

@section('content')
<div class="card">
    <div class="card-header">
        Market Product
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form action="/product/{{$data[0]->id}}/marketex" method="post" class="py-0 px-0">
                {{ csrf_field() }}
                @method('patch')
                <table class="col-md-12 mt-3 table table-hover table-light">
                    @foreach ($data as $item)
                    <tr>
                        <td>ID</td>
                        <td>:</td>
                        <td>{{$item->id}}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>:</td>
                        <td>{{$item->name}}</td>
                    </tr>
                    <tr>
                        <td>Sell Price</td>
                        <td>:</td>
                        <td>{{$item->sell_price}}</td>
                    </tr>
                    <tr>
                        <td>Buy Quantity</td>
                        <td>:</td>
                        <td><input type="text" name="buyqty" class="form-control" value=""></td>
                    </tr>
                    <tr>
                        <td>Sell Quantity</td>
                        <td>:</td>
                        <td><input type="text" name="sellqty" class="form-control" value=""></td>
                    </tr>
                    @endforeach
                </table>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{URL::previous()}}">
            <button class="btn btn-primary">Back</button>
        </a>
    </div>
</div>
@endsection

{{-- ======= --}}
