<h3>Point Summaries</h3>
<table class="table-bordered" style="width: 80%">
    <tr>
        <th>Start</th>
        <th>Min</th>
        <th>Max</th>
        <th>End</th>
    </tr>
    <tr>
        <td>{{ $pointSummary['startPoints'] }}</td>
        <td>{{ $pointSummary['minPoints'] }}</td>
        <td>{{ $pointSummary['maxPoints'] }}</td>
        <td>{{ $pointSummary['endPoints'] }}</td>
    </tr>
</table>
<table class="table-bordered" style="width: 80%">
    <h3>Avg Days in Status</h3>
    <tr>
        @foreach($daysInStatus as $status => $avgDays)
            <td><b>{{ $status }}: {{ $avgDays }}</b></td>
        @endforeach
    </tr>
</table>
