<?php

return [

  // maximum points we can get from currently implemented categories
  // note: we will expand this as we add more categories
  'max_partial' => 30 + 20 + 25, // work + sleep + diet

  // work scoring (hours)
  'work' => [
    // ranges are inclusive on both ends where sensible
    // keep simple and junior-friendly
    ['min' => 0,  'max' => 1,  'points' => -20],
    ['min' => 2,  'max' => 3,  'points' => -10],
    ['min' => 4,  'max' => 5,  'points' => +10],
    ['min' => 6,  'max' => 8,  'points' => +30], // cap (optimum)
    ['min' => 9,  'max' => 10, 'points' => +15],
    ['min' => 11, 'max' => 24, 'points' => +5],  // anything >10h
  ],

  // sleep scoring (hours)
  'sleep' => [
    ['min' => 0,  'max' => 4.99, 'points' => -20],
    ['min' => 5,  'max' => 5.99, 'points' => -10],
    ['min' => 6,  'max' => 6.99, 'points' => 0],
    ['min' => 7,  'max' => 7.99, 'points' => +20], // optimum
    ['min' => 8,  'max' => 8.99, 'points' => +15],
    ['min' => 9,  'max' => 9.99, 'points' => +10],
    ['min' => 10, 'max' => 24,   'points' => 0],
  ],

  // diet scoring
  'diet' => [
    'calories' => [
      ['min' => 0,    'max' => 1499, 'points' => -10],
      ['min' => 1500, 'max' => 1700, 'points' => 0],
      ['min' => 1800, 'max' => 2000, 'points' => +10], // optimum
      ['min' => 2001, 'max' => 2200, 'points' => 0],
      ['min' => 2201, 'max' => 9999, 'points' => -10],
    ],
    'high_protein_bonus' => 10,
    'low_fat_bonus' => 5,
  ],

];
