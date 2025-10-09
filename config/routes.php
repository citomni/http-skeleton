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
 * APPLICATION ROUTES (HTTP)
 * ------------------------------------------------------------------
 * This file returns the HTTP routing table as a plain PHP array.
 *
 * Matching order:
 *   1) Exact routes (top-level string keys like '/', '/contact')
 *   2) Regex/placeholder routes (in array order under 'regex')
 *   3) Error routes (by HTTP status: 403, 404, 405, 500, ...)
 *
 * Methods & defaults:
 *   - If 'methods' is omitted, router allows ['GET','HEAD','OPTIONS'].
 *   - If 'GET' is allowed, 'HEAD' is auto-added.
 *   - 'OPTIONS' is handled automatically with a proper Allow header.
 *   - Allowed method whitelist is: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS.
 *
 * Placeholders (regex section only):
 *   - Built-ins: {id}, {email}, {slug}, {code}
 *   - Unknown placeholders default to [^/]+
 *   - Paths are ASCII-only (router 404s non-ASCII URIs). Keep it simple.
 *
 * Merge rules (kernel, "last wins"):
 *   - Associative arrays (like this table) merge recursively per key.
 *     Defining '/' here overrides vendor '/', other vendor routes remain.
 *   - Numeric lists (e.g., 'methods') are replaced wholesale by the later source.
 *   - Setting 'regex' => [] empties only the regex section; 'routes' => [] empties all.
 *
 * Normalization:
 *   - Router trims trailing slash for matching ('/about/' -> '/about'), root stays '/'.
 *   - Avoid defining both '/foo' and '/foo/'; pick one (the first one).
 *
 * Access in code:
 *   $routes = $this->app->cfg->routes; // plain array (RAW_ARRAY_KEYS)
 *
 * Include in cfg:
 *   'routes' => require __DIR__ . '/routes.php',
 *
 * Pro tip:
 *   Keep 'params' short and explicit. They are passed to your controller action
 *   as positional arguments. Over-sharing is for social media, not routers.
 */
return [

	// -----------------------------------------------------------------
	// Exact routes (match first)
	// -----------------------------------------------------------------

	/* 
	'/' => [
		'controller'     => \App\Http\Controller\AppController::class,
		'action'         => 'index',
		'methods'        => ['GET'], // HEAD auto-added; OPTIONS handled automatically
		'template_file'  => 'public/index.html',
		'template_layer' => 'app',
		// 'params'      => [], // optional, passed positionally to the action
	],
	*/

	'/helloworld.html' => [
		'controller' => \App\Http\Controller\AppController::class,
		'action' => 'helloworld',
		'methods' => ['GET'],
		'template_file' => 'public/helloworld.html',
		'template_layer' => 'app'
	],



	// -----------------------------------------------------------------
	// Regex / placeholder routes (evaluated in array order)
	// Placeholders supported only here. Built-ins: {id}, {email}, {slug}, {code}
	// -----------------------------------------------------------------

	/*
	'regex' => [
		// '/user/{id}' => [
		// 	'controller'     => \App\Http\Controller\UserController::class,
		// 	'action'         => 'show',
		// 	'methods'        => ['GET'],
		// 	'template_file'  => 'public/user.html',
		// 	'template_layer' => 'app/http',
		// ],

		// '/email/{email}' => [
		// 	'controller' => \App\Http\Controller\EmailController::class,
		// 	'action'     => 'validate',
		// 	'methods'    => ['GET'],
		// ],

		// '/post/{slug}' => [
		// 	'controller' => \App\Http\Controller\PostController::class,
		// 	'action'     => 'view',
		// 	'methods'    => ['GET'],
		// ],
	],
	*/


	// -----------------------------------------------------------------
	// Error routes (optional). If omitted, a sane default is used.
	// Router will fall back to a plain-text error if your error route fails,
	// because infinite error loops are funny exactly once.
	// -----------------------------------------------------------------

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

	/*
	404 => [
		'controller'     => \CitOmni\Http\Controller\PublicController::class,
		'action'         => 'errorPage',
		'methods'        => ['GET'],
		'template_file'  => 'errors/error_default.html',
		'template_layer' => 'citomni/http',
		'params'         => [404],
	],
	*/

	/*
	405 => [
		'controller'     => \CitOmni\Http\Controller\PublicController::class,
		'action'         => 'errorPage',
		'methods'        => ['GET'],
		'template_file'  => 'errors/error_default.html',
		'template_layer' => 'citomni/http',
		'params'         => [405],
	],
	*/

	/*
	500 => [
		'controller'     => \CitOmni\Http\Controller\PublicController::class,
		'action'         => 'errorPage',
		'methods'        => ['GET'],
		'template_file'  => 'errors/error_default.html',
		'template_layer' => 'citomni/http',
		'params'         => [500],
	],
	*/
];
