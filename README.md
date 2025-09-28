# CitOmni HTTP Skeleton

Baseline HTTP application skeleton for CitOmni.
Lean, deterministic, and production-minded by default — no magic, no surprises.

> **Protocol scope:** HTTP (browser, API, WAN + LAN/intranet).
> If you need a mode-neutral base, use `citomni/app-skeleton`. For console apps, see `citomni/cli-skeleton`.

---

## Requirements

* PHP **8.2+**
* Composer
* Web server pointing docroot to `/public`
* Recommended PHP extensions: `json`, `mbstring`, `fileinfo`, `openssl` (common setups)

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

define('CITOMNI_ENVIRONMENT', 'dev'); // dev | stage | prod
define('CITOMNI_PUBLIC_PATH', __DIR__);
define('CITOMNI_APP_PATH', \dirname(__DIR__));

// Optional (recommended in stage/prod for maximum performance)
// define('CITOMNI_PUBLIC_ROOT_URL', 'https://www.example.com');

require CITOMNI_APP_PATH . '/vendor/autoload.php';

\CitOmni\Http\Kernel::run(__DIR__);
```

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

## Configuration model (deterministic “last-wins”)

Per mode (HTTP|CLI), runtime config is merged in this order:

1. **Vendor baseline**
   `\CitOmni\Http\Boot\Config::CFG`
2. **Providers** (classes listed in `/config/providers.php`)

   * `Boot\Services::CFG_HTTP` merged in
3. **App baseline**
   `/config/citomni_http_cfg.php`
4. **Environment overlay** (optional)
   `/config/citomni_http_cfg.{ENV}.php` (e.g., `.dev.php`, `.stage.php`, `.prod.php`)

> The merged config is exposed as a **deep, read-only wrapper**:
>
> ```php
> $this->app->cfg->http->base_url
> $this->app->cfg->routes
> ```

### Example: `config/citomni_http_cfg.php`

```php
<?php
declare(strict_types=1);

return [
	'http' => [
		// Set absolute base URL in stage/prod for max performance
		'base_url' => null,
	],
	'error_handler' => [
		// Verbose in dev; template/fallback in non-dev
		'display_errors' => (\defined('CITOMNI_ENVIRONMENT') && \CITOMNI_ENVIRONMENT === 'dev'),
	],
	'routes' => require __DIR__ . '/routes.php',
];
```

---

## Routes

Define routes in config/routes.php. Each route is an array with controller, action, and allowed HTTP methods. Regex routes go under the regex key. Error routes are keyed by status code (404, 500, etc).

```php
<?php
declare(strict_types=1);

use App\Http\Controller\HomeController;

return [
	'/' => [
		'controller'     => HomeController::class,
		'action'         => 'index',
		'methods'        => ['GET'],
		// 'template_file'  => 'public/index.html',
		// 'template_layer' => 'citomni/http',
	],

	// Example of an extra route:
	'/health' => [
		'controller' => HomeController::class,
		'action'     => 'health',
		'methods'    => ['GET'],
	],

	// Error routes (same shape):
	// 404 => [ 'controller' => ErrorController::class, 'action' => 'notFound', 'methods' => ['GET'] ],

	// Regex routes:
	// 'regex' => [
	// 	'/user/{id}' => [ 'controller' => UserController::class, 'action' => 'show', 'methods' => ['GET'] ],
	// ],
];
```

**Minimal controller**

```php
<?php
declare(strict_types=1);

namespace App\Http\Controller;

use CitOmni\Kernel\Controller\BaseController;
use CitOmni\Http\Response;

final class HomeController extends BaseController {
	public function index(): Response {
		$baseUrl = $this->app->cfg->http->base_url;
		return Response::html('<h1>Hello CitOmni</h1>');
	}

	public function health(): Response {
		return Response::json(['status' => 'ok']);
	}
}
```

> **Style:** PHP 8.2+, PSR-1/PSR-4, K&R braces, tabs for indentation.
> **Error handling:** fail fast — let the global handler log.

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

A typical provider (in a vendor package) exposes constants:

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
// Singleton per request/process
$db = $this->app->db;           // resolved by service id
$cfg = $this->app->cfg->http;   // deep, read-only wrapper
```

---

## Services & maps

* You can override or add services in `/config/services.php`.
* Definition forms:

  * `'id' => FQCN`
  * `'id' => ['class' => FQCN, 'options' => [...]]`
* **Constructor contract:** `new Service(App $app, array $options = [])`.
* Unknown id → **RuntimeException** (fail fast).

---

## Caching & performance

* CitOmni will use **precompiled caches** when present:

  * `var/cache/cfg.http.php`
  * `var/cache/services.http.php`
* Warm them atomically via your deployment process (e.g., CLI task or webhook).
* In production with `opcache.validate_timestamps=0`, invalidate per file or call `opcache_reset()` post-deploy.

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

The handler renders **once per request** (dev: verbose; non-dev: templated/fallback).

---

## Maintenance mode

* Toggle file: `var/flags/maintenance.php` (or via your deployment tooling).
* When enabled, `Maintenance->guard()` will:

  * Respond `503 Service Unavailable`
  * Send `Retry-After`
  * Render a branded maintenance page (see `/templates/public/maintenance.php.tpl`)
* Allowlists: add IPs to be whitelisted during maintenance.

**Example snippet (inside a controller/middleware):**

```php
$this->app->maintenance->guard(); // no-op if disabled
```

---

## Uploads & security

* Public uploads live in `public/uploads/` with hardened `.htaccess`.
* Consider hashed subdirectories under `uploads/u/` for large volumes.
* Avoid executing scripts from uploads — templates in `/config/tpl/` provide robust `.htaccess` presets for dev/stage/prod.

---

## Environment guidance

* **dev:** Auto-detect base URL, verbose errors, robots disallow.
* **stage:** Set absolute http.base_url in config overlay; errors hidden; robots disallow.
* **prod:** Set absolute http.base_url in config overlay; errors hidden; robots allow (as appropriate).

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

---

## Coding & Documentation Conventions

* PHP **8.2+ only**
* PSR-1 / PSR-4
* **PascalCase** classes, **camelCase** methods/vars, **UPPER_SNAKE_CASE** constants
* **K&R braces** (opening brace on same line)
* **Tabs** for indentation
* Clear PHPDoc and inline comments — **English only**
* Prefer explicit, deterministic code; avoid “magic”

Find additional conventions documented here:  
[CitOmni Coding & Documentation Conventions](https://github.com/citomni/kernel/blob/main/docs/CONVENTIONS.md)

---

## Troubleshooting

* **Blank page / autoload error**: Verify Composer autoload and PHP 8.2+.
* **Wrong base URL**: Set an absolute http.base_url in config/citomni_http_cfg.{env}.php (you can also define CITOMNI_PUBLIC_ROOT_URL early in index.php).
* **Routes not found**: Confirm `config/routes.php` returns an array and the controllers exist.
* **Uploads blocked**: Check the specialized `.htaccess` template used under `public/uploads/`.
* **Maintenance always on**: Ensure `var/flags/maintenance.php` is removed/false.

---

## License

CitOmni HTTP is released under the **GNU General Public License v3.0 or later**.
See [LICENSE](LICENSE) for details.

---

## Author

Developed by **Lars Grove Mortensen** © 2012-2025
Contributions and pull requests are welcome!

---

Built with ❤️ on the CitOmni philosophy: **low overhead**, **high performance**, and **ready for anything**.
