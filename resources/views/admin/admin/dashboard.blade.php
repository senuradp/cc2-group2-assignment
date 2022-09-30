@extends('admin.hotel.includes.dash')
@section('title', 'Dashboard')

@section('content')

    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h3>Dashboard</h3>
                <div>
                    <div id="ecommerce-dashboard-daterangepicker" class="btn btn-outline-light">
                        <i class="ti-calendar mr-2 text-muted"></i>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-3">
                                    Total Sales
                                </h6>
                                <h1 class="total_sales">0.00</h1>
                            </div>
                        </div>
                        <div id="sales"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="card-title mb-2">Hotel Sales</h6>
                            <div>
                                <a href="#" class="btn btn-outline-light btn-sm btn-floating mr-2">
                                    <i class="fa fa-refresh"></i>
                                </a>
                                <div class="dropdown">
                                    <a href="#" data-toggle="dropdown"
                                        class="btn btn-outline-light btn-sm btn-floating" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="monthly-sales"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title d-flex justify-content-between">
                            Sales
                           
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-3 total_sales">0</h2>
                            </div>
                            <div class="icon-block icon-block-xl icon-block-floating bg-secondary opacity-7">
                                <i class="ti-bar-chart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title d-flex justify-content-between">
                            Orders
                            
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-3" id="total_orders">0</h2>
                            </div>
                            <div class="icon-block icon-block-xl icon-block-floating bg-success opacity-7">
                                <i class="ti-package"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title d-flex justify-content-between">
                            Customers
                            
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-3" id="total_customers">0</h2>
                                
                            </div>
                            <div class="icon-block icon-block-xl icon-block-floating bg-warning opacity-7">
                                <i class="ti-user"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>

@endsection


@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function getData(start,end){
            return $.ajax({
                type: 'POST',
                url: '/site-admin/dashboard',
                data: {
                    'start_date': start,
                    'end_date': end,
                }
            });
        }

        $(document).ready(function() {

            $('#myTable').DataTable();


            var colors = {
                    primary: $('.colors .bg-primary').css('background-color').replace('rgb', '').replace(')', '')
                        .replace('(', '').split(','),
                    secondary: $('.colors .bg-secondary').css('background-color').replace('rgb', '').replace(')',
                        '').replace('(', '').split(','),
                    info: $('.colors .bg-info').css('background-color').replace('rgb', '').replace(')', '').replace(
                        '(', '').split(','),
                    success: $('.colors .bg-success').css('background-color').replace('rgb', '').replace(')', '')
                        .replace('(', '').split(','),
                    danger: $('.colors .bg-danger').css('background-color').replace('rgb', '').replace(')', '')
                        .replace('(', '').split(','),
                    warning: $('.colors .bg-warning').css('background-color').replace('rgb', '').replace(')', '')
                        .replace('(', '').split(','),
                },
                chartFontStyle = 'Fira Sans';

            var rgbToHex = function(rgb) {
                var hex = Number(rgb).toString(16);
                if (hex.length < 2) {
                    hex = "0" + hex;
                }
                return hex;
            };

            var fullColorHex = function(r, g, b) {
                var red = rgbToHex(r);
                var green = rgbToHex(g);
                var blue = rgbToHex(b);
                return red + green + blue;
            };

            colors.primary = '#' + fullColorHex(colors.primary[0], colors.primary[1], colors.primary[2]);
            colors.secondary = '#' + fullColorHex(colors.secondary[0], colors.secondary[1], colors.secondary[2]);
            colors.info = '#' + fullColorHex(colors.info[0], colors.info[1], colors.info[2]);
            colors.success = '#' + fullColorHex(colors.success[0], colors.success[1], colors.success[2]);
            colors.danger = '#' + fullColorHex(colors.danger[0], colors.danger[1], colors.danger[2]);
            colors.warning = '#' + fullColorHex(colors.warning[0], colors.warning[1], colors.warning[2]);

            $('#recent-orders').DataTable({
                lengthMenu: [5, 10],
                "columnDefs": [{
                    "targets": 5,
                    "orderable": false
                }]
            });

            var start = moment().subtract(29, 'days');
            var end = moment();

           

            function cb(start, end) {
                $('#ecommerce-dashboard-daterangepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end
                    .format('MMMM D, YYYY'));
                    getDataOnChange(start,end);
            }

            $('#ecommerce-dashboard-daterangepicker').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);
            
            cb(start, end);

            async function getDataOnChange(start,end){
                const res = await getData(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
                console.log(res);

                if(JSON.parse(res["avg_sales_datas"]).length!=0){
                    sales(JSON.parse(res["avg_sales_datas"]),JSON.parse(res["avg_sales_dates"]));
                    monthlySales(JSON.parse(res["avg_pkg_counts"]),JSON.parse(res["avg_pkg_names"]));
                    $('.total_sales').html("{{ env('DEFAULT_PRICE_TYPE_ID') }} "+parseFloat(res["total_sales"]).toFixed(2));
                    $('#total_orders').html(res["total_orders"]);
                    $('#total_customers').html(res["total_customers"]);
                }else{
                    sales([0],[0]);
                    monthlySales([0],[0]);
                    $('.total_sales').html("0");
                    $('#total_orders').html("0");
                    $('#total_customers').html("0");
                }
                
            }

            function sales(data,categories){
                var options = {
                    chart: {
                        type: 'bar',
                        fontFamily: chartFontStyle,
                        offsetX: -18,
                        height: 350,
                        width: '103%',
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Total',
                        data : data
                    }],
                    colors: [colors.primary, colors.info],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '50%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 8,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: categories
                    },
                    fill: {
                        opacity: 1
                    },
                    legend: {
                        position: "top",
                    },
                    tooltip: {
                        shared: false,
                        y: {
                            formatter: function(val) {
                                return '{{env('DEFAULT_PRICE_TYPE_ID')}} ' + val;
                            }
                        }
                    }
                };

                $("#sales").html("");

                var chart = new ApexCharts(document.querySelector("#sales"), options);

                chart.render();
            } 

            

            function monthlySales(series,labels) {
                var options = {
                    series: series,
                    chart: {
                        type: 'donut',
                        height: 320,
                        fontFamily: chartFontStyle,
                    },
                    labels: labels,
                    colors: [colors.primary, colors.success],
                    track: {
                        background: "#cccccc"
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        colors: [colors.primary, colors.success],
                    },
                    plotOptions: {
                        pie: {
                            expandOnClick: true,
                            donut: {
                                labels: {
                                    show: true,
                                    value: {
                                        formatter: function(val) {
                                            return  val;
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        shared: false,
                        y: {
                            formatter: function(val) {
                                return  val;
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                };

                $("#monthly-sales").html("");

                var chart = new ApexCharts(document.querySelector("#monthly-sales"), options);

                chart.render();
            }


            monthlySales();

        });
    </script>
@endsection
