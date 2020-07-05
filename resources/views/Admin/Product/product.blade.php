{{-- extends --}}
@extends('Admin.layout.master',['title' => 'Product - Laravel 7'])
{{-- ======= --}}
{{-- section --}}
@section('content')
<h4 class="text-info mb-3">Product <i class="fa fa-shopping-cart"></i></h4>
@if ($products->count())
<div class="col-md-12">
    <div class="col-md-8 float-left">
        <a href="/product/create">
            <button class="btn btn-primary">Add Product <i class="fa fa-plus"></i></button>
        </a>
        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <a class="dropdown-item"><i class="fa fa-print"></i> Print</a>
            <a class="dropdown-item" target="_blank" href="/product/excel"><i class="fa fa-file-excel-o"></i> Export
                Excel</a>
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
            <th><i class="fa fa-check"></i></th>
            <th scope="col">Name </th>
            <th scope="col">Branch Name </th>
            <th scope="col">Quantity </th>
            <th scope="col">Sell Price </th>
            <th scope="col">Status </th>
            <th scope="col">Buy Price </th>
            <th scope="col">Created At </th>
            <th scope="col" colspan="2">Action</th>
        </tr>
        @foreach ($products as $product)
        @if ($product->qty < 20 && $product->qty > 0)
            <tr class="bg-warning" title="Running Low !">
                @elseif ($product->qty <= 0) <tr class="bg-danger text-white" title="Out Of Product !!">
                    @endif
                    <td scope="col"><input type="checkbox" name="list[]" id=""></td>
                    <td scope="col">{{Str::limit($product->name,20)}}</td>
                    <td scope="col">{{$product->Branch_name}}</td>
                    <td scope="col">{{$product->qty}}</td>
                    <td scope="col">{{$product->sell_price}}</td>
                    <td scope="col">{{$product->status}}</td>
                    <td scope="col">{{$product->buy_price}}</td>
                    <td scope="col">{{$product->created_at->diffForHumans()}}</td>
                    <td>
                        <div class="btn-group border border-white">
                            <a href="/product/{{$product->slug}}">
                                <button class="btn rounded-0 btn-sm btn-info" title="Show Info"><i
                                        class="fa fa-info-circle"></i></button>
                            </a>
                            <a href="/product/{{$product->id}}/edit">
                                <button class="btn rounded-0 btn-sm btn-secondary" title="Edit"><i
                                        class="fa fa-pencil-square-o"></i></button>
                            </a>
                            <a href="/product/{{$product->id}}/market">
                                <button class="btn rounded-0 btn-sm btn-success" title="Market"><i
                                        class="fa fa-shopping-basket"></i></button>
                            </a>
                            <button type="submit" class="btn rounded-0 btn-sm btn-danger deleteUser" data-toggle="modal"
                                data-target="#deleteProduct" data-userid="{{$product->id}}" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
            </tr>
            @endforeach
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/product/delete" method="post">
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
                    <input type="hidden", name="product_id" id="product_id">
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
        Showing {{$products->firstItem()}} to {{$products->lastItem()}} of {{$products->total()}} Entries
    </span>
    {{$products->links()}}
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
        $(document).on('click', '.deleteUser', function () {
            var userID = $(this).attr('data-userid');
            $('#product_id').val(userID);
        }); 
    </script>
@endsection

{{-- ======= --}}
