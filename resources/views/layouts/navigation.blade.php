<nav class="valex-nav">
    @can('dashboard.view')
        <a href="{{ route('dashboard') }}" class="valex-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Dashboard</span>
        </a>
    @endcan
    @can('structures.view')
        <a href="{{ route('structures.index') }}" class="valex-nav-link {{ request()->routeIs('structures.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Structures</span>
        </a>
    @endcan
    @can('users.view')
        <a href="{{ route('users.index') }}" class="valex-nav-link {{ request()->routeIs('users.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Utilisateurs</span>
        </a>
    @endcan
    @can('inventory.view')
        <a href="{{ route('inventory.index') }}" class="valex-nav-link {{ request()->routeIs('inventory.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Inventaire</span>
        </a>
    @endcan
    @can('maintenance.view')
        <a href="{{ route('maintenance.index') }}" class="valex-nav-link {{ request()->routeIs('maintenance.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Maintenance</span>
        </a>
    @endcan
    @can('locations.view')
        <a href="{{ route('locations.index') }}" class="valex-nav-link {{ request()->routeIs('locations.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Locaux</span>
        </a>
    @endcan
    @can('equipments.view')
        <a href="{{ route('equipments.index') }}" class="valex-nav-link {{ request()->routeIs('equipments.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Équipements</span>
        </a>
    @endcan
    @can('assignments.view')
        <a href="{{ route('assignments.index') }}" class="valex-nav-link {{ request()->routeIs('assignments.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Affectations</span>
        </a>
    @endcan
    @can('tickets.view')
        <a href="{{ route('tickets.index') }}" class="valex-nav-link {{ request()->routeIs('tickets.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Tickets</span>
        </a>
    @endcan
    @can('intervenants.view')
        <a href="{{ route('intervenants.index') }}" class="valex-nav-link {{ request()->routeIs('intervenants.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Intervenants</span>
        </a>
    @endcan
    @can('licenses.view')
        <a href="{{ route('licenses.index') }}" class="valex-nav-link {{ request()->routeIs('licenses.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Licences</span>
        </a>
    @endcan
    @can('network.view')
        <a href="{{ route('network.ranges.index') }}" class="valex-nav-link {{ request()->routeIs('network.*') ? 'is-active' : '' }}">
            <span class="valex-nav-dot"></span>
            <span>Réseau</span>
        </a>
    @endcan
</nav>
