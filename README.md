# CitOmni HTTP Skeleton

Baseline HTTP application skeleton for CitOmni.
Lean, deterministic, and production-minded by default - no magic, no surprises.

♻️ **Green by design** - lower memory use and CPU cycles -> less server load, more requests per watt, better scalability, smaller carbon footprint.

> **Protocol scope:** HTTP (browser, API, WAN + LAN/intranet).
> If you need a mode-neutral base, use [`citomni/app-skeleton`](https://github.com/citomni/app-skeleton).
> For console apps, see [`citomni/cli-skeleton`](https://github.com/citomni/cli-skeleton).

---

### Green by design

CitOmni's "Green by design" claim is empirically validated at the framework level.

The core runtime achieves near-floor CPU and memory costs per request on commodity shared infrastructure, sustaining hundreds of RPS per worker with extremely low footprint.

See the full test report here:
[CitOmni Docs → /reports/2025-10-02-capacity-and-green-by-design.md](https://github.com/citomni/docs/blob/main/reports/2025-10-02-capacity-and-green-by-design.md)

---
## Requirements

* PHP **8.2+**
* Composer
* Web server pointing docroot to `/public`
* Recommended PHP extensions: `json`, `mbstring`, `fileinfo`, `openssl`, `gd` (for captcha/graphics)

---

## Install

```bash
composer create-project citomni/http-skeleton my-app
cd my-app
```

---

## Quick start

1. Configure environment + paths in **`public/index.php`**:

```php
<?php
declare(strict_types=1);

define('CITOMNI_START_TIME', microtime(true));
define('CITOMNI_ENVIRONMENT', 'dev'); // dev | stage | prod
define('CITOMNI_PUBLIC_PATH', __DIR__);
define('CITOMNI_APP_PATH', \dirname(__DIR__));

// Optional (recommended in stage/prod for maximum performance)
// define('CITOMNI_PUBLIC_ROOT_URL', 'https://www.example.com');

require CITOMNI_APP_PATH . '/vendor/autoload.php';

\CitOmni\Http\Kernel::run(__DIR__);
```

> In **dev**, the base URL is auto-detected.
> In **stage/prod**, you **must** either define `CITOMNI_PUBLIC_ROOT_URL` early **or** set an **absolute** `http.base_url` in config. Otherwise, boot will throw a RuntimeException.

2. Start the built-in PHP server (for local dev):

```bash
php -S 127.0.0.1:8000 -t public
```

3. Visit `http://127.0.0.1:8000`.

---

## Project layout

```
/config/                     # Config files (HTTP mode, providers, routes, services)
  citomni_http_cfg.php       # App baseline config (HTTP)
  citomni_http_cfg.{ENV}.php # Optional env overlays: dev|stage|prod
  citomni_cli_cfg.php        # Present for symmetry; ignored unless CLI is installed
  providers.php              # Provider classes listed here (see Providers)
  routes.php                 # Route map (arrays of routes: controller/action/methods)
  services.php               # Optional: local service map overrides
  tpl/                       # Template snippets (htaccess/robots, etc.)
/language/{en,da}/           # Translations (optional)
/public/                     # Web root (index.php, assets/, uploads/)
/src/                        # Your application code (PSR-4: App\)
/templates/                  # View templates (public errors/maintenance, etc.)
/tests/                      # PHPUnit tests (optional)
/var/                        # cache, logs, flags, nonces, state (not web-accessible)
```

**Security notes**

* `/public/uploads/` is segregated and hardened with `.htaccess` templates.
* Internal folders (`/config`, `/var`, `/src`, `/templates`) include deny rules where relevant.

---

## Configuration model (deterministic "last-wins")

Per mode (HTTP|CLI), runtime config is merged in this order:

1. **Vendor baseline**
   `\CitOmni\Http\Boot\Config::CFG`
2. **Providers** (classes listed in `/config/providers.php`)

   * `Boot\Services::CFG_HTTP` merged in
3. **App baseline**
   `/config/citomni_http_cfg.php`
4. **Environment overlay** (optional)
   `/config/citomni_http_cfg.{ENV}.php`

> The merged config is exposed as a **deep, read-only wrapper**:
>
> ```php
> $this->app->cfg->http->base_url
> $this->app->cfg->routes // remains a raw array
> ```

### Example: `config/citomni_http_cfg.php`

```php
<?php
declare(strict_types=1);

return [
	'http' => [
		// In stage/prod set an absolute base URL (or define CITOMNI_PUBLIC_ROOT_URL early)
		'base_url' => null,
	],
	'error_handler' => [
		'display_errors' => (\defined('CITOMNI_ENVIRONMENT') && \CITOMNI_ENVIRONMENT === 'dev'),
	],
	// Routes remain a raw array for performance
	'routes' => require __DIR__ . '/routes.php',
];
```

---

## Routes

Define routes in `config/routes.php`. Each route is an array with `controller`, `action`, and allowed HTTP methods. Regex routes are placed under the `regex` key.

```php
<?php
declare(strict_types=1);

return [
	// 1) Page rendered via LiteView template
	'/' => [
		'controller'     => \App\Http\Controller\HomeController::class,
		'action'         => 'index',
		'methods'        => ['GET'],
		'template_file'  => 'public/index.html',
		'template_layer' => 'citomni/http',
	],

	// 2) Raw HTML via the Response service
	'/hello' => [
		'controller' => \App\Http\Controller\HomeController::class,
		'action'     => 'hello',
		'methods'    => ['GET'],
	],

	// 3) JSON endpoint via the Response service
	'/api/health' => [
		'controller' => \App\Http\Controller\HomeController::class,
		'action'     => 'health',
		'methods'    => ['GET'],
	],

	// (Optional) Error routes - override the default errorPage()
	// 404 => [
	// 	'controller' => \App\Http\Controller\ErrorController::class,
	// 	'action'     => 'notFound',
	// 	'methods'    => ['GET'],
	// ],

	// Regex routes
	// 'regex' => [
	// 	'/user/{id}' => [
	// 		'controller' => \App\Http\Controller\UserController::class,
	// 		'action'     => 'show',
	// 		'methods'    => ['GET'],
	// 	],
	// ],
];
```

> **Placeholders available in regex routes:**
> `{id}` -> `[0-9]+`
> `{email}` -> `[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+`
> `{slug}` -> `[a-zA-Z0-9-_]+`
> `{code}` -> `[a-zA-Z0-9]+`

**Error routes are optional**: if a status code (e.g., `404`, `405`, `500`) is not defined in `routes`, the Router falls back to a **shared default**: `CitOmni\Http\Controller\PublicController::errorPage($status)`.

**Minimal controller matching the above:**

```php
<?php
declare(strict_types=1);

namespace App\Http\Controller;

use CitOmni\Kernel\Controller\BaseController;

final class HomeController extends BaseController {
	public function index(): void {
		// LiteView integration (template_file/layer comes from routeConfig)
		$this->app->view->render(
			$this->routeConfig['template_file'],
			$this->routeConfig['template_layer'],
			[
				'canonical' => \CITOMNI_PUBLIC_ROOT_URL,
				'noindex'   => 0,
			]
		);
	}

	public function hello(): void {
		// Raw HTML via Response service
		$this->app->response->html('<h1>Hello CitOmni</h1>');
	}

	public function health(): void {
		// JSON via Response service
		$this->app->response->json(['status' => 'ok']);
	}
}
```

> **Note:** Router automatically adds `HEAD` and handles `OPTIONS` based on your `methods`.
> **Error fallback:** If no specific error route is defined, the shared default `PublicController::errorPage($status)` is used.
> **Style:** PHP 8.2+, PSR-1/PSR-4, K&R braces, tabs for indentation.
> **Error handling:** fail fast - let the global handler log.

---

## Providers

Providers contribute **service maps** and **config** in a deterministic way.
List provider classes in `/config/providers.php`:

```php
<?php
declare(strict_types=1);

return [
	// \CitOmni\Infrastructure\Boot\Services::class,
	// \CitOmni\Auth\Boot\Services::class,
];
```

A typical provider exposes constants:

```php
namespace Vendor\Package\Boot;

final class Services {
	public const MAP_HTTP = [
		// 'db' => \App\Service\DbConnection::class,
	];
	public const CFG_HTTP = [
		// 'key' => ['options' => true],
	];
}
```

**Service access** at runtime:

```php
$db = $this->app->db;           // resolved by service id
$cfg = $this->app->cfg->http;   // deep, read-only wrapper
```

---

## Services & maps

* You can override or add services in `/config/services.php`.
* Definition forms:

  * `'id' => FQCN`
  * `'id' => ['class' => FQCN, 'options' => [...]]`
* **Constructor contract:** `new Service(App $app, array $options = [])`
* Unknown id -> **RuntimeException**

---

## Caching & performance

* CitOmni will use **precompiled caches** when present:

  * `var/cache/cfg.http.php`
  * `var/cache/services.http.php`
* Warm them atomically via your deployment process (e.g., CLI task or webhook).
* In production with `opcache.validate_timestamps=0`, invalidate per file or call `opcache_reset()` post-deploy.

At the end of each request in **dev**, `Kernel::run()` appends an HTML comment with execution time, memory usage, and file count - useful for quick profiling.

---

## Error handling

Configure the error handler in `citomni_http_cfg.php` (and overlays).
Typical defaults:

```php
'error_handler' => [
	'log_file'       => CITOMNI_APP_PATH . '/var/logs/system_error_log.json',
	'recipient'      => 'errors@example.com',
	// 'sender'       => '', // leave empty to use cfg->mail->from->email
	'max_log_size'   => 10_485_760, // 10 MB
	'template'       => CITOMNI_APP_PATH . '/templates/errors/failsafe_error.php',
	'display_errors' => (\defined('CITOMNI_ENVIRONMENT') && \CITOMNI_ENVIRONMENT === 'dev'),
],
```

The handler renders **at most once per request** (dev: verbose; non-dev: templated/fallback).

---

## Maintenance mode

* Toggle file: `var/flags/maintenance.php` (or via your deployment tooling).
* When enabled, `Maintenance->guard()` will:

  * Respond `503 Service Unavailable`
  * Send `Retry-After`
  * Render a branded maintenance page (see `/templates/public/maintenance.php.tpl`)
* Allow-lists: add IPs to be whitelisted during maintenance.

```php
$this->app->maintenance->guard(); // no-op if disabled
```

---

## Uploads & security

* Public uploads live in `public/uploads/` with hardened `.htaccess`.
* Consider hashed subdirectories under `uploads/u/` for large volumes.
* Avoid executing scripts from uploads - templates in `/config/tpl/` provide robust `.htaccess` presets.

---

## Environment guidance

* **dev:** Auto-detect base URL, verbose errors, robots disallow.
* **stage:** Must set absolute http.base_url in overlay or define CITOMNI_PUBLIC_ROOT_URL; errors hidden; robots disallow.
* **prod:** Must set absolute http.base_url in overlay or define CITOMNI_PUBLIC_ROOT_URL; errors hidden; robots allow (as appropriate).

Set overlays in:

```
config/citomni_http_cfg.dev.php
config/citomni_http_cfg.stage.php
config/citomni_http_cfg.prod.php
```

---

## Testing

This skeleton is ready for PHPUnit if you add it:

```bash
composer require --dev phpunit/phpunit:^10.5
```

Then place tests under `/tests` with PSR-4 namespace `App\Tests\`.

### CitOmni Testing (optional)

For integrated, framework-native test execution you can install [`citomni/testing`](https://github.com/citomni/testing):

```bash
composer require --dev citomni/testing
```

Benefits:

* Runs **inside the real CitOmni boot cycle** - no discrepancies between test and production conditions.
* Honors the same **config merge model** (baseline -> providers -> app -> env overlay).
* Provides **deterministic, reproducible** test runs with zero overhead in production.
* Exposes a minimal **DEV-only UI** for correctness checks, regression runs, and integration studies.

---

## Coding & Documentation Conventions

* PHP **8.2+ only**
* PSR-1 / PSR-4
* **PascalCase** classes, **camelCase** methods/vars, **UPPER_SNAKE_CASE** constants
* **K&R braces** (opening brace on same line)
* **Tabs** for indentation
* Clear PHPDoc and inline comments - **English only**
* Prefer explicit, deterministic code; avoid "magic"

See:
[CitOmni Coding & Documentation Conventions](https://github.com/citomni/docs/blob/main/contribute/CONVENTIONS.md)

---

## Troubleshooting

* **Blank page / autoload error**: Verify Composer autoload and PHP 8.2+.
* **Wrong base URL**: In stage/prod you must set absolute `http.base_url` in overlay **or** define `CITOMNI_PUBLIC_ROOT_URL`.
* **405 Method Not Allowed**: Check the `methods` list in your route; `HEAD` and `OPTIONS` are auto-added.
* **Routes not found**: Confirm `config/routes.php` returns an array and controllers exist.
* **Uploads blocked**: Check the specialized `.htaccess` template under `public/uploads/`.
* **Maintenance always on**: Ensure `var/flags/maintenance.php` is removed/false.
* **Captcha blank**: Ensure the `gd` extension (with FreeType) is installed.

---

## License

**CitOmni HTTP Skeleton** is open-source under the **MIT License**.  
See [LICENSE](LICENSE) for details.

**Trademark notice:** "CitOmni" and the CitOmni logo are trademarks of **Lars Grove Mortensen**.  
You may not use the CitOmni name or logo to imply endorsement or affiliation without prior written permission.

---

## Trademarks

"CitOmni" and the CitOmni logo are trademarks of **Lars Grove Mortensen**.  
You may make factual references to "CitOmni", but do not modify the marks, create confusingly similar logos,  
or imply sponsorship, endorsement, or affiliation without prior written permission.  
Do not register or use "citomni" (or confusingly similar terms) in company names, domains, social handles, or top-level vendor/package names.  
For details, see the project's [NOTICE](NOTICE).

---

## Author

Developed by **Lars Grove Mortensen** © 2012-present
Contributions and pull requests are welcome!

---

Built with ❤️ on the CitOmni philosophy: **low overhead**, **high performance**, and **ready for anything**.
