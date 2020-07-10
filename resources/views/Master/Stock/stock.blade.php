{{-- extends --}}

@extends('Master.layout.master',['title' => 'Product - Laravel 7'])

{{-- ======= --}}

{{-- section --}}
@section('content')

<h4 class="text-info mb-3">Stock <i class="fa fa-dropbox"></i></h4>
@if ($stocks->count())

<div class="col-md-12">
    <div class="col-md-6 float-left">
        <a href="/stock/create/{{$stocks[0]->branch_code}}">
            <button class="btn btn-primary rounded-0">Add Product <i class="fa fa-plus"></i></button>
        </a>
        @if ($stocks[0]->date)
        <a href="/stock/excel/{{$stocks[0]->branch_code}}/{{$stocks[0]->date}}" target="_blank">
            <button class="btn btn-success rounded-0">Export Excel <i class="fa fa-file-excel-o"></i></button>
        </a>
        @else
        <a href="/stock/excel/{{$stocks[0]->branch_code}}" target="_blank">
            <button class="btn btn-success rounded-0">Export Excel <i class="fa fa-file-excel-o"></i></button>
        </a>
        @endif
    </div>
    <div class="col-md-2 float-left pr-1">
        <form action="/stock/branch/{{$stocks[0]->branch_code}}" method="post" class="p-0">
            {{ csrf_field() }}
            <input type="month" name="date" max="3000-12-31" min="2000-01-01" onchange="this.form.submit();"
                class="form-control">
        </form>
    </div>
    <div class="col-md-4 float-left">
        <div class="input-group col-md-12">
            <input class="form-control py-2" type="search" value="" placeholder="Search ..." id="searchdata">
            <span class="input-group-append">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </div>
</div>

<div class="table-responsive px-1">
    @include('Master.Stock.table',$stocks)
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
                    <input type="hidden" name="branch_code" value="{{$stocks[0]->branch_code}}">
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
        Showing {{$stocks->firstItem()}} to {{$stocks->lastItem()}} of {{$stocks->total()}} Entries
    </span>
    {{$stocks->links()}}
</div>
{{-- EndPagination --}}

@else
<a href="/product/create">
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