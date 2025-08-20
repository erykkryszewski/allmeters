<!doctype html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'allmeters')</title>

    <!-- bootstrap css from cdn -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >

    <style>
      /* basic layout using bem */
      .layout {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }

      .layout__header {
        border-bottom: 1px solid #eeeeee;
      }

      .layout__content {
        flex: 1;
      }

      .layout__footer {
        border-top: 1px solid #eeeeee;
      }
    </style>
  </head>

  <body class="layout">
    <header class="layout__header">
      @include('partials.nav')
    </header>

    <main class="layout__content container py-4">
      @yield('content')
    </main>

    <footer class="layout__footer py-3 text-center text-muted">
      <small>AllMeters • v1.0</small>
    </footer>

    <!-- bootstrap js bundle from cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
