<div class="dashboard-nav d-flex flex-wrap align-items-center justify-content-between">
    <!-- Hambarger Remove Btn Start -->
    <div class="nav-left d-lg-none d-block">
        <div class="hambarger-btn">
            <i class="fas fa-bars"></i>
        </div>
    </div>
    @php
        $contactContent = getContent('contact.content', true);
    @endphp
    <div class="nav-left">
        <ul>
            <li>
                <i class="las la-headset"></i>
                @lang(' Support')
            </li>
            <li>
                <a href="mailto:{{ @$contactContent->data_values->support_email }}">
                    <i class="las la-envelope"></i>
                    {{ @$contactContent->data_values->support_email }}
                </a>
            </li>
        </ul>
    </div>

    <div class="nav-right">
        <ul class="prfile-menu">
            <li>
                <div class="user-profile">
                    <div class="dropdown">
                        <button class="btn dashboard-dropdown-button dropdown-toggle d-flex align-items-center "
                            type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="user-profile-meta">
                                <span class="name">{{ __(auth()->user()->fullname) }}</span>
                                <span class="meta-email">{{ auth()->user()->email }}</span>
                            </span>
                            <span class="ms-2 fs-4 text-white">
                                <i class="las la-angle-down"></i>
                            </span>
                        </button>
                        <ul class="dashboard-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile.setting') }}">
                                    <i class="fas fa-user"></i>
                                    @lang('Profile')
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.change.password') }}">
                                    <i class="fas fa-key"></i>
                                    @lang('Password')
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.logout') }}">
                                    <i class="fas fa-sign-out-alt"></i>
                                    @lang('Logout')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
