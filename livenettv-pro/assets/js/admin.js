/* LiveNetTV Pro Admin JavaScript */
(function($) {
    'use strict';

    var LNTVAdmin = {
        init: function() {
            this.initPaymentActions();
            this.initRejectForm();
            this.initPlanToggle();
        },

        initPaymentActions: function() {
            var self = this;

            $('#lntv-approve-btn').on('click', function(e) {
                e.preventDefault();
                var btn = $(this);
                var paymentId = btn.data('payment-id');

                if (!confirm(livenettvProAdmin.strings.confirmApprove)) {
                    return;
                }

                btn.prop('disabled', true).html('<span class="spinner is-active"></span> ' + livenettvProAdmin.strings.processing);

                $.ajax({
                    url: livenettvProAdmin.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'livenettv_pro_approve_payment',
                        nonce: livenettvProAdmin.nonce,
                        payment_id: paymentId
                    },
                    success: function(response) {
                        if (response.success) {
                            self.showNotice('Approval successful! Membership activated.', 'success');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            btn.prop('disabled', false).html('<span class="dashicons dashicons-yes-alt"></span> Approve Payment');
                            self.showNotice(response.data.message || 'Failed to approve payment', 'error');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).html('<span class="dashicons dashicons-yes-alt"></span> Approve Payment');
                        self.showNotice('A server error occurred', 'error');
                    }
                });
            });
        },

        initRejectForm: function() {
            var self = this;

            $('#lntv-reject-btn').on('click', function(e) {
                e.preventDefault();
                $('#lntv-reject-form').slideDown();
            });

            $('#lntv-cancel-reject').on('click', function(e) {
                e.preventDefault();
                $('#lntv-reject-form').slideUp();
                $('#lntv-reject-reason').val('');
            });

            $('#lntv-confirm-reject').on('click', function(e) {
                e.preventDefault();
                var btn = $(this);
                var paymentId = $('#lntv-reject-btn').data('payment-id');
                var reason = $('#lntv-reject-reason').val();

                if (!confirm(livenettvProAdmin.strings.confirmReject)) {
                    return;
                }

                btn.prop('disabled', true).next().prop('disabled', true);

                $.ajax({
                    url: livenettvProAdmin.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'livenettv_pro_reject_payment',
                        nonce: livenettvProAdmin.nonce,
                        payment_id: paymentId,
                        reason: reason
                    },
                    success: function(response) {
                        if (response.success) {
                            self.showNotice('Payment rejected. User notified.', 'warning');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            btn.prop('disabled', false).next().prop('disabled', false);
                            self.showNotice(response.data.message || 'Failed to reject payment', 'error');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).next().prop('disabled', false);
                        self.showNotice('A server error occurred', 'error');
                    }
                });
            });
        },

        initPlanToggle: function() {
            // Toggle recommended status visual
            $('input[name$="[recommended]"]').on('change', function() {
                var card = $(this).closest('.lntv-plan-card');
                if (this.checked) {
                    card.not('.lntv-plan-recommended').addClass('lntv-plan-recommended');
                } else {
                    card.removeClass('lntv-plan-recommended');
                }
            });
        },

        showNotice: function(message, type) {
            var noticeClass = 'notice notice-' + type;
            var notice = $('<div class="' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');

            $('.wrap h1').first().after(notice);

            // Make dismissible
            notice.append('<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss</span></button>');
            notice.on('click', '.notice-dismiss', function() {
                notice.fadeOut(300, function() { $(this).remove(); });
            });

            // Auto dismiss
            setTimeout(function() {
                notice.fadeOut(300, function() { $(this).remove(); });
            }, 5000);
        }
    };

    /* Initialize */
    $(document).ready(function() {
        LNTVAdmin.init();
    });

})(jQuery);
