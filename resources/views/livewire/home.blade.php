<div>



    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page"></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="">
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon blue">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">المستخدمين</h6>
                                <h6 class="font-extrabold mb-0">{{ $total_users }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon green">
                                    <i class="custom-statistic-card-icon bi bi-cash"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">المرابيح</h6>
                                <h6 class="font-extrabold mb-0">15500</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon blue">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">المستخدمين</h6>
                                <h6 class="font-extrabold mb-0">15500</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon blue">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">المستخدمين</h6>
                                <h6 class="font-extrabold mb-0">15500</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <div class="col-md-6 ">
                <label for="" class="d-block mb-5 chart-label">المستخدمين الجدد</label>

                <div id="chart" class="">
                </div>

            </div>

            <div class="col-md-6">
                <label for="" class="d-block mb-5 chart-label">
                    المستخدمين الجدد
                </label>
                <div id="chart1">
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        var options = {
            chart: {
                // type: 'bar',
                type: 'bar',
                height: '400px',
                // width: '500px',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,

                },
                defaultLocale: 'ar',
                locales: [{
                    name: 'ar',
                    options: {
                        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                            'September', 'October', 'November', 'December'
                        ],
                        shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                            'Nov', 'Dec'
                        ],
                        days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        toolbar: {
                            download: 'تحميل svg',
                            selection: 'Selection',
                            selectionZoom: 'Selection Zoom',
                            zoomIn: 'تكبير',
                            zoomOut: 'تصغير',
                            pan: 'Panning',
                            reset: 'Reset Zoom',
                        }
                    }
                }]
            },
            fill: {
                colors: ['#435EBE', '#E91E63', '#9C27B0']
            },
            plotOptions: {
                bar: {
                    borderRadius: 20
                }
            },
            series: [{
                name: ' المبيعات',
                data: @json($users_count)
            }],
            xaxis: {
                categories: @json($users)
            }
        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();
    </script>

    <script>
        var options = {
            chart: {
                type: 'area',
                height: '400px',
                defaultLocale: 'en',
                locales: [{
                    name: 'en',
                    options: {
                        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                            'September', 'October', 'November', 'December'
                        ],
                        shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                            'Nov', 'Dec'
                        ],
                        days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        toolbar: {
                            download: 'تحميل',
                            selection: 'تحديد',
                            selectionZoom: 'تحديد التكبير',
                            zoomIn: 'تكبير',
                            zoomOut: 'تصغير',
                            pan: 'Panning',
                            reset: 'إعادة الحجم الطبيعي',
                            menu: 'اللائحة',
                            DownloadSVG: 'asdfasdf'
                        }
                    }
                }]
            },
            series: [{
                name: 'المستخدمين الجدد',
                data: @json($users_count)
            }],
            xaxis: {
                categories: @json($users)
            }
        }

        var chart = new ApexCharts(document.querySelector("#chart1"), options);

        chart.render();
    </script>
@endpush
