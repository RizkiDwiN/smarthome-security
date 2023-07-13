<div class="header-divider"></div>
        <div class="container-fluid">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0 ms-2">
              <li class="breadcrumb-item">
                <!-- if breadcrumb is single--><span>Beranda</span>
              </li>
              <li class="breadcrumb-item active"><span>Peringatan</span></li>
            </ol>
          </nav>
        </div>
      </header>
      <div class="body flex-grow-1 px-3">
        <div class="container-lg">
          <div class="card mb-4">
            <div class="card-header"><strong>Peringatan</strong></div>
            <div class="card-body">
              <p class="text-medium-emphasis small">Data dari sensor yang di kirim ke database dan ditampilkan pada tabel ini.</p>
              <?php  
                $peringatan = $conn->query("SELECT * FROM sensordata ORDER BY reading_time DESC");
                $row = $peringatan->fetch_assoc();

                if ($row['value3'] == 1) {
                  echo '<div class="alert alert-warning" role="alert"> <strong>Waspada!</strong> ada pergerakan 🦹 di sekitar rumah anda, harap hati-hati.</div>';
                }

                if ($row['value4'] == 1) {
                  echo '<div class="alert alert-danger" role="alert"> <strong>Peringatan!</strong> terdeteksi 🔥 sekitar rumah anda, harap segera panggil hubungi pemadam.</div>';
                }
              ?>
              <div class="example">
                <div class="tab-content rounded-bottom">
                  <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-81">
                    <table id="myTable" class="display">
                      <thead>
                        <tr>
                          <th scope="col">No</th>
                          <th scope="col">Sensor</th>
                          <th scope="col">Deteksi</th>
                          <th scope="col">Sensor</th>
                          <th scope="col">Deteksi</th>
                          <th scope="col">Waktu</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php  
                            $no = 1;
                            $sql = $conn->query("SELECT * FROM sensordata ORDER BY reading_time DESC");
                            while($data = $sql->fetch_assoc()){
                        ?>
                        <tr>
                          <th scope="row"><?php echo $no++; ?></th>
                          <td><?php echo $data['sensor2']; ?></td>
                          <td><?php
                                if ($data['value3'] == 1) {
                                  echo "🦹 Terdeteksi pergerakan";
                                }else{
                                  echo "Tidak ada pergerakan";
                                }
                              ?></td>
                          <td><?php echo $data['sensor3']; ?></td>
                          <td><?php
                                if ($data['value4'] == 1) {
                                  echo "🔥 Terdeteksi";
                                }else{
                                  echo "🔥 Tidak Terdeteksi";
                                }
                              ?></td>
                          <td><?php echo $dibuat = date('d F Y | H:i', strtotime($data['reading_time'])); ?></td>
                        </tr>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>