(function () {
    'use strict';

    function initializeCarousel(carousel) {
        var section = carousel.closest('.lnt-home-carousel-section');
        var viewport = carousel.querySelector('[data-carousel-viewport]');
        var previousButton = section ? section.querySelector('[data-carousel-prev]') : null;
        var nextButton = section ? section.querySelector('[data-carousel-next]') : null;
        var controls = section ? section.querySelector('.lnt-home-carousel__controls') : null;
        var dragged = false;
        var dragging = false;
        var startX = 0;
        var startScrollLeft = 0;

        if (!viewport || !previousButton || !nextButton) {
            return;
        }

        function getScrollStep() {
            var firstSlide = viewport.querySelector('.lnt-home-carousel__slide');
            var track = viewport.querySelector('.lnt-home-carousel__track');
            var gap = 0;

            if (track) {
                gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
            }

            return firstSlide
                ? firstSlide.getBoundingClientRect().width + gap
                : Math.max(260, viewport.clientWidth * 0.8);
        }

        function updateControls() {
            var maximumScroll = Math.max(0, viewport.scrollWidth - viewport.clientWidth);
            var hasOverflow = maximumScroll > 2;

            previousButton.disabled = !hasOverflow || viewport.scrollLeft <= 2;
            nextButton.disabled = !hasOverflow || viewport.scrollLeft >= maximumScroll - 2;

            if (controls) {
                controls.classList.toggle('is-hidden', !hasOverflow);
            }
        }

        function scrollCarousel(direction) {
            viewport.scrollBy({
                left: direction * getScrollStep(),
                behavior: 'smooth'
            });
        }

        previousButton.addEventListener('click', function () {
            scrollCarousel(-1);
        });

        nextButton.addEventListener('click', function () {
            scrollCarousel(1);
        });

        viewport.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                scrollCarousel(-1);
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                scrollCarousel(1);
            }
        });

        viewport.addEventListener('scroll', updateControls, { passive: true });

        viewport.addEventListener('pointerdown', function (event) {
            if (event.pointerType === 'touch' || event.button !== 0) {
                return;
            }

            if (event.target.closest && event.target.closest('a, button')) {
                dragging = false;
                dragged = false;
                return;
            }

            dragging = true;
            dragged = false;
            startX = event.clientX;
            startScrollLeft = viewport.scrollLeft;
            viewport.classList.add('is-dragging');
            viewport.setPointerCapture(event.pointerId);
        });

        viewport.addEventListener('pointermove', function (event) {
            if (!dragging) {
                return;
            }

            var distance = event.clientX - startX;

            if (Math.abs(distance) > 4) {
                dragged = true;
            }

            viewport.scrollLeft = startScrollLeft - distance;
        });

        function stopDragging(event) {
            if (!dragging) {
                return;
            }

            dragging = false;
            viewport.classList.remove('is-dragging');

            if (viewport.hasPointerCapture(event.pointerId)) {
                viewport.releasePointerCapture(event.pointerId);
            }

            updateControls();
        }

        viewport.addEventListener('pointerup', stopDragging);
        viewport.addEventListener('pointercancel', stopDragging);

        viewport.addEventListener('click', function (event) {
            if (!dragged) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            dragged = false;
        }, true);

        if ('ResizeObserver' in window) {
            new ResizeObserver(updateControls).observe(viewport);
        } else {
            window.addEventListener('resize', updateControls);
        }

        window.addEventListener('load', updateControls, { once: true });
        updateControls();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-home-carousel]').forEach(initializeCarousel);
    });
}());
