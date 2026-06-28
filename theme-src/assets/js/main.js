(function($) {
    'use strict';

    $(document).ready(function() {
        // Live Search with Thumbnails
        var searchTimeout = null;
        var currentRequest = null;

        $('.ct-search-form[data-live-results="thumbs"] input[type="search"]').on('input', function() {
            var $input = $(this);
            var $form = $input.closest('.ct-search-form');
            var searchQuery = $input.val().trim();
            var $button = $form.find('button[type="submit"]');
            var nonce = $form.find('.ct-live-results-nonce').val();

            clearTimeout(searchTimeout);

            if (currentRequest) {
                currentRequest.abort();
            }

            var $dropdown = $form.find('.ct-live-search-results');
            if ($dropdown.length === 0) {
                $form.append('<div class="ct-live-search-results"></div>');
                $dropdown = $form.find('.ct-live-search-results');
            }

            if (searchQuery.length < 2) {
                $dropdown.removeClass('active').html('');
                $button.removeClass('loading');
                return;
            }

            $button.addClass('loading');

            searchTimeout = setTimeout(function() {
                currentRequest = $.ajax({
                    url: singlo_ajax_obj.ajaxurl,
                    type: 'GET',
                    data: {
                        action: 'singlo_live_search',
                        s: searchQuery,
                        nonce: nonce
                    },
                    success: function(response) {
                        $button.removeClass('loading');

                        if (response.success && response.data.results.length > 0) {
                            var html = '<div class="ct-search-results-inner">';

                            $.each(response.data.results, function(index, item) {
                                var thumbnailHtml = item.thumbnail
                                    ? '<img src="' + item.thumbnail + '" alt="' + item.title + '" class="ct-result-thumb">'
                                    : '<div class="ct-result-thumb-placeholder"></div>';

                                var metaHtml = '';
                                if (item.version || item.size) {
                                    metaHtml = '<div class="ct-result-meta">';
                                    if (item.version) metaHtml += '<span class="ct-result-version">v' + item.version + '</span>';
                                    if (item.size) metaHtml += '<span class="ct-result-size">' + item.size + '</span>';
                                    metaHtml += '</div>';
                                }

                                html += '<a href="' + item.url + '" class="ct-search-result-item">';
                                html += thumbnailHtml;
                                html += '<div class="ct-result-content">';
                                html += '<div class="ct-result-title">' + item.title + '</div>';
                                html += metaHtml;
                                html += '</div>';
                                html += '</a>';
                            });

                            html += '</div>';
                            $dropdown.html(html).addClass('active');
                        } else {
                            $dropdown.html('<div class="ct-no-results">No results found</div>').addClass('active');
                        }
                    },
                    error: function() {
                        $button.removeClass('loading');
                        $dropdown.removeClass('active').html('');
                    }
                });
            }, 300);
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.ct-search-form').length) {
                $('.ct-live-search-results').removeClass('active');
            }
        });

        $('.ct-search-form input[type="search"]').on('focus', function() {
            var $dropdown = $(this).closest('.ct-search-form').find('.ct-live-search-results');
            if ($dropdown.find('.ct-search-result-item').length > 0) {
                $dropdown.addClass('active');
            }
        });

        // Mobile Menu and Search Toggles
        $('.ct-toggle').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('toggle-panel');
            if (target) {
                var $panel = $(target);
                $panel.addClass('active').removeAttr('inert');
                if (target === '#search-modal') {
                    $panel.attr('aria-modal', 'true');
                }
                $('body').attr('data-panel', 'in');
                
                // Focus search if it's the search modal or contains one
                setTimeout(function() {
                    $panel.find('input[type="search"]').first().focus();
                }, 300);
            }
        });

        // Close Panels
        $('.ct-panel-close, .ct-panel-overlay, .ct-toggle-close').on('click', function(e) {
            e.preventDefault();
            $('.ct-panel').removeClass('active').attr('inert', '').attr('aria-modal', 'false');
            $('body').removeAttr('data-panel');
        });


        // Submenu Toggles for Mobile
        $(document).on('click', '.ct-toggle-dropdown-mobile', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $button = $(this);
            var $parentLi = $button.closest('li');
            var $subMenu = $parentLi.children('.sub-menu');
            
            var expanded = $button.attr('aria-expanded') === 'true';
            
            $button.attr('aria-expanded', !expanded);
            $parentLi.toggleClass('ct-active');
            $subMenu.slideToggle();
        });
// Download Timer Logic
        $('#initialBtn').on('click', function(e) {
            e.preventDefault();
            var $container = $('#download-container');
            var downloadUrl = $container.data('download-url');
            
            if (!downloadUrl) {
                alert('Download link not found.');
                return;
            }


            // Replace button with progress bar using new design
            $container.html('<div class="progress-wrapper">' +
                                '<div class="progress-container">' +
                                    '<div class="counter-left">10</div>' +
                                    '<div class="counter-right">seconds</div>' +
                                    '<div class="progress-bar" style="width: 0%;"></div>' +
                                '</div>' +
                                '<div class="wait-text">Preparing download...</div>' +
                             '</div>');

            var timeLeft = 10.0;
            var totalTime = 10.0;
            var $progressBar = $container.find('.progress-bar');
            var $counterLeft = $container.find('.counter-left');
            
            var timer = setInterval(function() {
                timeLeft -= 0.1;
                var progress = ((totalTime - timeLeft) / totalTime) * 100;
                $progressBar.css('width', progress + '%');
                $counterLeft.text(Math.ceil(timeLeft));

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    $container.html('<a href="' + downloadUrl + '" class="ga-button">' +
                                        '<span class="ga-svg-icon-wrap">' +
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' +
                                                '<polyline points="8 17 12 21 16 17"></polyline>' +
                                                '<line x1="12" y1="12" x2="12" y2="21"></line>' +
                                                '<path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path>' +
                                            '</svg>' +
                                        '</span>' +
                                        '<span class="ga-btn-inner-text">Continue to Download</span>' +
                                    '</a>');
                }
            }, 100);
        });

        // Read More / Read Less Toggle
        $(document).on('click', '.read-more-btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var $container = $btn.closest('.post-description');
            
            $container.toggleClass('expanded');
            
            if ($container.hasClass('expanded')) {
                $btn.find('.text').text('Read Less ↑');
            } else {
                $btn.find('.text').text('Read More ↓');
                // Scroll back to top of section
                $('html, body').animate({
                    scrollTop: $container.offset().top - 100
                }, 400);
            }
        });

        // Comment Captcha Verification
        $(document).on('submit', '#commentform', function(e) {
            var $form = $(this);
            var $answer = $form.find('#ga_math_captcha_answer');
            var $correct = $form.find('input[name="ga_math_captcha_correct_answer"]');
            var $error = $('.captcha-error');

            if ($answer.length && $correct.length) {
                if (parseInt($answer.val()) !== parseInt($correct.val())) {
                    e.preventDefault();
                    $error.fadeIn();
                    $answer.addClass('captcha-loading').focus();
                    setTimeout(function() {
                        $answer.removeClass('captcha-loading');
                    }, 1000);
                    return false;
                } else {
                    $error.hide();
                }
            }
        });
    });

})(jQuery);
