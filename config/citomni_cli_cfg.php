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
 * MAIN APPLICATION CONFIGURATION (CLI)
 * ------------------------------------------------------------------
 * This file defines all CLI-facing settings for your CitOmni app.
 *
 * MERGE MODEL (last wins):
 *   1) Vendor CLI baseline:   \CitOmni\Cli\Boot\Config::CFG
 *   2) Provider CFGs (whitelisted in /config/providers.php)
 *   3) App CLI base:          /config/citomni_cli_cfg.php  (this file)
 *   4) App CLI overlay:       /config/citomni_cli_cfg.{ENV}.php  ← last wins
 *
 *   {ENV} comes from CITOMNI_ENVIRONMENT ('dev' | 'stage' | 'prod').
 *
 * Typical keys:
 * - identity   -> app_name
 * - locale     -> timezone, charset
 * - cli        -> ansi output, verbosity level
 * - logs       -> dir, level, format
 * - maintenance-> same shape as HTTP (flag, backup, log)
 * - error_handler -> log_file, recipient, sender, display_errors
 *
 * Access in code:
 *   $this->app->cfg->cli->ansi;
 *   $this->app->cfg->logs->dir;
 *
 * Notes:
 * - Keep secrets here if needed (OPcache keeps PHP in memory).
 * - Overlays work the same way as HTTP: citomni_cli_cfg.{env}.php.
 */
return [

	/*
	 * ------------------------------------------------------------------
	 * APP IDENTITY
	 * ------------------------------------------------------------------
	 */
	// 'identity' => [
		// 'app_name' => 'My CitOmni App (CLI)',
	// ],


	/*
	 * ------------------------------------------------------------------
	 * LOCALE
	 * ------------------------------------------------------------------
	 */
	// 'locale' => [
		// 'timezone' => 'Europe/Copenhagen',
		// 'charset'  => 'UTF-8',
	// ],


	/*
	 * ------------------------------------------------------------------
	 * CLI SETTINGS
	 * ------------------------------------------------------------------
	 */
	// 'cli' => [
		// 'ansi'      => true, // enable ANSI colors in output
		// 'verbosity' => 1,    // 0=quiet .. 3=debug
	// ],


	/*
	 *------------------------------------------------------------------
	 * STATIC TEXT
	 *------------------------------------------------------------------
	 */
	/* 
	'txt' => [
		'log_file' => 'litetxt_errors.json',
	],
	*/


	/*
	 * ------------------------------------------------------------------
	 * LOG
	 * ------------------------------------------------------------------
	 */
	// 'log' => [
		// 'default_file' => 'citomni_app_log.json',  // Application log filename
	// ],


	/*
	 * ------------------------------------------------------------------
	 * ERROR HANDLER
	 * ------------------------------------------------------------------
	 */
	// 'error_handler' => [
		// 'log_file'       => CITOMNI_APP_PATH . '/var/logs/system_error_log.json',
		// 'recipient'      => 'errors@citomni.com',
		// 'sender'         => null, // fallback: cfg->mail->from->email
		// 'max_log_size'   => 10485760,
		// 'display_errors' => true,
	// ],


	/*
	 * ------------------------------------------------------------------
	 * MAINTENANCE
	 * ------------------------------------------------------------------
	 */
	// 'maintenance' => [
		// 'flag' => [
			// 'path'               => CITOMNI_APP_PATH . '/var/flags/maintenance.php',
			// 'allowed_ips'        => ['127.0.0.1'],
			// 'default_retry_after'=> 300,
		// ],
	// ],

];
