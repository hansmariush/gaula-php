<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $table = 'lodging_sitesettings';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'rotation_follows_rods' => 'boolean',
            'feat_messages' => 'boolean',
            'feat_chores' => 'boolean',
            'feat_room_checks' => 'boolean',
            'feat_week_tasks' => 'boolean',
            'feat_deposit' => 'boolean',
            'feat_companions' => 'boolean',
            'feat_rods' => 'boolean',
            'feat_fishing_price' => 'boolean',
            'day_prices' => 'boolean',
            'feat_calendar' => 'boolean',
            'feat_season_wizard' => 'boolean',
            'before_arrival' => 'boolean',
            'river_conditions' => 'boolean',
            'report_catch' => 'boolean',
            'ask_feedback' => 'boolean',
            'menu_gjenkjop' => 'boolean',
            'theme_picker' => 'boolean',
            'temp_warm' => 'decimal:1',
            'show_booking_ref' => 'boolean',
        ];
    }
}
