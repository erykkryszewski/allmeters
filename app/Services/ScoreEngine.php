<?php

namespace App\Services;

class ScoreEngine
{
  // compute total score and per-category breakdown
  public static function score(array $input): array
  {
    // read config once
    $cfg = config('score');

    $work   = self::scoreWork((float)($input['work_hours'] ?? 0), $cfg['work']);
    $sleep  = self::scoreSleep((float)($input['sleep_hours'] ?? 0), $cfg['sleep']);
    $diet   = self::scoreDiet(
      (int)($input['calories'] ?? 0),
      (bool)($input['high_protein'] ?? false),
      (bool)($input['low_fat'] ?? false),
      $cfg['diet']
    );

    $total = $work + $sleep + $diet;

    return [
      'total' => (int)$total,
      'breakdown' => [
        'work'  => (int)$work,
        'sleep' => (int)$sleep,
        'diet'  => (int)$diet,
      ],
      'max_partial' => (int)$cfg['max_partial'], // used for simple % view for now
    ];
  }

  // map work hours to points using configured ranges
  private static function scoreWork(float $hours, array $ranges): int
  {
    // simple linear search over ranges
    foreach ($ranges as $r) {
      if ($hours >= $r['min'] && $hours <= $r['max']) {
        return (int)$r['points'];
      }
    }
    // fallback in case of unexpected values
    return 0;
  }

  // map sleep hours to points using configured ranges
  private static function scoreSleep(float $hours, array $ranges): int
  {
    foreach ($ranges as $r) {
      if ($hours >= $r['min'] && $hours <= $r['max']) {
        return (int)$r['points'];
      }
    }
    return 0;
  }

  // compute diet points: calories band + bonuses
  private static function scoreDiet(int $calories, bool $highProtein, bool $lowFat, array $cfg): int
  {
    $points = 0;

    // calories band
    foreach ($cfg['calories'] as $r) {
      if ($calories >= $r['min'] && $calories <= $r['max']) {
        $points += (int)$r['points'];
        break;
      }
    }

    // bonuses
    if ($highProtein) {
      $points += (int)$cfg['high_protein_bonus'];
    }
    if ($lowFat) {
      $points += (int)$cfg['low_fat_bonus'];
    }

    return (int)$points;
  }
}
