@can('view users')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/users') }}">Users</a>
    </li>
@endcan
@can('view portfolios')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/portfolio') }}">Portfolio</a>
    </li>
@endcan
@can('view clients')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/clients') }}">Clients</a>
    </li>
@endcan
