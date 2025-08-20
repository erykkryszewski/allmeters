<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Day;
use App\Services\ScoreEngine;

class DayController extends Controller
{
  // save or update a daily entry by date
  public function store(Request $request, $date)
  {
    // validate input (basic for now)
    $data = $request->validate([
      'work_hours'   => ['required', 'integer', 'min:0', 'max:16'],
      'sleep_hours'  => ['required', 'numeric', 'min:0', 'max:16'],
      'calories'     => ['nullable', 'integer', 'min:0', 'max:6000'],
      'high_protein' => ['nullable', 'boolean'],
      'low_fat'      => ['nullable', 'boolean'],
    ]);

    // normalize checkboxes (unchecked are not sent → set false)
    $data['high_protein'] = (bool) ($data['high_protein'] ?? false);
    $data['low_fat'] = (bool) ($data['low_fat'] ?? false);

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
      ->with('status', 'Day saved. Score: ' . $result['total'] . ' / ' . $result['max_partial']);
  }
}
