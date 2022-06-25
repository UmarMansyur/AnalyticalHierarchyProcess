<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Perbandingan Kriteria</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Perbandingan Kriteria</div>
                <p class="card-title-desc">Pilih salah satu kriteria dan dilanjutkan dengan mengisi nilai pada kriteria pada form</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th colspan="2">Pilih yang lebih penting</th>
                                <th>Nilai perbandingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="" method="POST">
                                <?php
                                $variable = $conn->query("SELECT * FROM tb_criteria");
                                $list = [];
                                while ($array = mysqli_fetch_assoc($variable)) {
                                    array_push($list, $array);
                                }
                                $a = 0;
                                for ($data = 0; $data < 3; $data++) :
                                    for ($baru = $data + 1; $baru < 3; $baru++) :
                                        $a++;
                                ?>

                                        <tr class="text-center">
                                            <td>
                                                <div class="form-check">
                                                    <input type="hidden" name="id_kiri<?= $a ?>" value="<?= $list[$data]['criteria_id']  ?>">
                                                    <input checked class="form-check-input" type="radio" name="kriteria_radio<?= $a ?>" id="kriteria_radio<?= $a ?>" value="<?= $list[$data]['criteria_id'] ?>">
                                                    <label class="form-check-label" for="kriteria_radio<?= $a ?>">
                                                        <?= $list[$data]['description']  ?>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input type="hidden" name="id_kanan<?= $a ?>" value="<?= $list[$baru]['criteria_id']  ?>">
                                                    <input class="form-check-input" type="radio" name="kriteria_radio<?= $a ?>" id="a_radio<?= $a ?>" value="<?= $list[$baru]['criteria_id'] ?>">
                                                    <label class="form-check-label" for="a_radio<?= $a ?>">
                                                        <?= $list[$baru]['description'] ?>
                                                    </label>
                                                </div>

                                            </td>
                                            <td class="px-3">
                                                <input type="text" class="form-control" name="nilai_kriteria<?= $a ?>">
                                            </td>
                                        </tr>
                                    <?php endfor ?>
                                <?php endfor ?>
                                <tr class="">
                                    <td colspan="2"></td>
                                    <td class="d-grid"><button type="submit" class="btn btn-primary font-size-18" name="submit"><i class="dripicons-arrow-right"></i></button></td>
                                </tr>
                            </form>
                            <?php
                            if (isset($_POST['submit'])) {
                                for ($i = 1; $i <= 3; $i++) {
                                    $priority = $_POST['kriteria_radio' . $i];
                                    $criteria_1 = $_POST['id_kiri' . $i];
                                    $criteria_2 = $_POST['id_kanan' . $i];
                                    $value = $_POST['nilai_kriteria' . $i];
                                    if ($priority == $criteria_1) {
                                        $query = $conn->query("INSERT INTO tb_criteria_comparison VALUES (NULL, $criteria_1, $criteria_2, $priority, $value, '$_SESSION[id]')");
                                        $value = 1 / $value;
                                        $query = $conn->query("INSERT INTO tb_criteria_comparison VALUES (NULL, $criteria_1, $criteria_2, $criteria_2, $value, '$_SESSION[id]')");
                                    } else {
                                        $query = $conn->query("INSERT INTO tb_criteria_comparison VALUES (NULL, $criteria_1, $criteria_2, $priority, $value, '$_SESSION[id]')");
                                        $value = 1 / $value;
                                        $query = $conn->query("INSERT INTO tb_criteria_comparison VALUES (NULL, $criteria_1, $criteria_2, $criteria_1, $value, '$_SESSION[id]')");
                                    }
                                }
                            }
                            $criteria1 = $conn->query("SELECT * FROM `tb_criteria_comparison` WHERE criteria_1 = priority");
                            $criteria2 = $conn->query("SELECT * FROM `tb_criteria_comparison` WHERE criteria_2 = priority");
                            $criteria_kiri = [];
                            while ($getData = mysqli_fetch_assoc($criteria1)) {
                                array_push($criteria_kiri, $getData['value']);
                            }
                            $criteria_kanan = [];
                            while ($getData = mysqli_fetch_assoc($criteria2)) {
                                array_push($criteria_kanan, $getData['value']);
                            }
                            $perbandingan = [];
                            $atas = 0;
                            $bawah = 0;
                            for ($i = 0; $i < 3; $i++) {
                                for ($ia = 0; $ia < 3; $ia++) {
                                    if ($i == $ia) {
                                        $perbandingan[$i][$ia] = 1;
                                    } elseif ($ia > $i) {
                                        $perbandingan[$i][$ia] = $criteria_kiri[$atas];
                                        $atas++;
                                    } elseif ($ia < $i) {
                                        $perbandingan[$i][$ia] = $criteria_kanan[$bawah];
                                        $bawah++;
                                    }
                                }
                            }
                            $temp_jumlah = [];
                            $table_normal = [];
                            for ($baris = 0; $baris < 3; $baris++) {
                                $jumlah = 0;
                                for ($kolom = 0; $kolom < 3; $kolom++) {
                                    $jumlah = $jumlah + $perbandingan[$kolom][$baris];
                                }
                                array_push($temp_jumlah, $jumlah);
                            }
                            $kriteria = $conn->query("SELECT * FROM tb_criteria");
                            $temp_kriteria = [];
                            while ($getData = mysqli_fetch_assoc($kriteria)) {
                                array_push($temp_kriteria, $getData['criteria_id']);
                            }
                            for ($i = 0; $i < 3; $i++) {
                                for ($u = 0; $u < 3; $u++) {
                                    $table_normal[$i][$u] = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                    $nilainya = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                    $insert = $conn->query("INSERT INTO tb_normalisasi_kriteria VALUES (NULL, '$temp_kriteria[$i]', '$temp_kriteria[$u]', '$nilainya')");
                                }
                                echo "</br>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>