<?php

namespace App\Services;

class ScoreEngine
{
  // compute total score and per-category breakdown
  public static function score(array $input): array
  {
    // read config once
    $config = config('score');

    // extract inputs with safe defaults (explicit, no shortcuts)
    $workHours        = isset($input['work_hours']) ? (float)$input['work_hours'] : 0.0;
    $sleepHours       = isset($input['sleep_hours']) ? (float)$input['sleep_hours'] : 0.0;
    $calories         = isset($input['calories']) ? (int)$input['calories'] : 0;

    $highProtein      = isset($input['high_protein']) && $input['high_protein'] ? true : false;
    $lowFat           = isset($input['low_fat']) && $input['low_fat'] ? true : false;

    $activityLevel    = isset($input['activity_level']) ? (string)$input['activity_level'] : 'none';
    $wifeTime         = isset($input['wife_time']) && $input['wife_time'] ? true : false;

    $socialLevel      = isset($input['social_media_level']) ? (string)$input['social_media_level'] : 'ok';

    $smoking          = isset($input['smoking']) && $input['smoking'] ? true : false;
    $alcohol          = isset($input['alcohol']) && $input['alcohol'] ? true : false;
    $otherDrugs       = isset($input['other_drugs']) && $input['other_drugs'] ? true : false;

    $meditation       = isset($input['meditation']) && $input['meditation'] ? true : false;
    $reading          = isset($input['reading']) && $input['reading'] ? true : false;

    $chessGames       = isset($input['chess_games']) ? (int)$input['chess_games'] : 0;
    $otherChessGames  = isset($input['other_chess_games']) && $input['other_chess_games'] ? true : false;

    $waterLevel       = isset($input['water_level']) ? (string)$input['water_level'] : 'low';

    // compute category points
    $workPoints       = self::scoreWork($workHours, $config['work']);
    $sleepPoints      = self::scoreSleep($sleepHours, $config['sleep']);
    $dietPoints       = self::scoreDiet($calories, $highProtein, $lowFat, $config['diet']);
    $activityPoints   = self::scoreActivity($activityLevel, $config['activity']);
    $wifePoints       = self::scoreWife($wifeTime, (int)$config['wife_time_bonus']);
    $socialPoints     = self::scoreSocial($socialLevel, $config['social']);
    $substancePoints  = self::scoreSubstances($smoking, $alcohol, $otherDrugs, $config['substances']);
    $habitPoints      = self::scoreHabits($meditation, $reading, $config['habits']);
    $chessPoints      = self::scoreChess($chessGames, $otherChessGames, $config['chess']);
    $waterPoints      = self::scoreWater($waterLevel, $config['water']);

    // sum up
    $total = $workPoints + $sleepPoints + $dietPoints + $activityPoints
           + $wifePoints + $socialPoints + $substancePoints + $habitPoints
           + $chessPoints + $waterPoints;

    // return result
    return [
      'total' => (int)$total,
      'breakdown' => [
        'work'        => (int)$workPoints,
        'sleep'       => (int)$sleepPoints,
        'diet'        => (int)$dietPoints,
        'activity'    => (int)$activityPoints,
        'wife_time'   => (int)$wifePoints,
        'social'      => (int)$socialPoints,
        'substances'  => (int)$substancePoints,
        'habits'      => (int)$habitPoints,
        'chess'       => (int)$chessPoints,
        'water'       => (int)$waterPoints,
      ],
      'max_total_positive' => (int)$config['max_total_positive'],
    ];
  }

  // map work hours to points using configured ranges
  private static function scoreWork(float $hours, array $ranges): int
  {
    foreach ($ranges as $range) {
      $min = (float)$range['min'];
      $max = (float)$range['max'];
      $points = (int)$range['points'];
      if ($hours >= $min && $hours <= $max) {
        return $points;
      }
    }
    return 0;
  }

  // map sleep hours to points using configured ranges
  private static function scoreSleep(float $hours, array $ranges): int
  {
    foreach ($ranges as $range) {
      $min = (float)$range['min'];
      $max = (float)$range['max'];
      $points = (int)$range['points'];
      if ($hours >= $min && $hours <= $max) {
        return $points;
      }
    }
    return 0;
  }

  // compute diet points: calories band + bonuses
  private static function scoreDiet(int $calories, bool $highProtein, bool $lowFat, array $dietConfig): int
  {
    $points = 0;

    // calories band
    foreach ($dietConfig['calories'] as $range) {
      $min = (int)$range['min'];
      $max = (int)$range['max'];
      $bandPoints = (int)$range['points'];
      if ($calories >= $min && $calories <= $max) {
        $points += $bandPoints;
        break;
      }
    }

    // bonuses
    if ($highProtein === true) {
      $points += (int)$dietConfig['high_protein_bonus'];
    }
    if ($lowFat === true) {
      $points += (int)$dietConfig['low_fat_bonus'];
    }

    return (int)$points;
  }

  // activity: enum mapping
  private static function scoreActivity(string $level, array $map): int
  {
    if (isset($map[$level])) {
      return (int)$map[$level];
    }
    return 0;
  }

  // wife time: boolean bonus
  private static function scoreWife(bool $hasTime, int $bonus): int
  {
    return $hasTime ? $bonus : 0;
  }

  // social media: enum penalties
  private static function scoreSocial(string $level, array $map): int
  {
    if (isset($map[$level])) {
      return (int)$map[$level];
    }
    return 0;
  }

  // substances: sum of selected penalties
  private static function scoreSubstances(bool $smoking, bool $alcohol, bool $other, array $cfg): int
  {
    $points = 0;
    if ($smoking === true) {
      $points += (int)$cfg['smoking_penalty'];
    }
    if ($alcohol === true) {
      $points += (int)$cfg['alcohol_penalty'];
    }
    if ($other === true) {
      $points += (int)$cfg['other_drugs_penalty'];
    }
    return (int)$points;
  }

  // habits: simple boolean bonuses
  private static function scoreHabits(bool $meditation, bool $reading, array $cfg): int
  {
    $points = 0;
    if ($meditation === true) {
      $points += (int)$cfg['meditation_bonus'];
    }
    if ($reading === true) {
      $points += (int)$cfg['reading_bonus'];
    }
    return (int)$points;
  }

  // chess rules:
  // - if chess_games == 0 → 0
  // - if chess_games == 1 → one_game_points
  // - if chess_games > 1  → extra_game_penalty (we do not add one_game_points)
  // - if other_chess_games === true → add penalty on top
  private static function scoreChess(int $games, bool $otherGames, array $cfg): int
  {
    $points = 0;

    if ($games === 1) {
      $points += (int)$cfg['one_game_points'];
    } elseif ($games > 1) {
      $points += (int)$cfg['extra_game_penalty'];
    }

    if ($otherGames === true) {
      $points += (int)$cfg['other_games_penalty'];
    }

    return (int)$points;
  }

  // water: enum mapping
  private static function scoreWater(string $level, array $map): int
  {
    if (isset($map[$level])) {
      return (int)$map[$level];
    }
    return 0;
  }
}
