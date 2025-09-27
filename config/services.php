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

/**
 * Application service map (overrides vendor/provider maps by service id).
 *
 * Rules:
 * - Keys are service IDs (the name you access via $this->app->id).
 * - Values can be:
 *     1) FQCN string -> new FQCN($app)
 *     2) ['class' => FQCN, 'options' => [...]] -> new FQCN($app, $options)
 * - Order is irrelevant here (ids are unique). "Last wins" if duplicated.
 * - Keep this file pure data (no closures, no logic). Deterministic wiring only.
 *
 * Defaults:
 * - Baseline services are defined in:
 *     \CitOmni\Http\Boot\Services::MAP_HTTP
 * - Providers may add their own in /config/providers.php.
 * - This file always wins last (app-owned).
 *
 * Typical usage:
 * - Replace a vendor service with your own (e.g., custom router).
 * - Add runtime options to an existing service.
 * - Introduce entirely new app-specific services.
 */
return [

	/*
	 * ------------------------------------------------------------------
	 * ROUTER (example override)
	 * ------------------------------------------------------------------
	 * Baseline router is already defined by citomni/http.
	 * Uncomment to override or add runtime options (like cacheDir).
	 */
	// 'router' => [
	// 	'class'   => \CitOmni\Http\Service\Router::class,
	// 	'options' => [
	// 		'cacheDir' => __DIR__ . '/../var/cache/routes',
	// 	],
	// ],


	/*
	 * ------------------------------------------------------------------
	 * LOGGING (example override)
	 * ------------------------------------------------------------------
	 * citomni/infrastructure provides a log service.
	 * You can override log destination, format, level, etc.
	 */
	// 'log' => [
	// 	'class'   => \CitOmni\Infrastructure\Service\Log::class,
	// 	'options' => [
	// 		'dir'    => __DIR__ . '/../var/logs',
	// 		'level'  => 'info',  // trace|debug|info|warn|error
	// 		'format' => 'json',  // json|line
	// 	],
	// ],


	/*
	 * ------------------------------------------------------------------
	 * DATABASE (example custom service)
	 * ------------------------------------------------------------------
	 * Infrastructure package may already provide db services.
	 * If you want a custom one, map it here by id.
	 */
	// 'db' => \App\Service\DbConnection::class,


	/*
	 * ------------------------------------------------------------------
	 * VIEW ENGINE (example override)
	 * ------------------------------------------------------------------
	 * citomni/http ships with a View service.
	 * Override it to add custom templating behavior or caching strategy.
	 */
	// 'view' => \App\Service\View::class,


	/*
	 * ------------------------------------------------------------------
	 * SESSION (example override)
	 * ------------------------------------------------------------------
	 * citomni/http ships with a Session service (wraps PHP sessions).
	 * Override to add extra validation, storage, etc.
	 */
	// 'session' => [
	// 	'class'   => \CitOmni\Http\Service\Session::class,
	// 	'options' => [
	// 		// 'save_path' => '/custom/path',
	// 	],
	// ],

];
