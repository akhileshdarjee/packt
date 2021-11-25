@extends('website')

@section('title', $productId . ' - Product - ' . config('app.name'))

@section('body')
    <section class="single-product product-container" style="display: none;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('products') }}">Products</a></li>
                        <li class="active product-name">{{ $productId }}</li>
                    </ol>
                </div>
            </div>
            <div class="row mt-20">
                <div class="col-md-5">
                    <div class="single-product-slider">
                        <div id='carousel-custom' class='carousel slide' data-ride='carousel'>
                            <div class='carousel-outer'>
                                <div class='carousel-inner'>
                                    <div class='item active'>
                                        <img class="product-img" src='' alt='' />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="single-product-details">
                        <h2 class="product-name"></h2>
                        <div class="product-price mt-20"></div>
                        <p class="product-tagline mt-20"></p>
                        <p class="product-learn"></p>
                        <div class="product-type product-attr">
                            <span>Type:</span>
                            <span class="attr-value"></span>
                        </div>
                        <div class="product-category product-attr">
                            <span>Category:</span>
                            <span class="attr-value"></span>
                        </div>
                        <div class="product-concept product-attr">
                            <span>Concept:</span>
                            <span class="attr-value"></span>
                        </div>
                        <div class="product-languages product-attr widget widget-tag">
                            <span>Languages:</span>
                        </div>
                        <div class="product-tools product-attr widget widget-tag">
                            <span>Tools:</span>
                        </div>
                        <div class="product-pages product-attr">
                            <span>Pages:</span>
                            <span class="attr-value"></span>
                        </div>
                        <div class="product-length product-attr">
                            <span>Length:</span>
                            <span class="attr-value"></span>
                        </div>
                        <div class="product-publication-date product-attr">
                            <span>Publication Date:</span>
                            <span class="attr-value"></span>
                        </div>
                        <div class="product-isbn product-attr">
                            <span>ISBN:</span>
                            <span class="attr-value"></span>
                        </div>
                        <a href="" class="btn btn-main mt-20 fs-14 product-buy-link" target="_blank">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabCommon mt-20">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#details" aria-expanded="true">Description</a></li>
                            <li class=""><a data-toggle="tab" href="#authors" aria-expanded="false">Authors (<span class="product-total-authors"></span>)</a></li>
                        </ul>
                        <div class="tab-content patternbg">
                            <div id="details" class="tab-pane fade active in">
                                <h4>Product Description</h4>
                                <p class="product-description"></p>
                            </div>
                            <div id="authors" class="tab-pane fade">
                                <div class="post-comments product-authors"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="not-found" style="display: none;">
        <div class="block text-center">
            <i class="tf-ion-alert-circled fs-50"></i>
            <h2 class="text-center">Product not found</h2>
            <a href="{{ url()->current() }}" class="btn btn-main mt-20 fs-14">Try again</a>
        </div>
    </div>
    <div class="products-loader text-center">
        <div class="lds-ripple"><div></div><div></div></div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/website/product.js')) }}"></script>
@endpush
