@php
$i = 1;
@endphp
<table class="mt-3 tableData table-hover table table-light">
    <tr>
        <th>No</th>
        <th scope="col">Name </th>
        <th scope="col">Branch Name </th>
        <th scope="col">Sell Price </th>
        <th scope="col">Buy Price </th>
        <th scope="col">Quantity </th>
        <th scope="col" colspan="2">Action</th>
    </tr>
    @foreach ($data as $value)
        @if ($value->qty < 20 && $value->qty > 0)
            <tr class="bg-warning" title="Running Low !">
        @elseif($value->qty <= 0) 
            <tr class="bg-danger text-white" title="Out Of Product !!">
        @endif
                <td>{{$i++}}</td>
                <td scope="col">{{Str::limit($value->name,20)}}</td>
                <td scope="col">{{$value->Branch_name}}</td>
                <td scope="col">{{$value->sell_price}}</td>
                <td scope="col">{{$value->buy_price}}</td>
                <td scope="col">{{$value->qty}}</td>
                <td>
                    <div class="btn-group border border-white">
                        <a href="/stock/detail/{{$value->slug}}">
                            <button class="btn rounded-0 btn-sm btn-info" title="Show Info"><i
                                    class="fa fa-info-circle"></i></button>
                        </a>
                        <a href="/stock/edit/{{$value->slug}}">
                            <button class="btn rounded-0 btn-sm btn-secondary" title="Edit"><i
                                    class="fa fa-pencil-square-o"></i></button>
                        </a>
                        <button type="submit" class="btn rounded-0 btn-sm btn-danger deleteProduct" data-toggle="modal"
                            data-target="#deleteProduct" data-productid="{{$value->id}}" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>
        </tr>
        @endforeach
</table>