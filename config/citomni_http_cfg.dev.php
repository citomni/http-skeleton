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
 * HTTP CONFIG OVERLAY (dev)
 * ------------------------------------------------------------------
 * This file overrides /config/citomni_http_cfg.php for the "dev" environment.
 *
 * Merge model (last wins):
 *   vendor baseline -> providers -> app base -> this file (dev overlay)
 *
 * Dev behavior highlights:
 *   - If http.base_url is empty and CITOMNI_PUBLIC_ROOT_URL is not defined,
 *     the kernel may auto-detect a base URL from the incoming request
 *     (honoring http.trust_proxy if enabled). Handy for local dev.
 *   - display_errors defaults to ON in dev unless you explicitly turn it off.
 *   - Prefer low-friction debugging over micro-optimizations here.
 *
 * Notes:
 *   - CITOMNI_ENVIRONMENT must equal "dev" for this file to load.
 *   - If you *do* set http.base_url, keep it absolute and without trailing slash
 *     (e.g., "http://localhost/mycitomniapp"). Yes, https on localhost works too.
 *   - Do not duplicate the full documentation; see citomni_http_cfg.php for rules.
 */

return [

	/*
	 * ------------------------------------------------------------------
	 * HTTP - base URL and proxy policy
	 * ------------------------------------------------------------------
	 * Leave base_url commented to use dev auto-detection, or set it explicitly.
	 */
	'http' => [
		// 'base_url'        => 'http://localhost/mycitomniapp', // no trailing slash
		// 'trust_proxy'     => false, // enable only if you run behind a local reverse proxy
		// 'trusted_proxies' => ['127.0.0.1','::1'],
	],

	/*
	 * ------------------------------------------------------------------
	 * ERROR HANDLER - noisy on purpose (it is dev)
	 * ------------------------------------------------------------------
	 */
	'error_handler' => [
		// In dev we usually show errors; comment out to rely on the default
		'display_errors' => true,
		// 'log_file'     => CITOMNI_APP_PATH . '/var/logs/system_error_log.json',
		// 'recipient'    => '',   // leave empty for local dev unless you want emails
		// 'sender'       => null, // null => fallback to cfg->mail->from->email
		// 'max_log_size' => 10485760,
		// 'template'     => CITOMNI_APP_PATH . '/templates/errors/failsafe_error.php',
	],

	/*
	 * ------------------------------------------------------------------
	 * SESSION & COOKIE - dev defaults
	 * ------------------------------------------------------------------
	 */
	'session' => [
		// Auto-compute secure flags in dev; be stricter in prod overlay
		'cookie_secure' => null,
		'cookie_samesite' => 'Lax',
	],
	'cookie' => [
		// 'secure'  => null, // let the runtime compute it from scheme
		'httponly' => true,
		'samesite' => 'Lax',
		'path'     => '/',
	],

	/*
	 * ------------------------------------------------------------------
	 * MAIL - SMTP in dev for prod parity
	 * ------------------------------------------------------------------
	 * Default uses a local catcher (MailHog/Mailpit). Switch to the
	 * "real SMTP" block below when you want full end-to-end parity.
	 */
	'mail' => [
		'from' => [
			'email' => 'dev-no-reply@local.test',
			'name'  => 'CitOmni Dev',
		],

		// Use SMTP in dev to mirror prod behavior (ports, TLS, auth, etc.)
		'transport' => 'smtp',

		// MailHog/Mailpit (no auth, no TLS)
		'smtp' => [
			'host'       => 'localhost',
			'port'       => 1025,   // MailHog/Mailpit default
			'encryption' => null,   // no TLS to the catcher
			'auth'       => false,
			'username'   => '',
			'password'   => '',
			'debug' => [
				'level'  => 2,      // a bit of chatter helps in dev
				'output' => 'html',
			],
		],

		// --- Real SMTP (uncomment to test 1:1 with prod) ----------------
		// 'smtp' => [
		// 	'host'       => 'smtp.example.com',
		// 	'port'       => 587,
	// 		'encryption' => 'tls',   // or 'ssl' on 465
		// 	'auth'       => true,
		// 	'username'   => 'smtp-user',
		// 	'password'   => '********',
		// 	'debug' => [
		// 		'level'  => 2,      // bump up during debugging, 0 when you are done
		// 		'output' => 'html',
		// 	],
		// ],
	],

	/*
	 * ------------------------------------------------------------------
	 * VIEW - developer ergonomics
	 * ------------------------------------------------------------------
	 */
	'view' => [
		'cache_enabled'        => false, // live edits without cache invalidation
		'trim_whitespace'      => false,
		'remove_html_comments' => false,
		'allow_php_tags'       => true,
		// 'marketing_scripts'  => '<!-- Dev: keep this empty unless you test tags -->',
		'view_vars'            => [],
	],

	/*
	 * ------------------------------------------------------------------
	 * LOGGING - make it obvious
	 * ------------------------------------------------------------------
	 */
	'log' => [
		'default_file' => 'citomni_app_log.json',
	],

	/*
	 * ------------------------------------------------------------------
	 * MAINTENANCE - convenient defaults for local toggling
	 * ------------------------------------------------------------------
	 */
	'maintenance' => [
		'flag' => [
			// seconds >= 0; no, negative downtime is not a feature
			'default_retry_after' => 60,
			// 'allowed_ips' => ['127.0.0.1','::1'],
		],
	],

	/*
	 * ------------------------------------------------------------------
	 * WEBHOOKS - typically off in dev unless you test them
	 * ------------------------------------------------------------------
	 */
	'webhooks' => [
		'enabled' => false,
		// 'ttl_seconds' => 300,
		// 'ttl_clock_skew_tolerance' => 60,
		// 'allowed_ips' => ['127.0.0.1','::1'],
		// 'nonce_dir' => CITOMNI_APP_PATH . '/var/nonces/',
	],
];
