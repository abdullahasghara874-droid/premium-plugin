/**
 * LiveNetTV Pro Frontend JavaScript
 */
(function($) {
    'use strict';

    var LivenettvPro = {

        init: function() {
            this.bindEvents();
            this.initForm();
        },

        bindEvents: function() {
            $(document).on('change', 'input[name="livenettv_plan"]', this.onPlanChange.bind(this));
            $(document).on('change', 'input[name="livenettv_crypto"]', this.onCryptoChange.bind(this));
            $(document).on('click', '.livenettv-pro-copy-btn', this.copyToClipboard.bind(this));
            $(document).on('change', '#livenettv_screenshot', this.handleScreenshotPreview.bind(this));
            $(document).on('submit', '#livenettv-pro-payment-form', this.handleFormSubmit.bind(this));
        },

        initForm: function() {
            var checkedPlan = $('input[name="livenettv_plan"]:checked');
            if (checkedPlan.length) {
                this.onPlanChange.call(checkedPlan[0]);
            }
        },

        onPlanChange: function(e) {
            this.updatePaymentInfo();
        },

        onCryptoChange: function(e) {
            this.updatePaymentInfo();
        },

        updatePaymentInfo: function() {
            var planInput = $('input[name="livenettv_plan"]:checked');
            var cryptoInput = $('input[name="livenettv_crypto"]:checked');
            var paymentInfo = $('.livenettv-pro-payment-info');

            if (!planInput.length || !cryptoInput.length) {
                paymentInfo.slideUp();
                return;
            }

            var plan = planInput.val();
            var crypto = cryptoInput.val();

            paymentInfo.slideDown();

            paymentInfo.find('.livenettv-pro-qrcode').html('<span class="spinner is-active"></span>');

            $.ajax({
                url: livenettvPro.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'livenettv_get_crypto_data',
                    nonce: livenettvPro.nonce,
                    plan: plan,
                    crypto: crypto
                },
                success: function(response) {
                    if (response.success) {
                        paymentInfo.find('.livenettv-pro-wallet-address').text(response.data.wallet_address);
                        paymentInfo.find('.livenettv-pro-copy-btn').attr('data-copy', response.data.wallet_address);

                        var qrUrl = response.data.qr_url;
                        paymentInfo.find('.livenettv-pro-qrcode').html(
                            '<img src="' + qrUrl + '" alt="QR Code" width="200" height="200">'
                        );
                    } else {
                        paymentInfo.find('.livenettv-pro-qrcode').html(
                            '<p class="error">' + (response.data ? response.data.message : 'Error loading payment data') + '</p>'
                        );
                    }
                },
                error: function() {
                    paymentInfo.find('.livenettv-pro-qrcode').html(
                        '<p class="error">' + livenettvPro.i18n.error + '</p>'
                    );
                }
            });
        },

        copyToClipboard: function(e) {
            e.preventDefault();

            var btn = $(e.currentTarget);
            var text = btn.attr('data-copy');

            if (!text) {
                return;
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    LivenettvPro.showCopySuccess(btn);
                }).catch(function() {
                    LivenettvPro.fallbackCopy(text, btn);
                });
            } else {
                this.fallbackCopy(text, btn);
            }
        },

        fallbackCopy: function(text, btn) {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            this.showCopySuccess(btn);
        },

        showCopySuccess: function(btn) {
            var originalText = btn.html();
            btn.html('<span class="dashicons dashicons-yes"></span> ' + livenettvPro.i18n.copied);
            setTimeout(function() {
                btn.html(originalText);
            }, 2000);
        },

        handleScreenshotPreview: function(e) {
            var file = e.target.files[0];
            var preview = $('.livenettv-pro-upload-preview');

            preview.empty();

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.html('<img src="' + e.target.result + '" alt="Preview">');
                };
                reader.readAsDataURL(file);
            }
        },

        handleFormSubmit: function(e) {
            var form = $(e.target);
            var submitBtn = form.find('.livenettv-pro-submit-btn');
            var selectedPlan = $('input[name="livenettv_plan"]:checked');
            var selectedCrypto = $('input[name="livenettv_crypto"]:checked');
            var txid = $('#livenettv_txid').val().trim();
            var screenshot = $('#livenettv_screenshot')[0].files[0];

            if (!selectedPlan.length) {
                alert(livenettvPro.i18n.selectPlan || 'Please select a plan.');
                e.preventDefault();
                return false;
            }

            if (!selectedCrypto.length) {
                alert(livenettvPro.i18n.selectCrypto || 'Please select a payment method.');
                e.preventDefault();
                return false;
            }

            if (!txid) {
                alert(livenettvPro.i18n.enterTxid || 'Please enter your transaction ID.');
                e.preventDefault();
                return false;
            }

            if (!screenshot) {
                alert(livenettvPro.i18n.uploadScreenshot || 'Please upload a payment screenshot.');
                e.preventDefault();
                return false;
            }

            submitBtn.prop('disabled', true);
            submitBtn.find('.dashicons').removeClass('dashicons-yes').addClass('dashicons-update is-active');
        }
    };

    $(document).ready(function() {
        LivenettvPro.init();
    });

})(jQuery);
