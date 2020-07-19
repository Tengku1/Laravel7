{{-- extends --}}
@extends('layouts.master',['title' => 'Product - Crux'])
{{-- ======= --}}

{{-- section --}}
@section('content')
@php
$no = 1;
@endphp
<h4 class="text-info mb-3">User <i class="fa fa-user"></i></h4>

{{-- If the data is not empty --}}
@if ($data->count())
<div class="col-md-12">
    <div class="col-md-8 float-left">
        <a href="/user/excel" target="_blank">
            <button class="btn btn-success rounded-0">Export Excel <i class="fa fa-file-excel-o"></i></button>
        </a>
    </div>
    <div class="col-md-4 float-left">
        <form action="/user/search" method="get" class="px-0 py-0 col-md-12 input-group">
            <input class="form-control py-2 float-left" type="search" value="" placeholder="Search ..." id="searchdata" name="by">
            <span class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </form>
    </div>
</div>
<div class="table-responsive px-1">
    <table class="mt-3 tableData table-hover table table-light">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Name</th>
            <th scope="col">Full Name</th>
            <th scope="col">Email</th>
            <th scope="col">Modified At</th>
            <th scope="col" colspan="2">Action</th>
        </tr>
        @foreach ($data as $values)
        <tr>
            <td scope="col">{{$no++}}</td>
            <td scope="col">{{Str::limit($values->name,20)}}</td>
            <td scope="col">{{$values->full_name}}</td>
            <td scope="col">{{$values->email}}</td>
            <td scope="col">{{$values->modified_at->diffForHumans()}}</td>
            <td>
                <div class="btn-group">
                    <button type="submit" class="btn rounded-0 btn-sm btn-danger delete" data-toggle="modal" data-target="#delete" data-id="{{$values->id}}" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<!-- Modal -->
@include('layouts.modalDelete')
{{-- End Modal --}}

{{-- For Pagination --}}
<div class="btn-group">
    <span class="pt-2 mr-3 text-info">
        Showing {{$data->firstItem()}} to {{$data->lastItem()}} of {{$data->total()}} Entries
    </span>
    {{$data->links()}}
</div>
{{-- EndPagination --}}

{{-- End Show Data --}}


{{-- If Data is Empty --}}
@else
<a href="/branch/create">
    <div class="col-md-12">
        <button class="btn btn-primary">Add Data <i class="fa fa-plus"></i></button>
    </div>
</a>
<div class="row mt-3 alert alert-info">
    There are no Data here
</div>
@endif
{{-- End If --}}


@endsection
{{-- ======= --}}