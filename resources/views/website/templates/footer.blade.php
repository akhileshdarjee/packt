<section class="call-to-action bg-gray section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="title">
                    <h2>SUBSCRIBE TO NEWSLETTER</h2>
                    <p>Get updates to our latest products. We will never Spam you</p>
                </div>
                <div class="col-lg-6 col-md-offset-3" id="subscription-form">
                    <div class="input-group">
                        <input type="text" class="form-control" name="subscription_email" placeholder="Enter Your Email Address">
                        <span class="input-group-btn">
                            <button class="btn btn-main subscribe-email" type="button" data-action="{{ route('subscribe') }}">
                                Subscribe Now
                            </button>
                        </span>
                    </div>
                    <div id="subscription-success" class="success"></div>
                    <div id="subscription-fail" class="error"></div>
              </div>
            </div>
        </div>
    </div>
</section>
<footer class="footer section text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="social-media">
                    <li>
                        <a href="https://www.facebook.com/PacktPub/" target="_blank">
                            <i class="tf-ion-social-facebook"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/PacktPub" target="_blank">
                            <i class="tf-ion-social-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/company/packt-publishing/" target="_blank">
                            <i class="tf-ion-social-linkedin"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.youtube.com/user/packt1000/featured" target="_blank">
                            <i class="tf-ion-social-youtube"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://github.com/PacktPublishing" target="_blank">
                            <i class="tf-ion-social-github"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://hub.packtpub.com/feed/" target="_blank">
                            <i class="tf-ion-social-rss"></i>
                        </a>
                    </li>
                </ul>
                <ul class="footer-menu text-uppercase">
                    <li>
                        <a href="{{ route('about') }}">ABOUT</a>
                    </li>
                    <li>
                        <a href="{{ route('products') }}">PRODUCTS</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}">CONTACT</a>
                    </li>
                    <li>
                        <a href="https://www.packtpub.com/about/privacy-policy" target="_blank">PRIVACY POLICY</a>
                    </li>
                </ul>
                <p class="copyright-text">
                    Copyright &copy;{{ date('Y') }}. Developed by
                    <a href="https://www.linkedin.com/in/akhileshdarjee/" target="_blank">Akhilesh Darjee</a>
                </p>
            </div>
        </div>
    </div>
</footer>
<div class="page-loader" style="display: none;"></div>
