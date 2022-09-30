@extends('admin.admin.includes.dash')
@section('title', 'Tourist Bookings Graph')

@section('content')
    <!-- Content -->
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h3>Tourist Bookings Trend</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3">
                <label for="bookingDate" class="form-label">Select report year</label>
                <div class="datepicker-container datepicker-container-right">
                    <input type="text" name="bookingDate" id="bookingDate" placeholder="Choose your dates" required=""
                        class="form-control">
                </div>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="touristBookingsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        // print a line chart wiith the tourist bookings count for all the months of the year

        var ctx = document.getElementById('touristBookingsChart').getContext('2d');
        var datas = <?php echo json_encode($datas); ?>;
        console.log(datas);
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ],
                datasets: [{
                    label: 'Tourist Bookings',
                    data: datas,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var disabledDate = ['2022-09-06', '2022-09-08'];

        $('#bookingDate').daterangepicker({
            opens: 'left',
            minDate: moment().startOf('day'),
            isInvalidDate: function(ele) {
                var currDate = moment(ele._d).format('YYYY-MM-DD');
                return ["2022-09-09"].indexOf(currDate) != -1 || ["2022-09-11"].indexOf(currDate) != -1;
            },
            locale: {
                format: 'YYYY-MM-DD'
            }

        }, function(start, end, label) {
            $('input[name="startDate"]').val(start.format('YYYY-MM-DD'));
            $('input[name="endDate"]').val(end.format('YYYY-MM-DD'));
            // swal("A new date selection was made", start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'), "success")

        });
    </script>
@endsection
