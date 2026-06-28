/**
 * LiveNetTV Pro Admin JavaScript
 */
(function($) {
    'use strict';

    var LivenettvProAdmin = {

        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            $(document).on('click', '.livenettv-pro-copy-btn', this.copyToClipboard.bind(this));
            $(document).on('click', '.livenettv-pro-approve-btn', this.handleApprove.bind(this));
            $(document).on('click', '.livenettv-pro-reject-btn', this.handleReject.bind(this));
            $(document).on('submit', '#livenettv-reject-form', this.handleRejectFormSubmit.bind(this));
            $('#livenettv-reject-form').on('submit', this.handleRejectFormSubmit.bind(this));
            $(document).on('click', '.livenettv-pro-action-buttons .livenettv-pro-approve-btn', this.handleApproveAjax.bind(this));
        },

        copyToClipboard: function(e) {
            e.preventDefault();

            var btn = $(e.currentTarget);
            var text = btn.attr('data-copy');

            if (!text) {
                return;
            }

            var success = this.copyText(text);
            if (success) {
                this.showToast(livenettvProAdmin.i18n.copied || 'Copied!');
            }
        },

        copyText: function(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text);
                return true;
            }

            var textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            var success = document.execCommand('copy');
            document.body.removeChild(textarea);
            return success;
        },

        handleApprove: function(e) {
            var btn = $(e.currentTarget);
            var message = livenettvProAdmin.i18n.confirmApprove || 'Approve this payment and activate pro membership?';

            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }

            return true;
        },

        handleApproveAjax: function(e) {
            e.preventDefault();

            var btn = $(e.currentTarget);
            var href = btn.attr('href');
            var url = new URL(href, window.location.origin);
            var paymentId = url.searchParams.get('payment_id');
            var nonce = url.searchParams.get('_wpnonce');

            if (!confirm(livenettvProAdmin.i18n.confirmApprove || 'Approve this payment?')) {
                return;
            }

            btn.addClass('button-disabled').prop('disabled', true);

            $.ajax({
                url: livenettvProAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'livenettv_admin_approve',
                    nonce: livenettvProAdmin.nonce,
                    payment_id: paymentId
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = window.location.pathname + '?page=livenettv-pro-payments&approved=1';
                    } else {
                        alert(response.data.message || 'Error approving payment');
                        btn.removeClass('button-disabled').prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Server error. Please try again.');
                    btn.removeClass('button-disabled').prop('disabled', false);
                }
            });
        },

        handleReject: function(e) {
            var btn = $(e.currentTarget);

            if (!confirm(livenettvProAdmin.i18n.confirmReject || 'Reject this payment? User will be notified.')) {
                e.preventDefault();
                return false;
            }

            return true;
        },

        handleRejectFormSubmit: function(e) {
            e.preventDefault();

            var form = $(e.target);
            var btn = form.find('.livenettv-pro-reject-btn');
            var paymentId = form.find('input[name="payment_id"]').val();
            var reason = form.find('textarea[name="reason"]').val();

            if (!confirm(livenettvProAdmin.i18n.confirmReject || 'Reject this payment?')) {
                return false;
            }

            btn.addClass('button-disabled').prop('disabled', true);

            $.ajax({
                url: livenettvProAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'livenettv_admin_reject',
                    nonce: livenettvProAdmin.nonce,
                    payment_id: paymentId,
                    reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = window.location.pathname + '?page=livenettv-pro-payments&rejected=1';
                    } else {
                        alert(response.data.message || 'Error rejecting payment');
                        btn.removeClass('button-disabled').prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Server error. Please try again.');
                    btn.removeClass('button-disabled').prop('disabled', false);
                }
            });

            return false;
        },

        showToast: function(message, type) {
            type = type || 'success';

            var toast = $('<div class="livenettv-pro-toast ' + type + '">' + message + '</div>');
            toast.css({
                position: 'fixed',
                bottom: '30px',
                right: '30px',
                padding: '15px 25px',
                background: type === 'error' ? '#dc3232' : '#46b450',
                color: '#fff',
                borderRadius: '4px',
                boxShadow: '0 2px 10px rgba(0,0,0,0.2)',
                zIndex: 999999
            });

            $('body').append(toast);

            setTimeout(function() {
                toast.fadeOut(function() {
                    toast.remove();
                });
            }, 3000);
        }
    };

    $(document).ready(function() {
        LivenettvProAdmin.init();
    });

})(jQuery);
