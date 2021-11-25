@extends('website')

@section('title', config('app.name'))

@section('body')
    <div class="hero-slider">
        <div class="slider-item th-fullpage hero-area" style="background-image: url(images/slider/slider-1.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 text-center">
                        <p data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".1">FREE 7 Day Trial</p>
                        <h1 data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".5">Get unlimited online access to every Packt product free for 7 days</h1>
                        <a data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".8" class="btn" href="{{ route('products') }}">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="products section bg-gray">
        <div class="container">
            <div class="row">
                <div class="title text-center">
                    <h2>Latest Products</h2>
                </div>
            </div>
            <div class="row products-container" data-action="{{ route('latest.products') }}"></div>
            <div class="block text-center show-products" style="display: none;">
                <a href="{{ route('products') }}" class="btn btn-main mt-20 fs-14">Show all Products</a>
            </div>
            <div class="not-found" style="display: none;">
                <div class="block text-center">
                    <i class="tf-ion-alert-circled fs-50"></i>
                    <h2 class="text-center">No products found</h2>
                    <a href="{{ route('products') }}" class="btn btn-main mt-20 fs-14">Continue Shopping</a>
                </div>
            </div>
            <div class="products-loader text-center">
                <div class="lds-ripple"><div></div><div></div></div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/website/index.js')) }}"></script>
@endpush
