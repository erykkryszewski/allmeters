@extends('layouts.app')

@section('title', 'Entries')

@section('content')
  <div class="entries">
    <h1 class="entries__title h3 mb-3">Daily Form</h1>

    <!-- flash message -->
    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif

    <!-- simple form posting to entries.store -->
    <form class="entries-form" method="post" action="{{ route('entries.store', $date) }}">
      @csrf

      <div class="mb-3">
        <label class="form-label" for="work_hours">Work hours</label>
        <input class="form-control" type="number" step="1" min="0" max="16" id="work_hours" name="work_hours" required>
        @error('work_hours')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label" for="sleep_hours">Sleep hours</label>
        <input class="form-control" type="number" step="0.5" min="0" max="16" id="sleep_hours" name="sleep_hours" required>
        @error('sleep_hours')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label" for="calories">Calories</label>
        <input class="form-control" type="number" step="1" min="0" max="6000" id="calories" name="calories">
        @error('calories')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="high_protein" name="high_protein" value="1">
        <label class="form-check-label" for="high_protein">High protein</label>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="low_fat" name="low_fat" value="1">
        <label class="form-check-label" for="low_fat">Low fat</label>
      </div>

      <button class="btn btn-primary" type="submit">Save</button>
    </form>

    {{-- simple score preview (optional) --}}
    @if (session('status'))
      <div class="alert alert-info mt-3">
        {{ session('status') }}
      </div>
    @endif

  </div>
@endsection
