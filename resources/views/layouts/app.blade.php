<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم | شام</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons-1.9.1/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/vendors/toastr/toastr.min.css') }}">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <link rel="stylesheet" t ype="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    {{-- --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&family=Noto+Nastaliq+Urdu&display=swap"
        rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Harmattan&family=Tajawal&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">


    {{-- --}}
    @livewireStyles
</head>



<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo d-block m-auto" style="font-family: 'Noto Nastaliq Urdu', serif;">
                            <!-- <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo" srcset=""></a> -->
                            <h1 class="">شركة &nbsp; شام</h1>
                        </div>

                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>

                <div class="sidebar-menu">
                    <hr class="mx-5 mt-8">
                    <ul class="menu">
                        <li class="sidebar-item {{ request()->is('home') ? 'active' : '' }}">
                            <a href="/index.php/home" class='sidebar-link '>
                                <i class="bi bi-house-fill"></i>
                                <span>الرئيسية</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub">
                            <a href="/index/users" class='sidebar-link '>
                                <i class="bi bi-people-fill"></i>
                                <span>الوكلاء</span>
                            </a>
                            <ul
                                class="submenu {{ request()->is('/index/agents') || request()->is('/index/users') ? 'active' : '' }}">
                                <li class="submenu-item  ">
                                    <a href="/users"
                                        class="sidebar-link {{ request()->is('/index/users') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-person-check-fill {{ request()->is('/index/users') ? 'text-success' : '' }}"></i>
                                        <span>الوكلاء الرسمييين</span>
                                    </a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="/index/agents"
                                        class="sidebar-link {{ request()->is('/index/agents') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-person-fill {{ request()->is('/index/agents') ? 'text-success' : '' }}"></i>
                                        <span>الوكلاء الفرعيين</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub">
                            <a href="" class='sidebar-link '>
                                <i class="bi-currency-dollar"></i>
                                <span>أرصدة الوكلاء</span>
                            </a>
                            <ul
                                class="submenu {{ request()->is('/index/officialAgentBalance') || request()->is('/index/subAgentBalance') ? 'active' : '' }}">
                                <li class="submenu-item  ">
                                    <a href="/index.php/officialAgentBalance"
                                        class="sidebar-link {{ request()->is('/indexofficialAgentBalance') ? 'text-success' : '' }}">
                                        <i
                                            class="bi-currency-dollar {{ request()->is('/index/officialAgentBalance') ? 'text-success' : '' }}"></i>
                                        <span>أرصدة الوكلاء الرسميين</span>
                                    </a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="/index.php/subAgentBalance"
                                        class="sidebar-link {{ request()->is('/index/subAgentBalance') ? 'text-success' : '' }}">
                                        <i
                                            class="bi-currency-dollar {{ request()->is('/index/subAgentBalance') ? 'text-success' : '' }}"></i>
                                        <span>أرصدة الوكلاء الفرعيين</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item {{ request()->is('categories') ? 'active' : '' }}">
                            <a href="/categories" class='sidebar-link '>
                                <i class="bi bi-grid-fill"></i>
                                <span>الأقسام</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-handbag-fill"></i>
                                <span>المنتجات</span>
                            </a>
                            <ul
                                class="submenu {{ request()->is('products') || request()->is('stockedProducts') || request()->is('api-products') || request()->is('transferProducts') ? 'active' : '' }}">
                                <li class="submenu-item  ">
                                    <a href="/products"
                                        class="sidebar-link {{ request()->is('products') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-cart-check-fill {{ request()->is('products') ? 'text-success' : '' }}"></i>
                                        <span>المنتجات المخزونة</span>
                                    </a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="/stockedProducts"
                                        class="sidebar-link {{ request()->is('stockedProducts') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-bag-check-fill {{ request()->is('stockedProducts') ? 'text-success' : '' }}"></i>
                                        <span>إضافة منتجات مخزونة</span>
                                    </a>
                                </li>
                                <li class="submenu-item  ">
                                    <a href="/api-products"
                                        class="sidebar-link {{ request()->is('api-products') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-lightning-fill {{ request()->is('api-products') ? 'text-success' : '' }}"></i>
                                        <span>منتجات الـapi</span>
                                    </a>
                                </li>
                                <li class="submenu-item">
                                    <a href="/transferProducts"
                                        class="sidebar-link {{ request()->is('transferProducts') ? 'text-success' : '' }}">
                                        <i
                                            class="bi-hand-index-thumb {{ request()->is('transferProducts') ? 'text-success' : '' }}"></i>
                                        <span>منتجات التحويل اليدوي</span>
                                    </a>
                                </li>

                                <li class="submenu-item ">
                                    <a href="/productPrices"
                                        class="sidebar-link {{ request()->is('productPrices') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-cash {{ request()->is('productPrices') ? 'text-success' : '' }}"></i>
                                        <span> تحديد أسعار المنتجات</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item {{ request()->is('processTransferProducts') ? 'active' : '' }}">
                            <a href="/processTransferProducts"
                                class="sidebar-link {{ request()->is('processTransferProducts') ? 'text-success' : '' }}">
                                <i
                                    class="bi-lightning-charge-fill {{ request()->is('processTransferProducts') ? 'active' : '' }}"></i>
                                <span>معالجة منتجات التحويل</span>
                            </a>
                        </li>


                        <li class="sidebar-item {{ request()->is('returns') ? 'active' : '' }}">
                            <a href="/returns" class='sidebar-link '>
                                <i class="bi bi-reply-fill"></i>
                                <span>المرتجعات</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-cash"></i>
                                <span>تخصيص الأسعار</span>
                            </a>
                            <ul
                                class="submenu {{ request()->is('discounts') || request()->is('usersDiscounts') || request()->is('productsDiscounts') || request()->is('updateProductsDiscounts') ? 'active' : '' }}">
                                <li class="submenu-item  ">
                                    <a href="/discounts"
                                        class="sidebar-link {{ request()->is('discounts') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-cash {{ request()->is('discounts') ? 'text-success' : '' }}"></i>
                                        <span>مجموعة الأسعار</span>
                                    </a>
                                </li>
                                {{-- dont delete !imporatant --}}

                                {{-- <li class="submenu-item  ">
                                    <a href="/usersDiscounts"
                                        class="sidebar-link {{ request()->is('usersDiscounts') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-cash {{ request()->is('usersDiscounts') ? 'text-success' : '' }}"></i>
                                        <span> تخصيص الأسعار للمستخدمين</span>
                                    </a>
                                </li> --}}
                                <li class="submenu-item  ">
                                    <a href="/productsDiscounts"
                                        class="sidebar-link {{ request()->is('productsDiscounts') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-cash {{ request()->is('productsDiscounts') ? 'text-success' : '' }}"></i>
                                        <span> تخصيص لكافة المجموعات </span>
                                    </a>
                                </li>
                                <li class="submenu-item  ">
                                    <a href="/updateProductsDiscounts"
                                        class="sidebar-link {{ request()->is('updateProductsDiscounts') ? 'text-success' : '' }}">
                                        <i
                                            class="bi bi-cash {{ request()->is('updateProductsDiscounts') ? 'text-success' : '' }}"></i>
                                        <span>إدارة نسب أسعار الأقسام</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item {{ request()->is('sales') ? 'active' : '' }}">
                            <a href="/sales" class='sidebar-link '>
                                <i class="bi-receipt-cutoff"></i>
                                <span>المبيعات</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('messages') ? 'active' : '' }}">
                            <a href="/messages" class='sidebar-link '>
                                <i class="bi-envelope-fill"></i>
                                <span>الرسائل</span>
                        </a>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify" style="display: block;margin: -15px 0;"></i>
                </a>
            </header>

            <div class="page-content">
                @include('livewire.admin_profile.navbar')
                {{ $slot }}

                @yield('content')

            </div>
        </div>
    </div>



    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/mazer.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    @livewireScripts
    @stack('js')


    <script>
        function CopyToClipboard(id) {
            var r = document.createRange();
            r.selectNode(document.getElementById(id));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(r);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            console.log('ad');
        }


        function copy(id) {

            var copyText = document.getElementById(id);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "hideDuration": "1000",
                "timeOut": "1000",
                "positionClass": "toast-top-right",
                // "positionClass": "toast-bottom-center",
                "preventDuplicates": true,
                "preventOpenDuplicates": true
            }

            toastr.success('تم النسخ', '', {
                "iconClass": 'customer-info'
            });
        }




        window.addEventListener('hide-create-modal', event => {
            $('#createUserModal').modal('hide');
            $('#createCategoryModal').modal('hide');
            $('#createProductModal').modal('hide');
            $('#createStockedProductModal').modal('hide');
            $('#createPricesModal').modal('hide');
            $('#createMessageModal').modal('hide');
            $('#createAgentModal').modal('hide');
            $('#createBalanceModal').modal('hide');
            $('#withdrawBalanceModal').modal('hide');
            $('#createTransferProudctModal').modal('hide');
            $('#createMessageModal').modal('hide');

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "hideDuration": "2000",
                "timeOut": "2000",
                "positionClass": "toast-top-left",
                "preventDuplicates": true,
                "preventOpenDuplicates": true
            }
            toastr.success(event.detail.message);
        });


        window.addEventListener('hide-update-modal', event => {
            $('#updateUserModal').modal('hide');
            $('#updateCategoryModal').modal('hide');
            $('#updateProductModal').modal('hide');
            $('#updateStockedProductModal').modal('hide');
            $('#updatePricesModal').modal('hide');
            $('#showStockedProductModal').modal('hide');
            $('#updateTransferProudctModal').modal('hide');
            $('#editProfileModal').modal('hide');


            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "hideDuration": "2000",
                "timeOut": "2000",
                "positionClass": "toast-top-left",
            }
            toastr.info(event.detail.message);
        });


        window.addEventListener('hide-delete-modal', event => {
            $('#deleteUserModal').modal('hide');
            $('#deleteCategoryModal').modal('hide');
            $('#deleteProductModal').modal('hide');
            $('#deleteStockedProductModal').modal('hide');
            $('#deleteReturnsModal').modal('hide');
            $('#deleteMessageModal').modal('hide');
            $('#deleteAgentModal').modal('hide');
            $('#deleteStockedProductShowModal').modal('hide');
            $('#deleteTransferProudctModal').modal('hide');
            $('#showStockedProductModal').modal('hide');

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "hideDuration": "2000",
                "timeOut": "2000",
                "positionClass": "toast-top-left",
            }
            toastr.error(event.detail.message);
        });


        window.addEventListener('email-sended', event => {

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "hideDuration": "2000",
                "timeOut": "2000",
                "positionClass": "toast-top-left",
            }
            toastr.info(event.detail.message);
        });


        // disable button for a while
        $(".store-btn").click(function() {
            $('.store-btn').prop('disabled', true);

            setTimeout(function() {
                $('.store-btn').prop('disabled', false);
            }, 2000);
        });
    </script>
</body>

</html>
