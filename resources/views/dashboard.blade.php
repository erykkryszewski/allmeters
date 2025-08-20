@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <div class="dashboard">
    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">

        {{-- header --}}
        <div class="mb-4">
          <h1 class="h2 mb-1">Dashboard</h1>
          <p class="text-muted mb-0">Last 7 days overview with average and trend.</p>
        </div>

        {{-- top summary cards --}}
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-muted small text-uppercase mb-1">Average (7 Days)</div>
                <div class="display-6 mb-1">
                  @if ($avg !== null)
                    {{ $avg }}
                    <span class="text-muted fs-6">/ {{ $max }}</span>
                  @else
                    —
                  @endif
                </div>
                <div class="text-muted small">only days with saved entry are included</div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-muted small text-uppercase mb-1">Best Day</div>
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
                <div class="text-muted small text-uppercase mb-1">Worst Day</div>
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

        {{-- trend list --}}
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="h6 text-uppercase text-muted fw-semibold mb-0">7-Day Trend</div>
              {{-- simple legend --}}
              <div class="small text-muted">
                <span class="me-2">▲ up</span>
                <span>▼ down</span>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th class="text-end">Score</th>
                    <th class="text-end">Δ vs Prev</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($series as $item)
                    <tr>
                      <td>
                        {{ \Illuminate\Support\Carbon::parse($item['date'])->format('D, d M') }}
                      </td>
                      <td class="text-end">
                        @if ($item['score'] !== null)
                          <span class="fw-semibold">{{ $item['score'] }}</span>
                          <span class="text-muted small">/ {{ $max }}</span>
                        @else
                          <span class="text-muted">no entry</span>
                        @endif
                      </td>
                      <td class="text-end">
                        @if ($item['delta'] === null)
                          <span class="text-muted">—</span>
                        @else
                          @if ($item['delta'] > 0)
                            <span class="text-success">▲ +{{ $item['delta'] }}</span>
                          @elseif ($item['delta'] < 0)
                            <span class="text-danger">▼ {{ $item['delta'] }}</span>
                          @else
                            <span class="text-muted">0</span>
                          @endif
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>

        {{-- quick actions --}}
        <div class="d-flex gap-2">
          <a class="btn btn-outline-primary" href="{{ route('entries', now()->toDateString()) }}">Go To Today</a>
          <a class="btn btn-outline-secondary" href="{{ route('reports') }}">Open Reports</a>
        </div>

      </div>
    </div>
  </div>

  <style>
    /* view-only tweaks */
    .card { border-radius: 0.75rem; }
  </style>
@endsection
