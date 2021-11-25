@extends('website')

@section('title', 'Contact - ' . config('app.name'))

@section('body')
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        <h1 class="page-name">Contact Us</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{ route('index') }}">Home</a></li>
                            <li class="active">contact us</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="page-wrapper">
        <div class="contact-section">
            <div class="container">
                <div class="row">
                    <div class="contact-form col-md-6 col-md-offset-3">
                        <form id="contact-form" method="post" action="" role="form">
                            <div class="form-group">
                                <input type="text" placeholder="Your Name" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Your Email" class="form-control" name="email" id="email">
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Subject" class="form-control" name="subject" id="subject">
                            </div>
                            <div class="form-group">
                                <textarea rows="6" placeholder="Message" class="form-control" name="message" id="message"></textarea>   
                            </div>
                            <div id="mail-success" class="success">
                                Thank you. The Mailman is on His Way :)
                            </div>
                            <div id="mail-fail" class="error">
                                Sorry, don't know what happened. Try later :(
                            </div>
                            <div id="cf-submit">
                                <input type="submit" id="contact-submit" class="btn btn-transparent" value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
