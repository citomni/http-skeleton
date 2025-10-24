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
 * What providers do:
 *   - Contribute CONFIG via constants:   CFG_HTTP / CFG_CLI
 *   - Contribute SERVICES via constants: MAP_HTTP / MAP_CLI
 *
 * Ordering:
 *   - Later providers override earlier ones on conflicts (deterministic: last wins).
 *   - App-level config/services still merge/override after providers.
 *
 * Requirements:
 *   - Only plain FQCN strings here. No logic, no arrays, no closures.
 *   - Classes must be autoloadable via Composer (PSR-4). If class_exists(...) fails,
 *     the kernel will fail fast (by design).
 *
 * Typical picks:
 *   - citomni/auth           -> users, roles, login/register, member area.
 *   - citomni/infrastructure -> db, log, mail, txt, and other shared services.
 *   - your own providers     -> wire your app's services and defaults.
 *
 * Notes:
 *   - This file is app-owned; so framework updates will never overwrite it.
 *   - You can leave it empty to run on vendor baseline + your app config/services.
 *   - Yes, order matters; the last provider wins, not the loudest one.
 */
return [

	/*
	 * Example: Enable infrastructure services (db, log, mail, txt)
	 */
	// \CitOmni\Infrastructure\Boot\Services::class,

	/*
	 * Example: Enable authentication + user accounts
	 */
	// \CitOmni\Auth\Boot\Services::class,

	/*
	 * Example: Your own provider (remember PSR-4 and autoload)
	 */
	// \App\Boot\Services::class,
];
