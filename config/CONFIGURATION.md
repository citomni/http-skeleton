# Configuration Directory

## Purpose

The `/config` directory is the canonical application-owned control surface for CitOmni bootstrap behavior. It is intentionally small, explicit, and deterministic. Its role is not to mirror framework baselines, but to supply the application's own providers, service overrides, base configuration, and environment-specific overlays.

In other words: the directory should contain only the files that the application meaningfully owns.

## Design Principles

CitOmni treats configuration as a layered composition rather than a monolithic document. The effective runtime state is assembled from vendor baselines, optional provider registries, app-owned files, and, where relevant, environment overlays. For configuration arrays, the merge rule is recursive associative merge with last-wins semantics. For service maps, the rule is PHP array union with left-wins semantics, meaning the application has final authority over service definitions. fileciteturn4file9

This design has three practical consequences:

1. Application config files should stay minimal.
2. Overlays should express deliberate deviation, not duplication.
3. A missing file is often acceptable; an unnecessary file is usually noise.

## Recommended Directory Shape

A typical application using both HTTP and CLI will have a configuration directory shaped approximately as follows:

```text
/config
	providers.php
	services.php

	citomni_http_cfg.php
	citomni_http_cfg.dev.php
	citomni_http_cfg.stage.php
	citomni_http_cfg.prod.php
	citomni_http_routes.php
	citomni_http_routes.dev.php
	citomni_http_routes.stage.php
	citomni_http_routes.prod.php

	citomni_cli_cfg.php
	citomni_cli_cfg.dev.php
	citomni_cli_cfg.stage.php
	citomni_cli_cfg.prod.php
	citomni_cli_commands.php
	citomni_cli_commands.dev.php
	citomni_cli_commands.stage.php
	citomni_cli_commands.prod.php
```

Not every application needs every file. The correct set depends on whether the application uses HTTP, CLI, or both.

## File Taxonomy

### `providers.php`

This file declares which provider registries participate in application bootstrap. The kernel loads it from `/config/providers.php`; if the file is absent, the provider list defaults to an empty array. From a purely technical perspective the file is optional, but from an architectural perspective it is foundational because it defines which package registries may contribute configuration, routes, commands, and services. fileciteturn4file12

**Status:** Strongly recommended in every app.

**Role:**
- Whitelists package registries.
- Establishes the provider layer in the merge pipeline.
- Keeps package participation explicit rather than magical.

### `services.php`

This file is the application's service map override layer. Unlike cfg and dispatch files, it is not mode-specific. The same `services.php` is used for both HTTP and CLI boot. The kernel merges provider and vendor service maps first, then applies the app's `services.php` with PHP array union so that app-defined service IDs win over all lower layers. fileciteturn4file12

**Status:** Optional, but highly recommended whenever the app defines or overrides services.

**Role:**
- Add application-specific services.
- Override provider or vendor service definitions.
- Keep service ownership centralized.

**Important subtlety:** Because `services.php` is shared across modes, any definition placed here affects both HTTP and CLI unless the service itself is harmless outside one mode.

## HTTP Configuration Files

### `citomni_http_cfg.php`

This is the app-owned base configuration file for HTTP mode. It is loaded after the vendor HTTP baseline and provider `CFG_HTTP` arrays, but before any environment-specific HTTP overlay. The file itself states the intended merge model explicitly: vendor baseline, provider cfg, app HTTP base, then app HTTP overlay. It also states the policy that the file should remain minimal and should declare only keys intentionally overridden from lower layers. fileciteturn4file2turn4file17

**Status:** Recommended for every HTTP app.

**Role:**
- Define app-owned HTTP defaults.
- Override selected vendor or provider cfg keys.
- Serve as the stable base shared by all HTTP environments.

**What belongs here:**
- Durable app identity values.
- Locale defaults.
- Stable HTTP settings.
- Shared error-handler policy.
- Any other HTTP-facing configuration that should apply across all environments unless specifically overridden.

**What does not belong here:**
- Full copies of framework baseline config.
- Temporary environment tweaks.
- Values that differ only in dev, stage, or prod.

### `citomni_http_cfg.<env>.php`

These are HTTP environment overlays, typically `dev`, `stage`, and `prod`. They load only when `CITOMNI_ENVIRONMENT` matches the suffix, and they win last in the cfg merge order. The current HTTP skeleton files make their intent very clear: dev is diagnostic and permissive within bounds, while stage and prod should be explicit, deterministic, and closer to operational reality. fileciteturn4file14turn4file1turn4file0

**Status:** Optional, but recommended whenever environment differences exist.

**Role:**
- Express environment-specific deviations from the HTTP base file.
- Keep production posture explicit.
- Prevent cross-environment leakage of debug settings.

#### Dev overlay

The dev overlay is intended to improve local diagnostics. In the current skeleton it enables bounded non-fatal error rendering, disables template caching, leaves cookie security more flexible for plain HTTP localhost development, and keeps webhooks off by default. fileciteturn4file15turn4file16

Typical use:
- developer-facing error detail
- cache disabled for rapid feedback
- relaxed local-cookie posture where HTTPS is absent

#### Stage overlay

The stage overlay should approximate production behavior without exposing developer detail to clients. In the current skeleton it sets an explicit base URL, aligns cookie and session policy with production, keeps template caching on, and logs verbosely while rendering minimal client detail. fileciteturn4file1turn4file11

Typical use:
- explicit routing
- production-like cookie/session behavior
- QA-visible runtime without client-facing traces

#### Prod overlay

The prod overlay is the strictest environment-specific layer. In the current skeleton it requires an explicit absolute base URL, enables secure cookie/session posture, and keeps template caching enabled. It also emphasizes that stage and prod must never rely on base URL auto-detection. fileciteturn4file0turn4file7turn4file18

Typical use:
- explicit absolute base URL
- HTTPS-only cookie/session policy
- cache enabled
- no developer niceties

### `citomni_http_routes.php`

This is the application-owned HTTP route map. The kernel builds HTTP dispatch maps by merging the vendor baseline routes, provider `ROUTES_HTTP` arrays, the app's `citomni_http_routes.php`, and then any environment-specific route overlay. fileciteturn4file5

**Status:** Recommended in HTTP apps that define app-specific routes.

**Role:**
- Declare the app's own HTTP routes.
- Override or refine provider routes where appropriate.
- Keep routing decisions out of cfg.

### `citomni_http_routes.<env>.php`

These are optional route overlays for environment-specific HTTP routing. The kernel supports them, but they should usually be used sparingly. Environment-specific routing is sometimes necessary, but excessive use tends to obscure the application's real route contract. fileciteturn4file12

**Status:** Optional.

**Use with restraint.**

## CLI Configuration Files

### `citomni_cli_cfg.php`

This is the CLI equivalent of `citomni_http_cfg.php`. The kernel loads it in CLI mode after the vendor CLI baseline and provider `CFG_CLI` arrays, but before any environment-specific CLI overlay. fileciteturn4file9

**Status:** Recommended in applications that use CLI.

**Role:**
- Define app-owned CLI defaults.
- Override selected vendor or provider CLI config.

### `citomni_cli_cfg.<env>.php`

These are the CLI environment overlays. Their semantics mirror the HTTP cfg overlays: they load conditionally by environment name and win last in the CLI cfg merge order. fileciteturn4file5

**Status:** Optional.

### `citomni_cli_commands.php`

This is the app-owned CLI command map. In CLI mode, the kernel constructs the final command registry by merging the vendor CLI baseline, provider `COMMANDS_CLI` arrays, the app's `citomni_cli_commands.php`, and then any environment-specific command overlay. fileciteturn4file5

**Status:** Recommended in applications that expose app-owned commands.

**Role:**
- Register application commands.
- Override or augment provider command maps.

### `citomni_cli_commands.<env>.php`

These are optional command overlays for environment-specific CLI behavior. As with route overlays, they are supported but should remain exceptional rather than routine. fileciteturn4file12

## Which Files Are Actually Necessary?

The answer depends on whether one is asking a technical or architectural question.

### Minimal technical minimum

A CitOmni app can technically boot with very few files because missing files often fall back to empty arrays. In that narrow sense, even `providers.php` is not strictly mandatory at runtime if no providers are needed. fileciteturn4file12

### Sensible architectural minimum

In practice, the following should be treated as the baseline expectation:

- `providers.php`
- `services.php` when the app owns or overrides services
- `citomni_http_cfg.php` in HTTP apps
- `citomni_http_routes.php` in HTTP apps with app-owned routes
- `citomni_cli_cfg.php` in CLI apps
- `citomni_cli_commands.php` in CLI apps with app-owned commands

Environment overlays should exist only when there is an actual environment-specific deviation to express.

## Operational Guidance

### 1. Keep base files stable, overlays narrow

The base file should encode durable application intent. Overlays should encode controlled environmental deviation.

### 2. Do not mirror vendor baseline config

The HTTP base cfg file explicitly warns against reproducing baseline settings wholesale. Duplication creates review noise, obscures ownership, and makes future vendor changes harder to adopt cleanly. fileciteturn4file2

### 3. Prefer explicit production values

The current HTTP skeleton is unequivocal on base URL policy: development may auto-detect when appropriate, but stage and prod must use explicit absolute base URLs or define `CITOMNI_PUBLIC_ROOT_URL` at bootstrap. fileciteturn4file17turn4file7

### 4. Treat `/appinfo.html` as an inspection tool, not a config source of truth

The environment overlay comments repeatedly describe `/appinfo.html` as the practical way to inspect the merged runtime configuration and to copy exact array structure for overrides in dev and stage. That is operationally useful, but the true source of authority remains the bootstrap merge pipeline itself. fileciteturn4file0turn4file1turn4file14

### 5. Remember that `services.php` is cross-mode

This is the most easily overlooked detail in the directory. Cfg and dispatch files are mode-specific. `services.php` is not. A service override there is global to the app's HTTP and CLI runtime unless the service definition itself is mode-safe. fileciteturn4file12

## Recommended Defaults by App Type

### HTTP-only application

```text
/config
	providers.php
	services.php
	citomni_http_cfg.php
	citomni_http_cfg.dev.php
	citomni_http_cfg.stage.php
	citomni_http_cfg.prod.php
	citomni_http_routes.php
```

### CLI-only application

```text
/config
	providers.php
	services.php
	citomni_cli_cfg.php
	citomni_cli_cfg.dev.php
	citomni_cli_cfg.stage.php
	citomni_cli_cfg.prod.php
	citomni_cli_commands.php
```

### Application using both HTTP and CLI

```text
/config
	providers.php
	services.php

	citomni_http_cfg.php
	citomni_http_cfg.dev.php
	citomni_http_cfg.stage.php
	citomni_http_cfg.prod.php
	citomni_http_routes.php

	citomni_cli_cfg.php
	citomni_cli_cfg.dev.php
	citomni_cli_cfg.stage.php
	citomni_cli_cfg.prod.php
	citomni_cli_commands.php
```

## Final Rule of Thumb

A well-kept CitOmni `/config` directory should read less like a dump of available knobs and more like a disciplined statement of application ownership. Each file should answer one question only:

- which providers participate,
- which services the app owns,
- which HTTP and CLI defaults the app declares,
- and which deviations are genuinely environment-specific.

Anything beyond that is usually duplication masquerading as explicitness.
