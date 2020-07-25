{{-- extends --}}
@extends('layouts.master',['title' => 'Buy - Crux'])
{{-- ======= --}}

{{-- section --}}
@section('content')
@php
$no = 1;
@endphp
<h4 class="text-info mb-3">Buy Product <i class="fa fa-shopping-cart"></i></h4>

{{-- If the data is not empty --}}
@if ($data->count())
<div class="col-md-12 py-2">
    <div class="col-md-8 float-left mb-2">
        <button class="btn btn-primary rounded-0 float-left mr-1 mb-2" data-toggle="modal" data-target="#shoping"
            title="Buy Product">Add Sales <i class="fa fa-plus"></i></button>
        <div class="dropdown float-left mb-2">
            <button class="btn btn-info rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Show Entries
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @for ($i = 1; $i <= sizeof($data); $i++) <a href="/market/paginate/{{$i}}/" class="dropdown-item">
                    {{$i}}</a>
                    @endfor
            </div>
        </div>
    </div>
    <div class="col-md-4 float-left mb-2">
        <form action="/buy/search" method="get" class="px-0 py-0 input-group">
            <input class="form-control py-2 float-left" type="search" value="" placeholder="Search ..." id="searchdata"
                name="by">
            <span class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </form>
    </div>
</div>
<div class="table-responsive px-1 py-2">
    <table class="tableData table-hover table table-light">
        <tr>
            <th>No</th>
            <th>Reff ID</th>
            <th>Total Harga</th>
            <th>Action</th>
        </tr>
        @foreach ($data as $values)
        <td>{{$no++}}</td>
        <td>{{$values->id}}</td>
        <td>{{$values->qty * $values->buy_price}}</td>
        <td>
            <div class="btn-group border">
                <a href="/product/{{$values->slug}}">
                    <button class="btn rounded-0 btn-sm btn-info" title="Edit"><i class="fa fa-eye"></i></button>
                </a>
                <a href="/product/edit/{{$values->slug}}">
                    <button class="btn rounded-0 btn-sm btn-warning" title="Edit"><i
                            class="fa fa-pencil-square-o"></i></button>
                </a>
                <button type="submit" class="btn rounded-0 btn-sm btn-danger delete" data-toggle="modal"
                    data-target="#delete" data-id="{{$values->historyProductID}}" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
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
    <button class="btn btn-primary rounded-0 float-left mr-1 mb-2" data-toggle="modal" data-target="#shoping"
    title="Buy Product">Add Sales <i class="fa fa-plus"></i></button>
</div>
<div class="row mt-3 alert alert-info">
    No Data Available
</div>
{{-- End If --}}
@endif

<!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/market/deleteBuy" method="post">
                {{method_field('delete')}}
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You Want You Sure Delete This Record?
                    <input type="hidden" name="id" id="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect remove-data-from-delete-form">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('script')

<script>
    $(document).on('click', '.delete', function () {
        var id = $(this).attr('data-id');
        $('#id').val(id);
    });
</script>

@endsection
{{-- End Modal --}}

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
@section('script')
{{-- End Modal --}}





@endsection

{{-- ======= --}}
@endsection