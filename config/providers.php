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
 * PROVIDER REGISTRY WHITELIST
 * ------------------------------------------------------------------
 * Ordered list of provider registry classes to activate for this app.
 *
 * A provider in CitOmni is typically a package Boot\Registry class that may
 * contribute config, services, and dispatch maps for the active runtime mode.
 *
 * What a provider may contribute:
 *   - Config overlays via:  CFG_HTTP / CFG_CLI
 *   - Service maps via:     MAP_HTTP / MAP_CLI
 *   - HTTP route maps via:  ROUTES_HTTP
 *   - CLI command maps via: COMMANDS_CLI
 *
 * Merge model:
 *   - Config merges with last wins.
 *   - HTTP routes and CLI commands merge with last wins.
 *   - Services merge with left wins via PHP array union (+).
 *   - App-owned files in /config still have final precedence over providers.
 *
 * Effective precedence:
 *   - Config:        vendor baseline -> providers -> app base -> app env overlay
 *   - HTTP routes:   vendor baseline -> providers -> app base -> app env overlay
 *   - CLI commands:  vendor baseline -> providers -> app base -> app env overlay
 *   - Services:      app services -> providers -> vendor baseline
 *
 * Requirements:
 *   - Only plain FQCN strings belong here.
 *   - No arrays, no closures, no logic, no side effects.
 *   - Provider classes must be autoloadable through Composer.
 *   - Missing or invalid provider classes should fail fast by design.
 *
 * Policy:
 *   - Keep this file intentional and ordered.
 *   - Only list provider registry classes you actually want active in this app.
 *   - Do not put app config, service definitions, routes, or commands here.
 *   - Prefer a short, explicit list over speculative enablement.
 *
 * Notes:
 *   - This file is app-owned.
 *   - Provider order matters.
 *   - App-owned /config files still have final precedence:
 *       - Config: app base + env overlay win after providers.
 *       - Services: /config/services.php wins over providers by id.
 *       - HTTP routes and CLI commands: app files win after providers.
 *   - Missing or invalid provider classes should fail fast by design.
 */
return [

	/*
	|--------------------------------------------------------------------------
	| Example: Infrastructure package
	|--------------------------------------------------------------------------
	| Common low-level services such as db, log, mail, txt, and related cfg.
	*/
	// \CitOmni\Infrastructure\Boot\Registry::class,

	/*
	|--------------------------------------------------------------------------
	| Example: Authentication package
	|--------------------------------------------------------------------------
	| Login, register, member area, and related auth wiring for apps that use it.
	*/
	// \CitOmni\Authenticate\Boot\Registry::class,

	/*
	|--------------------------------------------------------------------------
	| Example: App provider
	|--------------------------------------------------------------------------
	| App-specific config, services, and routes collected in your own registry.
	*/
	// \App\Boot\Registry::class,

];
