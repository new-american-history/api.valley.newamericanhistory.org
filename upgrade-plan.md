# Laravel 8 → 12 Upgrade Plan: Valley of the Shadow API

## Overview

**Target**: Upgrade Laravel API from version 8.12 to 12.x (latest stable)
**Location**: `/api.valley.newamericanhistory.org`
**Strategy**: Incremental upgrade (8→9→10→11→12) with comprehensive modernization
**Timeline**: 4-5 days development + 1-2 days testing/deployment

**Key Note**: Laravel 12 (released September 2024) is the latest stable version. Laravel 13 is in development.

## Critical Architecture Context

The API uses a custom three-layer architecture:
- **App/** - HTTP layer (controllers, resources, middleware)
- **Domain/** - Business logic (models, enums, traits)
- **Support/** - Infrastructure (query builders, filters)

**Key Features**:
- Advanced filtering via custom `IndexQueryBuilder` extending Spatie Query Builder
- TEI XML processing for historical documents via `HasTeiTags` trait
- Spatie Enum package for type-safe enums (7 enum types)
- 50+ API endpoints serving Nuxt.js frontend
- 53 database migrations, 30+ models, 23 import commands

## Critical Files to Modify

### Phase-Specific Files

**Laravel 9 Upgrade**:
- `composer.json` - Update framework and remove deprecated packages
- `src/Support/Middleware/TrustProxies.php` - Rewrite to extend Laravel native (remove Fideloper)
- `src/App/HttpKernel.php` - Replace Fruitcake CORS with Laravel native
- `config/cors.php` - Create/update for native CORS configuration

**Laravel 10 Upgrade**:
- `src/App/Providers/RouteServiceProvider.php` - Remove `$namespace` property
- `composer.json` - Update to Laravel 10 dependencies

**Laravel 11 Upgrade**:
- All models in `src/Domain/*/Models/*.php` - Update casts and accessors/mutators
- All enum files in `src/Domain/Shared/Enums/*.php` - Migrate to native PHP enums
- `src/Domain/Shared/Traits/HasCountyEnum.php` - Update enum handling
- `composer.json` - Update to Laravel 11, remove Spatie Enum package

**Laravel 12 Upgrade**:
- `composer.json` - Update to Laravel 12 dependencies
- Verify all features remain compatible with Laravel 12

**Testing Throughout**:
- `src/Support/Queries/IndexQueryBuilder.php` - Verify compatibility with Spatie v5.x
- `src/Support/Filters/*.php` - Test custom filter implementations
- `src/Domain/Shared/Traits/HasTeiTags.php` - Verify TEI XML processing

---

## Phase 1: Pre-Upgrade (1-2 hours)

### 1.1 Backup & Documentation

```bash
# Create upgrade branch
git checkout -b upgrade/laravel-12

# Backup database
mysqldump -u root -p valley_db > backup_pre_upgrade_$(date +%Y%m%d).sql

# Document current state
php artisan --version > version_before.txt
composer show > packages_before.txt

# Create rollback tag
git tag pre-upgrade-laravel-12
```

### 1.2 Audit Current State

```bash
# Check dependency versions
composer outdated

# Test current API functionality (establish baseline)
curl -I https://api.valley.newamericanhistory.org/api/letters
curl "https://api.valley.newamericanhistory.org/api/letters?filter[county]=augusta"
curl "https://api.valley.newamericanhistory.org/api/letters?filter[date]=1863"
```

### 1.3 Create Test Script

Create `test-api-endpoints.sh` for manual testing:

```bash
#!/bin/bash
BASE_URL="http://localhost:8000/api"

echo "Testing API endpoints..."

# Test 1: Basic index
curl -s "$BASE_URL/letters" | jq -e '.data' > /dev/null && echo "✓ Letters index" || echo "✗ Letters index"

# Test 2: Exact filter
curl -s "$BASE_URL/letters?filter[county]=augusta" | jq -e '.data[0].county' > /dev/null && echo "✓ Exact filter" || echo "✗ Exact filter"

# Test 3: Date filter (year only)
curl -s "$BASE_URL/letters?filter[date]=1863" | jq -e '.data' > /dev/null && echo "✓ Date filter (year)" || echo "✗ Date filter (year)"

# Test 4: Date filter (year-month)
curl -s "$BASE_URL/letters?filter[date]=1863-07" | jq -e '.data' > /dev/null && echo "✓ Date filter (year-month)" || echo "✗ Date filter (year-month)"

# Test 5: Numeric comparison
curl -s "$BASE_URL/population-census?filter[age:gt]=18" | jq -e '.data' > /dev/null && echo "✓ Numeric filter" || echo "✗ Numeric filter"

# Test 6: Text search
curl -s "$BASE_URL/letters?filter[q]=home" | jq -e '.data' > /dev/null && echo "✓ Text search" || echo "✗ Text search"

# Test 7: Sorting
curl -s "$BASE_URL/letters?sort=-date" | jq -e '.data[0].date' > /dev/null && echo "✓ Sorting" || echo "✗ Sorting"

# Test 8: Show endpoint
curl -s "$BASE_URL/letters/aug-let-001" | jq -e '.data.valley_id' > /dev/null && echo "✓ Show endpoint" || echo "✗ Show endpoint"

# Test 9: TEI XML processing
curl -s "$BASE_URL/letters/aug-let-001" | jq -e '.data.body' > /dev/null && echo "✓ TEI processing" || echo "✗ TEI processing"

# Test 10: CORS headers
curl -I "$BASE_URL/letters" | grep -q "access-control-allow-origin" && echo "✓ CORS headers" || echo "✗ CORS headers"

echo "Test suite complete!"
```

Make executable: `chmod +x test-api-endpoints.sh`

---

## Phase 2: Laravel 8 → 9 (3-4 hours)

### 2.1 Update Dependencies

**File**: `composer.json`

Update the following sections:

```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^9.0",
    "laravel/tinker": "^2.7",
    "spatie/laravel-query-builder": "^5.0",
    "spatie/laravel-enum": "^3.0",
    "doctrine/dbal": "^3.3",
    "guzzlehttp/guzzle": "^7.0.1"
  },
  "require-dev": {
    "fakerphp/faker": "^1.9.1",
    "laravel/sail": "^1.0.1",
    "mockery/mockery": "^1.4.4",
    "nunomaduro/collision": "^6.1",
    "phpunit/phpunit": "^9.5.10",
    "spatie/laravel-ignition": "^1.0"
  }
}
```

**Removed packages** (replaced by Laravel native):
- `fruitcake/laravel-cors`
- `fideloper/proxy`
- `facade/ignition` (replaced by `spatie/laravel-ignition`)

Run: `composer update`

### 2.2 Replace TrustProxies Middleware

**File**: `src/Support/Middleware/TrustProxies.php`

Replace entire contents:

```php
<?php

namespace Support\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
```

### 2.3 Update HttpKernel for Native CORS

**File**: `src/App/HttpKernel.php`

In the `$middleware` array, replace:
```php
// OLD:
\Fruitcake\Cors\HandleCors::class,

// NEW:
\Illuminate\Http\Middleware\HandleCors::class,
```

### 2.4 Configure Native CORS

**File**: `config/cors.php` (create if doesn't exist)

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

**Note**: Adjust `allowed_origins` to specific domains for production security if needed.

### 2.5 Clear Caches & Test

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerate config cache
php artisan config:cache

# Run test script
./test-api-endpoints.sh

# Manual verification
curl -I http://localhost:8000/api/letters | grep -i "access-control"
php artisan route:list | head -20
```

**Success Criteria**:
- All test script checks pass (✓)
- CORS headers present in API responses
- No errors in `storage/logs/laravel.log`
- Custom filters work (date, exact, fuzzy, numeric, text search)

---

## Phase 3: Laravel 9 → 10 (2-3 hours)

### 3.1 Update Dependencies

**File**: `composer.json`

```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^10.0",
    "laravel/tinker": "^2.8",
    "spatie/laravel-query-builder": "^5.0",
    "spatie/laravel-enum": "^3.0",
    "doctrine/dbal": "^3.6",
    "guzzlehttp/guzzle": "^7.2"
  },
  "require-dev": {
    "fakerphp/faker": "^1.9.1",
    "laravel/sail": "^1.18",
    "mockery/mockery": "^1.4.4",
    "nunomaduro/collision": "^7.0",
    "phpunit/phpunit": "^10.0",
    "spatie/laravel-ignition": "^2.0"
  }
}
```

Run: `composer update`

### 3.2 Update RouteServiceProvider

**File**: `src/App/Providers/RouteServiceProvider.php`

Remove the `$namespace` property and update the `boot()` method:

```php
<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    // REMOVE THIS LINE:
    // protected $namespace = 'App\\Http\\Controllers';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
```

**Note**: The `routes/api.php` file already uses full controller class names (e.g., `[LetterController::class, 'index']`), so no route file changes needed.

### 3.3 Test & Verify

```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Verify routes load correctly
php artisan route:list

# Run test script
./test-api-endpoints.sh

# Test rate limiting (should see X-RateLimit headers)
curl -I http://localhost:8000/api/letters | grep -i "x-ratelimit"
```

**Success Criteria**:
- Routes load without errors
- All API endpoints respond correctly
- Rate limiting headers present (X-RateLimit-Limit: 60)
- Custom query builder still works

---

## Phase 4: Laravel 10 → 11 (6-8 hours)

### 4.1 Update Dependencies

**File**: `composer.json`

```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^11.0",
    "laravel/tinker": "^2.9",
    "spatie/laravel-query-builder": "^5.0",
    "doctrine/dbal": "^4.0",
    "guzzlehttp/guzzle": "^7.2"
  },
  "require-dev": {
    "fakerphp/faker": "^1.23",
    "laravel/sail": "^1.26",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.0",
    "phpunit/phpunit": "^10.5",
    "spatie/laravel-ignition": "^2.4"
  }
}
```

**Note**: `spatie/laravel-enum` removed - will migrate to native PHP enums.

Run: `composer update`

### 4.2 Migrate Spatie Enums to Native PHP Enums

**Critical Task**: Convert all 7 enum classes to native PHP 8.1 syntax.

#### Example: County Enum

**File**: `src/Domain/Shared/Enums/County.php`

**Before** (Spatie):
```php
<?php

namespace Domain\Shared\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self augusta()
 * @method static self franklin()
 */
final class County extends Enum
{
    protected static function values(): array
    {
        return [
            'augusta' => 'Augusta',
            'franklin' => 'Franklin',
        ];
    }
}
```

**After** (Native):
```php
<?php

namespace Domain\Shared\Enums;

enum County: string
{
    case AUGUSTA = 'augusta';
    case FRANKLIN = 'franklin';

    public function label(): string
    {
        return match($this) {
            self::AUGUSTA => 'Augusta',
            self::FRANKLIN => 'Franklin',
        };
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}
```

#### Enums to Migrate

Apply similar transformation to:

1. `src/Domain/Shared/Enums/County.php` - Augusta, Franklin
2. `src/Domain/Shared/Enums/State.php` - VA, PA, DC, MD, NC, WV
3. `src/Domain/Shared/Enums/Race.php` - Demographic categories
4. `src/Domain/Shared/Enums/Sex.php` - Male, Female
5. `src/Domain/Shared/Enums/Weekday.php` - Days of week
6. `src/Domain/Shared/Enums/Chapter.php` - Eve, War, Aftermath
7. Any domain-specific enums in `Domain/*/Enums/`

#### Pattern for All Enums

```php
enum EnumName: string
{
    case CASE_NAME = 'value';

    public function label(): string
    {
        return match($this) {
            self::CASE_NAME => 'Display Label',
        };
    }

    // Helper for backward compatibility if needed
    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}
```

### 4.3 Update Model Casts for Native Enums

**Pattern**: Update all models that use enum casts.

**Example**: `src/Domain/Papers/Models/Letter.php`

**Before**:
```php
protected $casts = [
    'county' => County::class . ':nullable',
];
```

**After**:
```php
protected function casts(): array
{
    return [
        'county' => County::class,
    ];
}
```

**Models to Update** (search for `County::class` or other enum casts):
- Population census models
- Agricultural census models
- Manufacturing census models
- Tax record models
- Church record models
- All models with `county`, `state`, `race`, `sex`, `chapter` fields

### 4.4 Update HasCountyEnum Trait

**File**: `src/Domain/Shared/Traits/HasCountyEnum.php`

**Before**:
```php
public function getCountyLabelAttribute(): ?string
{
    return $this->county ? $this->county->label : null;
}
```

**After**:
```php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function countyLabel(): Attribute
{
    return Attribute::make(
        get: fn () => $this->county?->label(),
    );
}
```

### 4.5 Update All Model Accessors/Mutators

**Pattern**: Convert old `get*Attribute()` methods to new `Attribute` syntax.

**Example**: `src/Domain/Papers/Models/Letter.php`

**Before**:
```php
public function getSourceFileAttribute($value)
{
    return !empty($value) ? url('/storage/data' . $value) : null;
}

public function getCleanTitleAttribute(): ?string
{
    $title = $this->title;
    $title = preg_replace('/^\w+ County: /', '', $title);
    return $title;
}

public function getDateFromTitleAttribute()
{
    // ... complex logic
}
```

**After**:
```php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function sourceFile(): Attribute
{
    return Attribute::make(
        get: fn ($value) => !empty($value) ? url('/storage/data' . $value) : null,
    );
}

protected function cleanTitle(): Attribute
{
    return Attribute::make(
        get: fn () => $this->computeCleanTitle(),
    );
}

protected function dateFromTitle(): Attribute
{
    return Attribute::make(
        get: fn () => $this->computeDateFromTitle(),
    );
}

// Extract complex logic to private methods
private function computeCleanTitle(): ?string
{
    $title = $this->title;
    $title = preg_replace('/^\w+ County: /', '', $title);
    return $title;
}

private function computeDateFromTitle()
{
    // ... complex logic here
}
```

**Find All Accessors**:
```bash
# Search for old accessor pattern
grep -r "public function get.*Attribute" src/Domain/
```

**Models Likely Affected**:
- `src/Domain/Papers/Models/Letter.php`
- `src/Domain/Papers/Models/Diary.php`
- `src/Domain/Newspapers/Models/Story.php`
- Any model with computed attributes

### 4.6 Update Array Casts

**Pattern**: Models with array/json casts.

**Example**: `src/Domain/Papers/Models/Letter.php`

**Before**:
```php
protected $casts = [
    'keywords' => 'array',
];
```

**After**:
```php
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

protected function casts(): array
{
    return [
        'keywords' => 'array', // Still works, or use AsArrayObject::class for objects
    ];
}
```

### 4.7 Test Enum Filtering in IndexQueryBuilder

**Critical**: Verify enum filtering still works with native enums.

**File**: `src/Support/Queries/IndexQueryBuilder.php`

Test that exact filters work with enum fields:

```bash
# Test county filter (enum field)
curl "http://localhost:8000/api/letters?filter[county]=augusta"

# Verify enum value in response
curl "http://localhost:8000/api/letters/aug-let-001" | jq '.data.county'

# Test enum label attribute
curl "http://localhost:8000/api/letters/aug-let-001" | jq '.data.county_label'
```

If enum filtering breaks, update `IndexQueryBuilder::mapAllowedFilters()` to handle native enums:

```php
// May need to adjust filter logic for enum comparisons
use Domain\Shared\Enums\County;

// In exact filter logic:
if ($field === 'county' && !empty($value)) {
    $enumValue = County::tryFrom($value);
    if ($enumValue) {
        // Filter by enum value
    }
}
```

### 4.8 Clear Caches & Test

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Run test script
./test-api-endpoints.sh

# Test enum-specific endpoints
curl "http://localhost:8000/api/letters?filter[county]=augusta" | jq '.data | length'
curl "http://localhost:8000/api/population-census?filter[county]=franklin" | jq '.data | length'

# Verify TEI processing still works
curl "http://localhost:8000/api/letters/aug-let-001" | jq '.data.body' | head -c 200

# Test all filter types
curl "http://localhost:8000/api/letters?filter[date]=1863-07" | jq '.meta'
curl "http://localhost:8000/api/population-census?filter[age:gt]=18&filter[county]=augusta" | jq '.meta'
```

**Success Criteria**:
- All enum fields return correct values
- Enum filtering works (exact match on enum values)
- `county_label` and similar attributes work
- Accessors return correct computed values
- No deprecation warnings in logs
- TEI XML processing intact
- All 6 filter types functional

---

## Phase 5: Laravel 11 → 12 (1-2 hours) ✅ COMPLETED

**Completed on**: 2026-01-23
**Status**: Successfully upgraded to Laravel 12.48.1
**Key Changes**:
- Updated Laravel Framework from 11.48.0 to 12.48.1
- Updated Spatie Query Builder from 5.8.1 to 6.4.0
- Updated PHPUnit from 10.5.60 to 11.5.48
- Fixed PDO::MYSQL_ATTR_SSL_CA deprecation warning (changed to \Pdo\Mysql::ATTR_SSL_CA)
- All routes and commands load successfully
- No errors or deprecation warnings in logs

### 5.1 Update Dependencies

**File**: `composer.json`

```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10",
    "spatie/laravel-query-builder": "^6.0",
    "doctrine/dbal": "^4.0",
    "guzzlehttp/guzzle": "^7.2"
  },
  "require-dev": {
    "fakerphp/faker": "^1.23",
    "laravel/sail": "^1.37",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.0",
    "phpunit/phpunit": "^11.0",
    "spatie/laravel-ignition": "^2.8"
  }
}
```

**Note**: Laravel 12 maintains most of Laravel 11's architecture, so this should be a relatively smooth upgrade.

Run: `composer update`

### 5.2 Verify Application Structure

Laravel 12 continues the streamlined application structure introduced in Laravel 11. No major structural changes are expected.

**Check for deprecations:**
```bash
# Check logs for any deprecation warnings
grep -i "deprecated" storage/logs/laravel.log

# Verify all routes load
php artisan route:list

# Check for any breaking changes in dependencies
composer outdated
```

### 5.3 Test Core Functionality

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Regenerate config cache
php artisan config:cache

# Run test script
./test-api-endpoints.sh

# Test key features
curl "http://localhost:8000/api/letters?filter[county]=augusta" | jq '.data | length'
curl "http://localhost:8000/api/letters?filter[date]=1863-07" | jq '.meta'
```

### 5.4 Update Any Laravel 12-Specific Features (Optional)

Laravel 12 may introduce new features you can adopt:
- Review Laravel 12 release notes for new features
- Consider adopting new convenience methods or improvements
- Update any deprecated method calls if warnings appear

**Success Criteria**:
- All API endpoints return correct data
- No deprecation warnings in logs
- Test script passes all checks
- Performance remains stable

---

## Phase 6: Integration Testing (2-3 hours)

### 6.1 Comprehensive Endpoint Testing

Test each major resource type:

```bash
# Newspapers
curl "http://localhost:8000/api/newspapers" | jq '.data | length'
curl "http://localhost:8000/api/newspaper-editions?filter[year]=1863" | jq '.data | length'
curl "http://localhost:8000/api/newspaper-stories?filter[headline]=battle" | jq '.data | length'

# Papers
curl "http://localhost:8000/api/letters?filter[county]=augusta&filter[date]=1863" | jq '.data | length'
curl "http://localhost:8000/api/diaries?filter[author]=staunton" | jq '.data | length'
curl "http://localhost:8000/api/battlefield-correspondence" | jq '.data | length'

# Censuses
curl "http://localhost:8000/api/population-census?filter[county]=franklin&filter[age:gte]=21" | jq '.data | length'
curl "http://localhost:8000/api/manufacturing-census" | jq '.data | length'
curl "http://localhost:8000/api/agricultural-census" | jq '.data | length'

# Military
curl "http://localhost:8000/api/soldiers-dossiers?filter[county]=augusta" | jq '.data | length'
curl "http://localhost:8000/api/regimental-movements" | jq '.data | length'

# Other
curl "http://localhost:8000/api/church-records" | jq '.data | length'
curl "http://localhost:8000/api/civil-war-images" | jq '.data | length'
curl "http://localhost:8000/api/southern-claims-commission" | jq '.data | length'
```

### 6.2 Test Filter Combinations

```bash
# Multiple filters
curl "http://localhost:8000/api/letters?filter[county]=augusta&filter[date]=1863&filter[q]=home" | jq '.meta'

# Sorting
curl "http://localhost:8000/api/letters?sort=-date&filter[county]=franklin" | jq '.data[0].date'

# Pagination
curl "http://localhost:8000/api/letters?perpage=10&page=2" | jq '.meta'

# Complex numeric filter
curl "http://localhost:8000/api/population-census?filter[age:gte]=21&filter[age:lt]=65&filter[county]=augusta" | jq '.meta.total'
```

### 6.3 Test Option Lists (31+ endpoints)

```bash
# Sample option list endpoints
curl "http://localhost:8000/api/option-lists/diary-authors" | jq 'length'
curl "http://localhost:8000/api/option-lists/letter-keywords" | jq 'length'
curl "http://localhost:8000/api/option-lists/soldiers-dossier-regiments" | jq 'length'
curl "http://localhost:8000/api/option-lists/states" | jq 'length'
```

### 6.4 Test Console Commands

```bash
# Test that artisan commands load
php artisan list | grep import

# Test help for import commands
php artisan import:letters --help
php artisan import:diaries --help

# If safe, test with --dry-run or small dataset
# DO NOT run full imports on production data
```

### 6.5 Frontend Integration Test

**From Nuxt.js frontend** at `/valley.newamericanhistory.org`:

```bash
# Start frontend dev server
cd /Users/brianmcmillen/Sites/Bunk/valley.newamericanhistory.org
npm run dev

# Visit search pages and test:
# - http://localhost:3000/eve/newspapers
# - http://localhost:3000/eve/letters-and-diaries
# - http://localhost:3000/war/statistics/population-census
# - Verify all filters, sorting, pagination work
# - Check detail pages load correctly
```

### 6.6 Monitor Logs

```bash
# Watch for errors during testing
tail -f /Users/brianmcmillen/Sites/Bunk/api.valley.newamericanhistory.org/storage/logs/laravel.log

# Check for deprecation warnings
grep -i "deprecated" storage/logs/laravel.log

# Check for errors
grep -i "error" storage/logs/laravel.log
```

---

## Phase 7: Deployment (2-4 hours)

### 7.1 Pre-Deployment Checklist

- [ ] All test script checks pass
- [ ] No errors in Laravel logs
- [ ] Frontend can consume API successfully
- [ ] CORS headers present
- [ ] Rate limiting works
- [ ] All 6 filter types functional
- [ ] Enum values correct
- [ ] TEI XML processing intact
- [ ] Database backup created

### 7.2 Staging Deployment

```bash
# Commit changes
git add .
git commit -m "Upgrade Laravel 8 to 12

- Migrate to native PHP enums (County, State, Race, Sex, Chapter)
- Update to new Attribute accessor/mutator syntax
- Replace Fideloper proxy with Laravel native TrustProxies
- Replace Fruitcake CORS with Laravel native CORS
- Remove namespace property from RouteServiceProvider
- Update Spatie Query Builder to v6.x
- Update all model casts to casts() method
- Update doctrine/dbal to v4.x
- Update PHPUnit to v11.x

Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>"

# Push to remote
git push origin upgrade/laravel-12

# Deploy to staging environment
# (use your standard deployment process)
```

### 7.3 Staging Testing

Run full test suite on staging:

```bash
# Run all API tests against staging URL
BASE_URL="https://staging-api.valley.newamericanhistory.org/api" ./test-api-endpoints.sh

# Test from staging frontend
# Visit staging frontend and verify all functionality
```

### 7.4 Production Deployment

**Prerequisites**:
- Staging tests pass
- Backup created
- Maintenance mode plan ready

```bash
# Enable maintenance mode
php artisan down --message="Upgrading to Laravel 11" --retry=60

# Pull latest code
git pull origin upgrade/laravel-11

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations (if any new ones)
php artisan migrate --force

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Disable maintenance mode
php artisan up

# Verify production
curl -I https://api.valley.newamericanhistory.org/api/letters
```

### 7.5 Post-Deployment Monitoring

**First 24 Hours**:

```bash
# Monitor error logs
tail -f storage/logs/laravel.log

# Monitor web server logs
tail -f /var/log/nginx/error.log

# Check response times
curl -w "@curl-format.txt" -o /dev/null -s "https://api.valley.newamericanhistory.org/api/letters"

# Monitor database queries (enable query log temporarily)
```

**Metrics to Watch**:
- Response time (should be similar to pre-upgrade)
- Error rate (should be zero or near-zero)
- Memory usage
- Database query count (check for N+1 queries)

---

## Rollback Plan

### When to Roll Back

Roll back if:
- Critical API endpoints return 500 errors
- Filtering breaks and can't be fixed within 30 minutes
- Frontend cannot consume API
- TEI XML processing breaks
- Performance degrades significantly

### Rollback Procedure

```bash
# 1. Enable maintenance mode
php artisan down

# 2. Revert code to pre-upgrade state
git reset --hard pre-upgrade-laravel-12

# 3. Restore composer dependencies
composer install

# 4. Restore database (if migrations were run)
mysql valley_db < backup_pre_upgrade_YYYYMMDD.sql

# 5. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 6. Rebuild caches
php artisan config:cache
php artisan route:cache

# 7. Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx

# 8. Disable maintenance mode
php artisan up

# 9. Verify rollback successful
curl -I https://api.valley.newamericanhistory.org/api/letters
./test-api-endpoints.sh
```

---

## Risk Mitigation

### High-Risk Areas

1. **Enum Migration** (Highest Risk)
   - **Risk**: Filtering breaks, enum casting fails
   - **Mitigation**: Test thoroughly after enum conversion; verify `IndexQueryBuilder` handles native enums
   - **Fallback**: Can temporarily keep Spatie package if critical issues arise

2. **Custom Query Builder** (High Risk)
   - **Risk**: Spatie QueryBuilder v5.x incompatibility
   - **Mitigation**: Test all 6 filter types after L9 upgrade
   - **Fallback**: Pin to Spatie QueryBuilder v4.x if v5.x breaks

3. **TEI XML Processing** (Medium Risk)
   - **Risk**: HasTeiTags trait breaks with PHP/Laravel updates
   - **Mitigation**: Test TEI-heavy endpoints after each upgrade step
   - **Fallback**: Debug DOM manipulation methods

4. **CORS/Proxy** (Medium Risk)
   - **Risk**: Frontend can't access API after middleware changes
   - **Mitigation**: Test CORS headers immediately after L9 upgrade
   - **Fallback**: Adjust `config/cors.php` settings

### Testing Gates

**Do not proceed to next phase unless**:
1. Test script shows all ✓ checks
2. No errors in `laravel.log`
3. Key endpoints respond correctly
4. Frontend can consume API (if available)

---

## Success Criteria

### Functional Requirements

- [ ] All 50+ API endpoints return 200 status
- [ ] Exact filters work (e.g., `filter[county]=augusta`)
- [ ] Fuzzy filters work (e.g., `filter[headline]=battle`)
- [ ] Date filters work with partial dates (e.g., `filter[date]=1863`, `filter[date]=1863-07`)
- [ ] Numeric comparison filters work (e.g., `filter[age:gt]=18`)
- [ ] Text search works (e.g., `filter[q]=home`)
- [ ] Sorting works (e.g., `sort=-date`)
- [ ] Pagination works (e.g., `perpage=10&page=2`)
- [ ] Show endpoints work (e.g., `/letters/aug-let-001`)
- [ ] Enum fields return correct values
- [ ] `county_label` and similar computed attributes work
- [ ] TEI XML fields render correctly (body, summary)
- [ ] Images and notes relationships load
- [ ] Option list endpoints return data
- [ ] CORS headers present on all responses
- [ ] Rate limiting enforced (X-RateLimit-Limit: 60)

### Performance Requirements

- [ ] Response times similar to pre-upgrade (<200ms for simple queries)
- [ ] No new N+1 query issues
- [ ] Memory usage stable

### Quality Requirements

- [ ] No errors in Laravel logs
- [ ] No deprecation warnings
- [ ] Frontend integration works
- [ ] All console commands load without errors

---

## Timeline Summary

| Phase | Duration | Cumulative |
|-------|----------|------------|
| Pre-upgrade preparation | 1-2 hours | 1-2 hours |
| Laravel 8 → 9 upgrade | 3-4 hours | 4-6 hours |
| Laravel 9 → 10 upgrade | 2-3 hours | 6-9 hours |
| Laravel 10 → 11 upgrade | 6-8 hours | 12-17 hours |
| Laravel 11 → 12 upgrade | 1-2 hours | 13-19 hours |
| Integration testing | 2-3 hours | 15-22 hours |
| Deployment & monitoring | 2-4 hours | 17-26 hours |
| **Total** | **17-26 hours** | **2-4 days** |

Add 1-2 days for staging deployment and production rollout.

**Total project timeline: 3-6 days**

---

## Final Notes

1. **Incremental approach is critical** - Test thoroughly after each Laravel version upgrade (8→9→10→11→12)
2. **Enum migration is the most complex task** - Allocate sufficient time and test filtering extensively
3. **Custom query builder must be validated** - This is the heart of the API's filtering system
4. **TEI XML processing is unique** - Ensure HasTeiTags trait remains functional
5. **Frontend integration testing is essential** - API is useless if Nuxt.js can't consume it
6. **Create backups before each phase** - Rollback capability is critical
7. **Monitor logs continuously** - Catch issues early
8. **Laravel 11 → 12 upgrade is relatively simple** - Most breaking changes occur in earlier versions

The upgrade path is well-defined and incrementally testable. Each phase has clear success criteria before proceeding to the next.
