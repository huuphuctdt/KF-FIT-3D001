@extends('clients.layout.master')s

@section('content')


<form method="GET" action="{{ route('product.list') }}" id="form-product-list">
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Department</h4>
                        {{-- <ul>
                            <li><a href="#">Fresh Meat</a></li>
                            <li><a href="#">Vegetables</a></li>
                            <li><a href="#">Fruit & Nut Gifts</a></li>
                            <li><a href="#">Fresh Berries</a></li>
                            <li><a href="#">Ocean Foods</a></li>
                            <li><a href="#">Butter & Eggs</a></li>
                            <li><a href="#">Fastfood</a></li>
                            <li><a href="#">Fresh Onion</a></li>
                            <li><a href="#">Papayaya & Crisps</a></li>
                            <li><a href="#">Oatmeal</a></li>
                        </ul> --}}
                        <div class="row">
                            <select name="category" id="select-category">
                                <option value="all">All Category</option>
                                @foreach ($productCategories as $productCategory)
                                    <option {{ request()->query('category') == $productCategory->id ? 'selected' : '' }} 
                                        value="{{ $productCategory->id }}">{{  $productCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="sidebar__item">
                        <h4>Price</h4>
                        <div class="price-range-wrap">
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="10" data-max="540">
                                <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                            </div>
                            <div class="range-slider">
                                <div class="price-input">
                                    <input type="text" id="minamount" name="min">
                                    <input type="text" id="maxamount" name="max">
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                <div class="filter__item">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="filter__sort">
                                <span>Sort By</span>
                                <select name="sort" id="sort">
                                    <option {{ request()->query('sort') == 0 ? 'selected' : '' }} value="0">Lastest</option>
                                    <option {{ request()->query('sort') == 1 ? 'selected' : '' }} value="1">Price Low To High</option>
                                    <option {{ request()->query('sort') == 2 ? 'selected' : '' }} value="2">Price High To Low</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="filter__found">
                                <h6><span>{{ $products->total() }}</span> Products found</h6>
                            </div>
                        </div>
                       
                    </div>
                </div>
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="{{ asset('images').'/'.$product->image }}">
                                    <ul class="product__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="#">{{ $product->name }}</a></h6>
                                    <h5>${{ $product->price }}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                {{ $products->appends(request()->query())->links('pagination.custom_paginate') }}
            </div>
        </div>
    </div>
</section>
</form>

@endsection

@section('js-custom')
<script type="text/javascript">
    $(document).ready(function(){
        $('#sort, #select-category').on('change', function(){
            $('#form-product-list').submit();
        });

        $(".price-range").on("slidestop", function() {
            $('#form-product-list').submit();
        });

        $( ".price-range").slider( "values", 
        [ 
            "{{ is_null(reque n')) ? $min : request()->query('min') }}",
            "{{ is_null(request()->query('max')) ? $max : request()->query('max') }}" 
        ]);

        $("#minamount").val("{{ is_null(request()->query('min')) ? $min : request()->query('min') }}");
        $("#maxamount").val("{{ is_null(request()->query('max')) ? $max : request()->query('max') }}");
    });
</script>
@endsection