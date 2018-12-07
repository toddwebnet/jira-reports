@extends('layouts.template')

@section('title',  "JIRA - Reports" )

@section('body')



    <title>Stacked Bar Chart</title>
    <script src="/js/chart.js/Chart.bundle.js"></script>



    <canvas id="myChart-PE" width="100%" height="40%"></canvas>
<div id="myChart-PE-summary"></div>

    <script>
      jQuery(document).ready(function () {
        loadData('PE');
      });

      function loadData(project) {
        endPoint = '/ajax/chart-data/' + project
        jQuery.ajax({
          url: endPoint,
          type: "GET",
          cache: false,
          dataType: "json",
        }).done(function (data) {
          drawChart(data.chart);
          $('#myChart-PE-summary').html(data.summaryData);
        });
      }


      function drawChart(barChartData) {

        var ctx = document.getElementById('myChart-'+barChartData.project).getContext('2d');
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
