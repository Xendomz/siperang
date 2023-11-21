<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">SIPERANG</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Request::is('/') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>
            @if (auth()->user()->isHasOutlet)
            <li class="menu-header">Item Page</li>
            <li class="{{ Request::is('supplier') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('supplier.index') }}"><i class="fas fa-truck-field"></i> <span>Supplier</span></a>
            </li>
            <li class="{{ Request::is('kategori') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('kategori.index') }}"><i class="fas fa-list"></i> <span>Kategori Barang</span></a>
            </li>
            <li class="{{ Request::is('barang') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('barang.index') }}"><i class="fas fa-box-open"></i> <span>Barang</span></a>
            </li>
            <li class="{{ Request::segment(1) == 'transaksi' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('transaksi.index') }}"><i class="fas fa-cash-register"></i> <span>Transaksi</span></a>
            </li>
            @endif
            @if (auth()->user()->isHasOutlet)
            @if (auth()->user()->isAdmin || auth()->user()->isOwner)
            <li class="menu-header">Settings</li>
            <li class="{{ Request::is('user') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('user.index') }}"><i class="fas fa-users"></i> <span>User Management</span></a>
            </li>
            @endif
            @endif
            @if (auth()->user()->isAdmin)
            <li class="{{ Request::is('outlet') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('outlet.index') }}"><i class="fas fa-shop"></i> <span>Outlet</span></a>
            </li>
            @endif
        </ul>
    </aside>
</div>
