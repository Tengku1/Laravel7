{{-- extends --}}

@extends('layouts.master',['title' => 'Product - Crux'])

{{-- ======= --}}

{{-- section --}}
@section('content')

<h4 class="text-info mb-3">Stock <i class="fa fa-dropbox"></i></h4>
@if ($data->count())

<div class="col-md-12">

    <div class="col-md-8 btn-group float-left">
        <a href="/stock/create/{{Auth::user()->branch_code}}">
            <button class="btn btn-primary rounded-0">Add Product <i class="fa fa-plus"></i></button>
        </a>
        <a href="/stock/excel/{{Auth::user()->branch_code}}">
            <button class="btn btn-success rounded-0">Export <i class="fa fa-file-excel-o"></i></button>
        </a>
    </div>
    <div class="col-md-4 float-left">
        <form action="/stock/{{$data[0]->BranchSlug}}/search" method="get" class="px-0 py-0 col-md-12 input-group">
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
    @include('layouts.tableProducts')
</div>

<!-- Modal -->
<div class="modal fade" id="deleteProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/stock/delete" method="post">
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
                    <input type="hidden" name="stock_id" id="stock_id">
                    <input type="hidden" name="branch_code" value="{{$data[0]->branch_code}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit"
                        class="btn btn-danger waves-effect remove-data-from-delete-form">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- For Pagination --}}
<div class="btn-group">
    <span class="pt-2 mr-3 text-info">
        Showing {{$data->firstItem()}} to {{$data->lastItem()}} of {{$data->total()}} Entries
    </span>
    {{$data->links()}}
</div>
{{-- EndPagination --}}

@else
<a href="/stock/create">
    <div class="col-md-12">
        <button class="btn btn-primary">Add Data <i class="fa fa-plus"></i></button>
    </div>
</a>

<div class="row mt-3 alert alert-info">
    There are no products here
</div>

@endif
@endsection

@section('script')

<script>
    $(document).on('click', '.deleteProduct', function () {
        var productID = $(this).attr('data-productid');
        $('#stock_id').val(productID);
    });
</script>

@endsection

{{-- ======= --}}