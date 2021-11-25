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
                        <form id="contact-form" data-action="{{ route('save.contact') }}" role="form">
                            <div class="form-group">
                                <input type="text" placeholder="Your Name" class="form-control" name="name">
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Your Email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Subject" class="form-control" name="subject">
                            </div>
                            <div class="form-group">
                                <textarea rows="6" placeholder="Message" class="form-control" name="message"></textarea>
                            </div>
                            <div id="contact-success" class="success"></div>
                            <div id="contact-fail" class="error"></div>
                            <div id="cf-submit">
                                <button class="btn btn-main btn-block save-contact fs-14" type="button">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/website/contact.js')) }}"></script>
@endpush
