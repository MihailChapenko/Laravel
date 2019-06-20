@if(auth()->user()->can('view users') || auth()->user()->can('crud users'))
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/users') }}">Users</a>
    </li>
@endif
@if(auth()->user()->can('view portfolios') || auth()->user()->can('crud portfolios'))
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/portfolio') }}">Portfolio</a>
    </li>
@endif
@if(auth()->user()->can('view clients') || auth()->user()->can('crud clients'))
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/clients') }}">Clients</a>
    </li>
@endif
