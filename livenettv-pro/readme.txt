=== LiveNetTV Pro Membership ===
Contributors: livenettv
Tags: membership, premium, payment, cryptocurrency, subscription
Requires at least: 5.5
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight, secure membership plugin with cryptocurrency payment support for WordPress.

== Description ==

LiveNetTV Pro Membership is a complete solution for managing premium memberships with cryptocurrency payments. Features include:

= Key Features =

*   **Cryptocurrency Payments** - Accept USDT (TRC20/ERC20), Bitcoin, Ethereum, and BNB
*   **Google Sign-In** - Allow users to register/login with their Google account
*   **Manual Payment Approval** - Admin review workflow for cryptocurrency payments
*   **Ad Removal** - Automatically remove ads for premium members
*   **Email Notifications** - Automated emails for payment approvals, rejections, and expiry warnings
*   **Membership Management** - Track active, expired, and pending memberships
*   **Responsive Design** - Mobile-friendly pricing tables and payment forms

= Membership Plans =

*   1 Month - $9.99
*   3 Months - $24.99
*   6 Months - $44.99
*   1 Year - $74.99 (Best Value)
*   Lifetime - $149.99

All plans include ad-free streaming, HD quality, priority support, and full channel access.

== Installation ==

1. Upload the `livenettv-pro` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to LiveNetTV Pro > Settings to configure:
   *   Google OAuth credentials
   *   Cryptocurrency wallet addresses
   *   Email settings
   *   Ad removal selectors
4. Create a page with the [livenettv_premium_plans] shortcode or use the "Premium Plans Page" template
5. Select the Pro page in settings

== Frequently Asked Questions ==

= How do I set up Google Sign-In? =

1. Go to the Google Cloud Console (https://console.cloud.google.com)
2. Create OAuth 2.0 credentials
3. Add the redirect URI shown in the plugin settings to your authorized redirect URIs
4. Copy the Client ID and Client Secret to the plugin settings

= How do cryptocurrency payments work? =

1. User selects a plan and payment method
2. System displays the wallet address for the selected cryptocurrency
3. User sends the payment and uploads a screenshot with transaction ID
4. Admin reviews and approves/rejects the payment
5. Once approved, the user's membership is activated

= Can I customize the ad removal selectors? =

Yes. Go to LiveNetTV Pro > Settings > Ad Removal. Add CSS selectors (one per line) for elements you want to hide for Pro members.

== Changelog ==

= 2.0.0 =
*   Complete rebuild with improved architecture
*   Added Google OAuth authentication
*   Added cryptocurrency payment support (USDT, BTC, ETH, BNB)
*   Added admin payment review workflow
*   Added automatic ad removal for Pro members
*   Added email notification system
*   Added WP-Cron for expiry checks
*   Improved security: proper nonces, capability checks, input validation
*   Responsive design with modern UI
*   Better theme integration

== Upgrade Notice ==

= 2.0.0 =
This is a complete rebuild. Please backup your database before upgrading. Previous payment records will be retained.

== Screenshots ==

1. Premium Plans pricing page
2. Authentication modal with Google Sign-In
3. Payment submission form
4. Admin dashboard
5. Payment review screen

== Security ==

This plugin follows WordPress security best practices:

*   Input validation and sanitization
*   Output escaping
*   Nonce verification for forms and AJAX
*   Capability checks for admin features
*   Prepared SQL statements
*   Secure file upload handling
