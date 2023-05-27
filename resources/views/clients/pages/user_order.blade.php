@extends('clients.layout.master')

@section('title')
    Order Confirm
@endsection

@section('content')
    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <!-- <h1 id="loading" style="display:none;">Loading</h1> -->
                <img style="display:none;" src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif?20151024034921" id="loading"
                height="400" width="400" />
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__product">Products</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->order_items as $item)
                                    <tr>
                                        <td class="shoping__cart__item">
                                            <img src="{{ asset('images/product') . '/' . $item['image'] }}" alt="">
                                            <h5>{{ $item['name'] }}</h5>
                                        </td>
                                        <td class="shoping__cart__price">
                                            ${{ number_format($item['price'], 2) }}
                                        </td>
                                        <td class="shoping__cart__quantity">
                                            <div class="quantity">
                                                {{ $item['qty'] }}
                                            </div>
                                        </td>
                                        <td class="shoping__cart__total">
                                            <div class="child_shoping__cart__total">
                                                ${{ number_format($item['qty'] * $item['price'], 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <ul>
                            @php
                                $total = 0;
                                foreach ($order->order_items as $item) {
                                    $total += $item['price'] * $item['qty'];
                                }
                            @endphp
                            <li class="total1">
                                <div class="total2">Total <span id="total_cart">${{ number_format($total, 2) }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
