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
	// 'http' => [
		// 'base_url'        => 'http://localhost/mycitomniapp', // no trailing slash
		// 'trust_proxy'     => false, // enable only if you run behind a local reverse proxy
		// 'trusted_proxies' => ['127.0.0.1','::1'],
	// ],



	/*
	 * ------------------------------------------------------------------
	 * ERROR HANDLER - noisy on purpose (it's dev)
	 * ------------------------------------------------------------------
	 */
	'error_handler' => [

		'render' => [
		
			// In dev, render common non-fatal issues to the browser for faster feedback.
			// (Fatals are automatically excluded by the handler.)
			'trigger' => E_WARNING | E_NOTICE | E_CORE_WARNING | E_COMPILE_WARNING | E_USER_WARNING | E_USER_NOTICE | E_RECOVERABLE_ERROR | E_DEPRECATED | E_USER_DEPRECATED,

			'detail' => [
			
				// Show developer details (stack traces + structured context) in dev.
				// In non-dev environments, the handler still behaves as minimal output.
				'level' => 1,

				'trace' => [
					'max_frames'      => 120,
					'max_arg_strlen'  => 512,
					'max_array_items' => 20,
					'max_depth'       => 3,
					'ellipsis'        => '...',
				],
			],

			// Make sure PHP itself reports everything to the handler in dev.
			'force_error_reporting' => E_ALL,
		],

		'log' => [
			// Keep logs exhaustive in dev as well.
			'trigger' => E_ALL,
		],

		// 'templates' => [
			// You can override the default branding for dev if desired; otherwise baseline applies.
			// 'html'          => \CITOMNI_APP_PATH . '/templates/error/dev_error.php',
			// 'html_failsafe' => \CITOMNI_APP_PATH . '/templates/error/dev_error_failsafe.php',
		// ],
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
			'email' => 'dev-no-reply@example.com',
			'name'  => 'CitOmni Dev',
		],	
		'transport' => 'smtp', // Use SMTP in dev to mirror prod behavior (ports, TLS, auth, etc.)
		
		// --- Real SMTP (to test 1:1 with prod) ----------------
		'smtp' => [
			'host'       => 'smtp.example.com',
			'port'       => 587,
			'encryption' => 'tls',
			'auth'       => true,
			'username'   => 'dev-no-reply@example.com',
			'password'   => '***',
			'timeout'    => 30,
			'auto_tls'   => true,
			'keepalive'  => false,
			'debug' => [
				'level'  => 2,      // a bit of chatter helps in dev
			],
		],
		
		// MailHog/Mailpit (no auth, no TLS)
		// 'smtp' => [
			// 'host'       => 'localhost',
			// 'port'       => 1025,   // MailHog/Mailpit default
			// 'encryption' => null,   // no TLS to the catcher
			// 'auth'       => false,
			// 'username'   => '',
			// 'password'   => '',
			// 'debug' => [
				// 'level'  => 2,      // a bit of chatter helps in dev
				// 'output' => 'html',
			// ],
		// ],		
		
		'logging' => [			
			'log_success' 	   => true, // Log successful sends to mail_log.json? (Dev = true, Prod = false)			
			'debug_transcript' => true, // Enable detailed SMTP transcript capture for error logs? (true/false)				
			'max_lines' 	   => 200, // Cap number of transcript lines persisted (avoid runaway logs).				
			'include_bodies'   => true, // Include full mail bodies in error logs? (never in prod!) true = log entire Body/AltBody on error, false = only log length + sha256.
		],
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
