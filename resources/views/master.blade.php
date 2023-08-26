<div class="row pl-4">
    <div class="col-md-6">
        <ul class="nav nav-pills card-header-pills">
            <li class="nav-item">
                <a href="{{ route('mountains.index') }}" class="nav-link {{ request()->routeIs('mountains.*') ? 'active' : '' }}">Mountains</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('peaks.index') }}" class="nav-link {{ request()->routeIs('peaks.*') ? 'active' : '' }}">Peaks</a>
            </li>
        </ul>
    </div>
</div>