<?php
$route = Request::route()->getName();
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
        <img src="{{asset($settingArr['logo'])}}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><b>{{$settingArr['company_name']??''}}</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset(Auth::user()->profile_photo)}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="{{route('admin.dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            @lang('lang.DASHBOARD')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                </li>

                <li class="nav-item {{$route == 'user.index' || $route == 'user.create' || $route == 'role.index' ? "menu-open" :''}}">
                    <a href="#" class="nav-link {{$route == 'user.index' || $route == 'user.create' || $route == 'role.index' ? "active" :''}}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            @lang('lang.USER_MANAGEMENT')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('user.index')}}" class="nav-link {{$route == 'user.index' ? "active" :''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('lang.USER_LIST')</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('role.index')}}" class="nav-link {{$route == 'role.index' ? "active" :''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('lang.USER_ROLE')</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{$route == 'department.index' || $route == 'department.create' || $route == 'departmentVsUsers.index' ? "menu-open" :''}}">
                    <a href="#" class="nav-link {{$route == 'department.index' || $route == 'department.create' || $route == 'departmentVsUsers.index' ? "active" :''}}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            @lang('lang.DEPARTMENT_MANAGEMENT')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('department.index')}}" class="nav-link {{$route == 'department.index' ? "active" :''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('lang.DEPARTMENT')</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('departmentVsUsers.index')}}" class="nav-link {{$route == 'departmentVsUsers.index' ? "active" :''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('lang.DEPARTMENT_VS_USERS')</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{$route == 'buyer.index' || $route == 'buyer.create' || $route == 'buyerVsUsers.index' ? "menu-open" :''}}">
                    <a href="#" class="nav-link {{$route == 'buyer.index' || $route == 'buyer.create' || $route == 'buyerVsUsers.index' ? "active" :''}}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            @lang('lang.BUYER_MANAGEMENT')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('buyer.index')}}" class="nav-link {{$route == 'buyer.index' ? "active" :''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('lang.BUYER')</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('buyerVsUsers.index')}}" class="nav-link {{$route == 'buyerVsUsers.index' ? "active" :''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('lang.BUYER_VS_USERS')</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item {{$route == 'orderBooking.index' || $route == 'orderBooking.create'  ? "menu-open" :''}}">
                    <a href="#" class="nav-link {{$route == 'orderBooking.index' || $route == 'orderBooking.create' ? "active" :''}}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            @lang('lang.ORDER_BOOKING_MANAGEMENT')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('orderBooking.index')}}" class="nav-link {{$route == 'orderBooking.index' ? "active" :''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('lang.ORDER_BOOKING')</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{$route == 'setting.index'  ? "menu-open" :''}}"">
                    <a href="#" class="nav-link {{$route == 'setting.index' ? "active" :''}}">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            @lang('lang.SETTINGS')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('setting.index')}}" class="nav-link {{$route == 'setting.index' ? "active" :''}}">
                                <i class="fa fa-cogs nav-icon"></i>
                                <p>@lang('lang.GENERAL_SETTING')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit();return false;">
                                <i class="nav-icon fa fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
