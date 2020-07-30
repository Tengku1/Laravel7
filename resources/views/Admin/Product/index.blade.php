{{-- extends --}}
@extends('layouts.master',['title' => 'Product - Crux'])
{{-- ======= --}}

{{-- section --}}
@section('content')
@php
$no = 1;
@endphp
<h4 class="text-info mb-3">Product <i class="fa fa-product-hunt"></i></h4>

{{-- If the data is not empty --}}
@if ($data->count())
<div class="col-md-12">
    <div class="col-md-8 float-left">
        <a href="/product/create">
            <button class="btn btn-primary">Add Data <i class="fa fa-plus"></i></button>
        </a>
        <a href="/product/excel" target="_blank">
            <button class="btn btn-success rounded-0">Export Excel <i class="fa fa-file-excel-o"></i></button>
        </a>
    </div>
    <div class="col-md-4 float-left">
        <form action="/product/search" method="get" class="px-0 py-0 col-md-12 input-group">
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
            <th scope="col">Sell Price</th>
            <th scope="col">Status</th>
            <th scope="col" colspan="2">Action</th>
        </tr>
        @foreach ($data as $values)
        @if ($values->status == 'out of stock')
        <tr class="bg-danger text-white" title="Product is empty !!">
        @elseif($values->status == 'running low')
        <tr class="bg-warning" title="Quantity below 20 !!">
        @else
        <tr>
        @endif
            <td scope="col">{{$no++}}</td>
            <td scope="col">{{Str::limit($values->name,20)}}</td>
            <td scope="col">Rp. {{number_format($values->sell_price)}}</td>
            
            <td scope="col">{{$values->status}}</td>
            <td>
                <div class="btn-group border">
                    <a href="/product/{{$values->slug}}">
                        <button class="btn rounded-0 btn-sm btn-info" title="Edit"><i class="fa fa-eye"></i></button>
                    </a>
                    <a href="/product/edit/{{$values->slug}}">
                        <button class="btn rounded-0 btn-sm btn-warning" title="Edit"><i
                                class="fa fa-pencil-square-o"></i></button>
                    </a>
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
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/product/destroy" method="post">
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
<a href="/product/create">
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