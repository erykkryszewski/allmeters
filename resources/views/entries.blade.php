@extends('layouts.app')

@section('title', 'Entries')

@section('content')
  <div class="entries">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8">

        {{-- page header --}}
        <div class="mb-4">
          <h1 class="h2 mb-1">Daily Form</h1>
          <p class="text-muted mb-0">Fill in today’s data. Keep it simple and consistent.</p>
        </div>

        {{-- flash message --}}
        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- main card --}}
        <div class="card shadow-sm">
          <div class="card-body">
            <form class="entries-form" method="post" action="{{ route('entries.store', $date) }}">
              @csrf

              {{-- core metrics --}}
              <div class="entries__section mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Core</h2>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-semibold" for="work_hours">Work Hours</label>
                    <input class="form-control" type="number" step="1" min="0" max="16" id="work_hours" name="work_hours" required>
                    @error('work_hours') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold" for="sleep_hours">Sleep Hours</label>
                    <input class="form-control" type="number" step="0.5" min="0" max="16" id="sleep_hours" name="sleep_hours" required>
                    @error('sleep_hours') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold" for="calories">Calories</label>
                    <input class="form-control" type="number" step="1" min="0" max="6000" id="calories" name="calories">
                    @error('calories') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold" for="water_level">Water</label>
                    <select class="form-select" id="water_level" name="water_level" required>
                      <option value="low">Low</option>
                      <option value="medium">Medium</option>
                      <option value="high">High</option>
                    </select>
                    @error('water_level') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>

              {{-- diet --}}
              <div class="entries__section mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Diet</h2>

                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="high_protein" name="high_protein" value="1">
                  <label class="form-check-label" for="high_protein">High Protein</label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="low_fat" name="low_fat" value="1">
                  <label class="form-check-label" for="low_fat">Low Fat</label>
                </div>
              </div>

              {{-- activity + social --}}
              <div class="entries__section mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Activity & Social</h2>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-semibold" for="activity_level">Activity</label>
                    <select class="form-select" id="activity_level" name="activity_level" required>
                      <option value="none">None</option>
                      <option value="short">Short (~30 min)</option>
                      <option value="long">Long (45+ min)</option>
                    </select>
                    @error('activity_level') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold" for="social_media_level">Social Media</label>
                    <select class="form-select" id="social_media_level" name="social_media_level" required>
                      <option value="ok">Ok</option>
                      <option value="medium">Medium</option>
                      <option value="high">High</option>
                    </select>
                    @error('social_media_level') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>

              {{-- relationships --}}
              <div class="entries__section mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Relationships</h2>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="wife_time" name="wife_time" value="1">
                  <label class="form-check-label" for="wife_time">Minimum 2 godziny czasu wolnego z Olą</label>
                </div>
              </div>

              {{-- habits --}}
              <div class="entries__section mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Habits</h2>

                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="meditation" name="meditation" value="1">
                      <label class="form-check-label" for="meditation">Meditation</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="reading" name="reading" value="1">
                      <label class="form-check-label" for="reading">Reading</label>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="smoking" name="smoking" value="1">
                      <label class="form-check-label" for="smoking">Smoking</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="alcohol" name="alcohol" value="1">
                      <label class="form-check-label" for="alcohol">Alcohol</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="other_drugs" name="other_drugs" value="1">
                      <label class="form-check-label" for="other_drugs">Other</label>
                    </div>
                  </div>
                </div>
              </div>

              {{-- chess --}}
              <div class="entries__section mb-4">
                <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Chess</h2>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-semibold" for="chess_games">Games 15+10</label>
                    <input class="form-control" type="number" step="1" min="0" max="20" id="chess_games" name="chess_games">
                    @error('chess_games') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="other_chess_games" name="other_chess_games" value="1">
                      <label class="form-check-label" for="other_chess_games">Any Other Games</label>
                    </div>
                  </div>
                </div>
              </div>


              <div class="d-flex justify-content-end">
                <button class="btn btn-primary" type="submit">Save</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <style>
    /* small view-specific tweaks, bem-friendly */
    .entries__section + .entries__section {
      border-top: 1px solid #eee;
      padding-top: 1rem;
    }
    .card {
      border-radius: 0.75rem;
    }
  </style>
@endsection
