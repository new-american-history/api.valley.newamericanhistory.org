<?php

namespace Domain\Censuses\Models;

use Domain\Shared\Traits\HasCountyEnum;
use Illuminate\Database\Eloquent\Model;

class AgriculturalCensus extends Model
{
    use HasCountyEnum;

    protected $table = 'agricultural_census';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['county_label'];

    protected $dates = ['date_taken'];

    protected $casts = [
        'year' => 'integer',
        'number_on_page' => 'integer',
        'page_number' => 'integer',
        // 'wages_paid' => 'integer', // @todo ONCE WE MIGRATE TO `INT`
        'farm_value' => 'integer',
        'farm_implements_value' => 'integer',
        'forest_products_value' => 'integer',
        'home_manufactures_value' => 'integer',
        'livestock_value' => 'integer',
        'market_garden_produce_value' => 'integer',
        'orchard_products_value' => 'integer',
        'slaughtered_animals_value' => 'integer',
        'total_value' => 'integer',
        'barley_bushels' => 'integer',
        'beeswax_pounds' => 'integer',
        'buckwheat_bushels' => 'integer',
        'butter_pounds' => 'integer',
        'cane_sugar_hogsheads' => 'integer',
        'cheese_pounds' => 'integer',
        'clover_seed_bushels' => 'integer',
        'corn_bushels' => 'integer',
        'cotton_bales' => 'integer',
        'cows' => 'integer',
        'flax_pounds' => 'integer',
        'flax_seed_bushels' => 'integer',
        'grass_seed_bushels' => 'integer',
        'hay_tons' => 'integer',
        'hemp_tons' => 'integer',
        'honey_pounds' => 'integer',
        'hops_pounds' => 'integer',
        'horses' => 'integer',
        'irish_potatoes_bushels' => 'integer',
        'maple_sugar_pounds' => 'integer',
        'milk_gallons' => 'integer',
        'molasses_gallons' => 'integer',
        'mules_and_asses' => 'integer',
        'oats_bushels' => 'integer',
        'oxen' => 'integer',
        'peas_and_beans_bushels' => 'integer',
        'rice_pounds' => 'integer',
        'rye_bushels' => 'integer',
        'sheep' => 'integer',
        'silk_cocoons_pounds' => 'integer',
        'spring_wheat_bushels' => 'integer',
        'sweet_potatoes_bushels' => 'integer',
        'swine' => 'integer',
        'tobacco_pounds' => 'integer',
        'wheat_bushels' => 'integer',
        'wine_gallons' => 'integer',
        'winter_wheat_bushels' => 'integer',
        'wool_pounds' => 'integer',
        'other_cattle' => 'integer',
        'total_animals' => 'integer',
        'total_grain_bushels' => 'integer',
        'improved_land_acres' => 'integer',
        'unimproved_land_acres' => 'integer',
        'woodland_acres' => 'integer',
        'other_unimproved_land_acres' => 'integer',
        'total_land_acres' => 'integer',
    ];

    public static $exactFilters = [
        'county',
        'year',
    ];

    public static $fuzzyFilters = [
        'first_name',
        'last_name',
        'location',
    ];

    public static $numericFilters = [
        // 'wages_paid', // @todo ONCE WE MIGRATE TO `INT`
        'farm_value',
        'farm_implements_value',
        'forest_products_value',
        'home_manufactures_value',
        'livestock_value',
        'market_garden_produce_value',
        'orchard_products_value',
        'slaughtered_animals_value',
        'total_value',
        'barley_bushels',
        'beeswax_pounds',
        'buckwheat_bushels',
        'butter_pounds',
        'cane_sugar_hogsheads',
        'cheese_pounds',
        'clover_seed_bushels',
        'corn_bushels',
        'cotton_bales',
        'cows',
        'flax_pounds',
        'flax_seed_bushels',
        'grass_seed_bushels',
        'hay_tons',
        'hemp_tons',
        'honey_pounds',
        'hops_pounds',
        'horses',
        'irish_potatoes_bushels',
        'maple_sugar_pounds',
        'milk_gallons',
        'molasses_gallons',
        'mules_and_asses',
        'oats_bushels',
        'oxen',
        'peas_and_beans_bushels',
        'rice_pounds',
        'rye_bushels',
        'sheep',
        'silk_cocoons_pounds',
        'spring_wheat_bushels',
        'sweet_potatoes_bushels',
        'swine',
        'tobacco_pounds',
        'wheat_bushels',
        'wine_gallons',
        'winter_wheat_bushels',
        'wool_pounds',
        'other_cattle',
        'total_animals',
        'total_grain_bushels',
        'improved_land_acres',
        'unimproved_land_acres',
        'woodland_acres',
        'other_unimproved_land_acres',
        'total_land_acres',
    ];
}
