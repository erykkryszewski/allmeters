<?php

return [

  /*
    single source of truth for scoring.
    keep numbers here so we do not hunt them in code later.
  */

  // positive caps (sum of best-case positives; penalties can reduce it)
  // work(30) + sleep(20) + diet(25) + activity(12) + wife(12) + meditation(5)
  // + reading(5) + chess one game(8) + water(8) = 125
  'max_total_positive' => 125,

  // work scoring (hours)
  // 6–8h is the sweet spot; 0–3h is strongly negative; 9–10h still ok but less;
  // above 10h small points (avoid rewarding overwork)
  'work' => [
    ['min' => 0,  'max' => 1,  'points' => -20],
    ['min' => 2,  'max' => 3,  'points' => -10],
    ['min' => 4,  'max' => 5,  'points' => 10],
    ['min' => 6,  'max' => 8,  'points' => 30], // optimum
    ['min' => 9,  'max' => 10, 'points' => 15],
    ['min' => 11, 'max' => 24, 'points' => 5],
  ],

  // sleep scoring (hours)
  // 7–8h best; 6h neutral; 5h and below negative; 9h a bit less; 10h+ neutral
  'sleep' => [
    ['min' => 0,  'max' => 4.99, 'points' => -20],
    ['min' => 5,  'max' => 5.99, 'points' => -10],
    ['min' => 6,  'max' => 6.99, 'points' => 0],
    ['min' => 7,  'max' => 7.99, 'points' => 20], // optimum
    ['min' => 8,  'max' => 8.99, 'points' => 15],
    ['min' => 9,  'max' => 9.99, 'points' => 10],
    ['min' => 10, 'max' => 24,   'points' => 0],
  ],

  // diet scoring
  // calories band + bonuses for high protein and low fat
  'diet' => [
    'calories' => [
      ['min' => 0,    'max' => 1499, 'points' => -10],
      ['min' => 1500, 'max' => 1700, 'points' => 0],
      ['min' => 1800, 'max' => 2000, 'points' => 10], // optimum
      ['min' => 2001, 'max' => 2200, 'points' => 0],
      ['min' => 2201, 'max' => 9999, 'points' => -10],
    ],
    'high_protein_bonus' => 10,
    'low_fat_bonus'      => 5,
  ],

  // activity (enum: none / short / long)
  // short ~30 min, long 45+ min; meaningful but below sleep/diet/work
  'activity' => [
    'none'  => 0,
    'short' => 8,
    'long'  => 12,
  ],

  // time with wife (boolean)
  // important habit (similar weight to activity)
  'wife_time_bonus' => 12,

  // social media (enum: ok / medium / high)
  // ok = one 15min session; medium/high are penalties
  'social' => [
    'ok'     => 0,
    'medium' => -15,
    'high'   => -30,
  ],

  // substances
  // cigarettes, alcohol same weight; "other" is stronger penalty by request
  'substances' => [
    'smoking_penalty'      => -20,
    'alcohol_penalty'      => -20,
    'other_drugs_penalty'  => -40,
  ],

  // habits
  'habits' => [
    'meditation_bonus' => 5,
    'reading_bonus'    => 5,
  ],

  // chess
  // one focused 15+10 is good; more than one = penalty; any other games = penalty
  'chess' => [
    'one_game_points'     => 8,   // points for the first classical game (15+10)
    'extra_game_penalty'  => -10, // if chess_games > 1
    'other_games_penalty' => -15, // if other_chess_games = true
  ],

  // water (enum: low / medium / high)
  // low = 0, medium = small bonus, high = a bit more; supportive, not dominant
  'water' => [
    'low'    => 0,
    'medium' => 5,
    'high'   => 8,
  ],
];
