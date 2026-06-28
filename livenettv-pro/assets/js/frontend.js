/* LiveNetTV Pro — Frontend JS */
;(function($) {
    'use strict';

    var LNTV = {

        init: function() {
            this.modal      = $('#lntv-auth-modal');
            this.panelLogin = $('#lntv-panel-login');
            this.panelReg   = $('#lntv-panel-register');
            this._redirect  = '';

            this.bindSubscribe();
            this.bindModal();
            this.bindLoginForm();
            this.bindRegisterForm();
            this.bindGoogleLogin();
            this.bindFaq();
            this.bindCryptoSelect();
            this.bindCopyBtn();
            this.bindFileUpload();
            this.autoOpenBilling();
        },

        // ---- Subscribe buttons ----
        bindSubscribe: function() {
            var self = this;

            // Guest — open auth modal
            $(document).on('click', '.lntv-open-auth-modal', function() {
                var $btn     = $(this);
                var redirect = $btn.data('redirect') || livenettvPro.paymentUrl;
                self.openModal(redirect);
            });

            // Logged-in — show billing form
            $(document).on('click', '.lntv-show-payment', function() {
                var plan = $(this).data('plan');
                self.showBilling(plan);
            });
        },

        // ---- Modal open / close ----
        openModal: function(redirect) {
            this._redirect = redirect || '';
            this.modal.removeAttr('hidden');
            $('body').addClass('lntv-modal-active');
            // Reset to login panel
            this.showPanel('login');
        },

        closeModal: function() {
            this.modal.attr('hidden', '');
            $('body').removeClass('lntv-modal-active');
            this.clearErrors();
        },

        bindModal: function() {
            var self = this;
            $('#lntv-modal-close, #lntv-modal-overlay').on('click', function() { self.closeModal(); });
            $(document).on('keydown', function(e) { if (27 === e.keyCode) self.closeModal(); });
            $('#lntv-goto-register').on('click', function() { self.showPanel('register'); });
            $('#lntv-goto-login').on('click',    function() { self.showPanel('login'); });
        },

        showPanel: function(which) {
            if ('login' === which) {
                this.panelLogin.removeAttr('hidden');
                this.panelReg.attr('hidden', '');
            } else {
                this.panelReg.removeAttr('hidden');
                this.panelLogin.attr('hidden', '');
            }
            this.clearErrors();
        },

        clearErrors: function() {
            $('.lntv-form-error').attr('hidden', '').text('');
        },

        showError: function($el, msg) {
            $el.removeAttr('hidden').text(msg);
        },

        // ---- Show billing form ----
        showBilling: function(plan) {
            var $section = $('#lntv-billing-section');
            if (!$section.length) return;

            $section.slideDown(300);

            // Pre-select plan radio
            if (plan) {
                var $radio = $('[name="livenettv_plan"][value="' + plan + '"]');
                if ($radio.length) {
                    $radio.prop('checked', true);
                    $radio.closest('.lntv-plan-radio').addClass('lntv-plan-radio--selected');
                }
            }

            // Scroll to billing section
            $('html, body').animate({ scrollTop: $section.offset().top - 80 }, 400);
        },

        // Auto-open billing if ?plan= in URL (user just logged in)
        autoOpenBilling: function() {
            var urlParams = new URLSearchParams(window.location.search);
            var plan      = urlParams.get('plan');
            var section   = $('#lntv-billing-section');

            if (plan && section.length) {
                this.showBilling(plan);
            }
        },

        // ---- Login form ----
        bindLoginForm: function() {
            var self = this;
            $('#lntv-login-form').on('submit', function(e) {
                e.preventDefault();
                var $form  = $(this);
                var $btn   = $form.find('button[type="submit"]');
                var $error = $('#lntv-login-error');

                var log = $.trim($form.find('[name="log"]').val());
                var pwd = $form.find('[name="pwd"]').val();
                var terms = $form.find('[type="checkbox"]').is(':checked');

                if (!log || !pwd) {
                    return self.showError($error, 'Please enter your username and password.');
                }
                if (!terms) {
                    return self.showError($error, 'Please agree to the Terms of Service.');
                }

                $btn.prop('disabled', true).html('<span class="lntv-spinner"></span> Signing in...');
                $error.attr('hidden', '');

                $.post(livenettvPro.ajaxUrl, {
                    action:   'livenettv_wp_login',
                    nonce:    livenettvPro.nonce,
                    log:      log,
                    pwd:      pwd,
                    redirect: self._redirect
                }, function(res) {
                    if (res.success) {
                        window.location.href = res.data.redirect;
                    } else {
                        self.showError($error, res.data.message || 'Login failed.');
                        $btn.prop('disabled', false).text('Login with Username or Email');
                    }
                }).fail(function() {
                    self.showError($error, 'Server error. Please try again.');
                    $btn.prop('disabled', false).text('Login with Username or Email');
                });
            });
        },

        // ---- Register form ----
        bindRegisterForm: function() {
            var self = this;
            $('#lntv-register-form').on('submit', function(e) {
                e.preventDefault();
                var $form  = $(this);
                var $btn   = $form.find('button[type="submit"]');
                var $error = $('#lntv-register-error');

                var username  = $.trim($form.find('[name="user_login"]').val());
                var email     = $.trim($form.find('[name="user_email"]').val());
                var pass      = $form.find('[name="user_pass"]').val();
                var pass2     = $form.find('[name="user_pass2"]').val();
                var capAnswer = $form.find('[name="captcha_answer"]').val();
                var capRight  = $form.find('[name="captcha_correct"]').val();
                var terms     = $form.find('[type="checkbox"]').is(':checked');

                if (!username || !email || !pass) {
                    return self.showError($error, 'All fields are required.');
                }
                if (pass !== pass2) {
                    return self.showError($error, 'Passwords do not match.');
                }
                if (!terms) {
                    return self.showError($error, 'Please agree to the Terms of Service.');
                }

                $btn.prop('disabled', true).html('<span class="lntv-spinner"></span> Creating account...');
                $error.attr('hidden', '');

                $.post(livenettvPro.ajaxUrl, {
                    action:           'livenettv_wp_register',
                    nonce:            livenettvPro.nonce,
                    user_login:       username,
                    user_email:       email,
                    user_pass:        pass,
                    user_pass2:       pass2,
                    captcha_answer:   capAnswer,
                    captcha_correct:  capRight,
                    redirect:         self._redirect
                }, function(res) {
                    if (res.success) {
                        window.location.href = res.data.redirect;
                    } else {
                        self.showError($error, res.data.message || 'Registration failed.');
                        $btn.prop('disabled', false).text('Sign Up');
                    }
                }).fail(function() {
                    self.showError($error, 'Server error. Please try again.');
                    $btn.prop('disabled', false).text('Sign Up');
                });
            });
        },

        // ---- Google login ----
        bindGoogleLogin: function() {
            var self = this;
            $(document).on('click', '#lntv-google-login-btn', function() {
                var $btn = $(this);
                $btn.prop('disabled', true).text('Connecting...');

                $.post(livenettvPro.ajaxUrl, {
                    action:   'livenettv_get_google_url',
                    nonce:    livenettvPro.nonce,
                    redirect: self._redirect
                }, function(res) {
                    if (res.success && res.data.auth_url) {
                        window.location.href = res.data.auth_url;
                    } else {
                        $btn.prop('disabled', false).text('Sign in with Google');
                        alert((res.data && res.data.message) || 'Google Sign-In failed.');
                    }
                }).fail(function() {
                    $btn.prop('disabled', false).text('Sign in with Google');
                });
            });
        },

        // ---- FAQ accordion ----
        bindFaq: function() {
            $(document).on('click', '.lntv-faq__q', function() {
                var $item = $(this).closest('.lntv-faq__item');
                var open  = $item.hasClass('lntv-faq__item--open');
                $('.lntv-faq__item').removeClass('lntv-faq__item--open');
                if (!open) $item.addClass('lntv-faq__item--open');
            });
        },

        // ---- Crypto selection → reveal wallet address ----
        bindCryptoSelect: function() {
            $(document).on('change', '.lntv-crypto-radios input[type="radio"]', function() {
                var crypto   = $(this).val();
                var wallets  = $(this).closest('.lntv-crypto-radios').data('wallets');
                var address  = wallets && wallets[crypto] ? wallets[crypto] : '';
                var $display = $('#lntv-wallet-display');
                var $addr    = $('#lntv-wallet-address');

                if (address) {
                    $addr.text(address);
                    $('#lntv-copy-wallet').data('copy', address);
                    $display.removeAttr('hidden');
                } else {
                    $display.attr('hidden', '');
                }
            });
        },

        // ---- Copy wallet address ----
        bindCopyBtn: function() {
            $(document).on('click', '.lntv-copy-btn', function() {
                var $btn = $(this);
                var text = $btn.data('copy') || $('#lntv-wallet-address').text();
                if (!text) return;

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function() {
                        $btn.text('Copied!');
                        setTimeout(function() { $btn.html('<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Copy'); }, 2000);
                    });
                } else {
                    var $t = $('<textarea>').val(text).appendTo('body').select();
                    document.execCommand('copy');
                    $t.remove();
                    $btn.text('Copied!');
                    setTimeout(function() { $btn.text('Copy'); }, 2000);
                }
            });
        },

        // ---- File upload preview ----
        bindFileUpload: function() {
            $(document).on('change', '#lntv-screenshot', function() {
                var file    = this.files && this.files[0];
                var $prev   = $('#lntv-upload-preview');
                $prev.empty();
                if (!file) return;
                var reader  = new FileReader();
                reader.onload = function(e) {
                    $prev.html('<img src="' + e.target.result + '" alt="Preview">');
                };
                reader.readAsDataURL(file);
            });
        }
    };

    // Plan radio visual feedback
    $(document).on('change', '.lntv-plan-radio input', function() {
        $('.lntv-plan-radio').removeClass('lntv-plan-radio--selected');
        $(this).closest('.lntv-plan-radio').addClass('lntv-plan-radio--selected');
    });

    // Prevent modal close when clicking inside box
    $(document).on('click', '.lntv-modal__box', function(e) { e.stopPropagation(); });

    $(document).ready(function() { LNTV.init(); });

})(jQuery);
