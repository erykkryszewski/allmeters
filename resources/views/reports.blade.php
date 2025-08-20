@extends('layouts.app')

@section('title', 'Reports')

@section('content')
  <div class="reports">
    <div class="row justify-content-center">
      <div class="col-12 col-xxl-10">

        {{-- header --}}
        <div class="mb-4">
          <h1 class="h2 mb-1">Reports</h1>
          <p class="text-muted mb-0">Range summary with average, best/worst and weak spots.</p>
        </div>

        {{-- filter card --}}
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <form class="row gy-3 gx-3 align-items-end" method="get" action="{{ route('reports') }}">
              <div class="col-sm-6 col-md-3">
                <label class="form-label fw-semibold" for="from">From</label>
                <input class="form-control" type="date" id="from" name="from" value="{{ $from }}">
              </div>
              <div class="col-sm-6 col-md-3">
                <label class="form-label fw-semibold" for="to">To</label>
                <input class="form-control" type="date" id="to" name="to" value="{{ $to }}">
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="form-check mt-4">
                  <input class="form-check-input" type="checkbox" id="weekends" name="weekends" value="1" {{ $includeWeekends ? 'checked' : '' }}>
                  <label class="form-check-label" for="weekends">Include Weekends</label>
                </div>
              </div>
              <div class="col-sm-6 col-md-3 d-grid">
                <button class="btn btn-primary" type="submit">Apply</button>
              </div>
            </form>
          </div>
        </div>

        {{-- top summary cards --}}
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-muted small text-uppercase mb-1">Average</div>
                <div class="display-6 mb-1">
                  @if ($avg !== null)
                    {{ $avg }} <span class="text-muted fs-6">/ {{ $max }}</span>
                  @else
                    —
                  @endif
                </div>
                <div class="text-muted small">based on saved days only</div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-muted small text-uppercase mb-1">Best</div>
                @if ($best)
                  <div class="h4 mb-1">{{ $best['score'] }} <span class="text-muted fs-6">/ {{ $max }}</span></div>
                  <div class="text-muted small">{{ \Illuminate\Support\Carbon::parse($best['date'])->format('D, d M Y') }}</div>
                @else
                  <div class="h4 mb-1">—</div>
                  <div class="text-muted small">no data</div>
                @endif
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-muted small text-uppercase mb-1">Worst</div>
                @if ($worst)
                  <div class="h4 mb-1">{{ $worst['score'] }} <span class="text-muted fs-6">/ {{ $max }}</span></div>
                  <div class="text-muted small">{{ \Illuminate\Support\Carbon::parse($worst['date'])->format('D, d M Y') }}</div>
                @else
                  <div class="h4 mb-1">—</div>
                  <div class="text-muted small">no data</div>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- weak spots --}}
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="h6 text-uppercase text-muted fw-semibold mb-3">Weak Spots (Avg Points / Day)</div>

            @if ($loggedCount === 0)
              <p class="text-muted mb-0">No data in selected range.</p>
            @else
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <th>Category</th>
                      <th class="text-end">Avg</th>
                      <th style="width: 45%;">Progress</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($weakSpots as $key => $val)
                      <tr>
                        <td>{{ $labels[$key] ?? $key }}</td>
                        <td class="text-end">{{ $val }}</td>
                        <td>
                          @php
                            // simple normalization for visual bar
                            // assume negative can go to -40 (other drugs); clamp to [-40, +30]
                            $min = -40;
                            $maxBar = 30;
                            $clamped = max($min, min($maxBar, (int)$val));
                            $percent = (int) round((($clamped - $min) / ($maxBar - $min)) * 100);
                          @endphp
                          <div class="progress" role="progressbar" aria-label="category avg">
                            <div class="progress-bar" style="width: {{ $percent }}%"></div>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>

        {{-- day list --}}
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="h6 text-uppercase text-muted fw-semibold mb-0">Days ({{ $loggedCount }})</div>
            </div>

            @if ($loggedCount === 0)
              <p class="text-muted mb-0">No entries saved in this range.</p>
            @else
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th class="text-end">Score</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($days as $d)
                      <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($d['date'])->format('D, d M Y') }}</td>
                        <td class="text-end">
                          <span class="fw-semibold">{{ $d['score'] }}</span>
                          <span class="text-muted small">/ {{ $max }}</span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>

        <div class="mt-3 d-flex gap-2">
          <a class="btn btn-outline-primary" href="{{ route('entries', now()->toDateString()) }}">Go To Today</a>
          <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">Back To Dashboard</a>
        </div>

      </div>
    </div>
  </div>

  <style>
    .card { border-radius: 0.75rem; }
  </style>
@endsection
