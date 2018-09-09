@extends('layouts.template')

@section('title',  "This is my Title" )

@section('body')



    <title>Stacked Bar Chart</title>
    <script src="/js/chart.js/Chart.bundle.js"></script>



    <canvas id="myChart-PE" width="100%" height="40%"></canvas>

    <canvas id="myChart-TRIAGE" width="100%" height="40%"></canvas>

    <script>
      jQuery(document).ready(function () {
        loadData('PE');
        loadData('TRIAGE');
      });

      function loadData(project) {
        endPoint = '/ajax/chart-data/' + project
        jQuery.ajax({
          url: endPoint,
          type: "GET",
          cache: false,
          dataType: "json",
        }).done(function (data) {
          drawChart(data);
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
