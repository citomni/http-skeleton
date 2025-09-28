<?php
declare(strict_types=1);
/*
 * SPDX-License-Identifier: GPL-3.0-or-later
 * Copyright (C) 2012-2025 Lars Grove Mortensen
 *
 * CitOmni HTTP Skeleton - Minimal starter for high-performance CitOmni HTTP apps.
 * Source:  https://github.com/citomni/http-skeleton
 * License: See the LICENSE file for full terms.
 */

/*
 * ------------------------------------------------------------------
 * MAIN APPLICATION CONFIGURATION (HTTP)
 * ------------------------------------------------------------------
 * This file defines all HTTP-facing settings for your CitOmni app.
 *
 * MERGE MODEL (last wins):
 *   1) Vendor HTTP baseline:   \CitOmni\Http\Boot\Config::CFG
 *   2) Provider CFGs (whitelisted in /config/providers.php)
 *   3) App HTTP base:          /config/citomni_http_cfg.php  (this file)
 *   4) App HTTP overlay:       /config/citomni_http_cfg.{ENV}.php  <- last wins
 *
 *   {ENV} comes from CITOMNI_ENVIRONMENT ('dev' | 'stage' | 'prod').
 *
 * MERGE RULES:
 *   - Associative arrays merge recursively; per-key, the last source wins.
 *   - Numeric arrays (lists) are replaced wholesale by the last source.
 *   - Empty values ('', 0, false, null, []) are valid overrides and still win.
 *
 * ACCESS IN CODE:
 *   - $this->app->cfg->identity->app_name
 *   - $this->app->cfg->http->base_url
 *   - $this->app->cfg->mail->smtp->host
 *   - $this->app->cfg->routes   // NOTE: Returns a raw array by design
 *
 * ROUTES:
 *   Keep routes in /config/routes.php and include them under the 'routes' key:
 *     'routes' => require __DIR__ . '/routes.php',
 *
 *   Matching order and behavior:
 *     - Exact routes are matched first (top-level keys like '/' or '/contact').
 *     - Then 'regex' routes are tested in array order.
 *     - Error routes are keyed by status code (403, 404, 405, 500).
 *     - HEAD is auto-allowed when GET is allowed; OPTIONS handled with Allow.
 *     - Placeholders are supported only inside 'regex' (built-ins: {id}, {email}, {slug}, {code}).
 *
 * BASE URL POLICY:
 *   - If CITOMNI_PUBLIC_ROOT_URL is defined, the kernel uses it verbatim in all environments.
 *   - Dev:
 *       If http.base_url is empty and the constant is not defined, the kernel may auto-detect
 *       CITOMNI_PUBLIC_ROOT_URL from the incoming request (honoring trust_proxy if enabled).
 *   - Stage/Prod:
 *       Never auto-detect. Provide an absolute http.base_url (e.g. 'https://www.example.com')
 *       in citomni_http_cfg.{ENV}.php, or define CITOMNI_PUBLIC_ROOT_URL yourself.
 *   - Always omit trailing slash in base_url.
 *
 * PROXY TRUST:
 *   - http.trust_proxy toggles honoring Forwarded/X-Forwarded-* headers during base URL detection.
 *   - http.trusted_proxies (array) is passed to Request::setTrustedProxies([...]) for stricter control.
 *
 * LOCALE:
 *   - Kernel enforces locale.timezone via date_default_timezone_set() and locale.charset via ini_set('default_charset').
 *     Use valid PHP timezone identifiers and charsets (e.g. 'Europe/Copenhagen', 'UTF-8').
 *
 * ERROR HANDLER QUICK NOTES:
 *   - 'sender' => null (or omitted) makes the kernel fall back to cfg->mail->from->email.
 *   - 'sender' => '' (empty string) disables that fallback on purpose.
 *   - 'display_errors' defaults to (CITOMNI_ENVIRONMENT === 'dev') if omitted.
 *   - With precompiled config caches (var/cache/cfg.http.php), computed booleans are "frozen"
 *     until you warm the cache again (no, hot reload does not read your mind).
 *
 * MAINTENANCE:
 *   - Guard returns HTTP 503 + Retry-After when the flag is active.
 *   - 'default_retry_after' must be >= 0. No, you cannot get negative seconds of downtime.
 *
 * ENV & SECRETS:
 *   - It is OK to keep secrets here (OPcache keeps PHP in memory).
 *   - Do not pass secrets to templates; hand only what the view needs.
 *
 * NOTES:
 *   - Controllers/Models/Services typically extend BaseController/BaseModel/BaseService to gain $this->app.
 *   - This file is app-owned and never overwritten by framework updates. Tidy beats clever.
 * ------------------------------------------------------------------
 */
return [


	/*
	 *------------------------------------------------------------------
	 * APPLICATION IDENTITY
	 *------------------------------------------------------------------
	 */
	'identity' => [
		'app_name' => 'My CitOmni App',

		// Public-facing support contact. For more info see language/contact.php
		'email'    => 'support@mycitomniapp.com',
		'phone'    => '(+45) 12 34 56 78',
	],


	/*
	 *------------------------------------------------------------------
	 * DATABASE CREDENTIALS
	 *------------------------------------------------------------------
	 */
	/*
	'db' => [
		'host'    => 'localhost',
		'user'    => 'root',
		'pass'    => '',
		'name'    => 'citomni',
		'charset' => 'utf8mb4',
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * E-MAIL SETTINGS
	 *------------------------------------------------------------------
	 */
	/*
	'mail' => [
		// Default sender for all system emails
		'from' => [
			'email' => 'system-emails@mycitomniapp.com',
			'name'  => 'MyCitOmniApp.com',
		],

		// Default reply-to (optional)
		'reply_to' => [
			'email' => '',
			'name'  => '',
		],

		// Default content format
		'format'    => 'html',     // 'html' | 'text' (maps to PHPMailer::isHTML)

		// Transport selection
		'transport' => 'smtp',     // 'smtp' | 'mail' | 'sendmail' | 'qmail'

		// Path for Sendmail/Qmail transports (optional)
		'sendmail_path' => '/usr/sbin/sendmail',

		// SMTP settings (only used when transport === 'smtp')
		'smtp' => [
			'host'       => 'send.example.com', // Semicolon list is allowed: "smtp1;smtp2"
			'port'       => 587,                // Common: 25, 465 (SSL), 587 (STARTTLS)
			'encryption' => 'tls',              // 'tls' | 'ssl' | null
			'auth'       => true,
			'username'   => 'system-emails@mycitomniapp.com',
			'password'   => '*******',

			// Operational tuning
			'auto_tls'   => true,               // PHPMailer::SMTPAutoTLS
			'timeout'    => 15,                 // seconds (PHPMailer::Timeout)
			'keepalive'  => false,              // reuse SMTP connection for batch jobs

			// Debugging (set level=0 in production)
			'debug' => [
				'level'  => 0,                  // 0..4
				'output' => 'html',             // 'echo' | 'html' | 'error_log'
			],
		],
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * LOCALE
	 *------------------------------------------------------------------
	 */
	/*
	'locale' => [
		'language' => 'da',
		'timezone' => 'Europe/Copenhagen',
		'charset'  => 'UTF-8', // PHP default_charset + HTML output
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * HTTP SETTINGS
	 *------------------------------------------------------------------
	 */
	'http' => [
		// 'base_url' => 'https://www.mycitomniapp.com', // Never include a trailing slash

		// trust_proxy: true only when behind a trusted reverse proxy/LB; enables honoring Forwarded/X-Forwarded-*.
		// trusted_proxies: optional allow-list of proxy IPs/CIDRs given to Request::setTrustedProxies().
		// When in doubt, keep trust_proxy=false. Guessing headers is not a security strategy.
		// 'trust_proxy'     => false,
		// 'trusted_proxies' => ['10.0.0.0/8','192.168.0.0/16','::1'],
	],


	/*
	 *------------------------------------------------------------------
	 * ERROR HANDLER
	 *------------------------------------------------------------------
	 */
	/*
	'error_handler' => [
		'log_file'       => CITOMNI_APP_PATH . '/var/logs/system_error_log.json',
		'recipient'      => 'errors@citomni.com',
		// sender: null (or omit) => fallback to cfg->mail->from->email; '' (empty string) => no fallback.
		'sender'         => null,
		'max_log_size'   => 10485760,
		'template'       => CITOMNI_APP_PATH . '/templates/errors/failsafe_error.php',
		// Defaults to (CITOMNI_ENVIRONMENT === 'dev') if omitted. Remember caches freeze this until warm.
		'display_errors' => (\defined('CITOMNI_ENVIRONMENT') && \CITOMNI_ENVIRONMENT === 'dev'),
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * SESSION
	 *------------------------------------------------------------------
	 */
	/*
	'session' => [
		// Core
		'name'                   => 'CITSESSID',
		'save_path'              => CITOMNI_APP_PATH . '/var/state/php_sessions',
		'gc_maxlifetime'         => 1440,
		'use_strict_mode'        => true,
		'use_only_cookies'       => true,
		'lazy_write'             => true,
		'sid_length'             => 48,
		'sid_bits_per_character' => 6,

		// Cookie flags
		'cookie_secure'          => null,      // dev: null (auto); stage/prod: set true
		'cookie_httponly'        => true,
		'cookie_samesite'        => 'Lax',     // 'Lax' | 'Strict' | 'None' (None requires Secure)
		'cookie_path'            => '/',
		'cookie_domain'          => null,

		// Optional hardening (disabled by default for zero overhead)
		'rotate_interval'        => 0,         // e.g. 1800 to rotate every 30 min
		'fingerprint' => [
			'bind_user_agent'  => false,       // true to bind UA hash
			'bind_ip_octets'   => 0,           // IPv4: 0..4 leading octets
			'bind_ip_blocks'   => 0,           // IPv6: 0..8 leading blocks
		],
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * COOKIE
	 *------------------------------------------------------------------
	 */
	/*
	'cookie' => [
		// 'secure'   => true|false, // omit to auto-compute
		'httponly' => true,
		'samesite' => 'Lax',
		'path'     => '/',
		// 'domain' => 'example.com',
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * SECURITY
	 *------------------------------------------------------------------
	 */
	/* 
	'security' => [
		'csrf_protection'		=> true, // true | false; Prevent CSRF (Cross-Site Request Forgery) attacks.
		'csrf_field_name'		=> 'csrf_token',
		
		// Anti-bots
		'captcha_protection'	=> true, // true | false; The native captcha will help prevent bots from filling out forms.
		'honeypot_protection'	=> true, // true | false; Enables honeypot protection to prevent automated bot submissions.	
		'form_action_switching'	=> true, // true | false; Enables dynamic form action switching to prevent bot submissions.
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * AUTHENTICATION & ACCOUNT POLICY
	 *------------------------------------------------------------------
	 */
	/*
	'auth' => [
		'account_activation'		=> true, // true | false; If enabled, the account will require email activation before the user can login.
		'twofactor_protection'		=> true, // true | false; Add a two-factor email authentication to the login page.
		'brute_force_protection'	=> true, // true | false; Temporarily block visitors that exceed the login attempts threshold.

		'roles' => [
			'user'      => 1,
			'creator'   => 2,
			'moderator' => 3,
			'operator'  => 5,
			'manager'   => 7,
			'admin'     => 9,
		],
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * VIEW / CONTENT / TEMPLATE ENGINE
	 *------------------------------------------------------------------
	 */
	/*
	'view' => [
		// Cache
		'cache_enabled'        => true,

		// Optimize HTML output
		'trim_whitespace'      => false,
		'remove_html_comments' => false,

		'allow_php_tags'       => true,

		// Marketing scripts (inserted into <head>)
		'marketing_scripts'    => '<!-- Your script -->',

		// Global variables for use in all templates.
		// Any values placed here will automatically be available in every template rendered by the framework.
		// Ideal for site-wide settings, company info, custom flags, or any data that needs to be accessible across all views.
		'view_vars'            => [],
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * STATIC TEXT
	 *------------------------------------------------------------------
	 */
	/*
	'txt' => [
		'log_file' => 'litetxt_errors.json',
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * LOG
	 *------------------------------------------------------------------
	 */
	/*
	'log' => [
		'default_file' => 'citomni_app_log.json',
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * MAINTENANCE FLAG
	 *------------------------------------------------------------------
	 */
	/*
	'maintenance' => [
		'flag' => [
			'path'               => CITOMNI_APP_PATH . '/var/flags/maintenance.php',
			'template'           => CITOMNI_APP_PATH . '/templates/public/maintenance.php',

			// Whitelist of client IPs allowed to bypass maintenance mode
			'allowed_ips'        => [
				'127.0.0.1',      // localhost
				'192.168.1.100',  // example LAN IP
			],

			// Default Retry-After when the flag file does not specify one (seconds >= 0)
			'default_retry_after'=> 600,
		],

		// Lightweight rotation of generated maintenance flag files
		'backup' => [
			'enabled' => true,
			'keep'    => 3, // number of versions to keep (0..n)
			'dir'     => CITOMNI_APP_PATH . '/var/backups/flags/',
		],

		'log' => [
			'filename' => 'maintenance_flag.json',
		],
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * ADMIN WEBHOOKS
	 *------------------------------------------------------------------
	 * Remote control for admin operations (maintenance, deploy, etc.).
	 * One powerful channel -> protect with IP allow-list + strong secret.
	 */
	/* 
	'webhooks' => [
		'enabled' => true,  // Master kill-switch for all admin webhooks
		'ttl_seconds' => 300,  // Max allowed request age in seconds (rejects expired/replayed requests)
		'ttl_clock_skew_tolerance' => 60,  // Extra leeway for clock drift, in seconds
		'allowed_ips' => [],  // Optional allow-list of source IPs (empty = no restriction, or filled like ['203.0.113.10','198.51.100.7'])
		'nonce_dir' => CITOMNI_APP_PATH . '/var/nonces/' // Filesystem path for storing used nonces to prevent replay attacks
	],
	*/


	/*
	 *------------------------------------------------------------------
	 * ROUTES
	 *------------------------------------------------------------------
	 * HTTP routing table.
	 *
	 * - Exact routes: top-level keys like '/' or '/kontakt.html'
	 * - Regex routes: nested under the 'regex' key (evaluated in array order)
	 * - Error routes: keyed by HTTP status code (403, 404, 405, 500)
	 *
	 * Include from /config/routes.php to keep things tidy:
	 *   'routes' => require __DIR__ . '/routes.php',
	 */
	// 'routes' => require __DIR__ . '/routes.php',
];
