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
 * HTTP CONFIG OVERLAY (prod)
 * ------------------------------------------------------------------
 * This file overrides /config/citomni_http_cfg.php for the "prod" environment.
 *
 * Merge model (last wins):
 *   vendor baseline -> providers -> app base -> this file (prod overlay)
 *
 * Typical usage:
 *   - Set an absolute http.base_url (required in stage/prod unless the constant
 *     CITOMNI_PUBLIC_ROOT_URL is defined at bootstrap).
 *   - Disable developer niceties (display_errors off, stricter cookies, etc.).
 *   - Adjust transport and logging for production.
 *
 * Notes:
 *   - CITOMNI_ENVIRONMENT must equal "prod" for this file to load.
 *   - Keep base_url absolute, no trailing slash (e.g. "https://www.example.com").
 *   - If you define CITOMNI_PUBLIC_ROOT_URL earlier, kernel will use it verbatim.
 *   - Keep secrets out of templates; pass only what views need.
 */
return [

	/*
	 * ------------------------------------------------------------------
	 * HTTP - base URL and proxy policy
	 * ------------------------------------------------------------------
	 * Required in prod unless you define CITOMNI_PUBLIC_ROOT_URL.
	 */
	// 'http' => [
	// 	'base_url'        => 'https://www.example.com', // no trailing slash
	// 	// Enable only if behind a trusted reverse proxy/LB
	// 	// 'trust_proxy'     => true,
	// 	// 'trusted_proxies' => ['10.0.0.0/8','192.168.0.0/16','203.0.113.5'],
	// ],

	/*
	 * ------------------------------------------------------------------
	 * ERROR HANDLER - fail fast, log well
	 * ------------------------------------------------------------------
	 */
	// 'error_handler' => [
	// 	// In prod we do not show errors to end users
	// 	'display_errors' => false,
	// 	// 'log_file'     => CITOMNI_APP_PATH . '/var/logs/system_error_log.json',
	// 	// 'recipient'    => 'errors@example.com',
	// 	// 'sender'       => null, // null => fallback to cfg->mail->from->email
	// 	// 'max_log_size' => 10485760,
	// 	// 'template'     => CITOMNI_APP_PATH . '/templates/errors/failsafe_error.php',
	// ],

	/*
	 * ------------------------------------------------------------------
	 * SESSION & COOKIE - tighten the bolts
	 * ------------------------------------------------------------------
	 */
	// 'session' => [
	// 	'cookie_secure'  => true,   // HTTPS-only cookies in prod
	// 	'cookie_samesite'=> 'Lax',  // or 'Strict' if your flows allow it
	// ],
	// 'cookie' => [
	// 	// 'secure'  => true,   // omit to auto-compute, or force true in prod
	// 	'httponly' => true,
	// 	'samesite' => 'Lax',
	// 	'path'     => '/',
	// 	// 'domain' => 'example.com',
	// ],

	/*
	 * ------------------------------------------------------------------
	 * MAIL - real transport, real credentials
	 * ------------------------------------------------------------------
	 */
	// 'mail' => [
	// 	'from' => [
	// 		'email' => 'no-reply@example.com',
	// 		'name'  => 'Example App',
	// 	],
	// 	'transport' => 'smtp',
	// 	'smtp' => [
	// 		'host'       => 'smtp.example.com',
	// 		'port'       => 587,
	// 		'encryption' => 'tls',
	// 		'auth'       => true,
	// 		'username'   => 'smtp-user',
	// 		'password'   => '********',
	// 		'debug' => [
	// 			'level'  => 0,      // prod: keep it quiet
	// 			'output' => 'html',
	// 		],
	// 	],
	// ],

	/*
	 * ------------------------------------------------------------------
	 * VIEW - production defaults
	 * ------------------------------------------------------------------
	 */
	// 'view' => [
	// 	'cache_enabled'        => true,
	// 	'trim_whitespace'      => true,   // optional micro-optimization
	// 	'remove_html_comments' => true,   // optional; do not remove conditional ones you need
	// ],

	/*
	 * ------------------------------------------------------------------
	 * LOGGING - boring but useful
	 * ------------------------------------------------------------------
	 */
	// 'log' => [
	// 	'default_file' => 'citomni_app_log.json',
	// ],

	/*
	 * ------------------------------------------------------------------
	 * MAINTENANCE - be explicit about downtime
	 * ------------------------------------------------------------------
	 */
	// 'maintenance' => [
	// 	'flag' => [
	// 		// seconds >= 0; no, you cannot get negative downtime
	// 		'default_retry_after' => 600,
	// 	],
	// 	// 'log' => ['filename' => 'maintenance_flag.json'],
	// ],

	/*
	 * ------------------------------------------------------------------
	 * WEBHOOKS - production posture
	 * ------------------------------------------------------------------
	 */
	// 'webhooks' => [
	// 	'enabled'                  => false, // enable only if you really use them
	// 	// 'ttl_seconds'           => 300,
	// 	// 'ttl_clock_skew_tolerance' => 60,
	// 	// 'allowed_ips'           => ['198.51.100.7','203.0.113.10'],
	// 	// 'nonce_dir'             => CITOMNI_APP_PATH . '/var/nonces/',
	// ],
];
