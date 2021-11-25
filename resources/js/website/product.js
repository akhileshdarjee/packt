$(document).ready(function() {
    showProductDetails();
});

function showProductDetails() {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('body').find('.products-loader').hide();

            if (data.success && typeof data.data.product !== 'undefined' && data.data.product) {
                var product = data.data.product;
                document.title = data.data.title;

                $('body').find('.product-name').html(product.title);
                $('body').find('.product-description').html(product.description);
                $('body').find('.product-buy-link').attr('href', product.url);
                $('body').find('.product-img').attr('src', product.image);
                $('body').find('.product-img').attr('alt', product.title);

                if (typeof product.prices !== 'undefined' && product.prices && product.prices.length) {
                    var prices = '<table class="table">\
                        <tbody>';

                    $.each(product.prices, function(type, price) {
                        prices += '<tr>\
                            <td>\
                                <div class="product-info"><strong>' + price.name + '</strong></div>\
                            </td>\
                            <td>' + price.price + '</td>';

                        if (typeof price.buy_link !== 'undefined' && price.buy_link) {
                            prices += '<td><a href="' + price.buy_link + '" target="_blank">Add to cart</a></td>';
                        }

                        prices += '</tr>';
                    });

                    prices += '</tbody>\
                        </table>';

                    $('body').find('.product-price').html(prices);
                }
                else {
                    $('body').find('.product-price').hide();
                }

                if (typeof product.tagline !== 'undefined' && product.tagline) {
                    $('body').find('.product-tagline').html(product.tagline);
                }
                else {
                    $('body').find('.product-tagline').hide();
                }

                if (typeof product.learn !== 'undefined' && product.learn) {
                    $('body').find('.product-learn').html(product.learn);
                }
                else {
                    $('body').find('.product-learn').hide();
                }

                if (typeof product.product_type !== 'undefined' && product.product_type) {
                    var attr_container = $('body').find('.product-type');
                    $(attr_container).find('.attr-value').html(product.product_type);
                }
                else {
                    $('body').find('.product-type').hide();
                }

                if (typeof product.category !== 'undefined' && product.category) {
                    var attr_container = $('body').find('.product-category');
                    $(attr_container).find('.attr-value').html(product.category);
                }
                else {
                    $('body').find('.product-category').hide();
                }

                if (typeof product.concept !== 'undefined' && product.concept) {
                    var attr_container = $('body').find('.product-concept');
                    $(attr_container).find('.attr-value').html(product.concept);
                }
                else {
                    $('body').find('.product-concept').hide();
                }

                if (typeof product.languages !== 'undefined' && product.languages && product.languages.length) {
                    var total_languages = 0;
                    var values = '<ul class="widget-tag-list">';

                    $.each(product.languages, function(idx, lang) {
                        if (lang.name) {
                            values += '<li><a href="#">' + lang.name + '</a></li>';
                            total_languages += 1;
                        }
                    });

                    values += '</ul>';

                    if (total_languages) {
                        $('body').find('.product-languages').append(values);
                    }
                    else {
                        $('body').find('.product-languages').hide();
                    }
                }
                else {
                    $('body').find('.product-languages').hide();
                }

                if (typeof product.tools !== 'undefined' && product.tools && product.tools.length) {
                    var total_tools = 0;
                    var values = '<ul class="widget-tag-list">';

                    $.each(product.tools, function(idx, tool) {
                        if (tool.name) {
                            values += '<li><a href="#">' + tool.name + '</a></li>';
                            total_tools += 1;
                        }
                    });

                    values += '</ul>';

                    if (total_tools) {
                        $('body').find('.product-tools').append(values);
                    }
                    else {
                        $('body').find('.product-tools').hide();
                    }
                }
                else {
                    $('body').find('.product-tools').hide();
                }

                if (typeof product.pages !== 'undefined' && product.pages) {
                    var attr_container = $('body').find('.product-pages');
                    $(attr_container).find('.attr-value').html(product.pages);
                }
                else {
                    $('body').find('.product-pages').hide();
                }

                if (typeof product.length !== 'undefined' && product.length) {
                    var attr_container = $('body').find('.product-length');
                    $(attr_container).find('.attr-value').html(product.length);
                }
                else {
                    $('body').find('.product-length').hide();
                }

                if (typeof product.publication_date !== 'undefined' && product.publication_date) {
                    var attr_container = $('body').find('.product-publication-date');
                    $(attr_container).find('.attr-value').html(product.publication_date);
                }
                else {
                    $('body').find('.product-publication-date').hide();
                }

                if (typeof product.isbn13 !== 'undefined' && product.isbn13) {
                    var attr_container = $('body').find('.product-isbn');
                    $(attr_container).find('.attr-value').html(product.isbn13);
                }
                else {
                    $('body').find('.product-isbn').hide();
                }

                if (typeof product.authors !== 'undefined' && product.authors && product.authors.length) {
                    $('body').find('.product-total-authors').html(product.authors.length);
                    var values = '<ul class="media-list comments-list m-bot-50 clearlist">';

                    $.each(product.authors, function(idx, author) {
                        values += '<li class="media">\
                            <div class="media-body">\
                                <div class="comment-info">\
                                    <h4 class="comment-author">\
                                        <a href="' + author.profile_url + '" class="author-link" target="_blank">' + author.name + '</a>\
                                    </h4>\
                                </div>\
                                <p class="author-description">' + author.about + '</p>\
                            </div>\
                        </li>';
                    });

                    values += '</ul>';
                    $('body').find('.product-authors').html(values);
                }
                else {
                    $('body').find('.product-authors').hide();
                }

                $('body').find('.product-container').show();
            }
            else {
                $('body').find('.not-found').show();
            }
        },
        error: function(e) {
            $('body').find('.products-loader').hide();
            $('body').find('.not-found').show();
        }
    });
}
