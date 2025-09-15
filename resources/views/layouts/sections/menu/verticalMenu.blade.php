<!-- image-background -->
<aside id="layout-menu" class="layout-menu menu-vertical menu"
  style="background-image: url('{{ asset('assets/img/dswd-bg.png') }}');
         background-size: cover;
         background-repeat: no-repeat;
         color: #fff;">

  <!-- colored-background -->
  <!-- <aside id="layout-menu" class="layout-menu menu-vertical menu" style="background:#ff3131; color: #fff;"> -->



  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo mt-4">
    <a href="{{url('dashboard')}}" class="app-brand-link">
      <span class="app-brand-logo demo me-1">

        <img src="{{ asset('assets/img/logo-dswd.png') }}" alt="DSWD Logo" height="68" style="margin: 30px 0 30px 0;" />

      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="menu-toggle-icon d-xl-block align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

    {{-- adding active and open class if child is active --}}

    {{-- menu headers --}}
    @if (isset($menu->menuHeader))
    <li class="menu-header mt-7">
      <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
    </li>
    @else

    {{-- active menu method --}}
    @php
    $activeClass = null;
    $currentRouteName = Route::currentRouteName();

    if ($currentRouteName === $menu->slug) {
    $activeClass = 'active';
    }
    elseif (isset($menu->submenu)) {
    if (gettype($menu->slug) === 'array') {
    foreach($menu->slug as $slug){
    if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
    $activeClass = 'active open';
    }
    }
    }
    else{
    if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
    $activeClass = 'active open';
    }
    }
    }
    @endphp

    {{-- main menu --}}
    <li class="menu-item {{$activeClass}}">
      <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
        @isset($menu->icon)
        <i class="{{ $menu->icon }}"></i>
        @endisset
        <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
        @isset($menu->badge)
        <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
        @endisset
      </a>

      {{-- submenu --}}
      @isset($menu->submenu)
      @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
      @endisset
    </li>
    @endif
    @endforeach
  </ul>

</aside>
<style>
  /* General menu items and icons */
  .layout-menu .menu-inner .menu-item a,
  .layout-menu .menu-inner .menu-header-text,
  .layout-menu .menu-inner .menu-item i {
    color: #ffffff !important;
  }

  .layout-menu .menu-inner .menu-item a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #ffffff !important;
  }

  /* Menu bullets and indicators */
  .layout-menu .menu-item::before,
  .layout-menu .menu-item .menu-link::before {
    color: #ffffff !important;
    background-color: #ffffff !important;
  }

  /* Active/open menu item */
  .layout-menu .menu-item.active,
  .layout-menu .menu-item.open {
    border-left: 3px solid #ffffff !important;
  }

  .layout-menu .menu-item.active::before,
  .layout-menu .menu-item.open::before {
    background-color: #ffffff !important;
  }

  /* ------------------------------
   Dropdown Arrow (Restore & Style)
------------------------------- */
  .layout-menu .menu-toggle::after {
    content: '\203A';
    /* Unicode › */
    float: right;
    margin-left: auto;
    transition: transform 0.3s ease;
    color: #ffffff !important;
    font-size: 1.4rem;
  }

  /* Rotate arrow when open */
  .layout-menu .menu-item.open>.menu-toggle::after {
    transform: rotate(90deg);
    /* ▼ */
  }

  /* ------------------------------
   Submenu Styles
------------------------------- */
  .layout-menu .menu-sub {
    background-color: rgba(255, 255, 255, 0.05);
    padding-left: 1rem;
    border-left: 2px solid rgba(255, 255, 255, 0.33);
    transition: all 0.3s ease-in-out;
  }

  .layout-menu .menu-sub .menu-item a {
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 0.9rem;
    padding-left: 1.5rem;
  }

  .layout-menu .menu-sub .menu-item a:hover {
    background-color: rgba(255, 255, 255, 0.15);
    color: #ffffff !important;
  }

  .layout-menu .menu-sub .menu-item.active>a {
    color: #ffffff !important;
    font-weight: bold;
    border-left: 3px solid #ffffff;
    background-color: rgba(255, 255, 255, 0.1);
  }

  .layout-menu .menu-sub .menu-item {
    margin-bottom: 5px;
  }
</style>