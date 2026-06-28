<?php
/**
 * Template Name: Contact Us
 *
 * Contact Us Page Template for Singlo Theme
 * Matches Singlo Theme Design with WordPress Integration
 */

get_header(); ?>

<!-- Cloudflare Turnstile Script -->
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

<style>
    /* Contact Page Styles - Matching Singlo Theme */
    .contact-page-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .contact-hero {
    text-align: center;
    margin-bottom: 50px;
    padding: 40px 20px;
    background: transparent; /* Makes background fully transparent */
    border-radius: 16px;
    color: #000000;
    position: relative; /* For potential overlay effects */
    z-index: 1;
}

    .contact-hero h1 {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 15px;
        letter-spacing: -0.025em;
    }

    .contact-hero p {
        font-size: 18px;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .contact-form-section,
    .contact-info-section {
        background: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--theme-border-color);
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 25px;
        color: var(--theme-palette-color-1);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title svg {
        width: 28px;
        height: 28px;
    }

    /* Form Styles */
    .contact-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 14px;
        color: var(--theme-text-color);
    }

    .form-group label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--theme-form-field-border-initial-color);
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        transition: all 0.2s ease;
        background: #f8fafc;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--theme-form-field-border-focus-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-group textarea {
        min-height: 150px;
        resize: vertical;
    }

    /* Turnstile Container */
    .turnstile-container {
        margin: 10px 0;
    }

    /* Submit Button */
    .submit-button {
        background: var(--theme-button-background-initial-color);
        color: var(--theme-button-text-initial-color);
        border: none;
        padding: 14px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }

    .submit-button:hover {
        background: var(--theme-button-background-hover-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }

    .submit-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .submit-button svg {
        width: 20px;
        height: 20px;
    }

    /* Contact Info Styles */
    .contact-info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 16px;
        border: 1px solid var(--theme-border-color);
        transition: all 0.2s ease;
    }

    .contact-info-item:hover {
        background: white;
        border-color: var(--theme-palette-color-1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .contact-info-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background: var(--theme-palette-color-1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .contact-info-icon svg {
        width: 24px;
        height: 24px;
    }

    .contact-info-content h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--theme-text-color);
    }

    .contact-info-content p {
        font-size: 14px;
        color: #64748b;
    }

    .contact-info-content a {
        color: var(--theme-link-initial-color);
        text-decoration: none;
        transition: color 0.2s;
    }

    .contact-info-content a:hover {
        color: var(--theme-button-background-hover-color);
    }

    /* Alert Messages */
    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: none;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert.show {
        display: flex;
    }

    .alert-success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .alert-error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .contact-hero h1 {
            font-size: 32px;
        }

        .contact-hero p {
            font-size: 16px;
        }

        .contact-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .contact-form-section,
        .contact-info-section {
            padding: 25px;
        }

        .section-title {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .contact-page-wrapper {
            padding: 20px 15px;
        }

        .contact-hero {
            padding: 30px 20px;
            margin-bottom: 30px;
        }

        .contact-hero h1 {
            font-size: 28px;
        }

        .contact-form-section,
        .contact-info-section {
            padding: 20px;
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>

<main class="contact-page-wrapper">
    <!-- Hero Section -->
    <div class="contact-hero">
        <h1>Get In Touch</h1>
        <p>Have questions or feedback? We'd love to hear from you. Fill out the form below and we'll get back to you as soon as possible.</p>
    </div>

    <!-- Contact Grid -->
    <div class="contact-grid">
        <!-- Contact Form Section -->
        <div class="contact-form-section">
            <h2 class="section-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Send us a Message
            </h2>

            <!-- Alert Messages -->
            <div id="success-alert" class="alert alert-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Thank you! Your message has been sent successfully. We'll get back to you soon.</span>
            </div>

            <div id="error-alert" class="alert alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="error-message">Something went wrong. Please try again.</span>
            </div>

            <!-- Contact Form -->
            <form id="contact-form" class="contact-form">
                <div class="form-group">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="What is this regarding?">
                </div>

                <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea id="message" name="message" required placeholder="Write your message here..."></textarea>
                </div>

                <!-- Cloudflare Turnstile -->
                <div class="turnstile-container">
                    <div class="cf-turnstile" data-sitekey="0x4AAAAAAAiFIvKPCjFIjQtX" data-callback="onTurnstileSuccess"></div>
                </div>

                <button type="submit" class="submit-button" id="submit-btn" disabled>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Send Message
                </button>
            </form>
        </div>

        <!-- Contact Info Section -->
        <div class="contact-info-section">
            <h2 class="section-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Contact Information
            </h2>

            <div class="contact-info-item">
                <div class="contact-info-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="contact-info-content">
                    <h3>Email</h3>
                    <p><a href="mailto:livenettvtools@gmail.com">livenettvtools@gmail.com</a></p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="contact-info-content">
                    <h3>Response Time</h3>
                    <p>We typically respond within 24-48 hours</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="contact-info-content">
                    <h3>Support</h3>
                    <p>Available Monday - Friday, 9 AM - 5 PM</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                    </svg>
                </div>
                <div class="contact-info-content">
                    <h3>Telegram</h3>
                    <p><a href="https://t.me/live_tv_help" target="_blank" rel="noopener">Join our community</a></p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V5h16zM4 19V9.044l7.386 5.745a.994.994 0 001.228 0L20 9.044 20.002 19H4z"/>
                    </svg>
                </div>
                <div class="contact-info-content">
                    <h3>Microsoft Teams</h3>
                    <p><a href="https://teams.live.com/l/chat/0/0?users=live:.cid.2dc56f1e6b7013ea" target="_blank" rel="noopener">Chat with us</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Turnstile Configuration
    let turnstileToken = null;

    function onTurnstileSuccess(token) {
        turnstileToken = token;
        document.getElementById('submit-btn').disabled = false;
    }

    // Form Submission
    document.getElementById('contact-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-btn');
        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');
        const errorMessage = document.getElementById('error-message');

        // Hide alerts
        successAlert.classList.remove('show');
        errorAlert.classList.remove('show');

        // Validate Turnstile
        if (!turnstileToken) {
            errorMessage.textContent = 'Please complete the security verification.';
            errorAlert.classList.add('show');
            return;
        }

        // Disable submit button
        submitBtn.disabled = true;
        const originalButtonHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<svg class="animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';

        // Collect form data
        const formData = new FormData();
        formData.append('action', 'singlo_contact_form');
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('subject', document.getElementById('subject').value || '');
        formData.append('message', document.getElementById('message').value);
        formData.append('turnstile_token', turnstileToken);

        try {
            const response = await fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Show success message
                successAlert.classList.add('show');

                // Reset form
                document.getElementById('contact-form').reset();
                turnstileToken = null;

                // Reset Turnstile
                if (typeof turnstile !== 'undefined') {
                    turnstile.reset();
                }

                // Scroll to success message
                successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Re-enable button
                submitBtn.disabled = true; // Keep disabled until new turnstile is completed
            } else {
                // Show error message
                errorMessage.textContent = result.data.message || 'Something went wrong. Please try again.';
                errorAlert.classList.add('show');

                // Reset Turnstile
                if (typeof turnstile !== 'undefined') {
                    turnstile.reset();
                }
            }
        } catch (error) {
            console.error('Error:', error);
            errorMessage.textContent = 'Network error. Please check your connection and try again.';
            errorAlert.classList.add('show');

            // Reset Turnstile
            if (typeof turnstile !== 'undefined') {
                turnstile.reset();
            }
        } finally {
            // Re-enable submit button
            submitBtn.disabled = true; // Keep disabled until new turnstile
            submitBtn.innerHTML = originalButtonHTML;
        }
    });
</script>

<?php get_footer(); ?>