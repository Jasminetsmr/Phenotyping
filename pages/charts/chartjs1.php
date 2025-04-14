<?php
  $active_menu = "chartjs";
  include_once "../layout/header.php";
?>

<body class="hold-transition skin-blue sidebar-mini">
  <script src="../../plugins/chartjs/Chart.min.js"></script>
  <div class="wrapper">
    <?php include_once "../../_db/db_conn.php"; ?>
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    <div class="content-wrapper">
      <section class="content">
        <?php include_once("chartjs/main_header.php") ?>      
      </section>
    </div>
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>

<script>
  $(function () {
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas);

    var areaChartData = {

      <?php  $sql = "SELECT
                        date_format( waktu, '%H %p' ) AS jam,
                        AVG( suhu_internal ) AS suhu_udara,
                        AVG( suhu_eksternal ) AS suhu_akar1 
                      FROM
                        db_kontrol_finv
                      WHERE
                        waktu >= '2022-10-22 00:00:00' 
                        AND waktu <= '2022-11-27 24:00:00' 
                      GROUP BY
                        date_format(
                        waktu,
                        '%H %p');
                ";?>
          
          <?php
          $i =1;                  
          if($result = $mysqli->query($sql)){
              if($result->num_rows > 0){
                echo "labels: [";
                  while($row = $result->fetch_array()){
                      echo "'$row[jam]',";
                      $i = $i+1;
                  }
                echo "],";
                    $result->free();
              } 
            }
          ?>

      datasets: [
      {
          label: "Batas Atas",
          fillColor: "rgba(255, 69, 0, 1.0)",
          strokeColor: "rgba(255, 69, 0, 1.0)",
          pointColor: "rgba(255, 69, 0, 1.0)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",

          <?php                   
          if($result = $mysqli->query($sql)){
              if($result->num_rows > 0){
                echo "data : [";
                  while($row = $result->fetch_array()){
                      echo "28,";
                  }
                echo "]";
                    $result->free();
              } 
            }
          ?>
        },
        {
          label: "Suhu Luar",
          fillColor: "rgba(210, 214, 222, 1)",
          strokeColor: "rgba(210, 214, 222, 1)",
          pointColor: "rgba(210, 214, 222, 1)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",

          <?php                  
          if($result = $mysqli->query($sql)){
              if($result->num_rows > 0){
                echo "data : [";
                  while($row = $result->fetch_array()){
                      echo "$row[suhu_udara],";
                  }
                echo "]";
                    $result->free();
              } 
            }
          ?>

        },
        {
          label: "Suhu Dalam",
          fillColor: "rgba(34, 139, 34, 1.0)",
          strokeColor: "rgba(34, 139, 34, 1.0)",
          pointColor: "rgba(34, 139, 34, 1.0)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",

          <?php                   
          if($result = $mysqli->query($sql)){
              if($result->num_rows > 0){
                echo "data : [";
                  while($row = $result->fetch_array()){
                      echo "$row[suhu_akar1],";
                  }
                echo "]";
                    $result->free();
              } 
            }
          ?>
        },
        
      ]
    };

<?php $mysqli->close();?>
    

    var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: true,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - Whether the line is curved between points
      bezierCurve: true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension: 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot: true,
      //Number - Radius of each point dot in pixels
      pointDotRadius: 5,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth: 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius: 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke: true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth: 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true
    };


    //Create the line chart
    areaChart.Line(areaChartData, areaChartOptions);

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    var lineChart = new Chart(lineChartCanvas);
    var lineChartOptions = areaChartOptions;
    lineChartOptions.datasetFill = false;
    lineChart.Line(areaChartData, lineChartOptions);

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    var barChart = new Chart(barChartCanvas);
    var barChartData = areaChartData;
    barChartData.datasets[1].fillColor = "#00a65a";
    barChartData.datasets[1].strokeColor = "#00a65a";
    barChartData.datasets[1].pointColor = "#00a65a";
    var barChartOptions = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: true,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - If there is a stroke on each bar
      barShowStroke: true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth: 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing: 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing: 1,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to make the chart responsive
      responsive: true,
      maintainAspectRatio: true
    };

    barChartOptions.datasetFill = false;
    barChart.Bar(barChartData, barChartOptions);
  });
</script>