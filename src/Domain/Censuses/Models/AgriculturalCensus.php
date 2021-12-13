<?php

namespace Domain\Censuses\Models;

use Illuminate\Database\Eloquent\Model;

class AgriculturalCensus extends Model
{
    protected $table = 'agricultural_census';

    protected $guarded = [];

    protected $dates = ['date_taken'];

    protected $casts = [
        'year' => 'integer',
        'wages_paid' => 'integer',
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
}
