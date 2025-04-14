<?php
  include_once "../layout/header.php";
?>

<body class="hold-transition skin-blue sidebar-mini">
  <script src="chartjs/Chart.bundle.js"></script>
  <div class="wrapper">
    <?php include_once "../../_db/db_conn.php"; ?>
    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    
    
    <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <b>Interpretasi</b> Data
      </h1>
    </section>
      <section class="content">
      <form action="#" method="get">
      <!-- <label for="data">Data :</label> -->
        <!-- <select name="data" id="data" value="<?php //echo "$_GET[data]"?>">
            <option value="piksel">Piksel-Luas</option>
            <option value="hijau">Index Kehijauan</option>
            <option value="abu">Index Keabuan</option>
          </select>   -->
      <label for="mulai">Tanggal :</label>
          <input type="date" id="dari" name="dari"
                value="<?php echo "$_GET[dari]"?>"
                min="2023-07-17" max="2023-07-17">
        <label for="stop">Sampai :</label>
          <input type="date" id="sampai" name="sampai"
                value="<?php echo "$_GET[sampai]"?>"
                min="2023-07-17" max="2023-07-17">
        <input type="submit">

        <?php 
            $mulai = "$_GET[dari] 00:00:00";
            $selesai = "$_GET[sampai] 24:58:58";
            //$data = "$_GET[data]";
        ?>
    </form>  
        <?php include_once("main_header.php") ?>      
      </section>
    </div>  
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <div class="control-sidebar-bg"></div>
  </div>
<?php include_once "../layout/footer.php" ?>

<!--CHART MAKER-->
<?php  $sql = "SELECT
	waktu_climate AS waktu,
  rh AS rh
FROM
	ds_climate
  ";?>  


    <script>
        var randomColorFactor = function() {
            return Math.round(Math.random() * 255);
        };
        var randomColor = function(opacity) {
            return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
        };
        
        var config = {
            type: 'line',
            data: {
              //Label Nya disini label : []
                <?php
                if($result = $mysqli->query($sql)){
                  if($result->num_rows > 0){
                    echo "labels: [";
                      while($row = $result->fetch_array()){
                          echo "'$row[waktu]',";
                      }
                    echo "],";
                        $result->free();
                  } 
                }
              ?>
        
            //Dataset, terdapt 6 data suhu akar. 
                datasets: [{
                    label: "Relative Humidity",
                    <?php
                      if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                          echo "data: [";
                            while($row = $result->fetch_array()){
                                echo "'$row[rh]',";
                            }
                          echo "],";
                              $result->free();
                        } 
                      }
                    ?>
                    fill: false,
                    backgroundColor: ['rgba(10, 10, 225, 0.8)',],
                    borderColor: ['rgba(10, 10, 225, 0.8)',],
                    borderWidth: 2,
                    borderDash: [5, 0],
                },           
                //BATAS KODINGAN SUHU                
              ]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                hover: {
                    mode: 'label'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Waktu'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                                beginAtZero: false,
                                steps: 5,
                                stepValue: 5,
                                // max: 1500
                            },
                        scaleLabel: {
                            display: true,
                            labelString: 'Relative Humidity (%)'
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Relative Humidity Green House dari <?php echo "$_GET[dari] sampai $_GET[sampai]" ?>'
                }
            }
        };

//        $.each(config.data.datasets, function(i, dataset) {
//            var background = randomColor(0.5);
//            dataset.borderColor = background;
//            dataset.backgroundColor = background;
//            dataset.pointBorderColor = background;
//            dataset.pointBackgroundColor = background;
//            dataset.pointBorderWidth = 2;
//        });

        window.onload = function() {
            var ctx = document.getElementById("chart1").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };

        $('#randomizeData').click(function() {
            $.each(config.data.datasets, function(i, dataset) {
                dataset.data = dataset.data.map(function() {
                    return randomScalingFactor();
                });

            });

            window.myLine.update();
        });

        $('#addDataset').click(function() {
            var background = randomColor(0.8);
            var newDataset = {
                label: 'Dataset ' + config.data.datasets.length,
                borderColor: background,
                backgroundColor: background,
                pointBorderColor: background,
                pointBackgroundColor: background,
                pointBorderWidth: 1,
                fill: false,
                data: [],
            };

            for (var index = 0; index < config.data.labels.length; ++index) {
                newDataset.data.push(randomScalingFactor());
            }

            config.data.datasets.push(newDataset);
            window.myLine.update();
        });

        $('#addData').click(function() {
            if (config.data.datasets.length > 0) {
                var month = MONTHS[config.data.labels.length % MONTHS.length];
                config.data.labels.push(month);

                $.each(config.data.datasets, function(i, dataset) {
                    dataset.data.push(randomScalingFactor());
                });

                window.myLine.update();
            }
        });

        $('#removeDataset').click(function() {
            config.data.datasets.splice(0, 1);
            window.myLine.update();
        });

        $('#removeData').click(function() {
            config.data.labels.splice(-1, 1); // remove the label first

            config.data.datasets.forEach(function(dataset, datasetIndex) {
                dataset.data.pop();
            });

            window.myLine.update();
        });
</script>