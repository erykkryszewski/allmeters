@extends('layouts.app')

@section('title', 'entries')

@section('content')
  <div class="entries">
    <h1 class="entries__title h3 mb-3">daily form (placeholder)</h1>

    <!-- show date passed from the controller -->
    <p class="entries__info text-muted">
      date: <strong>{{ $date }}</strong>
    </p>

    <p class="entries__todo">
      the form will live here in the next chapter.
    </p>
  </div>
@endsection
