
            <aside class="main-sidebar">
                <section class="portal-info">
                    <div class="portal-logo" img="{{ asset(ClinicInfo::image()) }}"></div>
                    <div class="portal-name-block">
                        <p class="portal-label">Portal Name</p>
                        <p class="portal-name">{{ ClinicInfo::name() }}</p>
                    </div>
                </section>
                <section class="sidebar-wrapper">
                <section class="sidebar">
                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    <?php 
                        $sidebar_outer = Sidebar::get();
                    ?>

            @foreach($sidebar_outer as $sidebar)
                <li class="header">{{ $sidebar["header"] }}</li>
                @foreach($sidebar["pages"] as $item)
                    @if( count($item["restrictions"]) == 0 || (Auth::check() && in_array(Auth::user()->role, $item["restrictions"])) )
                        <li class="{{ count($item['pages']) > 0 ? 'treeview' : '' }} {{ Request::is($item['prefix'].'*') || Request::is($item['url']) ? 'active' : '' }} ">
                            <a href="{{ $item['url'] }}">
                                @if(isset($item['icon']))
                                <span class="sidebar-icon"><img src="/lib/icons/shcreps_{{$item['icon']}}.svg"></span>
                                @endif
                                <span class="sidebar-label">{{ $item['label'] }}</span>
                        @if(count($item['pages']) > 0)
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                        @endif
                            </a>
                        @if(count($item['pages']) > 0)
                            <ul class="treeview-menu">
                            @foreach($item['pages'] as $submenu)
                            @if( count($submenu["restrictions"]) == 0 || (Auth::check() && in_array(Auth::user()->role, $submenu["restrictions"])) )
                                <li class="{{ Request::is($item['prefix'].'/'.$submenu['url']) || Request::is($item['prefix'].'/'.$submenu['url'].'/*') ? 'active' : ''}}" menu="{{ $item['prefix'] . '-' . $submenu['url'] }}">
                                    <a href="{{ '/'.$item['prefix'].'/'.$submenu['url'] }}">
                                        <i class="fa fa-angle-right"></i><span>{{ $submenu['label'] }}</span>
                                    </a>
                                </li>
                            @endif
                            @endforeach
                            </ul>
                        @endif
                        </li>
                    @endif
                @endforeach
            @endforeach
                </ul>
                </section>
                </section>
            </aside>