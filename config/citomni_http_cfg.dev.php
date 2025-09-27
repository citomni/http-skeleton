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
 * HTTP CONFIG OVERLAY (dev)
 * ------------------------------------------------------------------
 * This file overrides /config/citomni_http_cfg.php for the "dev" environment.
 *
 * Merge model: last wins -> applied after vendor, providers, and base cfg.
 *
 * Typical usage:
 *   - Set an absolute http.base_url (required in stage/prod).
 *   - Override any environment-specific settings (db, mail, logging).
 *
 * Notes:
 *   - CITOMNI_ENVIRONMENT must equal "dev" for this file to load.
 *   - Keep base_url absolute, no trailing slash (e.g. "http://localhost/mycitomniapp").
 *   - Do not duplicate full documentation; see citomni_http_cfg.php for rules.
 * ------------------------------------------------------------------
 */
return [

	'http' => [
		// 'base_url'    => 'http://localhost/mycitomniapp', // Never include a trailing slash!
	],




];
