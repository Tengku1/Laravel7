<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Product PDF</title>
</head>

<body>
    <div class="table-responsive px-1 text-center">
        <h3>Product PDF</h3>
        <table class="table table-bordered thead-dark pt-2">
        <tr class="thead-dark">
            <th>ID</th>
            <th>Name</th>
            <th>Sell Price</th>
            <th>Status</th>
            <th>Branch Code</th>
            <th>Quantity</th>
            <th>Buy Price</th>
        </tr>
        @foreach ($products as $product)
        <tr>
            <td>{{$product->id}}</td>
            <td>{{Str::limit($product->name,20)}}</td>
            <td>{{$product->sell_price}}</td>
            <td>{{$product->status}}</td>
            <td>{{$product->branch_code}}</td>
            <td>{{$product->qty}}</td>
            <td>{{$product->buy_price}}</td>
        </tr>
        @endforeach
    </table>
    </div>
</body>

</html>