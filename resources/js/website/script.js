// Setup ajax for making csrf token used by laravel
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

$(document).ready(function() {
    // Preloader
    $(window).on('load', function () {
        $('#preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    });

    //Hero Slider
    $('.hero-slider').slick({
        // autoplay: true,
        infinite: true,
        arrows: true,
        prevArrow: '<button type=\'button\' class=\'heroSliderArrow prevArrow tf-ion-chevron-left\'></button>',
        nextArrow: '<button type=\'button\' class=\'heroSliderArrow nextArrow tf-ion-chevron-right\'></button>',
        dots: false,
        autoplaySpeed: 7000,
        pauseOnFocus: false,
        pauseOnHover: false
    });

    $('.hero-slider').slickAnimation();

    $('body').on('change', '.currency-selector', function() {
        var currency = $(this).val();
        var action = $(this).data('action');

        if (currency && action) {
            $('body').find('.page-loader').show();

            $.ajax({
                type: 'POST',
                url: action,
                data: {'currency': currency},
                dataType: 'json',
                success: function(data) {
                    location.reload();
                },
                error: function(e) {
                    location.reload();
                }
            });
        }
    });

    $('body').on('click', '.subscribe-email', function() {
        var subscriptionForm = $('body').find('#subscription-form');
        $(subscriptionForm).find('#subscription-success').hide();
        $(subscriptionForm).find('#subscription-fail').hide();

        var email = $(subscriptionForm).find('[name="subscription_email"]').val();
        var action = $(this).data('action');

        if (email && action) {
            $('body').find('.page-loader').show();

            $.ajax({
                type: 'POST',
                url: action,
                data: {'email': email},
                dataType: 'json',
                success: function(data) {
                    $('body').find('.page-loader').hide();

                    if (data['success']) {
                        $(subscriptionForm).find('[name="subscription_email"]').val('');
                        $(subscriptionForm).find('#subscription-success').html(data.message);
                        $(subscriptionForm).find('#subscription-success').show();

                        setTimeout(function() {
                            $(subscriptionForm).find('#subscription-success').html('');
                            $(subscriptionForm).find('#subscription-success').hide();
                        }, 5000);
                    }
                    else {
                        $(subscriptionForm).find('#subscription-fail').html(data.message);
                        $(subscriptionForm).find('#subscription-fail').show();
                    }
                },
                error: function(e) {
                    $('body').find('.page-loader').hide();
                    $(subscriptionForm).find('#subscription-fail').html('Some error occurred. Please try again');
                    $(subscriptionForm).find('#subscription-fail').show();
                }
            });
        }
        else {
            $(subscriptionForm).find('#subscription-fail').html('Please enter valid email address');
            $(subscriptionForm).find('#subscription-fail').show();
        }
    });
});

function getProducts() {
    var productsContainer = $('body').find('.products-container');

    if ($(productsContainer).length) {
        var action = $(productsContainer).data('action');
        var loader = $('body').find('.products-loader');

        if (action) {
            $.ajax({
                type: 'GET',
                url: action,
                dataType: 'json',
                success: function(data) {
                    if (data['success']) {
                        var products = '';

                        if (typeof data.data.products !== 'undefined' && data.data.products.length > 0) {
                            $.each(data.data.products, function(idx, product) {
                                products += getProductBox(product);
                            });

                            $(productsContainer).html(products);
                            $('body').find('.show-products').show();

                            if (typeof data.data.prev_page_url !== 'undefined' && typeof data.data.next_page_url !== 'undefined') {
                                var pagination = makePagination(data.data);

                                if ($('body').find('.products-pagination').length) {
                                    $('body').find('.products-pagination').html(pagination);
                                }
                            }
                        }
                        else {
                            $('body').find('.not-found').show();
                        }
                    }

                    $(loader).hide();
                },
                error: function(e) {
                    $(loader).hide();
                }
            });
        }
    }
}

function getProductBox(product) {
    if (!product.image) {
        product.image = 'https://via.placeholder.com/240x300?text=Packt';
    }

    var product = '<div class="col-md-3 product-box">\
        <div class="product-item">\
            <a href="' + product.url + '">\
                <div class="product-thumb">\
                    <img class="img-responsive" src="' + product.image + '" alt="' + product.title + '" />\
                </div>\
            </a>\
            <div class="product-content">\
                <h4><a href="' + product.url + '">' + product.title + '</a></h4>\
                <p class="price">' + product.price + '</p>\
            </div>\
        </div>\
    </div>';

    return product;
}

function makePagination(data) {
    var first_enabled = true;
    var last_enabled = true;
    var first = data['first_page_url'];
    var prev = data['prev_page_url'];
    var next = data['next_page_url'];
    var last = data['last_page_url'];

    var pagination = '<div class="text-center">\
        <ul class="pagination post-pagination">';

    if (data['current_page'] == 1) {
        first_enabled = false;
    }

    if (data['current_page'] == data['last_page']) {
        last_enabled = false;
    }

    pagination += '<li class="' + (first_enabled ? "" : " disabled") + '">\
            <a href="' + (first_enabled ? first : "#") + '" tabindex="0">First</a>\
        </li>\
        <li class="' + (prev ? "" : " disabled") + '">\
            <a href="' + (prev ? prev : "#") + '" tabindex="1">Previous</a>\
        </li>\
        <li class="' + (next ? "" : " disabled") + '">\
            <a href="' + (next ? next : "#") + '" tabindex="2">Next</a>\
        </li>\
        <li class="' + (last_enabled ? "" : " disabled") + '">\
            <a href="' + (last_enabled ? last : "#") + '" tabindex="3">Last</a>\
        </li>';

    pagination += '</ul>\
        </div>';

    return pagination;
}
