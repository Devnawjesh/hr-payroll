<aside class="left-sidebar">
    <div class="slimscroll-left-sidebar">
        <nav class="sidebar-nav">
            @php
                $s = $sidebarState ?? [];
            @endphp
            <div class="sidebar-header text-center" style="padding: 25px 0; position: relative; top: 0;">
                <figure class="side-user-bg" style="background-image: url('assets/img/sidebar.jpg'); margin: 0; position: absolute; top: 0; right: 0; bottom: 0; left: 0; opacity: 0.2; background-size: cover; background-position: center center;">
                    <img src="assets/img/sidebar.jpg" alt="" style="display: none;">
                </figure>

                <img
                    src="assets/img/user/default.jpg"
                    alt="profile image"
                    style="width: 50px; height: 50px; border-radius: 50%; margin: 0 auto; object-fit: cover;"
                >

                <h5 class="text-center font-weight-medium" style="color: #ffffff; padding-bottom: 10px;">
                    HR Payroll User
                </h5>
            </div>

            <ul class="sidebar-menu">
                <li id="menu-dashboard" data-id="menu-dashboard" class="main {{ ($s['isDashboard'] ?? false) ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(($s['canDepartmentView'] ?? false) || ($s['canDepartmentCreate'] ?? false) || ($s['canDesignationView'] ?? false) || ($s['canDesignationCreate'] ?? false))
                    <li id="menu-employees" data-id="menu-employees" class="main {{ ($s['isEmployees'] ?? false) ? 'active' : '' }}">
                        <a class="has-arrow" href="#" aria-expanded="{{ ($s['isEmployees'] ?? false) ? 'true' : 'false' }}">
                            <i class="icon-user"></i>
                            <span>Employees</span>
                        </a>
                        <ul aria-expanded="{{ ($s['isEmployees'] ?? false) ? 'true' : 'false' }}">
                            <li><a href="#">Employee List</a></li>
                            <li><a href="#">Add Employee</a></li>
                            @if($s['canDepartmentView'] ?? false)
                                <li class="{{ request()->routeIs('departments.index') || request()->routeIs('departments.edit') ? 'active' : '' }}"><a href="{{ route('departments.index') }}">Departments</a></li>
                            @endif
                            @if($s['canDesignationView'] ?? false)
                                <li class="{{ request()->routeIs('designations.index') || request()->routeIs('designations.edit') ? 'active' : '' }}"><a href="{{ route('designations.index') }}">Designations</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                <li id="menu-attendance" data-id="menu-attendance" class="main">
                    <a class="has-arrow" href="#">
                        <i class="icon-clock"></i>
                        <span>Attendance</span>
                    </a>
                    <ul aria-expanded="true">
                        <li><a href="#">Attendance List</a></li>
                        <li><a href="#">Add Attendance</a></li>
                        <li><a href="#">Attendance Approvals</a></li>
                    </ul>
                </li>

                <li id="menu-leave" data-id="menu-leave" class="main">
                    <a class="has-arrow" href="#">
                        <i class="icon-calendar"></i>
                        <span>Leave Management</span>
                    </a>
                    <ul aria-expanded="true">
                        <li><a href="#">Leave List</a></li>
                        <li><a href="#">Apply Leave</a></li>
                        <li><a href="#">Leave Types</a></li>
                        <li><a href="#">Leave Approvals</a></li>
                    </ul>
                </li>

                <li id="menu-payroll" data-id="menu-payroll" class="main">
                    <a class="has-arrow" href="#">
                        <i class="icon-wallet"></i>
                        <span>Payroll</span>
                    </a>
                    <ul aria-expanded="true">
                        <li><a href="#">Payroll List</a></li>
                        <li><a href="#">Generate Payroll</a></li>
                        <li><a href="#">Salary Setup</a></li>
                        <li><a href="#">Allowances</a></li>
                        <li><a href="#">Deductions</a></li>
                    </ul>
                </li>

                <li id="menu-loan" data-id="menu-loan" class="main">
                    <a class="has-arrow" href="#">
                        <i class="icon-credit-card"></i>
                        <span>Loans</span>
                    </a>
                    <ul aria-expanded="true">
                        <li><a href="#">Loan List</a></li>
                        <li><a href="#">Add Loan</a></li>
                        <li><a href="#">Loan Approvals</a></li>
                    </ul>
                </li>

                <li id="menu-holiday" data-id="menu-holiday" class="main">
                    <a class="has-arrow" href="#">
                        <i class="icon-plane"></i>
                        <span>Holidays</span>
                    </a>
                    <ul aria-expanded="true">
                        <li><a href="#">Holiday List</a></li>
                        <li><a href="#">Add Holiday</a></li>
                    </ul>
                </li>

                <li id="menu-reports" data-id="menu-reports" class="main">
                    <a class="has-arrow" href="#">
                        <i class="icon-chart"></i>
                        <span>Reports</span>
                    </a>
                    <ul aria-expanded="true">
                        <li><a href="#">Attendance Report</a></li>
                        <li><a href="#">Leave Report</a></li>
                        <li><a href="#">Payroll Report</a></li>
                        <li><a href="#">Employee Report</a></li>
                    </ul>
                </li>

                @if($s['canUserManagementMenu'] ?? false)
                    <li id="menu-user-management" data-id="menu-user-management" class="main {{ ($s['isUserManagement'] ?? false) ? 'active' : '' }}">
                        <a class="has-arrow" href="#" aria-expanded="{{ ($s['isUserManagement'] ?? false) ? 'true' : 'false' }}">
                            <i class="icon-lock"></i>
                            <span>User Management</span>
                        </a>
                        <ul aria-expanded="{{ ($s['isUserManagement'] ?? false) ? 'true' : 'false' }}">
                            @if($s['canUserList'] ?? false)
                                <li class="{{ request()->routeIs('users.index') || request()->routeIs('users.edit') || request()->routeIs('users.approval') ? 'active' : '' }}"><a href="{{ route('users.index') }}">User List</a></li>
                            @endif
                            @if($s['canUserCreate'] ?? false)
                                <li class="{{ request()->routeIs('users.create') ? 'active' : '' }}"><a href="{{ route('users.create') }}">Add User</a></li>
                            @endif
                            @if(($s['canRoleView'] ?? false) || ($s['canRoleCreate'] ?? false) || ($s['canRoleUpdate'] ?? false) || ($s['canRoleAssign'] ?? false))
                                <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}"><a href="{{ route('roles.index') }}">Roles</a></li>
                            @endif
                            @if($s['canPermissionsMenu'] ?? false)
                                <li class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}"><a href="{{ route('permissions.index') }}">Permissions</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if($s['canSettingsView'] ?? false)
                    <li id="menu-settings" data-id="menu-settings" class="main {{ ($s['isSettings'] ?? false) ? 'active' : '' }}">
                        <a href="{{ route('settings.edit') }}">
                            <i class="icon-settings"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
