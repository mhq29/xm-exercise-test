<!DOCTYPE html>
<html>
<head>
	<!-- Add Bootstrap CSS via CDN -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Adding chart.js to show chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <!-- Adding moment.js for the conversion of timestamp -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> 
</head>
<body>
    <!-- Adding canvas to show chart -->
<canvas id="myChart"></canvas>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Date</th>
            <th>Open</th>
            <th>High</th>
            <th>Low</th>
            <th>Close</th>
            <th>Volume</th>
        </tr>
    </thead>
     <tbody>
        <!-- Looping through the historical data to display it in the table -->
        @foreach($historicalData as $data)
        <tr>
            @if (!empty($data))
            <td>{{ date('Y-m-d', $data['date']) }}</td>
            <td>{{ $data['open'] ?? '' }}</td>
            <td>{{ $data['high'] ?? '' }}</td>
            <td>{{ $data['low'] ?? '' }}</td>
            <td>{{ $data['close'] ?? '' }}</td>
            <td>{{ $data['volume'] ?? '' }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
</body>

<!-- Adding a script to show chart using chart.js -->
<script defer>
    // Getting the chart data from PHP variable and setting it in a JS variable
    var chartData = {!! json_encode($historicalData) !!} 
    
    var ctx = document.getElementById('myChart').getContext('2d'); //Getting the chart id
    
    // Creating a new chart object and updating it with the data
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(function(entry) {
                return moment(entry.date * 1000).format('MMM DD YYYY');
                
            }),
            datasets: [{
                label: 'Open Price',
                data: chartData.map(function(entry) {
                    return entry.open;
                }),
                borderColor: 'red',
                fill: false
            }, {
                label: 'Close Price',
                data: chartData.map(function(entry) {
                    return entry.close;
                }),
                borderColor: 'blue',
                fill: false
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Stock Prices'
            }
        }
    });
</script>

</html>