<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Email xac nhan don dat hang!!</h1>
    <h2>XIN CHAO : {{ $order->user->name }}</h2>
    <table border="1">
        <tr>
            <th>STT</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
        @php $total = 0; @endphp
        @foreach ($order->order_items as $key => $item)
            <tr>
                <th>{{ $key+1 }}</th>
                <th>{{ $item->name }}</th>
                <th>{{ $item->price }}</th>
                <th>{{ $item->qty }}</th>
                <th>{{ number_format($item->qty * $item->price, 2) }}</th>
                @php
                    $total += $item->qty * $item->price;
                @endphp
            </tr>
        @endforeach
        <tr>
            <td colspan="4">TOTAL </td>
            <td>{{ number_format($total,2) }}</td>
        </tr>   
    </table>
</body>

</html>
