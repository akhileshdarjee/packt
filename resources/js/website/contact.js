$(document).ready(function() {
    $('body').on('click', '.save-contact', function() {
        var contactForm = $('body').find('#contact-form');
        $(contactForm).find('#contact-success').hide();
        $(contactForm).find('#contact-fail').hide();
        var action = $(contactForm).data('action');

        if (action) {
            $('body').find('.page-loader').show();
            var data = new FormData($(contactForm)[0]);

            $.ajax({
                type: 'POST',
                url: action,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('body').find('.page-loader').hide();

                    if (data['success']) {
                        $(contactForm)[0].reset();
                        $(contactForm).find('#contact-success').html(data.message);
                        $(contactForm).find('#contact-success').show();

                        setTimeout(function() {
                            $(contactForm).find('#contact-success').html('');
                            $(contactForm).find('#contact-success').hide();
                        }, 5000);
                    }
                    else {
                        $(contactForm).find('#contact-fail').html(data.message);
                        $(contactForm).find('#contact-fail').show();
                    }
                },
                error: function(e) {
                    $('body').find('.page-loader').hide();
                    $(contactForm).find('#contact-fail').html('Some error occurred. Please try again');
                    $(contactForm).find('#contact-fail').show();
                }
            });
        }
        else {
            $(contactForm).find('#contact-fail').html('Some error occurred. Please refresh the page and try again');
            $(contactForm).find('#contact-fail').show();
        }
    });
});
