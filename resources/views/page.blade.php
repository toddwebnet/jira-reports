@extends('layouts.template')

@section('title',  "This is my Title" )

@section('body')



    <title>Stacked Bar Chart</title>
    <script src="/js/chart.js/Chart.bundle.js"></script>



    <canvas id="myChart" width="400px" height="300px"></canvas>
    <script>
      jQuery(document).ready(function () {
        loadData();
      });

      function loadData() {
        endPoint = '/ajax/chart-data'
        jQuery.ajax({
          url: endPoint,
          type: "GET",
          cache: false,
          dataType: "json",
        }).done(function (data) {
          // alert(data.word);

          barChartData = {
            labels: ['January', 'February'],
            datasets: [{
              label: 'Open',
              backgroundColor: "#009900",
              data: [
                100,
                200,
              ]
            }, {
              label: 'Dataset 2',
              backgroundColor: "#990000",
              data: [
                299, 399
              ]
            }, {
              label: 'Dataset 3',
              backgroundColor: "#000099",
              data: [
                199, 29
              ]
            }]

          };

          drawChart(data);


        });
      }


      function drawChart(barChartData) {

        var ctx = document.getElementById('myChart').getContext('2d');
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
