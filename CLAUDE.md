# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 8 API for "The Valley of the Shadow" project, a digital archive of American Civil War primary sources documenting lives in Augusta County, Virginia, and Franklin County, Pennsylvania. The API migrates historical data from previous versions and exposes RESTful endpoints at api.valley.newamericanhistory.org/api.

**Tech Stack:**
- PHP 8.3
- Laravel 8.12
- MySQL database
- Spatie Laravel Query Builder for filtering and sorting

## Project Structure

The codebase uses a custom three-layer architecture with PSR-4 namespaces defined in composer.json:
- `App\` → `src/App/`
- `Domain\` → `src/Domain/`
- `Support\` → `src/Support/`

### `src/App/` - Application Layer (namespace: `App\`)
Contains HTTP-specific logic organized by resource type within `App\Api\[Concept]\`:
- **Controllers**: Handle HTTP requests, delegate to queries, return resources
- **Queries**: Extend `IndexQueryBuilder` to configure allowed filters/sorts per model
- **Resources**: Transform models into JSON API responses
- **Console/Commands**: Import commands that extend `BaseImportCommand`

Example flow: `App\Api\Papers\Controllers\LetterController` → `App\Api\Papers\Queries\LetterIndexQuery` → `App\Api\Papers\Resources\LetterResource`

### `src/Domain/` - Domain Layer (namespace: `Domain\`)
Contains business logic and models organized by domain concept within `Domain\[Concept]\`:
- **Models**: Eloquent models with static filter configuration arrays
- **Enums**: Domain-specific enumerations (County, State, Race, Sex, etc.)
- **Traits**: Shared model behaviors (HasTeiTags, HasCountyEnum, HasManipulatedHtml)

**Filter Configuration Pattern:**
Models define static arrays that `IndexQueryBuilder` automatically maps to Spatie query builder filters:
- `$exactFilters`: Exact match filters (e.g., `['county', 'valley_id']`)
- `$exactFiltersWithCommas`: Comma-separated values for exact match (e.g., `['author']` allows `?filter[author]=John,Jane`)
- `$fuzzyFilters`: LIKE search filters (e.g., `['headline', 'keywords']`)
- `$numericFilters`: Numeric comparison filters automatically get `:gt`, `:gte`, `:lt`, `:lte`, `:ne` operators
- `$dateFilters`: Date range filters with smart partial date parsing (year-only, year-month)
- `$excludedSorts`: Fields excluded from sorting

### `src/Support/` - Shared Infrastructure (namespace: `Support\`)
Contains framework utilities:
- **Queries/IndexQueryBuilder.php**: Base query builder that reads model filter arrays and configures Spatie query builder
- **Filters/**: Custom filter implementations (DateFilter, TextSearchFilter, ExactFilterWithCommas)
- **Middleware/**: Application middleware

## Common Commands

**Install dependencies:**
```bash
composer install
```

**Database migrations:**
```bash
php artisan migrate
php artisan migrate:fresh  # Fresh migration (drops all tables)
```

**Import historical data:**
```bash
php artisan import:all  # Import all Valley data types (calls all individual import commands)
php artisan import:letters  # Import specific data type
php artisan import:diaries
php artisan import:population-census
php artisan import:newspapers
# See src/App/Console/Commands/ for all 20+ import commands
```

**Run tests:**
```bash
vendor/bin/phpunit  # Run all tests
vendor/bin/phpunit --testsuite=Unit  # Run unit tests only
vendor/bin/phpunit --testsuite=Feature  # Run feature tests only
vendor/bin/phpunit tests/Unit/SpecificTest.php  # Run specific test file
```

**Run Laravel development server:**
```bash
php artisan serve  # Starts server at http://localhost:8000
```

**Docker local development:**
```bash
docker-compose up -d  # Start containers (requires external journey-network)
docker-compose down  # Stop containers
docker-compose exec valley-api php artisan migrate  # Run commands in container
```
The Docker setup uses PHP 8.3-FPM with Nginx and connects to an external MySQL database (journey-db). Access at http://local.api.valley.newamericanhistory.org via Traefik.

**Clear caches:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## API Architecture

All API routes are defined in `routes/api.php`. The API follows a consistent pattern:

**Index endpoints** (list resources):
- Controller receives Request
- Query object (extends IndexQueryBuilder) applies filters/sorts configured in model
- Returns paginated ResourceCollection
- URL parameters: `?filter[field]=value&sort=field&perpage=50`

**Show endpoints** (individual resources):
- Controller receives route parameter (usually `valley_id`)
- Queries model directly
- Returns single Resource

**Filter examples:**
- Exact: `?filter[county]=augusta`
- Fuzzy: `?filter[headline]=battle`
- Date range: `?filter[date]=1863` (matches whole year)
- Date range: `?filter[date]=1863-07` (matches month)
- Comparison: `?filter[age:gt]=18`
- Text search: `?filter[q]=searchterm` (searches all fuzzy and exact fields)

## Adding New Features

**To add a new model with API endpoint:**

1. Create migration in `database/migrations/`
2. Create model in `src/Domain/[Concept]/Models/` with filter arrays
3. Create controller in `src/App/Api/[Concept]/Controllers/`
4. Create query class in `src/App/Api/[Concept]/Queries/` extending `IndexQueryBuilder`
5. Create resource in `src/App/Api/[Concept]/Resources/`
6. Register routes in `routes/api.php`

**TEI XML Processing:**
Models with TEI (Text Encoding Initiative) XML fields use the `HasTeiTags` trait to handle XML transformation. The `$teiFields` array defines which fields contain TEI markup.

**Date Filtering:**
The `DateFilter` class supports partial dates (year-only, year-month) and automatically expands them to ranges. For example, filtering by "1863" searches from 1863-01-01 to 1863-12-31.

## Data Import

Import commands in `src/App/Console/Commands/` handle migrating data from legacy Valley project XML/CSV files. All commands extend `BaseImportCommand` and parse TEI (Text Encoding Initiative) XML or CSV files, transforming them into database records.

**Import workflow:**
1. `import:all` orchestrates all individual import commands sequentially
2. Each import command (e.g., `import:letters`, `import:diaries`) processes specific data types
3. Commands read from source files in the data directory (4.22 GB of TEI/CSV files)
4. TEI XML parsing extracts structured data and handles markup transformation
5. Data is inserted into MySQL database with appropriate relationships

**Available import commands:**
- Census data: `import:population-census`, `import:agricultural-census`, `import:manufacturing-census`, `import:slaveowning-census`, `import:veteran-census`
- Papers: `import:letters`, `import:diaries`, `import:battlefield-correspondence`
- Newspapers: `import:newspapers`
- Military: `import:soldier-dossiers`, `import:regimental-movements`
- Tax records: `import:augusta-tax-records`, `import:franklin-tax-records`
- Claims: `import:chambersburg-claims`, `import:southern-claims-commission`
- Other: `import:church-records`, `import:civil-war-images`, `import:cohabitation-records`, `import:fire-insurance-policies`, `import:free-black-registry`, `import:memory-articles`
