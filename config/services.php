<?php
declare(strict_types=1);
/*
 * SPDX-License-Identifier: MIT
 * Copyright (C) 2012-present Lars Grove Mortensen
 *
 * CitOmni HTTP Skeleton - Minimal starter for high-performance CitOmni HTTP apps.
 * Source:  https://github.com/citomni/http-skeleton
 * License: See the LICENSE file for full terms.
 */

/**
 * APPLICATION SERVICE MAP
 * ------------------------------------------------------------------
 * Deterministic app-level service wiring by id.
 *
 * This file is the app-owned service map and has the highest precedence
 * when service ids collide.
 *
 * Contract:
 *   - Keys are service ids accessed as $this->app->{id}.
 *   - Values may be:
 *       1) FQCN string
 *       2) ['class' => FQCN, 'options' => [...]]
 *   - Services are resolved as singletons per request/process.
 *   - Service constructors must follow:
 *       new Service(App $app, array $options = []).
 *
 * Effective precedence (services, left wins via PHP array union +):
 *   1) App services:      /config/services.php
 *   2) Provider services: registries listed in /config/providers.php
 *   3) Vendor baseline
 *
 * Policy:
 *   - Keep this file minimal.
 *   - Only declare app-owned services or deliberate overrides.
 *   - Do not mirror vendor or provider service maps here.
 *   - Keep definitions explicit and deterministic.
 *   - Pure data only. No logic, closures, factories, or side effects.
 *
 * Notes:
 *   - This file is shared by both HTTP and CLI.
 *   - Order inside this array does not matter for resolution.
 *   - Duplicate keys in this file follow normal PHP array rules.
 *   - Invalid service classes or definitions should fail fast by design.
 */
return [

	/*
	|--------------------------------------------------------------------------
	| Example: App-owned service
	|--------------------------------------------------------------------------
	*/
	// 'myService' => \App\Service\MyService::class,

	/*
	|--------------------------------------------------------------------------
	| Example: App-owned service with options
	|--------------------------------------------------------------------------
	*/
	// 'myServiceWithOptions' => [
	// 	'class' => \App\Service\MyService::class,
	// 	'options' => [
	// 		'example' => true,
	// 	],
	// ],

];
