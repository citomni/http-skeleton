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
 * PROVIDER WHITELIST (ORDERED)
 * ------------------------------------------------------------------
 * List provider classes (FQCN) to activate - in the order they should apply.
 *
 * A "provider" in CitOmni is a Boot\Registry class in a package.
 *
 * What a provider (its Registry class) can contribute:
 *   - CONFIG defaults via constants:      CFG_HTTP / CFG_CLI
 *   - SERVICE wiring via constants:       MAP_HTTP / MAP_CLI
 *   - HTTP routes via constants:          ROUTES_HTTP
 *     (CLI command maps may come later; for now we don't expose ROUTES_CLI /
 *      COMMANDS_CLI etc.)
 *
 * Ordering rules:
 *   - Later providers override earlier ones on conflicts (deterministic: last wins).
 *   - Finally, the app's own /config/*.php still merge/override after all providers.
 *
 * Requirements:
 *   - Only plain FQCN strings here. No logic, no arrays, no closures.
 *   - Classes must be autoloadable via Composer (PSR-4). If class_exists(...) fails,
 *     the kernel will fail fast (by design).
 *
 * Typical picks:
 *   - citomni/infrastructure -> db, log, mail, txt, etc.
 *   - citomni/auth           -> users, roles, login/register, member area.
 *   - your own app provider  -> site-specific wiring and overrides.
 *
 * Notes:
 *   - This file is app-owned; framework updates will not overwrite it.
 *   - You can leave it empty to run on vendor baseline + your app config/services.
 *   - Yes, order matters. The last provider wins, not the loudest one.
 */
return [

	/*
	 * Example: Enable infrastructure services (db, log, mail, txt),
	 *          plus its default cfg and routes.
	 */
	// \CitOmni\Infrastructure\Boot\Registry::class,

	/*
	 * Example: Enable authentication / user accounts (login, register,
	 *          profile, member area, RoleGate, etc.)
	 */
	// \CitOmni\Auth\Boot\Registry::class,

	/*
	 * Example: Your own app-level provider (remember PSR-4 and autoload).
	 */
	// \App\Boot\Registry::class,
];
