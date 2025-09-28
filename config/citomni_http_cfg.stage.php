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
 * HTTP CONFIG OVERLAY (stage)
 * ------------------------------------------------------------------
 * This file overrides /config/citomni_http_cfg.php for the "stage" environment.
 *
 * Merge model (last wins):
 *   vendor baseline -> providers -> app base -> this file (stage overlay)
 *
 * Typical usage:
 *   - Set an absolute http.base_url (required in stage/prod unless the constant
 *     CITOMNI_PUBLIC_ROOT_URL is defined at bootstrap).
 *   - Mirror your prod posture (cookies, errors, logging) but keep safe knobs
 *     for validation. "Like prod, but movable."
 *
 * Notes:
 *   - CITOMNI_ENVIRONMENT must equal "stage" for this file to load.
 *   - Keep base_url absolute, no trailing slash (e.g. "https://stage.example.com").
 *   - If you define CITOMNI_PUBLIC_ROOT_URL earlier, kernel will use it verbatim.
 *   - Keep secrets out of templates; pass only what views need.
 */
return [

	/*
	 * ------------------------------------------------------------------
	 * HTTP - base URL and proxy policy
	 * ------------------------------------------------------------------
	 * Required in stage unless you define CITOMNI_PUBLIC_ROOT_URL.
	 */
	// 'http' => [
	// 	'base_url'        => 'https://stage.example.com', // no trailing slash
	// 	// Enable only if behind a trusted reverse proxy/LB
	// 	// 'trust_proxy'     => true,
	// 	// 'trusted_proxies' => ['10.0.0.0/8','192.168.0.0/16','203.0.113.5'],
	// ],

	/*
	 * ------------------------------------------------------------------
	 * ERROR HANDLER - stage posture
	 * ------------------------------------------------------------------
	 * Default is (CITOMNI_ENVIRONMENT === 'dev') for display_errors; in stage
	 * we usually keep it off and read the logs like adults.
	 */
	// 'error_handler' => [
	// 	'display_errors' => false,
	// 	// 'log_file'     => CITOMNI_APP_PATH . '/var/logs/system_error_log.json',
	// 	// 'recipient'    => 'errors@example.com',
	// 	// 'sender'       => null, // null => fallback to cfg->mail->from->email
	// 	// 'max_log_size' => 10485760,
	// 	// 'template'     => CITOMNI_APP_PATH . '/templates/errors/failsafe_error.php',
	// ],

	/*
	 * ------------------------------------------------------------------
	 * SESSION & COOKIE - prod-like defaults
	 * ------------------------------------------------------------------
	 */
	// 'session' => [
	// 	'cookie_secure'   => true,   // HTTPS-only cookies on stage
	// 	'cookie_samesite' => 'Lax',  // or 'Strict' if your flows allow it
	// ],
	// 'cookie' => [
	// 	// 'secure'  => true,   // omit to auto-compute, or force true
	// 	'httponly' => true,
	// 	'samesite' => 'Lax',
	// 	'path'     => '/',
	// 	// 'domain' => 'stage.example.com',
	// ],

	/*
	 * ------------------------------------------------------------------
	 * MAIL - stage transport
	 * ------------------------------------------------------------------
	 * Use a sandbox or blackhole by default; accidentally emailing customers
	 * is exciting exactly once.
	 */
	// 'mail' => [
	// 	'from' => [
	// 		'email' => 'no-reply@stage.example.com',
	// 		'name'  => 'Example App (Stage)',
	// 	],
	// 	'transport' => 'smtp',
	// 	'smtp' => [
	// 		'host'       => 'smtp.stage.example.com',
	// 		'port'       => 587,
	// 		'encryption' => 'tls',
	// 		'auth'       => true,
	// 		'username'   => 'smtp-user',
	// 		'password'   => '********',
	// 		'debug' => [
	// 			'level'  => 0,      // keep it quiet unless diagnosing mail
	// 			'output' => 'html',
	// 		],
	// 	],
	// ],

	/*
	 * ------------------------------------------------------------------
	 * VIEW - stage defaults
	 * ------------------------------------------------------------------
	 */
	// 'view' => [
	// 	'cache_enabled'        => true,
	// 	'trim_whitespace'      => true,   // optional
	// 	'remove_html_comments' => true,   // optional; mind conditional comments
	// ],

	/*
	 * ------------------------------------------------------------------
	 * LOGGING - dial it in
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
	// 		// seconds >= 0; negative downtime still not supported
	// 		'default_retry_after' => 300,
	// 	],
	// ],

	/*
	 * ------------------------------------------------------------------
	 * WEBHOOKS - stage posture
	 * ------------------------------------------------------------------
	 * Enable only if you actually validate them end-to-end.
	 */
	// 'webhooks' => [
	// 	'enabled'                  => false,
	// 	// 'ttl_seconds'           => 300,
	// 	// 'ttl_clock_skew_tolerance' => 60,
	// 	// 'allowed_ips'           => ['198.51.100.7','203.0.113.10'],
	// 	// 'nonce_dir'             => CITOMNI_APP_PATH . '/var/nonces/',
	// ],
];
