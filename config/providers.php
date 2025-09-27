<?php
declare(strict_types=1);
/*
 * SPDX-License-Identifier: GPL-3.0-or-later
 * Copyright (C) 2012-2025 Lars Grove Mortensen
 *
 * CitOmni HTTP Skeleton – Minimal starter for high-performance CitOmni HTTP apps.
 * Source:  https://github.com/citomni/http-skeleton
 * License: See the LICENSE file for full terms.
 */

/**
 * Whitelist of provider classes to activate (in order).
 *
 * Behavior:
 * - Providers can contribute both config (CFG_HTTP / CFG_CLI) and services (MAP_HTTP / MAP_CLI).
 * - Order matters: later providers override earlier ones on conflicts (last wins).
 * - Keep pure data: only return FQCNs, no logic.
 *
 * Typical usage:
 * - Enable citomni/auth for user accounts, roles, and login/register flows.
 * - Enable citomni/infrastructure for db, logging, mail, txt, etc.
 * - Add your own providers or third-party ones here.
 *
 * Notes:
 * - This file is app-owned. Framework updates never overwrite it.
 * - You can leave it empty if you only want vendor baseline + app config/services.
 */
return [
	/*
	 * Example: enable authentication + user accounts
	 */
	// \CitOmni\Auth\Boot\Services::class,

	/*
	 * Example: enable infrastructure services (db, log, mail, txt)
	 */
	// \CitOmni\Infrastructure\Boot\Services::class,

	/*
	 * Example: enable your own provider
	 */
	// \App\Boot\Services::class,
];
