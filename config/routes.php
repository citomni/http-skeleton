<?php
declare(strict_types=1);
/*
 * SPDX-License-Identifier: GPL-3.0-or-later
 * Copyright (C) 2012-2025 Lars Grove Mortensen
 *
 * CitOmni HTTP Skeleton - HTTP routes table.
 * Source:  https://github.com/citomni/http-skeleton
 * License: See the LICENSE file for full terms.
 */

/**
 * APPLICATION ROUTES (HTTP)
 * ------------------------------------------------------------------
 * This file returns the HTTP routing table as a plain PHP array.
 *
 * Shape:
 * - Exact routes: keyed by path (string), e.g. '/' or '/contact'
 *     '/path' => [
 *         'controller'     => FQCN,
 *         'action'         => 'method',
 *         'methods'        => ['GET','POST'],   // numeric list (replaced on merge)
 *         'template_file'  => 'public/page.html',
 *         'template_layer' => 'citomni/http',
 *         'params'         => [...],            // optional, passed to action
 *     ]
 *
 * - Regex/placeholder routes: under key 'regex' (same shape as above)
 *     Placeholders built-in: {id}, {email}, {slug}, {code}
 *     (Unknown placeholders default to [^/]+)
 *
 * - Error routes: keyed by integer HTTP status (403, 404, 405, 500, ...)
 *
 * Merge rules (kernel, last wins):
 * - Associative arrays (like this table) merge recursively per key.
 *   Defining '/' here overrides vendor '/', but other vendor routes remain.
 * - Numeric lists (e.g., 'methods') are replaced wholesale by the later source.
 * - Setting 'regex' => [] empties only the regex section; 'routes' => [] empties all.
 *
 * Access in code:
 *   $routes = $this->app->cfg->routes; // plain array (RAW_ARRAY_KEYS)
 *
 * Include in cfg:
 *   'routes' => require __DIR__ . '/routes.php',
 */
return [

	// Example exact route:
	/* 
	'/' => [
		'controller'     => \CitOmni\Http\Controller\PublicController::class,
		'action'         => 'index',
		'methods'        => ['GET'],
		'template_file'  => 'public/index.html',
		'template_layer' => 'citomni/http',
	],
	*/


	// Regex/placeholder routes:
	/* 
	'regex' => [
		// '/user/{id}' => [
		// 	'controller' => \App\Http\Controller\UserController::class,
		// 	'action'     => 'show',
		// 	'methods'    => ['GET'],
		// ],
	],
	*/


	// Error routes:
	/* 
	403 => [
		'controller'     => \CitOmni\Http\Controller\PublicController::class,
		'action'         => 'errorPage',
		'methods'        => ['GET'],
		'template_file'  => 'errors/error_default.html',
		'template_layer' => 'citomni/http',
		'params'         => [403],
	],
	*/
	// 404 => [ /* same shape; params [404] */ ],
	// 405 => [ /* same shape; params [405] */ ],
	// 500 => [ /* same shape; params [500] */ ],
];
