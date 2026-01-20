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

The codebase uses a custom three-layer architecture defined in composer.json autoloading:

### `src/App/` - Application Layer
Contains HTTP-specific logic organized by resource type:
- **Controllers**: Handle HTTP requests, delegate to queries, return resources
- **Queries**: Extend `IndexQueryBuilder` to configure allowed filters/sorts per model
- **Resources**: Transform models into JSON API responses

Example flow: `LetterController` → `LetterIndexQuery` → `LetterResource`

### `src/Domain/` - Domain Layer
Contains business logic and models organized by domain concept:
- **Models**: Eloquent models with filtering configuration
- **Enums**: Domain-specific enumerations (County, State, Race, Sex, etc.)
- **Traits**: Shared model behaviors (HasTeiTags, HasCountyEnum, HasManipulatedHtml)

Models define static filter arrays that configure query behavior:
- `$exactFilters`: Exact match filters
- `$exactFiltersWithCommas`: Comma-separated exact match filters
- `$fuzzyFilters`: LIKE search filters
- `$numericFilters`: Numeric comparison filters (gt, gte, lt, lte, ne)
- `$dateFilters`: Date range filters with smart parsing
- `$excludedSorts`: Fields excluded from sorting

### `src/Support/` - Shared Infrastructure
Contains framework utilities:
- **Queries/IndexQueryBuilder.php**: Base query builder that maps model filter arrays to Spatie query builder filters
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
php artisan import:all  # Import all Valley data types
php artisan import:letters  # Import specific data type
php artisan import:diaries
php artisan import:population-census
# See src/App/Console/Commands/ for all import commands
```

**Run Laravel development server:**
```bash
php artisan serve
```

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

Import commands in `src/App/Console/Commands/` handle migrating data from legacy Valley project XML/CSV files. Commands extend `BaseImportCommand` and parse TEI XML or CSV files, transforming them into database records.
