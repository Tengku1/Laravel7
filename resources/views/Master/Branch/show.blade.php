@extends('layout.master',['title'=>'Show Data - Crux'])

@section('content')
<div class="card">
    <div class="card-header">
        Detail Branch
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="col-md-12 mt-3 table table-hover table-light">
                <tr>
                    <td>Code</td>
                    <td>:</td>
                    <td>{{$branch->code}}</td>
                </tr>
                <tr>
                    <td>Branch Name</td>
                    <td>:</td>
                    <td>{{$branch->name}}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td>{{$branch->address_name}}</td>
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