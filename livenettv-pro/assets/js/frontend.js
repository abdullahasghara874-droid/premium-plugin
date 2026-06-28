/* LiveNetTV Pro Frontend JavaScript */
(function($) {
    'use strict';

    var LNTV = {
        init: function() {
            this.initAuthModal();
            this.initSubscribeButtons();
            this.initPaymentForm();
            this.initFaqAccordion();
            this.initCopyToClipboard();
            this.bindEvents();
        },

        bindEvents: function() {
            $(document).on('click', '.lntv-faq-question', this.toggleFaq);
            $(document).on('submit', '.lntv-auth-form', this.handleAuthForm);
            $(document).on('click', '.lntv-auth-tab', this.switchAuthTab);
            $(document).on('click', '.lntv-modal-overlay', this.closeModalOnOverlay);
            $(document).on('click', '.lntv-modal-close', this.closeModal);
        },

        /* Auth Modal */
        initAuthModal: function() {
            // Modal is added via wp_footer hook
        },

        showAuthModal: function(selectedPlan) {
            var modal = $('#lntv-auth-modal');
            if (modal.length) {
                if (selectedPlan) {
                    modal.find('input[name="livenettv_selected_plan"]').val(selectedPlan);
                }
                modal.addClass('active');
                $('body').addClass('lntv-modal-open');
            }
        },

        closeModal: function(e) {
            e && e.preventDefault();
            $('#lntv-auth-modal').removeClass('active');
            $('body').removeClass('lntv-modal-open');
            $('.lntv-auth-form input').removeClass('error');
            $('.lntv-form-error').text('');
        },

        closeModalOnOverlay: function(e) {
            if ($(e.target).hasClass('lntv-modal-overlay')) {
                LNTV.closeModal();
            }
        },

        switchAuthTab: function(e) {
            e.preventDefault();
            var tab = $(this);
            var target = tab.data('tab');

            tab.siblings().removeClass('active');
            tab.addClass('active');

            $('.lntv-auth-panel').removeClass('active');
            $('#lntv-panel-' + target).addClass('active');
        },

        handleAuthForm: function(e) {
            e.preventDefault();
            var form = $(this);
            var isLogin = form.hasClass('lntv-login-form');
            var submitBtn = form.find('button[type="submit"]');
            var errors = false;

            // Clear previous errors
            form.find('.lntv-form-error').text('');
            form.find('input').removeClass('error');

            // Validate
            var email = form.find('input[name="email"]').val().trim();
            if (!email) {
                form.find('input[name="email"]').addClass('error');
                form.find('.lntv-email-error').text('Email is required');
                errors = true;
            } else if (!LNTV.isValidEmail(email)) {
                form.find('input[name="email"]').addClass('error');
                form.find('.lntv-email-error').text('Invalid email address');
                errors = true;
            }

            if (!isLogin || !form.hasClass('lntv-wp-login')) {
                var password = form.find('input[name="password"]').val();
                if (!password || password.length < 6) {
                    form.find('input[name="password"]').addClass('error');
                    form.find('.lntv-password-error').text('Password must be at least 6 characters');
                    errors = true;
                }
            }

            // Registration specific
            if (!isLogin) {
                var captcha = form.find('input[name="captcha"]').val();
                if (!captcha) {
                    form.find('input[name="captcha"]').addClass('error');
                    form.find('.lntv-captcha-error').text('Please solve the math problem');
                    errors = true;
                }

                var terms = form.find('input[name="terms"]');
                if (terms.length && !terms.is(':checked')) {
                    form.find('.lntv-terms-error').text('You must agree to the terms');
                    errors = true;
                }
            }

            if (errors) return;

            // Submit
            submitBtn.prop('disabled', true).html('<span class="lntv-spinner"></span> Processing...');

            var data = {
                action: isLogin ? 'livenettv_wp_login' : 'livenettv_wp_register',
                nonce: livenettvPro.nonce,
                email: email,
                redirect: form.find('input[name="redirect"]').val() || ''
            };

            if (isLogin) {
                data.password = form.find('input[name="password"]').val();
            } else {
                data.password = form.find('input[name="password"]').val();
                data.name = form.find('input[name="name"]').val() || '';
                data.captcha = form.find('input[name="captcha"]').val();
                data.captcha_answer = form.find('input[name="captcha_answer"]').val();
            }

            $.post(livenettvPro.ajaxUrl, data, function(response) {
                if (response.success) {
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    } else {
                        window.location.reload();
                    }
                } else {
                    LNTV.showFormError(form, response.data.message || 'An error occurred');
                    submitBtn.prop('disabled', false).text(isLogin ? 'Log In' : 'Create Account');
                }
            }).fail(function() {
                submitBtn.prop('disabled', false).text(isLogin ? 'Log In' : 'Create Account');
                LNTV.showFormError(form, 'A server error occurred');
            });
        },

        showFormError: function(form, message) {
            form.find('.lntv-form-error').last().text(message);
        },

        isValidEmail: function(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },

        /* Subscribe Buttons */
        initSubscribeButtons: function() {
            $(document).on('click', '.lntv-subscribe-btn', function(e) {
                e.preventDefault();
                var plan = $(this).data('plan');
                if (livenettvPro.isLoggedIn === '1') {
                    // User is logged in, redirect to payment form
                    window.location.href = livenettvPro.paymentUrl + '?plan=' + encodeURIComponent(plan);
                } else {
                    // Show auth modal
                    LNTV.showAuthModal(plan);
                }
            });
        },

        /* Payment Form */
        initPaymentForm: function() {
            var form = $('#lntv-payment-form');
            if (!form.length) return;

            // Crypto selection
            $(document).on('click', '.lntv-crypto-option', function() {
                var option = $(this);
                var crypto = option.data('crypto');
                var wallet = option.data('wallet');

                $('.lntv-crypto-option').removeClass('selected');
                option.addClass('selected');

                $('#lntv_selected_crypto').val(crypto);
                $('.lntv-wallet-address').text(wallet);
                $('.lntv-wallet-display').show();
            });

            // File upload
            $(document).on('click', '.lntv-file-upload', function() {
                $(this).find('input[type="file"]').click();
            });

            $(document).on('change', '.lntv-file-upload input[type="file"]', function() {
                var input = this;
                var preview = $('.lntv-file-preview');

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        preview.html('<img src="' + e.target.result + '" alt="Preview">');
                        $preview.show();
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Form submission
            form.on('submit', function(e) {
                // Basic validation
                if (!$('#lntv_selected_crypto').val()) {
                    e.preventDefault();
                    alert('Please select a payment method');
                    return false;
                }

                if (!$('.lntv-file-upload input[type="file"]')[0].files[0]) {
                    e.preventDefault();
                    alert('Please upload a payment screenshot');
                    return false;
                }

                // Form will submit normally via admin-post.php
                return true;
            });
        },

        /* FAQ Accordion */
        initFaqAccordion: function() {
            // Already handled via event binding
        },

        toggleFaq: function(e) {
            e.preventDefault();
            var item = $(this).closest('.lntv-faq-item');
            var isActive = item.hasClass('active');

            // Close all
            $('.lntv-faq-item').removeClass('active');

            // Open clicked if it wasn't active
            if (!isActive) {
                item.addClass('active');
            }
        },

        /* Copy to Clipboard */
        initCopyToClipboard: function() {
            $(document).on('click', '.lntv-copy-btn', function(e) {
                e.preventDefault();
                var btn = $(this);
                var text = btn.data('copy') || $('.lntv-wallet-address').text();

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function() {
                        btn.text('Copied!').prop('disabled', true);
                        setTimeout(function() {
                            btn.text('Copy').prop('disabled', false);
                        }, 2000);
                    });
                } else {
                    // Fallback
                    var textarea = $('<textarea>').val(text).appendTo('body');
                    textarea.select();
                    document.execCommand('copy');
                    textarea.remove();
                    btn.text('Copied!').prop('disabled', true);
                    setTimeout(function() {
                        btn.text('Copy').prop('disabled', false);
                    }, 2000);
                }
            });
        }
    };

    /* Google Auth */
    var LNTVGoogle = {
        init: function() {
            $(document).on('click', '#lntv-google-login-btn, .lntv-btn-google', this.handleGoogleLogin);
        },

        handleGoogleLogin: function(e) {
            e.preventDefault();
            var btn = $(this);
            btn.prop('disabled', true).html('<span class="lntv-spinner"></span> Connecting...');

            var plan = $('input[name="livenettv_selected_plan"]').val() || '';
            var redirect = livenettvPro.paymentUrl + (plan ? '?plan=' + encodeURIComponent(plan) : '');

            $.post(livenettvPro.ajaxUrl, {
                action: 'livenettv_get_google_auth_url',
                nonce: livenettvPro.nonce,
                redirect: redirect
            }, function(response) {
                if (response.success && response.data.auth_url) {
                    window.location.href = response.data.auth_url;
                } else {
                    btn.prop('disabled', false).html('<i class="fab fa-google"></i> Continue with Google');
                    alert(response.data && response.data.message ? response.data.message : 'Failed to connect to Google');
                }
            }).fail(function() {
                btn.prop('disabled', false).html('<i class="fab fa-google"></i> Continue with Google');
                alert('A server error occurred');
            });
        }
    };

    /* Initialize */
    $(document).ready(function() {
        LNTV.init();
        LNTVGoogle.init();
    });

})(jQuery);
