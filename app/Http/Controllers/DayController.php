<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Day;
use App\Services\ScoreEngine;

class DayController extends Controller
{
  // save or update a daily entry by date
  public function store(Request $request, $date) // request is all stuff from form, date is the date from url
  {
    // validate input (basic for now)
    $data = $request->validate([
      'work_hours'          => ['required', 'integer', 'min:0', 'max:16'],
      'sleep_hours'         => ['required', 'numeric', 'min:0', 'max:16'],
      'calories'            => ['nullable', 'integer', 'min:0', 'max:6000'],
      'high_protein'        => ['nullable', 'boolean'],
      'low_fat'             => ['nullable', 'boolean'],

      'activity_level'      => ['required', 'in:none,short,long'],
      'wife_time'           => ['nullable', 'boolean'],
      'social_media_level'  => ['required', 'in:ok,medium,high'],

      'smoking'             => ['nullable', 'boolean'],
      'alcohol'             => ['nullable', 'boolean'],
      'other_drugs'         => ['nullable', 'boolean'],

      'meditation'          => ['nullable', 'boolean'],
      'reading'             => ['nullable', 'boolean'],
      'chess_games'         => ['nullable', 'integer', 'min:0', 'max:20'],
      'other_chess_games'   => ['nullable', 'boolean'],   

      'water_level'         => ['required', 'in:low,medium,high'],
    ]);

    // normalize checkboxes (unchecked = false)
    $data['high_protein']     = (bool) ($data['high_protein'] ?? false);
    $data['low_fat']          = (bool) ($data['low_fat'] ?? false);
    $data['wife_time']        = (bool) ($data['wife_time'] ?? false);
    $data['smoking']          = (bool) ($data['smoking'] ?? false);
    $data['alcohol']          = (bool) ($data['alcohol'] ?? false);
    $data['other_drugs']      = (bool) ($data['other_drugs'] ?? false);
    $data['meditation']       = (bool) ($data['meditation'] ?? false);
    $data['reading']          = (bool) ($data['reading'] ?? false);
    $data['other_chess_games']= (bool) ($data['other_chess_games'] ?? false); 



    // attach the date coming from the url
    $data['date'] = $date;

    // compute score with current partial engine
    // note: this uses only fields we currently collect
    $result = ScoreEngine::score($data);
    $data['score'] = $result['total'];
    $data['score_breakdown'] = json_encode($result['breakdown']);

    // upsert by date (only one entry per day)
    Day::updateOrCreate(
      ['date' => $date],  // unique key
      $data               // values to set
    );

    // redirect back to the entries page with a success message
    return redirect()
      ->route('entries', $date)
      ->with('status', 'Day saved. Score: ' . $result['total'] . ' / ' . $result['max_total_positive']);

  }
}
