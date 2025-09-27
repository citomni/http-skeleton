# `config/` — Application Configuration (CitOmni)

This folder contains **all application-owned configuration** for your CitOmni app.
Nothing here is vendor-managed; it’s your **single source of truth**.

CitOmni loads configuration **deterministically** with a *last-wins* merge:

1. **Vendor baseline** (from `citomni/http` or `citomni/cli`)
2. **Providers** whitelisted in `config/providers.php`
3. **App base config** (`citomni_http_cfg.php` or `citomni_cli_cfg.php`)
4. **Environment overlay** (`citomni_*_cfg.{env}.php`) ← **last wins**

Associative arrays are merged **recursively**; numeric lists are **replaced** wholesale.
Empty values (`''`, `0`, `false`, `null`, `[]`) are **valid overrides** and still win.

> **Environment selection** is controlled by `CITOMNI_ENVIRONMENT` (`dev|stage|prod`) defined in `/public/index.php` (HTTP) or `/bin/console` (CLI).

---

## Recommended layout

```
app-root/
└─ config/
   ├─ citomni_http_cfg.php             # HTTP config (base)
   ├─ citomni_http_cfg.dev.php         # dev overlay (optional)
   ├─ citomni_http_cfg.stage.php       # stage overlay (optional)
   ├─ citomni_http_cfg.prod.php        # prod overlay (optional)
   ├─ citomni_cli_cfg.php              # CLI config (base)
   ├─ citomni_cli_cfg.dev.php          # CLI overlay(s) (optional)
   ├─ providers.php                    # list of provider FQCNs (whitelist)
   ├─ services.php                     # app service map overrides (HTTP & CLI)
   ├─ routes.php                       # HTTP routes (exact/regex/error)
   └─ README.md                        # you are here
```

---

## Accessing configuration at runtime

The merged config is wrapped in a **deep, read-only** object:

```php
$this->app->cfg->locale->timezone;   // "Europe/Copenhagen"
$this->app->cfg->http->base_url;     // "https://www.example.dk" (no trailing slash)
$this->app->cfg->mail->smtp->host;   // "send.example.com"

// Lists remain arrays:
foreach ($this->app->cfg->routes->toArray() as $path => $route) { /* ... */ }

// Raw array if needed:
$cfg = $this->app->cfg->toArray();
```

---

## `citomni_http_cfg.php` (HTTP base config)

This file **returns an array**. Example shape (trimmed to key areas you likely override):

```php
<?php
declare(strict_types=1);
defined('CITOMNI_ENVIRONMENT') or die('No direct script access.');

return [
	'identity' => [
		'app_name' => 'My CitOmni App',
		'email'    => 'support@mycitomniapp.com',
		'phone'    => '(+45) 12 34 56 78',
	],

	'db' => [
		'host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'citomni', 'charset' => 'utf8mb4',
	],

	'mail' => [
		'from' => ['email' => 'system-emails@mycitomniapp.com', 'name' => 'CitOmni.com'],
		'format' => 'html',
		'transport' => 'smtp',
		'sendmail_path' => '/usr/sbin/sendmail',
		'smtp' => [
			'host' => 'send.example.com', 'port' => 587, 'encryption' => 'tls', 'auth' => true,
			'username' => 'system-emails@mycitomniapp.com', 'password' => '*********',
			'auto_tls' => true, 'timeout' => 15, 'keepalive' => false,
			'debug' => ['level' => 0, 'output' => 'html'],
		],
	],

	'locale' => ['language' => 'da', 'timezone' => 'Europe/Copenhagen', 'charset' => 'UTF-8'],

	'http' => [
		'base_url'        => 'https://www.example.dk', // no trailing slash
		'trust_proxy'     => false,
		'trusted_proxies' => ['10.0.0.0/8','192.168.0.0/16','::1'],
	],

	'error_handler' => [
		'log_file' => CITOMNI_APP_PATH . '/var/logs/system_error_log.json',
		'recipient' => 'errors@mycitomniapp.com',
		'sender' => null, // falls back to cfg->mail->from->email
		'max_log_size' => 10485760,
		'template' => CITOMNI_APP_PATH . '/templates/errors/failsafe_error.php',
		'display_errors' => (\defined('CITOMNI_ENVIRONMENT') && \CITOMNI_ENVIRONMENT === 'dev'),
	],

	'session' => [
		'name' => 'CITSESSID',
		'save_path' => CITOMNI_APP_PATH . '/var/state/php_sessions',
		'gc_maxlifetime' => 1440,
		'use_strict_mode' => true,
		'use_only_cookies' => true,
		'lazy_write' => true,
		'sid_length' => 48,
		'sid_bits_per_character' => 6,
		'cookie_secure' => null,  // dev: null (auto); stage/prod: set true
		'cookie_httponly' => true,
		'cookie_samesite' => 'Lax',
		'cookie_path' => '/',
		'cookie_domain' => null,
		'rotate_interval' => 0,
		'fingerprint' => ['bind_user_agent' => false, 'bind_ip_octets' => 0, 'bind_ip_blocks' => 0],
	],

	'cookie' => [ 'httponly' => true, 'samesite' => 'Lax', 'path' => '/' ],

	'security' => [
		'csrf_protection' => true,
		'csrf_field_name' => 'csrf_token',
		'captcha_protection' => true,
		'honeypot_protection' => true,
		'form_action_switching' => true,
	],

	'auth' => [
		'account_activation' => true,
		'twofactor_protection' => true,
		'brute_force_protection' => true,
		// Provider-driven roles; override here if desired:
		'roles' => ['user'=>1,'creator'=>2,'moderator'=>3,'operator'=>5,'manager'=>7,'admin'=>9],
	],

	'view' => [
		'cache_enabled' => false,
		'trim_whitespace' => false,
		'remove_html_comments' => false,
		'allow_php_tags' => true,
		'marketing_scripts' => '<!-- your snippet here -->',
		'view_vars' => [],
	],

	'txt' => [ 'log_file' => 'litetxt_errors.json' ],

	'log' => [ 'default_file' => 'citomni_app_log.json' ],

	'maintenance' => [
		'flag' => [
			'path' => CITOMNI_APP_PATH . '/var/flags/maintenance.php',
			'template' => CITOMNI_APP_PATH . '/templates/public/maintenance.php',
			'allowed_ips' => ['127.0.0.1','192.168.1.100'],
			'default_retry_after' => 600,
		],
		'backup' => ['enabled'=>true, 'keep'=>3, 'dir'=>CITOMNI_APP_PATH . '/var/backups/flags/'],
		'log' => ['filename' => 'maintenance_flag.json'],
	],

	'webhooks' => [
		'enabled' => true,
		'ttl_seconds' => 300,
		'ttl_clock_skew_tolerance' => 60,
		'allowed_ips' => [],
		'nonce_dir' => CITOMNI_APP_PATH . '/var/nonces/',
	],

	'routes' => require __DIR__ . '/routes.php',
];
```

### Environment overlays for HTTP

```php
// config/citomni_http_cfg.stage.php
<?php
declare(strict_types=1);
return ['http' => ['base_url' => 'https://stage.example.com']];
```

```php
// config/citomni_http_cfg.prod.php
<?php
declare(strict_types=1);
return ['http' => ['base_url' => 'https://www.example.com']];
```

---

## `citomni_cli_cfg.php` (CLI base config)

Keep CLI specifics here. **Overlays work the same way as HTTP: `citomni_cli_cfg.{env}.php`.**

Typical keys:

```php
return [
	'identity' => ['app_name' => 'My CitOmni App (CLI)'],
	'locale'   => ['timezone' => 'Europe/Copenhagen', 'charset' => 'UTF-8'],
	'cli'      => ['ansi' => true, 'verbosity' => 1], // 0..3
	'logs'     => ['dir' => __DIR__.'/../var/logs', 'level' => 'info', 'format' => 'json'],
	'maintenance'  => [ /* same shape as HTTP */ ],
	'error_handler'=> [ /* same keys as HTTP */ ],
];
```

---

## `providers.php` (whitelist of provider packages)

Providers are vendor packages that contribute config and/or services.
This file returns a **sequential array** of **FQCNs**. Order matters (later overrides earlier on conflicts).

```php
<?php
declare(strict_types=1);

return [
	\CitOmni\Auth\Boot\Services::class,
	\CitOmni\Infrastructure\Boot\Services::class,
	// ...
];
```

**Where do vendor/provider maps come from?**

* HTTP baseline: `\CitOmni\Http\Boot\Services::MAP_HTTP`
* CLI baseline:  `\CitOmni\Cli\Boot\Services::MAP_CLI`
* Providers: `Boot\Services::MAP_HTTP` / `MAP_CLI` and `CFG_HTTP` / `CFG_CLI` (merged before your app files)

---

## `services.php` (app service map overrides)

Services are accessed as `$this->app->id` (lazy singletons per request/process).
This file **overrides** vendor/provider maps **by service id**.

**Entry forms:**

* Plain FQCN → `new FQCN($app)`
* With options → `new FQCN($app, $options)`

```php
<?php
declare(strict_types=1);

return [
	// Override router (with optional runtime options)
	'router' => [
		'class'   => \CitOmni\Http\Service\Router::class,
		'options' => ['cacheDir' => __DIR__ . '/../var/cache/routes'],
	],

	// Use vendor default for others by simply omitting them here
	// 'view'    => \CitOmni\Http\Service\View::class,
	// 'session' => \CitOmni\Http\Service\Session::class,
];
```

> **Constructor contract:** `__construct(App $app, array $options = [])`.

---

## `routes.php` (HTTP routing)

Return an array with:

* **Exact routes**: `'/path' => ['controller'=>FQCN, 'action'=>'method', 'methods'=>['GET']]`
* **Regex/placeholder routes** under key `'regex'` (same shape)
* **Error routes** keyed by HTTP status code (`403`, `404`, `405`, `500`, …)

```php
<?php
declare(strict_types=1);

use CitOmni\Http\Controller\PublicController;

return [
	'/' => [
		'controller'     => PublicController::class,
		'action'         => 'index',
		'methods'        => ['GET'],
		'template_file'  => 'public/index.html',
		'template_layer' => 'citomni/http',
	],

	'/kontakt.html' => [
		'controller'     => PublicController::class,
		'action'         => 'contact',
		'methods'        => ['GET','POST'],
		'template_file'  => 'public/contact.html',
		'template_layer' => 'citomni/http',
	],

	'/captcha' => [
		'controller' => PublicController::class,
		'action'     => 'captcha',
		'methods'    => ['GET'],
	],

	403 => [ /* same shape; params [403] */ ],
	404 => [ /* same shape; params [404] */ ],
	405 => [ /* same shape; params [405] */ ],
	500 => [ /* same shape; params [500] */ ],

	'regex' => [
		// '/user/{id}' => [ 'controller' => ..., 'action' => 'show', 'methods' => ['GET'] ],
	],
];
```

**Placeholders built-in:** `{id}`, `{email}`, `{slug}`, `{code}` (unknown placeholders default to `[^/]+`).
Enable routes in HTTP cfg with:

```php
'routes' => require __DIR__ . '/routes.php',
```

---

## Environment & base URL policy

* Set `CITOMNI_ENVIRONMENT` in `/public/index.php`: `'dev'|'stage'|'prod'`.
* **Dev:** if `http.base_url` is empty, the kernel may auto-detect `CITOMNI_PUBLIC_ROOT_URL`.
* **Stage/Prod:** **must** set an absolute `http.base_url` in the corresponding overlay file; kernel fails fast otherwise.
* Never include a trailing slash in `base_url` (e.g., use `https://www.example.com`).

---

## Configuration & Services Cache

CitOmni Kernel can use **pre-compiled** cache files for both **configuration** and the **service map** to reduce startup overhead.

### What it does

* On boot, `App` tries to **read cache files** from `var/cache/`:

  * `cfg.http.php` / `cfg.cli.php` — fully merged config as `return [ ... ];`
  * `services.http.php` / `services.cli.php` — final service map as `return [ ... ];`
* If the files **exist**, they are used directly.
  If missing/invalid, the kernel falls back to compile path:

  1. Vendor baseline → 2) Providers (`/config/providers.php`) → 3) App config (+ env overlays) — *last wins*.

> The **maintenance flag** is always read at runtime (not embedded in cache), so you can toggle without regenerating.

### File locations

```
<app-root>/var/cache/
  cfg.http.php
  cfg.cli.php
  services.http.php
  services.cli.php
```

Each file is a side-effect-free PHP script that **only** returns an array:

```php
<?php
return [ /* merged config or final service map */ ];
```

### How to warm (programmatic)

```php
$result = $this->app->warmCache(
	overwrite: true,          // overwrite existing cache files
	opcacheInvalidate: true   // best-effort opcache_invalidate() on written files
);
// $result = ['cfg' => ?string, 'services' => ?string] (null = skipped)
```

You can call this from an internal HTTP endpoint, a CLI command, or a small deploy script.

### Production recommendations

* **Write once, read often**: Warm cache on **deploy** (stage/prod).
* **OPcache**: With `opcache.validate_timestamps=0`, run `opcache_reset()` after deploy or rely on `opcache_invalidate()` during warm.
* Ensure `<app-root>/var/cache/` is writable at warm time.
* In **!dev**, set an **absolute** `http.base_url` in your env config; avoid auto-detect.

### Failure behavior

* Missing/corrupt cache → kernel **re-compiles** automatically at boot (fail-open).
* `warmCache()` **throws** on I/O errors (no catch-all); global error handler logs them.

---

## Roles (provider-driven)

`roles.php` is **deprecated**. Roles come from providers (e.g., `citomni/auth`) and can be overridden via:

```php
'auth' => [
	'roles' => ['user'=>1,'creator'=>2,'moderator'=>3,'operator'=>5,'manager'=>7,'admin'=>9],
],
```

`RoleGate` consumes `$this->app->cfg->auth->roles`.

---

## Maintenance flag

Small PHP file at `cfg->maintenance->flag->path`:

```php
<?php
return ['enabled'=>true,'allowed_ips'=>['127.0.0.1'],'retry_after'=>300];
```

When enabled, the HTTP guard returns `503` + `Retry-After`.
Backups/rotation follow `cfg->maintenance->backup`.

---

## Inheritance note (Controllers/Models/Services)

By convention:

* Controllers extend `\CitOmni\Kernel\Controller\BaseController`
* Models extend `\CitOmni\Kernel\Model\BaseModel`
* Services extend `\CitOmni\Kernel\Service\BaseService`

This gives you `$this->app` and a consistent boot contract.
It’s **fully legal** to supply your own constructor in any controller/model/service; for services, the expected signature is:

```php
__construct(\CitOmni\Kernel\App $app, array $options = [])
```

(Aligns with how `config/services.php` passes `options`.)

---

## Common pitfalls

* Trailing slash in `http.base_url` → breaks URL joining.
* Missing provider in `providers.php` → its services/config aren’t active.
* `services.php` overrides **by id**; omit keys to inherit vendor/provider defaults.
* Forgetting to warm caches for prod → unnecessary boot cost.

---

## Quick checklist

* [ ] `citomni_http_cfg.php` / `citomni_cli_cfg.php` exist and return arrays
* [ ] Overlays in `citomni_*_cfg.{env}.php` set absolute `http.base_url` for stage/prod
* [ ] `providers.php` lists the providers you want active
* [ ] `services.php` overrides only what you customize (rest inherited)
* [ ] `routes.php` is included from HTTP cfg as `'routes'`
* [ ] (Optional) Cache warmed to `var/cache/` at deploy

**Done.** Deterministic, app-owned config with clear override rules and minimal runtime overhead.
