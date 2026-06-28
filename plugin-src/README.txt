=== LiveNetTV Pro Membership ===
Contributors: livenettv
Tags: membership, premium, ads, ad remover, crypto payment, google login, pro membership
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ad-Free Premium Membership system for LiveNetTV.tools with crypto payments and Google Sign-In.

== Description ==

LiveNetTV Pro Membership is a comprehensive premium membership plugin designed to remove ads for paying members while keeping the website accessible to free visitors.

= Key Features =

**For Free Visitors:**
* No login required to browse
* Full access to website content and downloads
* All advertisements visible
* "Go Pro - Remove Ads" prompts in footer and download pages

**For Pro Members:**
* Google Sign-In authentication (only required at purchase)
* Multiple membership plans (1 Month, 3 Months, 6 Months, 1 Year, Lifetime)
* Complete ad removal across the entire website
* Faster, cleaner browsing experience

**Payment System (Phase 1 - Manual Crypto):**
* Supports USDT, BTC, ETH, and BNB
* Automatic QR code generation for each wallet
* One-click copy wallet addresses
* Payment screenshot upload
* Transaction ID (TXID) submission
* Manual admin approval workflow

**Admin Dashboard:**
* View user name and Google email
* See selected plan and payment details
* Review uploaded screenshots
* Check transaction IDs
* View payment status and submission time
* One-click Approve/Reject buttons

**When Approved:**
* Pro membership automatically activated
* Expiry date set based on plan duration
* All ads removed for the user
* Confirmation email sent
* Payment marked as approved

**When Rejected:**
* Payment marked as rejected
* Email with reason sent to user
* User can submit a new payment

**Auto Expiry System:**
* WP-Cron checks memberships hourly
* Automatic status change when expired
* Email notification sent to user
* Ads become visible again

**Future Ready:**
* Architecture supports Binance Pay Merchant API upgrade
* No database changes needed for automatic payments
* Same data structure works with webhooks

= Shortcodes =

* `[livenettv_pro_form]` - Complete payment form with plan selection
* `[livenettv_pro_status]` - Current user membership status
* `[livenettv_pro_cta text="Go Pro"]` - Call-to-action button for free users
* `[livenettv_login_button]` - Google Sign-In button

== Installation ==

1. Upload the `livenettv-pro` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Pro Membership" menu in admin sidebar
4. Configure Google OAuth credentials in "Google Settings"
5. Add your crypto wallet addresses in "Payment Wallets"
6. Customize ad CSS selectors in "Ad Settings"
7. Add the `[livenettv_pro_form]` shortcode to a page
8. Set that page in "Plans & Pricing" settings

== Frequently Asked Questions ==

= How do users sign up? =

Users sign in using Google OAuth. Clicking "Sign in with Google" redirects them to authenticate with Google, then creates a WordPress account automatically.

= How does the payment process work? =

1. User selects a plan
2. User chooses crypto payment method (USDT/BTC/ETH/BNB)
3. QR code and wallet address are displayed
4. User uploads payment screenshot and enters TXID
5. Admin reviews in dashboard
6. Admin approves or rejects
7. User receives email notification

= How are ads removed? =

The plugin injects CSS to hide specified ad elements and runs JavaScript to hide dynamically loaded ads. Ads are removed from:
* Header
* Footer
* Sidebar
* Content areas
* Download pages
* Sticky ads
* Popups

= What happens when membership expires? =

1. WP-Cron runs hourly to check expiries
2. When expired, status changes to "expired"
3. User receives expiry email
4. Ads become visible again
5. User can renew any time

= How do I upgrade to Binance Pay automatic processing? =

The plugin is built for future automatic integration. When ready:
1. Keep all existing database tables
2. Add your Binance Pay API credentials
3. Connect webhooks for payment confirmation
4. No data migration required

== Changelog ==

= 1.0.0 =
* Initial release
* Google OAuth login
* Crypto payment submission (USDT, BTC, ETH, BNB)
* Manual admin approval workflow
* Multi-plan support (1 Month, 3 Months, 6 Months, 1 Year, Lifetime)
* Complete ad removal for Pro users
* Email notifications (approval, rejection, expiry, warnings)
* WP-Cron for membership expiry
* Full admin dashboard
* QR code generation for crypto wallets
* Responsive frontend UI

== Upgrade Notice ==

= 1.0.0 =
Initial release. Activate and configure Google OAuth and wallet addresses to get started.

== Security ==

* Nonce verification on all forms
* Capability checks for admin actions
* SQL prepared statements for all queries
* Escaping of all output
* File upload validation (type, size)
* Secure file storage location
* State token for OAuth CSRF protection

== Requirements ==

* WordPress 5.8+
* PHP 7.4+
* Google Cloud OAuth credentials
* Cryptocurrency wallet addresses
