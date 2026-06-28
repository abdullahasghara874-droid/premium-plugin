/**
 * Singlo star rating hover and submit behavior.
 */
(function() {
    'use strict';

    function getCookie(name) {
        var value = document.cookie.split('; ').find(function(row) {
            return row.indexOf(name + '=') === 0;
        });

        return value ? value.split('=')[1] : '';
    }

    function setRated(postId) {
        var key = 'singlo_rated_' + postId;

        try {
            localStorage.setItem(key, 'true');
        } catch (e) {}

        var date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        document.cookie = key + '=true; expires=' + date.toUTCString() + '; path=/; SameSite=Lax';
    }

    function hasRated(postId) {
        var key = 'singlo_rated_' + postId;
        var rated = false;

        try {
            rated = localStorage.getItem(key) === 'true';
        } catch (e) {}

        return rated || getCookie(key) === 'true';
    }

    function updateRatingDisplay(ratingContainer, starsOuter, newAvg, newCount) {
        var numericAvg = parseFloat(newAvg) || 0;
        var newWidth = Math.max(0, Math.min(100, (numericAvg / 5) * 100));
        var starsInner = starsOuter.querySelector('.stars-inner');
        var ratingNumber = ratingContainer.querySelector('.singlo-rating-number');
        var ratingCount = ratingContainer.querySelector('.singlo-rating-count');
        var ratingValueMeta = ratingContainer.querySelector('meta[itemprop="ratingValue"]');
        var ratingCountMeta = ratingContainer.querySelector('meta[itemprop="ratingCount"]');

        if (starsInner) {
            starsInner.style.width = newWidth + '%';
        }

        starsOuter.setAttribute('data-rating', newAvg);

        if (ratingNumber) {
            ratingNumber.textContent = newAvg;
        }

        if (ratingCount) {
            ratingCount.textContent = newCount;
        }

        if (ratingValueMeta) {
            ratingValueMeta.setAttribute('content', newAvg);
        }

        if (ratingCountMeta) {
            ratingCountMeta.setAttribute('content', newCount);
        }
    }

    function initStarRatingHover() {
        var starContainers = document.querySelectorAll('.stars-outer:not([data-rating-bound="1"])');

        starContainers.forEach(function(container) {
            var starsHover = container.querySelector('.stars-hover');
            var interactSpans = container.querySelectorAll('.stars-interact span');
            var ratingContainer = container.closest('.singlo-star-rating');

            container.setAttribute('data-rating-bound', '1');

            if (!starsHover || !interactSpans.length || !ratingContainer) {
                return;
            }

            interactSpans.forEach(function(span) {
                span.addEventListener('mouseenter', function() {
                    var rating = parseFloat(this.getAttribute('data-val')) || 0;
                    starsHover.style.width = Math.max(0, Math.min(100, (rating / 5) * 100)) + '%';
                });

                span.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var rating = parseInt(this.getAttribute('data-val'), 10);
                    var postId = ratingContainer.getAttribute('data-post-id');
                    var nonce = ratingContainer.getAttribute('data-nonce');

                    if (!postId || !nonce || ratingContainer.getAttribute('data-rating-busy') === '1') {
                        return;
                    }

                    if (hasRated(postId)) {
                        alert('You have already rated this app.');
                        return;
                    }

                    if (typeof jQuery === 'undefined' || typeof singlo_ajax_obj === 'undefined') {
                        alert('Rating is temporarily unavailable.');
                        return;
                    }

                    ratingContainer.setAttribute('data-rating-busy', '1');

                    jQuery.ajax({
                        url: singlo_ajax_obj.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'singlo_rate_post',
                            post_id: postId,
                            rating: rating,
                            nonce: nonce
                        },
                        success: function(response) {
                            if (response && response.success) {
                                updateRatingDisplay(ratingContainer, container, response.data.new_avg, response.data.new_count);
                                setRated(postId);
                                alert(response.data.message || 'Thank you for your rating!');
                                return;
                            }

                            alert(response && response.data ? response.data : 'Error submitting rating.');
                        },
                        error: function() {
                            alert('Error submitting rating.');
                        },
                        complete: function() {
                            ratingContainer.removeAttribute('data-rating-busy');
                        }
                    });
                });
            });

            container.addEventListener('mouseleave', function() {
                starsHover.style.width = '0%';
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initStarRatingHover);
    } else {
        initStarRatingHover();
    }
})();
