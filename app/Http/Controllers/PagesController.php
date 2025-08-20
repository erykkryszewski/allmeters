<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;   
use App\Models\Day;

class PagesController extends Controller
{
  // show last 7 days summary and trend
  public function dashboard()
  {
    // get today and 6 days back (7 days window)
    $today = Carbon::today();
    $start = (clone $today)->subDays(6);

    // fetch days from db for the window
    $rows = Day::whereBetween('date', [$start->toDateString(), $today->toDateString()])
      ->orderBy('date', 'asc')
      ->get()
      ->keyBy('date'); // map by date for easy lookup

    // build a fixed 7-day series (strings 'YYYY-MM-DD')
    $series = [];
    $cursor = $start->copy();

    $prevScore = null; // for day-to-day delta
    $scoresForAvg = []; // only existing scores for average
    $best = null; // ['date' => ..., 'score' => ...]
    $worst = null;

    for ($i = 0; $i < 7; $i++) {
      $dateStr = $cursor->toDateString();

      // find row if present
      $row = $rows->get($dateStr);
      $score = $row ? (int)$row->score : null;

      // compute delta vs previous day (only if both scores exist)
      $delta = null;
      if ($score !== null && $prevScore !== null) {
        $delta = $score - $prevScore;
      }
      if ($score !== null) {
        $prevScore = $score;
      }

      // track best / worst among existing scores
      if ($score !== null) {
        $scoresForAvg[] = $score;

        if ($best === null || $score > $best['score']) {
          $best = ['date' => $dateStr, 'score' => $score];
        }
        if ($worst === null || $score < $worst['score']) {
          $worst = ['date' => $dateStr, 'score' => $score];
        }
      }

      $series[] = [
        'date'  => $dateStr,
        'score' => $score, // can be null if no entry
        'delta' => $delta, // can be null for first or missing
      ];

      $cursor->addDay();
    }

    // average only over days that exist
    $avg = null;
    if (count($scoresForAvg) > 0) {
      $avg = (int) round(array_sum($scoresForAvg) / count($scoresForAvg));
    }

    // pass to view
    return view('dashboard', [
      'series' => $series,
      'avg'    => $avg,
      'best'   => $best,
      'worst'  => $worst,
      'max'    => (int) config('score.max_total_positive'), // for nice % context
    ]);
  }

  // show entries page for a given date
  public function entries($date)
  {
    // pass the date to the view to confirm routing works
    return view('entries', ['date' => $date]);
  }

  // show reports for a date range with optional weekends
  public function reports(Request $request)
  {
    // read filters from query
    // default: current month, exclude weekends
    $fromParam = $request->query('from');
    $toParam = $request->query('to');
    $includeWeekends = $request->boolean('weekends'); // true if on/1/true

    $today = \Illuminate\Support\Carbon::today();
    $from = $fromParam ? \Illuminate\Support\Carbon::parse($fromParam) : $today->copy()->startOfMonth();
    $to   = $toParam   ? \Illuminate\Support\Carbon::parse($toParam)   : $today->copy();

    // guard: ensure from <= to
    if ($from->gt($to)) {
      $tmp = $from;
      $from = $to;
      $to = $tmp;
    }

    // fetch rows in range
    $rows = \App\Models\Day::whereBetween('date', [$from->toDateString(), $to->toDateString()])
      ->orderBy('date', 'asc')
      ->get();

    // filter out weekends if not included
    if (!$includeWeekends) {
      $rows = $rows->filter(function ($row) {
        return !\Illuminate\Support\Carbon::parse($row->date)->isWeekend();
      });
    }

    // build plain array of days
    $days = [];
    foreach ($rows as $row) {
      $days[] = [
        'date' => $row->date,
        'score' => (int)$row->score,
        'breakdown' => $row->score_breakdown ? json_decode($row->score_breakdown, true) : null,
      ];
    }

    // basic stats
    $loggedCount = count($days);
    $avg = null;
    $best = null; // ['date' => ..., 'score' => ...]
    $worst = null;

    if ($loggedCount > 0) {
      $sum = 0;
      foreach ($days as $d) {
        $sum += $d['score'];
        if ($best === null || $d['score'] > $best['score']) {
          $best = ['date' => $d['date'], 'score' => $d['score']];
        }
        if ($worst === null || $d['score'] < $worst['score']) {
          $worst = ['date' => $d['date'], 'score' => $d['score']];
        }
      }
      $avg = (int) round($sum / $loggedCount);
    }

    // category aggregation (avg points per recorded day)
    $categoryKeys = ['work','sleep','diet','activity','wife_time','social','substances','habits','chess','water'];
    $catSum = array_fill_keys($categoryKeys, 0);
    $catAvg = array_fill_keys($categoryKeys, null);

    if ($loggedCount > 0) {
      foreach ($days as $d) {
        $bd = is_array($d['breakdown']) ? $d['breakdown'] : [];
        foreach ($categoryKeys as $k) {
          if (isset($bd[$k])) {
            $catSum[$k] += (int)$bd[$k];
          }
        }
      }
      foreach ($categoryKeys as $k) {
        $catAvg[$k] = (int) round($catSum[$k] / $loggedCount);
      }
    }

    // weak spots = categories with the lowest avg
    // we show the bottom 3 (most negative / najsłabsze)
    $sorted = $catAvg;
    asort($sorted); // ascending: most negative first
    $weakSpots = array_slice($sorted, 0, 3, true);

    // labels for view
    $labels = [
      'work' => 'Work',
      'sleep' => 'Sleep',
      'diet' => 'Diet',
      'activity' => 'Activity',
      'wife_time' => 'Time With Wife',
      'social' => 'Social Media',
      'substances' => 'Substances',
      'habits' => 'Habits',
      'chess' => 'Chess',
      'water' => 'Water',
    ];

    return view('reports', [
      'from' => $from->toDateString(),
      'to'   => $to->toDateString(),
      'includeWeekends' => $includeWeekends,

      'days' => $days,
      'loggedCount' => $loggedCount,
      'avg' => $avg,
      'best' => $best,
      'worst' => $worst,

      'catAvg' => $catAvg,
      'weakSpots' => $weakSpots,
      'labels' => $labels,

      'max' => (int) config('score.max_total_positive'),
    ]);
  }
}
