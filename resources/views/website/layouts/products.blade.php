@extends('website')

@section('title', 'Products - ' . config('app.name'))

@section('body')
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        <h1 class="page-name">Products</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{ route('index') }}">Home</a></li>
                            <li class="active">products</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="products section">
        <div class="container">
            <div class="row products-container" data-action="{{ \Request::fullUrl() }}"></div>
            <div class="not-found" style="display: none;">
                <div class="block text-center">
                    <i class="tf-ion-alert-circled fs-50"></i>
                    <h2 class="text-center">No products found</h2>
                    <a href="{{ \Request::fullUrl() }}" class="btn btn-main mt-20 fs-14">Try Again</a>
                </div>
            </div>
            <div class="products-loader text-center">
                <div class="lds-ripple"><div></div><div></div></div>
            </div>
            <div class="products-pagination"></div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/website/index.js')) }}"></script>
@endpush
