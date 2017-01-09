    <!-- Main Header -->
            <header class="main-header">

                <!-- Logo -->
                <a href="/" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>:D</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>HealthPortal</b></span>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <!-- <span class="sr-only">Toggle navigation</span> -->
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <div class="ui secondary menu">
                            <!-- <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Client
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">TEST</li>
                                </ul>
                            </li>
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Inventory
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">TEST</li>
                                </ul>
                            </li>
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Services
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">TEST</li>
                                </ul>
                            </li> -->

                            <!-- User Account Menu -->
                            <a href="/account/profile" class="dropdown-toggle item" data-toggle="dropdown">
                                <span>Hello, 
                                    {{{ (Auth::check() ? Auth::user()->name : "user") }}}!
                                </span>
                            </a>
                            <!-- <a href="#" class="dropdown-toggle item sync-toggle">
                                <i class='fa fa-refresh'></i>
                                &nbsp;&nbsp;Sync
                            </a> -->
                            <a href="{{ url('/logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <button class='ui icon button headerbutton'>
                                    <i class="fa fa-sign-out"></i>
                                </button>
                            </a>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </nav>
            </header>