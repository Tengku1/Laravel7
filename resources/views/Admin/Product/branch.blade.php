{{-- extends --}}
@extends('Admin.layout.master',['title' => 'Branch - Laravel 7'])
{{-- ======= --}}
{{-- section --}}
@section('content')
<h4 class="text-info mb-3">Branch <i class="fa fa-code-fork"></i></h4>

@if ($data->count())
<div class="col-md-12">
    <div class="col-md-8 float-left">
        <a href="/branch/create">
            <button class="btn btn-primary">Add Data <i class="fa fa-plus"></i></button>
        </a>
        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <a class="dropdown-item"><i class="fa fa-print"></i> Print</a>
            <a class="dropdown-item" href="#"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            <a class="dropdown-item" href="#"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>
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
    <table class="mt-3 tableData table-hover table table-light">
        <tr>
            <th scope="col">Code</th>
            <th scope="col">Name</th>
            <th scope="col">Address</th>
            <th scope="col" colspan="2">Action</th>
        </tr>
        @foreach ($data as $values)
        <tr>
            <td scope="col">{{$values->code}}</td>
            <td scope="col">{{Str::limit($values->name,20)}}</td>
            <td scope="col">{{Str::limit($values->address_name,20)}}</td>
            <td>
                <div class="btn-group">
                    <a href="/product/branch/{{$values->code}}">
                        <button class="btn rounded-0 btn-sm btn-info" title="Show Info"><i
                                class="fa fa-eye"></i></button>
                    </a>
                    <a href="/branch/{{$values->code}}/edit">
                        <button class="btn rounded-0 btn-sm btn-secondary" title="Edit"><i
                                class="fa fa-pencil-square-o"></i></button>
                    </a>
                    <button type="submit" class="btn rounded-0 btn-sm btn-danger" data-toggle="modal"
                        data-target="#exampleModal" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span>Are you sure you want to delete this data?
                </span>
            </div>
            <div class="modal-footer">
                <div class="btn-group justify-content-center">
                    <form action="/branch/{{$values->code}}/delete" method="post" class="px-0 py-0">
                        @csrf
                        @method("delete")
                        <button type="submit" class="btn rounded-0 btn-sm btn-link" title="Delete">Yes</button>
                    </form>
                    <button type="button" class="btn rounded-0 btn-sm btn-link" data-dismiss="modal">
                        <span aria-hidden="true">No</span>
                    </button>
                </div>
            </div>
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
<a href="/branch/create">
    <div class="col-md-12">
        <button class="btn btn-primary">Add Data <i class="fa fa-plus"></i></button>
    </div>
</a>
<div class="row mt-3 alert alert-info">
    There are no Data here
</div>
@endif
@endsection
{{-- ======= --}}