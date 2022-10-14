<div>
    <div class="card">
        <div class="card-content">
            <header class="">
                <nav class="navbar navbar-expand navbar-header ">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            {{-- <i class="bi bi-justify fs-3"></i> --}}
                            <i class="bi bi-justify fs-3" style="display: block;margin: -15px 0;"></i>

                        </a>

                        {{-- <button wire:click='edit'>asdfasdf</button> --}}

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <li class="nav-item dropdown me-1">
                                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-envelope bi-sub fs-4 text-gray-600"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <h6 class="dropdown-header">الإيميل</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="#">إيميل جديد</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-bell bi-sub fs-4 text-gray-600"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <h6 class="dropdown-header">الإشعارات</h6>
                                        </li>
                                        <li><a class="dropdown-item">لا يوجد إشعارات</a></li>
                                    </ul>
                                </li>
                            </ul>


                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                                            <p class="mb-0 text-sm text-gray-600">المدير</p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="{{ asset("assets/images/faces/2.jpg") }}">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                                    style="min-width: 11rem;">
                                    <li>
                                        <h6 class="dropdown-header">أهلا بك ! {{ auth()->user()->name }}</h6>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item" wire:click.prevent='edit()'
                                            data-bs-toggle="modal" data-bs-target="#editProfileModal">الحساب</a>

                                    </li>
                                    {{-- <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                            Settings</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-wallet me-2"></i>
                                            Wallet</a></li>
                                    <li> --}}
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="/logout"><i
                                                class="icon-mid bi bi-box-arrow-left me-2"></i>
                                            تسجيل الخروج</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
        </div>

        @include('livewire.admin_profile.profile')

    </div>

</div>
