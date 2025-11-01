<?php
declare(strict_types=1);
/*
 * SPDX-License-Identifier: MIT
 * Copyright (C) 2012-2025 Lars Grove Mortensen
 *
 * CitOmni HTTP Skeleton - Minimal starter for high-performance CitOmni HTTP apps.
 * Source:  https://github.com/citomni/http-skeleton
 * License: See the LICENSE file for full terms.
 */

/**
 * APPLICATION SERVICE MAP (APP-WINS)
 * ------------------------------------------------------------------
 * Deterministic service wiring by id. This file overrides vendor/provider
 * definitions where ids collide. Pure data, zero logic.
 *
 * Contract:
 *   - Keys are service ids (accessed as $this->app->id).
 *   - Values can be:
 *       1) FQCN string                      -> new FQCN(App $app)
 *       2) ['class'=>FQCN,'options'=>[...]] -> new FQCN(App $app, array $options)
 *   - Services are singletons per request/process (resolved by App::__get()).
 *   - Constructors must follow: new Service(App $app, array $options = []).
 *
 * Merge precedence (deterministic):
 *   1) Vendor baseline: \CitOmni\Http\Boot\Services::MAP_HTTP
 *   2) Providers in /config/providers.php (each ::MAP_HTTP)      // merged left-biased
 *   3) This file (config/services.php)                            // wins by id
 *
 * Notes:
 *   - Order inside this array does not matter for resolution; ids are unique.
 *     If you duplicate a key in *this* file, the last literal wins (PHP array rules).
 *   - Keep it ASCII-only and explicit. Clever factories belong elsewhere.
 *   - If a class is missing or invalid, the kernel fails fast (by design).
 */
return [

	/*
	 * ------------------------------------------------------------------
	 * ROUTER (example override)
	 * ------------------------------------------------------------------
	 * Baseline router is defined by citomni/http. Override to tweak behavior
	 * or pass runtime options (kept minimal for low overhead).
	 */
	// 'router' => [
	// 	'class'   => \CitOmni\Http\Service\Router::class,
	// 	'options' => [
	// 		// 'cacheDir' => CITOMNI_APP_PATH . '/var/cache/routes',
	// 	],
	// ],


	/*
	 * ------------------------------------------------------------------
	 * LOGGING (example override)
	 * ------------------------------------------------------------------
	 * Provided by citomni/infrastructure (if enabled via providers.php).
	 * Point logs where you want them; keep formats boring and machine-friendly.
	 */
	// 'log' => [
	// 	'class'   => \CitOmni\Infrastructure\Service\Log::class,
	// 	'options' => [
	// 		'dir'    => CITOMNI_APP_PATH . '/var/logs',
	// 		'level'  => 'info',  // trace|debug|info|warn|error
	// 		'format' => 'json',  // json|line
	// 	],
	// ],


	/*
	 * ------------------------------------------------------------------
	 * DATABASE (example custom service)
	 * ------------------------------------------------------------------
	 * Infrastructure may already provide DB access. If you prefer a custom
	 * adapter, wire it here under your chosen id.
	 */
	// 'db' => \App\Service\DbConnection::class,


	/*
	 * ------------------------------------------------------------------
	 * VIEW ENGINE (example override)
	 * ------------------------------------------------------------------
	 * citomni/http ships with a View service. Override to change templating
	 * behavior, caching, or output policies.
	 */
	// 'view' => \App\Service\View::class,


	/*
	 * ------------------------------------------------------------------
	 * SESSION (example override)
	 * ------------------------------------------------------------------
	 * Wraps PHP sessions. Override to customize storage or validation.
	 */
	// 'session' => [
	// 	'class'   => \CitOmni\Http\Service\Session::class,
	// 	'options' => [
	// 		// 'save_path' => CITOMNI_APP_PATH . '/var/state/php_sessions',
	// 	],
	// ],

	/*
	 * ------------------------------------------------------------------
	 * MAIL (example override)
	 * ------------------------------------------------------------------
	 * Provided by citomni/infrastructure. Override to inject transport tweaks.
	 */
	// 'mail' => [
	// 	'class'   => \CitOmni\Infrastructure\Service\Mail::class,
	// 	'options' => [
	// 		// 'override_from' => ['email' => 'no-reply@example.com', 'name' => 'Example App'],
	// 	],
	// ],

];
