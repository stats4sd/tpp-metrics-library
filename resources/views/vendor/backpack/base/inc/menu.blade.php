<!-- =================================================== -->
<!-- ========== Top menu items (ordered left) ========== -->
<!-- =================================================== -->
<ul class="nav navbar-nav d-md-down-none">

    @if (backpack_auth()->check())
        <!-- Topbar. Contains the left part -->
        @include(backpack_view('inc.topbar_left_content'))
    @endif

</ul>
<!-- ========== End of top menu left items ========== -->



<!-- ========================================================= -->
<!-- ========= Top menu right items (ordered right) ========== -->
<!-- ========================================================= -->
<ul class="nav navbar-nav ml-auto @if(config('backpack.base.html_direction') == 'rtl') mr-0 @endif">
    @if (backpack_auth()->guest())
        <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a>
        </li>
        @if (config('backpack.base.registration_open'))
            <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
        @endif
    @else
        <!-- Topbar. Contains the right part -->
        @include(backpack_view('inc.topbar_right_content'))

        <li class="nav-item pr-4">
            <a class="nav-link" href="{{ route('backpack.account.info') }}">My Account</a>
        </li>

        <!-- Logout button - tailored to use Laravel Breeze -->
        <li class="nav-item pr-4">
            <form method="POST" action={{ route('logout') }}>
            @csrf
            <button class="btn btn-link text-dark" type="submit"><i class="la la-lock"></i> {{ trans('backpack::base.logout') }}</button>
            </form>
        </li>

    @endif
</ul>
<!-- ========== End of top menu right items ========== -->
