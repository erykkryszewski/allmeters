<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
  // show dashboard placeholder
  public function dashboard()
  {
    // no data yet, we only render a view
    return view('dashboard');
  }

  // show entries page for a given date
  public function entries($date)
  {
    // pass the date to the view to confirm routing works
    return view('entries', ['date' => $date]);
  }

  // show reports placeholder
  public function reports()
  {
    // later we will add a date range filter here
    return view('reports');
  }
}
