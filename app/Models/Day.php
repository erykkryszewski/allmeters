<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // allows to use simpler sql queries using php

class Day extends Model
{
    // fields we can fill in bulk
  protected $fillable = [
    'date',
    'work_hours',
    'sleep_hours',
    'calories',
    'high_protein',
    'low_fat',
    'activity_level',
    'wife_time',
    'social_media_level',
    'smoking',
    'alcohol',
    'other_drugs',
    'meditation',
    'reading',
    'chess_games',
    'water_level',
    'score',
    'score_breakdown',
  ];
}
