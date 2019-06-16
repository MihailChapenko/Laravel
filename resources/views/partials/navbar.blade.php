@hasrole('admin|super-admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/users') }}">Users</a>
    </li>
@endhasrole
@role('super-admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/clients') }}">Clients</a>
    </li>
@endrole
