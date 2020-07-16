@php
$i = 1;
@endphp
<table class="mt-3 tableData table-hover table table-light">
    <tr>
        <th><i class="fa fa-check"></i></th>
        <th>No</th>
        <th scope="col">Name </th>
        <th scope="col">Branch Name </th>
        <th scope="col">Sell Price </th>
        <th scope="col">Buy Price </th>
        <th scope="col">Quantity </th>
        <th scope="col">Created At </th>
        <th scope="col" colspan="2">Action</th>
    </tr>
    @foreach ($stocks as $stock)
    @if ($stock->qty < 20 && $stock->qty > 0)
        <tr class="bg-warning" title="Running Low !">
            @elseif ($stock->qty <= 0) <tr class="bg-danger text-white" title="Out Of Product !!">
                @endif
                <td scope="col"><input type="checkbox" name="list[]" id=""></td>
                <td>{{$i++}}</td>
                <td scope="col">{{Str::limit($stock->name,20)}}</td>
                <td scope="col">{{$stock->Branch_name}}</td>
                <td scope="col">{{$stock->sell_price}}</td>
                <td scope="col">{{$stock->buy_price}}</td>
                <td scope="col">{{$stock->qty}}</td>
                <td scope="col">{{date_format($stock->created_at,'Y-M-d H-i-s')}}</td>
                <td>
                    <div class="btn-group border border-white">
                        <a href="/stock/{{$stock->slug}}">
                            <button class="btn rounded-0 btn-sm btn-info" title="Show Info"><i
                                    class="fa fa-info-circle"></i></button>
                        </a>
                        <a href="/stock/{{$stock->product_id}}/edit">
                            <button class="btn rounded-0 btn-sm btn-secondary" title="Edit"><i
                                    class="fa fa-pencil-square-o"></i></button>
                        </a>
                        <button type="submit" class="btn rounded-0 btn-sm btn-danger deleteProduct" data-toggle="modal"
                            data-target="#deleteProduct" data-productid="{{$stock->id}}" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>
        </tr>
        @endforeach
</table>