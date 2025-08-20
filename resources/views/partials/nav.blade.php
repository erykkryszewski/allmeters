<nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <!-- simple brand -->
    <a class="navbar-brand" href="{{ route('dashboard') }}">AllMeters</a>

    <ul class="navbar-nav ms-auto">
      <!-- dashboard link -->
      <li class="nav-item">
        <a
          class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
          href="{{ route('dashboard') }}"
        >Dashboard</a>
      </li>

      <!-- entries link (today as a quick example) -->
      <li class="nav-item">
        <a
          class="nav-link {{ request()->routeIs('entries') ? 'active' : '' }}"
          href="{{ route('entries', now()->toDateString()) }}"
        >Today</a>
      </li>

      <!-- reports link -->
      <li class="nav-item">
        <a
          class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}"
          href="{{ route('reports') }}"
        >Reports</a>
      </li>
    </ul>
  </div>
</nav>
