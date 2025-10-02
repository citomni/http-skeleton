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
	 * ERROR HANDLER - fail fast, never leave client with blank page, log well
	 * ------------------------------------------------------------------
	 */
	/*
	'error_handler' => [

		'render' => [

			// Which non-fatal PHP error levels (bitmask) should trigger rendering?
			// - 0 (default here): do not render non-fatal errors (prod/stage-friendly).
			// - DO NOT include fatal classes: E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR.
			//   The handler will sanitize fatals away even if misconfigured.
			//
			// Typical dev choice (see dev overlay):
			//   E_WARNING | E_NOTICE | E_CORE_WARNING | E_COMPILE_WARNING |
			//   E_USER_WARNING | E_USER_NOTICE | E_RECOVERABLE_ERROR |
			//   E_DEPRECATED | E_USER_DEPRECATED
			'trigger' => 0,

			'detail' => [

				// How much detail should be shown to the client?
				// 0 = minimal client message (always recommended for prod/stage)
				// 1 = developer details (stack traces, structured context) – ONLY active when CITOMNI_ENVIRONMENT === 'dev'
				//     (Setting 1 in non-dev envs will still behave as 0.)
				//
				// Logs are always detailed regardless of this flag.

				'level' => 0,

				// Trace formatting limits (only apply when detail.level = 1 AND we are in 'dev').
				// Keep bounded to avoid huge responses and leakage; logs still carry full structured info.
				'trace' => [
					'max_frames'      => 120,  // Maximum number of stack frames included.
					'max_arg_strlen'  => 512,  // Max chars shown per string argument.
					'max_array_items' => 20,   // Max array items per level.
					'max_depth'       => 3,    // Recursion depth when dumping arrays/objects.
					'ellipsis'        => '...',// Ellipsis marker for truncated output.
				],
			],

			// Optional hard override of PHP's error_reporting at install time (int mask).
			// - Leave unset/null to respect ini/error_reporting as-is.
			// - Example (dev overlay): E_ALL
			
			// 'force_error_reporting' => null,
		],

		'log' => [

			// Which PHP error levels (bitmask) should be logged?
			// - Default: E_ALL (recommended; logs are for developers/ops).
			// - Router 404/405/5xx are always logged, but into separate files:
			//   - http_router_404.jsonl
			//   - http_router_405.jsonl
			//   - http_router_5xx.jsonl
			///
			'trigger'    => E_ALL,

			
			// Log directory (absolute). Files are JSONL with size-guarded rotation.
			// - Rotation strategy: sidecar lock + copy+truncate, timestamped rotated files.
			// - Retention: keeps at most 'max_files' rotated siblings per base; live file persists.
			///
			'path'       => \CITOMNI_APP_PATH . '/var/logs',

			
			// Rotate a log before the next write would exceed this many bytes.
			// Keep conservative defaults to protect disk on shared hosts.
			///
			'max_bytes'  => 2_000_000, // ~2MB

			
			// Maximum number of rotated files to keep per base (live file excluded).
			'max_files'  => 10,
		],

		'templates' => [

			// Optional primary HTML template for error pages.
			// - Plain PHP file; receives $data array with keys:
			//   status, status_text, error_id, title, message, details|null, request_id, year
			// - If missing/unreadable, handler falls back to 'html_failsafe' and finally inline minimal HTML.
			'html'          => \CITOMNI_APP_PATH . '/templates/error/error.php',

			// Optional failsafe HTML template. Leave null/empty to skip.
			'html_failsafe' => null,
		],

		'status_defaults' => [

			// Default HTTP status codes when a category-specific status is not explicitly set.
			// - 'exception' and 'shutdown' are almost always 500.
			// - 'php_error' is used when rendering non-fatal PHP errors (dev only by default).
			// - 'http_error' is a fallback; router passes explicit status (404/405/5xx) anyway.
			'exception' => 500,
			'shutdown'  => 500,
			'php_error' => 500,
			'http_error'=> 500,
		],
	],
	*/


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
	// 		'default_retry_after' => 600,
	// 	],
	// 	// 'log' => ['filename' => 'maintenance_flag.json'],
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
