<noscript>
    <style>.wrapper-main { display: none; }</style>
    <div class="noscriptmsg">We're sorry, but This Software doesn't work properly without JavaScript enabled.</div>
</noscript>

<header class="topbar clearfix">
    <nav class="navbar navbar-light app-topbar-nav">
        <div class="app-topbar-left">
            <div class="logo-container" style="min-width: 210px; height: 50px; background-color: rgba(255, 255, 255, 1); text-align:center">
                <a class="navbar-brand text-start" href="#">
                    <img src="{{ asset(config('madpos_ui.logo')) }}" alt="Zerithon" style="height: 30px !important;">
                </a>
            </div>

            <ul class="navbar-nav me-2">
                <li class="d-none d-md-block">
                    <a href="#" class="sidebar-toggle"><i class="icon-menu"></i></a>
                </li>
                <li class="d-md-none">
                    <a href="#" id="sidebar-toggle"><i class="icon-menu"></i></a>
                </li>
            </ul>
        </div>

        <div class="app-topbar-right">
            <ul class="navbar-nav ms-auto" style="display: flex; flex-direction: row;">

                        <li class="dropdown d-none d-sm-block">
                            <a href="#" class="dropdown-toggle topbar-hold-trigger" data-bs-toggle="dropdown" role="button" aria-expanded="false" title="Notifications">
                                <i class="icon-bell"></i>
                                <span class="hold-badge">3</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end hold-notification-dropdown" role="menu">
                                <div class="hold-notification-header">
                                    Notifications (3)
                                </div>
                                <div class="hold-notification-list">
                                    <a href="#" class="hold-notification-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Leave Request</strong>
                                            <small class="text-muted">Now</small>
                                        </div>
                                        <div class="small text-muted">
                                            New leave request submitted by employee.
                                        </div>
                                    </a>

                                    <a href="#" class="hold-notification-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Attendance Alert</strong>
                                            <small class="text-muted">10 min ago</small>
                                        </div>
                                        <div class="small text-muted">
                                            5 employees are late today.
                                        </div>
                                    </a>

                                    <a href="#" class="hold-notification-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Payroll Reminder</strong>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                        <div class="small text-muted">
                                            Payroll generation is pending for this month.
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>

                        <li style="margin-top: 8px;" class="d-none d-md-block">
                            <select class="form-control">
                                <option>English</option>
                                <option>Bangla</option>
                                <option>Hindi</option>
                                <option>French</option>
                            </select>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="user-img float-start">
                                    <img alt="user" src="{{ asset('assets/img/user/default.jpg') }}">
                                </span>
                            </a>

                            <div class="dropdown-menu topbar-dropdown-wrapper" role="menu">
                                <ul class="dropdown-user-inner">
                                    <li>
                                        <div class="dd-userbox">
                                            <div class="dd-img">
                                                <img alt="user" src="{{ asset('assets/img/user/default.jpg') }}">
                                            </div>
                                            <div class="dd-info">
                                                <h4>{{ auth()->user()->name ?? 'User' }}</h4>
                                                <p>{{ auth()->user()->email ?? '' }}</p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="divider"></li>
                                    <li><a href="{{ route('password.request') }}"><i class="icon-lock mr10"></i> Change Password</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:block; margin:0;">
                                            @csrf
                                            <button type="submit" style="border:none; background:none; width:100%; text-align:left; padding:10px 20px; color:inherit;">
                                                <i class="icon-logout mr10"></i> Sign Out
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>

            </ul>
        </div>
    </nav>
</header>
