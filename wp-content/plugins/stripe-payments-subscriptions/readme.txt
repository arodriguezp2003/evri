=== Stripe Payments Subscriptions Addon ===
Contributors: alexanderfoxc
Donate link: https://stripe-plugins.com
Tags: stripe
Requires at least: 4.7
Tested up to: 5.5
Stable tag: 2.0.27

Adds Stripe Subscriptions support to the core plugin.

== Description ==

Adds Stripe Subscriptions support to the core plugin.

== Changelog ==

= 2.0.27 =
* Added trial period support for variable subscriptions.
Requires Stripe Payments 2.0.40+.

= 2.0.26 =
* Core plugin version 2.0.38 support.

= 2.0.25 =
* Improved existing webhooks detection.

= 2.0.24 =
* Added coupons support for plans with trial (requires Stripe Payments 2.0.37+).
* Added product name to initial plan payment description.

= 2.0.23 =
* Updated webhooks signing secret errors handling.
* Code refactoring.

= 2.0.22 =
* Reuse coupons created in Stripe Dashboard when possible.
* Improved add-on settings page handling.

= 2.0.21 =
* `Past Due` subscription status now lets customers update CC details.
* Fixed rare issue during CC update process.

= 2.0.20 =
* `Setup Fee` and `Trial Setup Fee` are now properly working with zero-decimal currencies like JPY.

= 2.0.19 =
* `{sub_cancel_url}` and `{sub_update_cc_url}` email merge tags are now properly working for all subscriptions.

= 2.0.18 =
* Added security token check.

= 2.0.17 =
* Added support for the upcoming changes in core plugin.

= 2.0.16 =
* Trial setup fee is no longer counted as regular plan payment.
* Fixed calculation inconsistency in plan amount. 19.99 amount no longer becomes 19.98 when creating new plan.

= 2.0.15 =
* Restyled Subscriptions page.
* Credit Card update warning no longer displayed for inactive subscriptions.
* More strings are available for translation.
* Added support for upcoming features in the core plugin.

= 2.0.14 =
* "Cancel subscription" URL is now properly displayed when `[asp_show_my_transactions]` shortcode is used.
* Added preliminary support for new orders display style.
* Removed files that are no longer used.
* .pot file is now compatible with WP format.
* Minor admin-side visual tweaks.

= 2.0.13 =
* Added subscriptions support for Additional Payment Methods addon.
Requires Stripe Payments 2.0.29+ and APM 2.0.12+.

= 2.0.12 =
* Added "Trial Setup Fee" subscription plan option.
* Revamped subscription plan edit page.
* All-off coupons are now working with subscriptions.
* Added "View on Stripe" link to subscription details page.
* Trial orders have "(trial)" added to order title now.
* Added notification when trying to make plan billing interval longer than a year (Stripe's limitation).

= 2.0.11 =
* Subscription products now support shipping. Requires Stripe Payments 2.0.23+.
* Added "One-time Setup Fee" plan option. Requires Stripe Payments 2.0.23+.
* Imporved zero-cents currencies handling.
* Products with Live mode plans are using Live mode regardless of the core plugin "Live Mode" setting.

= 2.0.10 =
* Show error on payment popup if plan can't be found. Requires Stripe Payments 2.0.21+.

= 2.0.9 =
* Added coupons support for subscription products. Requires Stripe Payments 2.0.20+.
* Slightly decreased payment processing time by combining API requests.

= 2.0.8 =
* Added quantity support for subscription products. Requires Stripe Payments 2.0.19+.
* Fixed PHP warnings when browsing "Products" and "Subscriptions" pages.
* Resolved issue with plugin deactivation when core plugin is deactivated.

= 2.0.7 =
* Consistent amount rounding between frontend and backend.
* Prevent double execution of some functions.

= 2.0.6 =
* Fixed fatal error on plugin deactivation (when core plugin is diactivated as well).

= 2.0.5 =
* Fixed excess "Incomplete" payment was created on Stripe Dashboard along with subscription initial payment. 
Requires Stripe Payments 2.0.17+.
* Removed unnecessary server requests to speed up subscription process.

= 2.0.4 =
* Fixed fatal error on credit card update page.
* Added more informative error messages on credit card update page.
* Proper error message is now displayed on settings page when webhook creation fails.

= 2.0.3 =
* Fixed issues with zero-cents currency processing. Requires Stripe Payments 2.0.15+.

= 2.0.2 =
* Fixed some symbols in plan names were incorrectly displayed on Stripe Dashboard.

= 2.0.1 =
* Fixed subscriptions with tax issues. Requires Stripe Payments 2.0.12+.

= 2.0.0 =
* Added Stripe SCA support.

= 1.5.5 =
* Customer name and billing address are added to Stripe Dashboard. Works only if "Collect Address on Checkout" option is enabled in product settings.

= 1.5.4 =
* Added functions for other addons to handle subscription ended or canceled actions.
This allows addons like MailChimp, ConvertKit etc. to unsubscribe customers when subscription ended.
* Minor text updates.

= 1.5.3 =
* Minor bugfixes to prevent PHP notices.

= 1.5.2 =
* Fixed link to Settings page in webhooks notification message.

= 1.5.1 =
* Fixed tax percent was improperly rounded down.

= 1.5.0 =
* Implemented credit card update functionality. Check out [documentation](https://s-plugins.com/stripe-subscription-payments-addon/#cc-update)
* Added configurable option to email customer when his\her credit card is close to expiry (Stripe Payments -> Settings, Subscriptions tab).
* Added update credit card URL to subscription details screen.
* Added {sub_update_cc_url} email shortcode that produces update credit card link for a customer.
* Subscriptions dates are now displayed using WP date settings and considers timezone.
* Assigned text domain and generated .pot file for translation purposes.

= 1.4.7 =
* Fixed race condition when "Create Webhook" buttons were clicked in a row.
* Fixed Subscription Plans and Subscriptions tables column sorting.
* Fixed minor HTML issues on Settings page that could produce improper settings tab display in some browsers.

= 1.4.6 =
* Fixed negative amount display in email receipts and checkout results page when coupon is used with trial subscriptions.
Requires core plugin version 1.9.18+

= 1.4.5 =
* Trial subscriptions are now displaying 0 as payment amount on checkout results and email receipts.
Payment button in Stripe pop-up for those now shows "Start Free Trial" instead of payment amount.
Requires core plugin version 1.9.18+

= 1.4.4 =
* Added {sub_cancel_url} email merge tag that allows you to send cancellation URL to your customers.
Requires core plugin version 1.9.17+ to function properly.
* Added cancellation URL to subscription details screen.
* Added core plugin's [asp_show_my_transactions] shortcode support.

= 1.4.3 =
* Fixed webhooks notice was displayed even if webhooks were properly created.

= 1.4.2 =
* Fixed total amount display on checkout results page and emails when coupon is used.

= 1.4.1 =
* Fixed all new plans were created in Test mode under some circumstances.
* Fixed PHP notices on subscription plan update.

= 1.4.0 =
* Added automatic webhook creation functionality on settings page.
* Added admin notice if webhooks are not configured.

= 1.3.5 =
* Fixed Stripe mode was improperly set for subscription plans during payment processing.

= 1.3.4 =
* Added variable amount and currency support for Subscriptions (requires Stripe Payments 1.9.14+).
* Fixed plan mode wasn't properly indicated sometimes in Subscriptions list.

= 1.3.3 =
* Added quantity consideration for subscriptions (requires Stripe Payments 1.9.14+).
* Added Coupons support for subscriptions (requires Stripe Payments 1.9.14+).

= 1.3.2 =
* Webhooks URLs on the settings page are now displayed with "https://" regardless of the "WordPress Address" setting.

= 1.3.1 =
* Fixed rare race condition issue when first plan payment could get not counted for eMember subscriptions.
* Made debug output less aggressive.

= 1.3.0 =
* Error is displayed when trying to create a plan with negative numeric values for some inputs (Amount, Duration etc).
* Fixed a couple of PHP Notices.

= 1.2.9 =
* Plan name is now used as product name when creating or updating plans. This is to make sure customers get correct product name in Stripe's receipt email.
* Added notice regarding plan modes on plan create\edit page. Made Mode radioboxes larger and easier to click.

= 1.2.8 =
* Added eMember integration support (requires core plugin version 1.9.3+).
* Fixed PHP Notice during checkout process.

= 1.2.7 =
* Added the asp_subscription_invoice_paid action hook.
* Added the asp_subscription_ended action hook.
* Added the asp_subscription_canceled action hook.

= 1.2.6 =
* Webhook signature is now only checked if received webhook type is of one those that needs to be processed.

= 1.2.5 =
* Fixed webhook signature check was failing under some cirsumstances.
* Fixed currency was improperly taken from product setting rather than plan setting.

= 1.2.4 =
* Fixed "Can't find plan ID" error if plans were created in older addon version.

= 1.2.3 =
* Fixed plan data couldn't be fetched from Stripe under some circumstances.

= 1.2.2 =
* Added link to "Add new plan" page if no plans created.

= 1.2.1 =
* Ended subscriptions are no longer displayed as "Canceled" now.
* Fixed typos.

= 1.2 =
* Fixed PHP fatal error when core plugin is not installed or not active.
* Plugin now checks for minimum required core plugin version (1.8.4.) and shows error message if it's lower.
* Enabled addon update functionality.

= 1.1 =
* The subscription amount description now gets displayed on the front-end product box.

= 1.0 =
* First public test release.