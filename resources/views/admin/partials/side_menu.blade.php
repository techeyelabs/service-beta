
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="{{ asset('assets/images/user.png') }}" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ \Auth::guard('admin')->user()->name }}</div>
                    <div class="email">{{ \Auth::guard('admin')->user()->email }}</div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="{{request()->route()->getName() == 'admin-dashboard'?'active':''}}">
                        <a href="{{ route('admin-dashboard') }}">
                            <i class="material-icons">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @if($rights->service_list == 1)
                    <li class="{{request()->route()->getName() == 'admin-service-list'?'active':''}}">
                        <a href="{{ route('admin-service-list') }}">
                            <i class="material-icons">work</i>
                            <span>Service List</span>
                        </a>
                    </li>
                    @endif

                    @if($rights->total_sale == 1)
                    <li class="{{request()->route()->getName() == 'admin-sales-list'?'active':''}}">
                        <a href="{{ route('admin-sales-list') }}">
                            <i class="material-icons">list</i>
                            <span>Total Sale</span>
                        </a>
                    </li>
                    @endif

                    @if($rights->withdraw_request == 1)
                    <li class="{{request()->route()->getName() == 'admin-withdraw-list'?'active':''}}">
                        <a href="{{ route('admin-withdraw-list') }}">
                            <i class="material-icons">attach_money</i>
                            <span>Withdraw Request</span>
                        </a>
                    </li>
                    @endif
                    <hr>

                    @if($rights->buyers == 1)
                    <li class="{{request()->route()->getName() == 'admin-buyer-list'?'active':''}}">
                        <a href="{{ route('admin-buyer-list') }}">
                            <i class="material-icons">perm_identity</i>
                            <span>Buyers</span>
                        </a>
                    </li>
                    @endif

                    @if($rights->sellers == 1)
                    <li class="{{request()->route()->getName() == 'admin-seller-list'?'active':''}}">
                        <a href="{{ route('admin-seller-list') }}">
                            <i class="material-icons">account_box</i>
                            <span>Sellers</span>
                        </a>
                    </li>
                    @endif

                    @if($rights->affiliates == 1)
                    <li class="{{request()->route()->getName() == 'admin-affiliator-list'?'active':''}}">
                        <a href="{{ route('admin-affiliator-list') }}">
                            <i class="material-icons">assignment_ind</i>
                            <span>Affiliators</span>
                        </a>
                    </li>
                    @endif

                    <hr>
                    @if($rights->lottery == 1)
                    <li class="{{request()->route()->getName() == 'admin-lottery-list'?'active':''}}">
                        <a href="{{ route('admin-lottery-list') }}">
                            <i class="material-icons">perm_identity</i>
                            <span>Lottery</span>
                        </a>
                    </li>
                    @endif
                    <hr>

                    @if($rights->banners == 1)
                    <li class="{{request()->route()->getName() == 'admin-service-banner'?'active':''}}">
                        <a href="{{ route('admin-service-banner') }}">
                            <i class="material-icons">flag</i>
                            <span>Banners</span>
                        </a>
                    </li>
                    @endif

                    @if($rights->news == 1)
                    <li class="{{request()->route()->getName() == 'admin-notification-list'?'active':''}}">
                        <a href="{{ route('admin-notification-list') }}">
                            <i class="material-icons">error</i>
                            <span>News</span>
                        </a>
                    </li>
                    @endif

                    @if($rights->direct_message == 1)
                    <li class="{{request()->route()->getName() == 'admin-direct-message-list'?'active':''}}">
                        <a href="{{ route('admin-direct-message-list') }}">
                            <i class="material-icons">error</i>
                            <span>Direct Message</span>
                        </a>
                    </li>
                    @endif

                    @if($rights->contact_us == 1)
                    <li class="{{request()->route()->getName() == 'admin-contact-us'?'active':''}}">
                        <a href="{{ route('admin-contact-us') }}">
                            <i class="material-icons">error</i>
                            <span>Contact Us</span>
                        </a>
                    </li>
                    @endif






                    <hr/>
                    @if($rights->admin == 1)
                    <li class="{{request()->route()->getName() == 'admin-list'?'active':''}}">
                        <a href="{{ route('admin-list') }}">
                            <i class="material-icons">error</i>
                            <span>Admin</span>
                        </a>
                    </li>
                    @endif

                    @if($rights->add_admin == 1)
                    <li class="{{request()->route()->getName() == 'new-admin-add'?'active':''}}">
                        <a href="{{ route('new-admin-add') }}">
                            <i class="material-icons">error</i>
                            <span>Add Admin</span>
                        </a>
                    </li>
                    @endif


                    

                    

                    

                    {{--<li class="{{request()->route()->getName() == 'affiliator-application-list'?'active':''}}">
                        <a href="{{ route('affiliator-application-list') }}">
                            <i class="material-icons">how_to_reg</i>
                            <span>Affiliator Applications</span>
                        </a>
                    </li>--}}

                    <hr>


                    <li>
                        <a href="{{ route('admin-logout') }}">
                            <i class="material-icons">logout</i>
                            <span>Logout</span>
                        </a>
                    </li>



                    
                    {{-- <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">widgets</i>
                            <span>Settings</span>
                        </a>
                        <ul class="ml-menu">                             
                            <li>
                                <a href="{{ route('admin-category-list') }}">Categories</a>
                            </li>
                            <li>
                                <a href="{{ route('admin-purchase-history') }}">Purchase History</a>
                            </li>
                        </ul>
                    </li> --}}
                    
                   
                    
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2019 <a href="javascript:void(0);">share-work.jp</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.0.5
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        
    </section>

