@extends('website')

@section('title', 'About - ' . config('app.name'))

@section('body')
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        <h1 class="page-name">About Us</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{ route('index') }}">Home</a></li>
                            <li class="active">about us</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="about">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-40">Packt Publishing</h2>
                    <p>Founded in 2004 in Birmingham, UK, Packt's mission is to help the world put software to work in new ways, through the delivery of effective learning and information services to IT professionals.</p>
                    <p>Working towards that vision, we have published over 6,500 books and videos so far, providing IT professionals with the actionable knowledge they need to get the job done - whether that's specific learning on an emerging technology or optimizing key skills in more established tools.</p>
                    <p>As part of our mission, we have also awarded over $1,000,000 through our Open Source Project Royalty scheme, helping numerous projects become household names along the way.</p>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-md-3 text-center">
                    <img src="{{ asset('images/about/shield.png') }}">
                    <p class="mt-10">Packt's eBooks and videos are free from DRM</p>
                </div>
                <div class="col-md-3 text-center">
                    <img src="{{ asset('images/about/book.png') }}">
                    <p class="mt-10">Packt has published over 6,500 books and videos</p>
                </div>
                <div class="col-md-3 text-center">
                    <img src="{{ asset('images/about/trophy.png') }}">
                    <p class="mt-10">Packt has donated over $1,000,000 to Open Source projects</p>
                </div>
                <div class="col-md-3 text-center">
                    <img src="{{ asset('images/about/thermometer.png') }}">
                    <p class="mt-10">Packt is a global company based in Birmingham, UK</p>
                </div>
            </div>
        </div>
    </section>
@endsection
