<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Perbandingan alternatif berdasarkan <?= $_GET['sub'] ?></h4>
        </div>
    </div>
</div>
<?php if (isset($_GET['id_pengguna'])) : ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Ubah Perbandingan alternatif</div>
                    <p class="card-title-desc">Silahkan isi nilai kembali pada form di bawah</p>
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
                                    $variable = $conn->query("SELECT * FROM tb_alternatif");
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
                                                        <input type="hidden" name="id_kiri<?= $a ?>" value="<?= $list[$data]['alternatif_id']  ?>">
                                                        <input checked class="form-check-input" type="radio" name="alternatif_radio<?= $a ?>" id="alternatif_radio<?= $a ?>" value="<?= $list[$data]['alternatif_id'] ?>">
                                                        <label class="form-check-label" for="alternatif_radio<?= $a ?>">
                                                            <?= $list[$data]['description']  ?>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="hidden" name="id_kanan<?= $a ?>" value="<?= $list[$baru]['alternatif_id']  ?>">
                                                        <input class="form-check-input" type="radio" name="alternatif_radio<?= $a ?>" id="a_radio<?= $a ?>" value="<?= $list[$baru]['alternatif_id'] ?>">
                                                        <label class="form-check-label" for="a_radio<?= $a ?>">
                                                            <?= $list[$baru]['description'] ?>
                                                        </label>
                                                    </div>

                                                </td>
                                                <td class="px-3">
                                                    <input type="text" class="form-control" name="nilai_alternatif<?= $a ?>" required>
                                                </td>
                                            </tr>
                                        <?php endfor ?>
                                    <?php endfor ?>
                                    <tr class="">
                                        <?php
                                        $sudah = mysqli_fetch_assoc($conn->query("SELECT count(id_alternatif_comparison) as n FROM tb_alternatif_comparison WHERE id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'"));
                                        ?>
                                        <td colspan="2"></td>

                                        <td class="d-grid"><button type="submit" class="btn btn-warning font-size-18" name="ubah"><i class="dripicons-arrow-up"></i></button></td>
                                    </tr>
                                </form>
                                <?php
                                $banyak_alternatif = mysqli_fetch_assoc($conn->query("SELECT COUNT(alternatif_id) AS banyak FROM tb_alternatif"));
                                if (isset($_POST['ubah'])) {
                                    $hapus_alternatif = $conn->query("DELETE FROM tb_alternatif_comparison WHERE id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]' ");
                                    $hapus_alternatif = $conn->query("DELETE FROM tb_normalisasi_alternatif WHERE id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'");
                                    for ($i = 1; $i <= $banyak_alternatif['banyak']; $i++) {
                                        $priority = $_POST['alternatif_radio' . $i];
                                        $alternatif_1 = $_POST['id_kiri' . $i];
                                        $alternatif_2 = $_POST['id_kanan' . $i];
                                        $value = $_POST['nilai_alternatif' . $i];
                                        if ($priority == $alternatif_1) {
                                            $query = $conn->query("INSERT INTO tb_alternatif_comparison VALUES (NULL, $alternatif_1, $alternatif_2, $priority, $value, '$_SESSION[id]', '$_GET[id_criteria]')");
                                            $value = 1 / $value;
                                            $query = $conn->query("INSERT INTO tb_alternatif_comparison VALUES (NULL, $alternatif_1, $alternatif_2, $alternatif_2, $value, '$_SESSION[id]', '$_GET[id_criteria]')");
                                        } else {
                                            $query = $conn->query("INSERT INTO tb_alternatif_comparison VALUES (NULL, $alternatif_1, $alternatif_2, $priority, $value, '$_SESSION[id]', '$_GET[id_criteria]')");
                                            $value = 1 / $value;
                                            $query = $conn->query("INSERT INTO tb_alternatif_comparison VALUES (NULL, $alternatif_1, $alternatif_2, $alternatif_1, $value, '$_SESSION[id]', '$_GET[id_criteria]')");
                                        }
                                    }
                                    $alternatif1 = $conn->query("SELECT * FROM `tb_alternatif_comparison` WHERE alternatif_1 = priority AND  id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'");
                                    $alternatif2 = $conn->query("SELECT * FROM `tb_alternatif_comparison` WHERE alternatif_2 = priority AND id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'");
                                    $alternatif_kiri = [];
                                    while ($getData = mysqli_fetch_assoc($alternatif1)) {
                                        array_push($alternatif_kiri, $getData['value']);
                                    }
                                    $alternatif_kanan = [];
                                    while ($getData = mysqli_fetch_assoc($alternatif2)) {
                                        array_push($alternatif_kanan, $getData['value']);
                                    }
                                    $perbandingan = [];
                                    $atas = 0;
                                    $bawah = 0;
                                    for ($i = 0; $i < $banyak_alternatif['banyak']; $i++) {
                                        for ($ia = 0; $ia < $banyak_alternatif['banyak']; $ia++) {
                                            if ($i == $ia) {
                                                $perbandingan[$i][$ia] = 1;
                                            } elseif ($ia > $i) {
                                                $perbandingan[$i][$ia] = $alternatif_kiri[$atas];
                                                $atas++;
                                            } elseif ($ia < $i) {
                                                $perbandingan[$i][$ia] = $alternatif_kanan[$bawah];
                                                $bawah++;
                                            }
                                        }
                                    }
                                    $temp_jumlah = [];
                                    $table_normal = [];
                                    for ($baris = 0; $baris < $banyak_alternatif['banyak']; $baris++) {
                                        $jumlah = 0;
                                        for ($kolom = 0; $kolom < $banyak_alternatif['banyak']; $kolom++) {
                                            $jumlah = $jumlah + $perbandingan[$kolom][$baris];
                                        }
                                        array_push($temp_jumlah, $jumlah);
                                    }
                                    $alternatif = $conn->query("SELECT * FROM tb_alternatif");
                                    $temp_alternatif = [];
                                    while ($getData = mysqli_fetch_assoc($alternatif)) {
                                        array_push($temp_alternatif, $getData['alternatif_id']);
                                    }
                                    for ($i = 0; $i < $banyak_alternatif['banyak']; $i++) {
                                        for ($u = 0; $u < $banyak_alternatif['banyak']; $u++) {
                                            $table_normal[$i][$u] = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                            $nilainya = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                            $insert = $conn->query("INSERT INTO tb_normalisasi_alternatif VALUES (NULL, '$temp_alternatif[$i]', '$temp_alternatif[$u]', '$nilainya', '$_SESSION[id]', '$_GET[id_criteria]')");
                                        }
                                        echo "</br>";
                                    }
                                }
                                if (isset($alternatif)) {
                                    echo "<script>
                                alert('Berhasil');
                                window.location.href = 'index.php?page=perbandingan_alternatif&id_pengguna='$_SESSION[id]'&sub='$_GET[sub]'&id_criteria='$_GET[id_criteria]';
                            </script>";
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Perbandingan alternatif</div>
                    <p class="card-title-desc">Pilih salah satu alternatif dan dilanjutkan dengan mengisi nilai pada alternatif pada form</p>
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
                                    $variable = $conn->query("SELECT * FROM tb_alternatif");
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
                                                        <input type="hidden" name="id_kiri<?= $a ?>" value="<?= $list[$data]['alternatif_id']  ?>">
                                                        <input checked class="form-check-input" type="radio" name="alternatif_radio<?= $a ?>" id="alternatif_radio<?= $a ?>" value="<?= $list[$data]['alternatif_id'] ?>">
                                                        <label class="form-check-label" for="alternatif_radio<?= $a ?>">
                                                            <?= $list[$data]['description']  ?>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="hidden" name="id_kanan<?= $a ?>" value="<?= $list[$baru]['alternatif_id']  ?>">
                                                        <input class="form-check-input" type="radio" name="alternatif_radio<?= $a ?>" id="a_radio<?= $a ?>" value="<?= $list[$baru]['alternatif_id'] ?>">
                                                        <label class="form-check-label" for="a_radio<?= $a ?>">
                                                            <?= $list[$baru]['description'] ?>
                                                        </label>
                                                    </div>

                                                </td>
                                                <td class="px-3">
                                                    <input type="text" class="form-control" name="nilai_alternatif<?= $a ?>" required>
                                                </td>
                                            </tr>
                                        <?php endfor ?>
                                    <?php endfor ?>
                                    <tr class="">
                                        <?php
                                        $sudah = mysqli_fetch_assoc($conn->query("SELECT count(id_alternatif_comparison) as n FROM tb_alternatif_comparison WHERE id_users = '$_SESSION[id]' AND criteria_id =' $_GET[id_criteria]'"));
                                        ?>
                                        <td colspan="2"></td>
                                        <?php if ($sudah['n'] > 0) : ?>
                                            <td class="d-grid"><button type="submit" class="btn btn-primary font-size-18" name="submit" disabled><i class="dripicons-arrow-right"></i></button></td>
                                        <?php else : ?>
                                            <td class="d-grid"><button type="submit" class="btn btn-primary font-size-18" name="submit"><i class="dripicons-arrow-right"></i></button></td>
                                        <?php endif ?>
                                    </tr>
                                </form>
                                <?php
                                $banyak_alternatif = mysqli_fetch_assoc($conn->query("SELECT COUNT(alternatif_id) AS banyak FROM tb_alternatif"));
                                if (isset($_POST['submit'])) {
                                    for ($i = 1; $i <= $banyak_alternatif['banyak']; $i++) {
                                        $priority = $_POST['alternatif_radio' . $i];
                                        $alternatif_1 = $_POST['id_kiri' . $i];
                                        $alternatif_2 = $_POST['id_kanan' . $i];
                                        $value = $_POST['nilai_alternatif' . $i];
                                        if ($priority == $alternatif_1) {
                                            $query = $conn->query("INSERT INTO tb_alternatif_comparison VALUES (NULL, $alternatif_1, $alternatif_2, $priority, $value, '$_SESSION[id]', '$_GET[id_criteria]')");
                                            $value = 1 / $value;
                                            $query = $conn->query("INSERT INTO tb_alternatif_comparison VALUES (NULL, $alternatif_1, $alternatif_2, $alternatif_2, $value, '$_SESSION[id]', '$_GET[id_criteria]')");
                                        } else {
                                            $query = $conn->query("INSERT INTO tb_alternatif_comparison VALUES (NULL, $alternatif_1, $alternatif_2, $priority, $value, '$_SESSION[id]', '$_GET[id_criteria]')");
                                            $value = 1 / $value;
                                            $query = $conn->query("INSERT INTO tb_alternatif_comparison VALUES (NULL, $alternatif_1, $alternatif_2, $alternatif_1, $value, '$_SESSION[id]', '$_GET[id_criteria]')");
                                        }
                                    }
                                    $alternatif1 = $conn->query("SELECT * FROM `tb_alternatif_comparison` WHERE alternatif_1 = priority AND  id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'");
                                    $alternatif2 = $conn->query("SELECT * FROM `tb_alternatif_comparison` WHERE alternatif_2 = priority AND id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'");
                                    $alternatif_kiri = [];
                                    while ($getData = mysqli_fetch_assoc($alternatif1)) {
                                        array_push($alternatif_kiri, $getData['value']);
                                    }
                                    $alternatif_kanan = [];
                                    while ($getData = mysqli_fetch_assoc($alternatif2)) {
                                        array_push($alternatif_kanan, $getData['value']);
                                    }
                                    $perbandingan = [];
                                    $atas = 0;
                                    $bawah = 0;
                                    for ($i = 0; $i < $banyak_alternatif['banyak']; $i++) {
                                        for ($ia = 0; $ia < $banyak_alternatif['banyak']; $ia++) {
                                            if ($i == $ia) {
                                                $perbandingan[$i][$ia] = 1;
                                            } elseif ($ia > $i) {
                                                $perbandingan[$i][$ia] = $alternatif_kiri[$atas];
                                                $atas++;
                                            } elseif ($ia < $i) {
                                                $perbandingan[$i][$ia] = $alternatif_kanan[$bawah];
                                                $bawah++;
                                            }
                                        }
                                    }
                                    $temp_jumlah = [];
                                    $table_normal = [];
                                    for ($baris = 0; $baris < $banyak_alternatif['banyak']; $baris++) {
                                        $jumlah = 0;
                                        for ($kolom = 0; $kolom < $banyak_alternatif['banyak']; $kolom++) {
                                            $jumlah = $jumlah + $perbandingan[$kolom][$baris];
                                        }
                                        array_push($temp_jumlah, $jumlah);
                                    }
                                    $alternatif = $conn->query("SELECT * FROM tb_alternatif");
                                    $temp_alternatif = [];
                                    while ($getData = mysqli_fetch_assoc($alternatif)) {
                                        array_push($temp_alternatif, $getData['alternatif_id']);
                                    }
                                    for ($i = 0; $i < $banyak_alternatif['banyak']; $i++) {
                                        for ($u = 0; $u < $banyak_alternatif['banyak']; $u++) {
                                            $table_normal[$i][$u] = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                            $nilainya = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                            $insert = $conn->query("INSERT INTO tb_normalisasi_alternatif VALUES (NULL, '$temp_alternatif[$i]', '$temp_alternatif[$u]', '$nilainya', '$_SESSION[id]', '$_GET[id_criteria]')");
                                        }
                                        echo "</br>";
                                    }
                                }
                                if (isset($alternatif)) {
                                    echo "<script>
                                alert('Berhasil');
                                window.location.href = '';
                            </script>";
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<?php if ($sudah['n'] > 0) : ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Matrik Normalisasi</div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-danger">
                                <tr class="text-white">
                                    <th>alternatif</th>
                                    <?php
                                    $query = $conn->query("SELECT * FROM tb_alternatif");
                                    while ($data = mysqli_fetch_assoc($query)) :
                                    ?>
                                        <th><?= $data['description'] ?></th>
                                    <?php endwhile ?>
                                    <th>Jumlah</th>
                                    <th>Vektor Eigen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = $conn->query("SELECT * FROM tb_alternatif");
                                while ($data = mysqli_fetch_assoc($query)) :
                                ?>
                                    <tr>
                                        <td><?= $data['description'] ?></td>
                                        <?php $alternatif = $conn->query("SELECT * FROM tb_normalisasi_alternatif WHERE alternatif_1 = '$data[alternatif_id]' AND  id_users = '$_SESSION[id]' AND criteria_id = $_GET[id_criteria]");
                                        while ($getData = mysqli_fetch_assoc($alternatif)) : ?>
                                            <td><?= round($getData['nilai'], 2) ?></td>
                                        <?php endwhile ?>
                                        <?php $count = $conn->query("SELECT SUM(nilai) AS jumlah FROM tb_normalisasi_alternatif  WHERE alternatif_1 = '$data[alternatif_id]' AND  id_users = '$_SESSION[id]' AND criteria_id = $_GET[id_criteria]");
                                        $banyak_alternatif = mysqli_fetch_assoc($conn->query("SELECT COUNT(alternatif_id) AS banyak FROM tb_alternatif"));
                                        while ($total = mysqli_fetch_assoc($count)) : ?>
                                            <td><?= round($total['jumlah'], 3) ?></td>
                                            <td><?= round($total['jumlah'] / $banyak_alternatif['banyak'], 3) ?></td>
                                        <?php endwhile ?>

                                    </tr>
                                <?php endwhile ?>

                                <tr>
                                    <td colspan="5">Max. Lambda</td>
                                    <?php
                                    $alternatif1 = $conn->query("SELECT * FROM `tb_alternatif_comparison` WHERE alternatif_1 = priority AND id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'");
                                    $alternatif2 = $conn->query("SELECT * FROM `tb_alternatif_comparison` WHERE alternatif_2 = priority AND id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'");
                                    $alternatif_kiri = [];
                                    while ($getData = mysqli_fetch_assoc($alternatif1)) {
                                        array_push($alternatif_kiri, $getData['value']);
                                    }
                                    $alternatif_kanan = [];
                                    while ($getData = mysqli_fetch_assoc($alternatif2)) {
                                        array_push($alternatif_kanan, $getData['value']);
                                    }
                                    $banyak_alternatif = mysqli_fetch_assoc($conn->query("SELECT COUNT(alternatif_id) AS banyak FROM tb_alternatif"));
                                    $perbandingan = [];
                                    $atas = 0;
                                    $bawah = 0;
                                    for ($i = 0; $i < $banyak_alternatif['banyak']; $i++) {
                                        for ($ia = 0; $ia < $banyak_alternatif['banyak']; $ia++) {
                                            if ($i == $ia) {
                                                $perbandingan[$i][$ia] = 1;
                                            } elseif ($ia > $i) {
                                                $perbandingan[$i][$ia] = $alternatif_kiri[$atas];
                                                $atas++;
                                            } elseif ($ia < $i) {
                                                $perbandingan[$i][$ia] = $alternatif_kanan[$bawah];
                                                $bawah++;
                                            }
                                        }
                                    }
                                    $temp_jumlah = [];
                                    $table_normal = [];
                                    for ($baris = 0; $baris < $banyak_alternatif['banyak']; $baris++) {
                                        $jumlah = 0;
                                        for ($kolom = 0; $kolom < $banyak_alternatif['banyak']; $kolom++) {
                                            $jumlah = $jumlah + $perbandingan[$kolom][$baris];
                                        }
                                        array_push($temp_jumlah, $jumlah);
                                    }
                                    $query = $conn->query("SELECT * FROM tb_alternatif");
                                    $eigenVektornya = 0;
                                    $index = 0;
                                    while ($data = mysqli_fetch_assoc($query)) {
                                        $count = $conn->query("SELECT SUM(nilai) AS jumlah FROM tb_normalisasi_alternatif  WHERE alternatif_1 = '$data[alternatif_id]' AND  id_users = '$_SESSION[id]' AND criteria_id = '$_GET[id_criteria]'");
                                        while ($total = mysqli_fetch_assoc($count)) {
                                            $eigenVektornya = $eigenVektornya + ($temp_jumlah[$index]) * (round($total['jumlah'] / $banyak_alternatif['banyak'], 3));
                                            $index++;
                                        }
                                    }
                                    $ci = ($eigenVektornya -  $banyak_alternatif['banyak']) / ($banyak_alternatif['banyak'] - 1)
                                    ?>
                                    <td><?= $eigenVektornya ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5">Konsistensi Index</td>
                                    <td><?= $ci ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5">Ratio</td>
                                    <?php $ratio = mysqli_fetch_assoc($conn->query("SELECT * FROM tb_ir WHERE ukuran_matriks = '$banyak_alternatif[banyak]'")); ?>
                                    <td><?= $ratio['nilai_ir'] ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5">Konsistensi Ratio</td>
                                    <?php $ratio = mysqli_fetch_assoc($conn->query("SELECT * FROM tb_ir WHERE ukuran_matriks = '$banyak_alternatif[banyak]'")); ?>
                                    <td><?= $ci / $ratio['nilai_ir'] ?></td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <p class="badge badge-pill badge-soft-<?= $ci / $ratio['nilai_ir'] < 0.1 ? 'success' : 'danger' ?> font-size-20"><?= $ci / $ratio['nilai_ir'] < 0.1 ? 'Bagus nilai konsisten' : 'Waduh nilai belum konsisten' ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($ci / $ratio['nilai_ir'] < 0.1) : ?>
                        <?php
                        $cek = mysqli_fetch_assoc($conn->query("SELECT count(id_rangking) FROM tb_rangking WHERE id_users = '$_SESSION[id]'"));
                        if ($cek <= 0) {
                            $arrayKriteria = [];
                            $dataKriteria = $conn->query("SELECT * FROM tb_criteria ORDER BY criteria_id");
                            while ($d = mysqli_fetch_assoc($dataKriteria)) {
                                array_push($arrayKriteria, $d);
                            }
                            $arrayAlternatif = [];
                            $dataAlternatif = $conn->query("SELECT * FROM tb_alternatif ORDER BY alternatif_id");
                            while ($d = mysqli_fetch_assoc($dataAlternatif)) {
                                array_push($arrayAlternatif, $d);
                            }
                            $kriteria_eigen = [];
                            $getVektorEigen = $conn->query("SELECT SUM(nilai/(SELECT count(criteria_id) FROM tb_criteria)) AS eigen FROM tb_normalisasi_kriteria WHERE id_users = '$_SESSION[id]' GROUP BY criteria_1;");
                            while ($eigentKriteria = mysqli_fetch_assoc($getVektorEigen)) {
                                array_push($kriteria_eigen, $eigentKriteria);
                            }

                            $alternatif_eigen = [];
                            $getVektorAlternatif = $conn->query("SELECT SUM(nilai/(SELECT count(alternatif_id) FROM tb_alternatif)) AS eigen FROM tb_normalisasi_alternatif WHERE criteria_id = '$_GET[id_criteria]' AND id_users = '$_SESSION[id]' GROUP BY alternatif_1;");
                            while ($eigenAlternatif = mysqli_fetch_assoc($getVektorAlternatif)) {
                                array_push($alternatif_eigen, $eigenAlternatif);
                            }
                            $nilaiEigenAlternatif = 0;
                            for ($index = 0; $index < $banyak_alternatif['banyak']; $index++) {
                                $nilaiEigenAlternatif = $nilaiEigenAlternatif + $kriteria_eigen[$index]['eigen'] * $alternatif_eigen[$index]['eigen'];
                            }
                            $ini = 0;
                            for ($h = 0; $h < $banyak_alternatif['banyak']; $h++) {
                                if ($_GET['sub'] == $arrayKriteria[$h]['description']) {
                                    $ini = $h;
                                }
                            }

                            $id_alternatif = $arrayAlternatif[$ini]['alternatif_id'];
                            $conn->query("INSERT INTO tb_rangking VALUES(NULL, '$_SESSION[id]', '$id_alternatif', '$nilaiEigenAlternatif')");
                        }

                        // echo $arrayAlternatif[$ini]['alternatif_id'];
                        // var_dump($getIdAlternatif);
                        ?>
                        <div class="float-end">
                            <button type="button" class="btn btn-success waves-effect btn-label waves-light"><i class="dripicons-arrow-right label-icon"></i> Lanjut</button>
                        </div>
                    <?php else : ?>
                        <div class="float-end">
                            <a href="?page=perbandingan_alternatif&id_pengguna=<?= $_SESSION['id'] ?>&sub=<?= $_GET['sub'] ?>&id_criteria=<?= $_GET['id_criteria'] ?>" class="btn btn-danger waves-effect btn-label waves-light"><i class="dripicons-arrow-up label-icon"></i> Ubah nilai</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>