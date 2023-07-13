 <?php  
    $sql = $conn->query("SELECT * FROM sensordata ORDER BY reading_time DESC");
    $data = $sql->fetch_assoc();
 ?>
 <div class="header-divider"></div>
        <div class="container-fluid">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0 ms-2">
              <li class="breadcrumb-item">
                <!-- if breadcrumb is single--><span>Beranda</span>
              </li>
              <li class="breadcrumb-item active"><span>Dashboard</span></li>
            </ol>
          </nav>
        </div>
      </header>
      <div class="body flex-grow-1 px-3">
        <div class="container-lg">
          <div class="row">
            <div class="col-sm-6 col-lg-3">
              <div class="card mb-4 text-white bg-primary">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fs-4 fw-semibold"><?php echo $data['value1'],'°C' ?></div>
                    <div>Suhu Saat Ini</div>
                  </div>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                  <canvas class="chart" id="card-chart1" height="70"></canvas>
                </div>
              </div>
            </div>
            <!-- /.col-->
            <div class="col-sm-6 col-lg-3">
              <div class="card mb-4 text-white bg-info">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fs-4 fw-semibold"><?php echo $data['value2'],'%' ?></div>
                    <div>Kelembaban saat ini</div>
                  </div>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                  <canvas class="chart" id="card-chart2" height="70"></canvas>
                </div>
              </div>
            </div>
            <!-- /.col-->
            <div class="col-sm-6 col-lg-3">
              <div class="card mb-4 text-white bg-warning">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fs-4 fw-semibold">
                      <?php
                         if ($data['value3'] == 1) {
                           echo "Pesan";
                         
                      ?>
                    </div>
                    <div><?php
                         
                           echo "<strong>Waspada!</strong>Terdapat gerakan mencurigakan";
                         }else{
                           echo "Tidak ada pergerakan terdeteksi";
                         }
                      ?></div>
                  </div>
                </div>
                <div class="c-chart-wrapper mt-3" style="height:70px;">
                  <canvas class="chart" id="card-chart3" height="70"></canvas>
                </div>
              </div>
            </div>
            <!-- /.col-->
            <div class="col-sm-6 col-lg-3">
              <div class="card mb-4 text-white bg-danger">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fs-4 fw-semibold"><?php
                         if ($data['value4'] == 1) {
                           echo "Pesan";
                         
                      ?></div>
                    <div><?php
                         
                           echo "<strong>Peringatan!</strong>Terdeteksi api di dalam rumah";
                         }else{
                           echo "Api tidak terdeteksi";
                         }
                      ?></div>
                  </div>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                  <canvas class="chart" id="card-chart4" height="70"></canvas>
                </div>
              </div>
            </div>
            <!-- /.col-->
          </div>
        <!-- /.row -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div>
                <h4 class="card-title mb-0">Traffic</h4>
                <div class="small text-medium-emphasis">January - July 2022</div>
              </div>
              <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                  <input class="btn-check" id="option1" type="radio" name="options" autocomplete="off">
                  <label class="btn btn-outline-secondary"> Day</label>
                  <input class="btn-check" id="option2" type="radio" name="options" autocomplete="off" checked="">
                  <label class="btn btn-outline-secondary active"> Month</label>
                  <input class="btn-check" id="option3" type="radio" name="options" autocomplete="off">
                  <label class="btn btn-outline-secondary"> Year</label>
                </div>
                <button class="btn btn-primary" type="button">
                  <svg class="icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cloud-download"></use>
                  </svg>
                </button>
              </div>
            </div>
            <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
              <canvas class="chart" id="sensor-chart" height="300"></canvas>
            </div>
          </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
    <?php

    // Mendapatkan data sensor dari database
    $se = "SELECT DATE(reading_time) AS reading_date, AVG(value1) AS avg_value1, AVG(value2) AS avg_value2 FROM sensordata GROUP BY DATE(reading_time)";
    $result = $conn->query($se);

    $sensorData = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $readingDate = date('Y-m-d', strtotime($row['reading_date']));
            $sensorData[$readingDate] = array(
                "reading_date" => $row["reading_date"],
                "avg_value1" => $row["avg_value1"],
                "avg_value2" => $row["avg_value2"]
            );
        }
    }

    // Menutup koneksi ke database
    $conn->close();
    ?>

    // Mengubah format data menjadi array terpisah untuk label dan nilai
    var labels = [];
    var values1 = [];
    var values2 = [];
    <?php
    foreach ($sensorData as $readingDate => $data) {
        echo "labels.push('" . $readingDate . "');";
        echo "values1.push(" . $data["avg_value1"] . ");";
        echo "values2.push(" . $data["avg_value2"] . ");";
    }
    ?>

    // Menggambar grafik menggunakan Chart.js
    var ctx = document.getElementById('sensor-chart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                    label: 'Suhu',
                    data: values1,
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Kelembaban',
                    data: values2,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Value'
                    }
                }
            }
        }
    });
</script>

          </div>
          
          </div>
          <!-- /.row-->
        </div>
      </div>