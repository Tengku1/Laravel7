{{-- extends --}}
@extends('layouts.master',['title' => 'Buy - Crux'])
{{-- ======= --}}

{{-- section --}}
@section('content')
@php
$no = 1;
@endphp
<h4 class="text-info mb-3">Buy Product<i class="fa fa-shopping-cart"></i></h4>

{{-- If the data is not empty --}}
@if ($data->count())
<div class="col-md-12 py-2">
    <div class="col-md-8 float-left mb-2">
        @if (Auth::user()->roles[0] == "Master")
        <button class="btn btn-primary rounded-0 float-left mr-1 mb-2" data-toggle="modal" data-target="#shoping"
            title="Buy Product">Add Sales <i class="fa fa-plus"></i></button>
        @else
        <a href="/market/detail/buy?branch={{$branch[0]->slug}}">
            <button class="btn btn-primary rounded-0 float-left mr-1 mb-2" title="Buy Product">Add Sales <i
                    class="fa fa-plus"></i></button>
        </a>
        @endif
        <div class="dropdown float-left mb-2">
            <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Show Entries
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @for ($i = 1; $i <= sizeof($getSizeData); $i++) <a href="/market/buy/paginate/{{$i}}/"
                    class="dropdown-item">
                    {{$i}}</a>
                    @endfor
            </div>
        </div>
    </div>
</div>
<div class="table-responsive px-1 py-2">
    <table class="tableData table table-light">
        <tr class="bg-dark text-white">
            <th>No</th>
            <th>Reff ID</th>
            <th>Action</th>
        </tr>
        @foreach ($data as $values)
        @if ($values->has_finished == "false")
        <tr class="bg-warning" title="Please Complete The Transaction !">
            @else
        <tr>
            @endif
            <td>{{$no++}}</td>
            <td>{{$values->id}}</td>
            <td>
                @if ($values->has_finished == "true")
                <form action="/market/detail/buy/show" class="px-0 py-0" method="get">
                    <button class="btn rounded-0 btn-sm btn-info" name="id" title="Edit" value="{{$values->id}}"><i
                            class="fa fa-eye"></i></button>
                </form>
                @else
                <form action="/market/detail/buy" class="px-0 py-0" method="get">
                    <button class="btn rounded-0 btn-sm btn-info" name="branch" title="Edit"
                        value="{{$values->branchSlug}}"><i class="fa fa-pencil-square-o"></i></button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>

{{-- Pagination --}}
@include('layouts.paginationTable')
{{-- == Pagination == --}}


@else
{{-- If Data is Empty --}}
<div class="row px-2">
    @if (Auth::user()->roles[0] == "Master")
    <button class="btn btn-primary rounded-0 float-left mr-1 mb-2" data-toggle="modal" data-target="#shoping"
        title="Buy Product">Add Sales <i class="fa fa-plus"></i></button>
    @else
    <a href="/market/detail/buy?branch={{$branch[0]->slug}}">
        <button class="btn btn-primary rounded-0 float-left mr-1 mb-2" title="Buy Product">Add Sales <i
                class="fa fa-plus"></i></button>
    </a>
    @endif
</div>
<div class="row mt-3 alert alert-info">
    No Data Available
</div>
{{-- End If --}}
@endif

<!-- Modal Add -->
<div class="modal fade" id="shoping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/market/detail/buy" method="get">
                <div class="modal-header">
                    Select Branch
                </div>
                <div class="modal-body">
                    <label for="branch">Select Branch</label>
                    <select name="branch" id="branch" class="form-control">
                        <option value=""></option>
                        @foreach ($branch as $item)
                        <option value="{{$item->slug}}">{{$item->branch_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info waves-effect remove-data-from-delete-form">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======= --}}
@endsection