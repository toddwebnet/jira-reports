@extends('layouts.template')

@section('title',  "JIRA - Reports" )

@section('body')



    <title>Stacked Bar Chart</title>
    <script src="/js/chart.js/Chart.bundle.js"></script>
    <div id="UserList" style="text-align:center"></div>


    <canvas id="myChart-A" width="100%" height="40%"></canvas>
    <div id="myChart-A-summary"></div>

    <script>
      jQuery(document).ready(function () {
        loadUsers('A');
        loadData('A', '<?=$dude?>');
      });

      function selectDude(dude)
      {
        document.location = '/' + dude;
      }

      function loadUsers(project) {
        endPoint = '/ajax/user-data/' + project
        jQuery.ajax({
          url: endPoint,
          type: "GET",
          cache: false,
        }).done(function (data) {
          $('#UserList').html(data);
        });
      }

      function loadData(project, dude) {
        $('#myChart-A-summary').html('');
        endPoint = '/ajax/chart-data/' + project + '/' + dude
        jQuery.ajax({
          url: endPoint,
          type: "GET",
          cache: false,
          dataType: "json",
        }).done(function (data) {

          drawChart(data.chart);
          $('#myChart-A-summary').html(data.summaryData);
        });
      }


      function drawChart(barChartData) {

        var ctx = document.getElementById('myChart-' + barChartData.project).getContext('2d');
        window.myBar = new Chart(ctx, {
          type: 'bar',
          data: barChartData,
          options: {
            title: {
              display: true,
              text: barChartData.title
            },
            tooltips: {
              mode: 'index',
              intersect: false
            },
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
              }],
              yAxes: [{
                stacked: true
              }]
            }
          }
        });
      };
    </script>


@endsection
