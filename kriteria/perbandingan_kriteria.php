<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Perbandingan Kriteria</h4>
        </div>
    </div>
</div>
<?php if (isset($_GET['id_pengguna'])) : ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Ubah Perbandingan Kriteria</div>
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
                                                    <input type="text" class="form-control" name="nilai_kriteria<?= $a ?>" required>
                                                </td>
                                            </tr>
                                        <?php endfor ?>
                                    <?php endfor ?>
                                    <tr class="">
                                        <?php
                                        $sudah = mysqli_fetch_assoc($conn->query("SELECT count(id_criteria_comparison) as n FROM tb_criteria_comparison WHERE id_users = '$_SESSION[id]'"));
                                        ?>
                                        <td colspan="2"></td>
                                        
                                            <td class="d-grid"><button type="submit" class="btn btn-warning font-size-18" name="ubah"><i class="dripicons-arrow-up"></i></button></td>
                                    </tr>
                                </form>
                                <?php
                                $banyak_kriteria = mysqli_fetch_assoc($conn->query("SELECT COUNT(criteria_id) AS banyak FROM tb_criteria"));
                                if (isset($_POST['ubah'])) {
                                    $hapus_criteria = $conn->query("DELETE FROM tb_criteria_comparison WHERE id_users = '$_SESSION[id]'");
                                    $hapus_criteria = $conn->query("DELETE FROM tb_normalisasi_kriteria WHERE id_users = '$_SESSION[id]'");
                                    for ($i = 1; $i <= $banyak_kriteria['banyak']; $i++) {
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
                                    $criteria1 = $conn->query("SELECT * FROM `tb_criteria_comparison` WHERE criteria_1 = priority AND  id_users = '$_SESSION[id]'");
                                    $criteria2 = $conn->query("SELECT * FROM `tb_criteria_comparison` WHERE criteria_2 = priority AND id_users = '$_SESSION[id]'");
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
                                    for ($i = 0; $i < $banyak_kriteria['banyak']; $i++) {
                                        for ($ia = 0; $ia < $banyak_kriteria['banyak']; $ia++) {
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
                                    for ($baris = 0; $baris < $banyak_kriteria['banyak']; $baris++) {
                                        $jumlah = 0;
                                        for ($kolom = 0; $kolom < $banyak_kriteria['banyak']; $kolom++) {
                                            $jumlah = $jumlah + $perbandingan[$kolom][$baris];
                                        }
                                        array_push($temp_jumlah, $jumlah);
                                    }
                                    $kriteria = $conn->query("SELECT * FROM tb_criteria");
                                    $temp_kriteria = [];
                                    while ($getData = mysqli_fetch_assoc($kriteria)) {
                                        array_push($temp_kriteria, $getData['criteria_id']);
                                    }
                                    for ($i = 0; $i < $banyak_kriteria['banyak']; $i++) {
                                        for ($u = 0; $u < $banyak_kriteria['banyak']; $u++) {
                                            $table_normal[$i][$u] = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                            $nilainya = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                            $insert = $conn->query("INSERT INTO tb_normalisasi_kriteria VALUES (NULL, '$temp_kriteria[$i]', '$temp_kriteria[$u]', '$nilainya', '$_SESSION[id]')");
                                        }
                                        echo "</br>";
                                    }
                                }
                                if ($kriteria) {
                                    echo "<script>
                                alert('Berhasil');
                                window.location.href = 'index.php?page=perbandingan_kriteria';
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
                                                    <input type="text" class="form-control" name="nilai_kriteria<?= $a ?>" required>
                                                </td>
                                            </tr>
                                        <?php endfor ?>
                                    <?php endfor ?>
                                    <tr class="">
                                        <?php
                                        $sudah = mysqli_fetch_assoc($conn->query("SELECT count(id_criteria_comparison) as n FROM tb_criteria_comparison WHERE id_users = '$_SESSION[id]'"));
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
                                $banyak_kriteria = mysqli_fetch_assoc($conn->query("SELECT COUNT(criteria_id) AS banyak FROM tb_criteria"));
                                if (isset($_POST['submit'])) {
                                    for ($i = 1; $i <= $banyak_kriteria['banyak']; $i++) {
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
                                    $criteria1 = $conn->query("SELECT * FROM `tb_criteria_comparison` WHERE criteria_1 = priority AND  id_users = '$_SESSION[id]'");
                                    $criteria2 = $conn->query("SELECT * FROM `tb_criteria_comparison` WHERE criteria_2 = priority AND id_users = '$_SESSION[id]'");
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
                                    for ($i = 0; $i < $banyak_kriteria['banyak']; $i++) {
                                        for ($ia = 0; $ia < $banyak_kriteria['banyak']; $ia++) {
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
                                    for ($baris = 0; $baris < $banyak_kriteria['banyak']; $baris++) {
                                        $jumlah = 0;
                                        for ($kolom = 0; $kolom < $banyak_kriteria['banyak']; $kolom++) {
                                            $jumlah = $jumlah + $perbandingan[$kolom][$baris];
                                        }
                                        array_push($temp_jumlah, $jumlah);
                                    }
                                    $kriteria = $conn->query("SELECT * FROM tb_criteria");
                                    $temp_kriteria = [];
                                    while ($getData = mysqli_fetch_assoc($kriteria)) {
                                        array_push($temp_kriteria, $getData['criteria_id']);
                                    }
                                    for ($i = 0; $i < $banyak_kriteria['banyak']; $i++) {
                                        for ($u = 0; $u < $banyak_kriteria['banyak']; $u++) {
                                            $table_normal[$i][$u] = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                            $nilainya = $perbandingan[$i][$u] / $temp_jumlah[$u];
                                            $insert = $conn->query("INSERT INTO tb_normalisasi_kriteria VALUES (NULL, '$temp_kriteria[$i]', '$temp_kriteria[$u]', '$nilainya', '$_SESSION[id]')");
                                        }
                                        echo "</br>";
                                    }
                                }
                                if ($kriteria) {
                                    echo "<script>
                                alert('Berhasil');
                                window.location.href = 'index.php?page=perbandingan_kriteria';
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
                                    <th>Kriteria</th>
                                    <?php
                                    $query = $conn->query("SELECT * FROM tb_criteria");
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
                                $query = $conn->query("SELECT * FROM tb_criteria");
                                while ($data = mysqli_fetch_assoc($query)) :
                                ?>
                                    <tr>
                                        <td><?= $data['description'] ?></td>
                                        <?php $kriteria = $conn->query("SELECT * FROM tb_normalisasi_kriteria WHERE criteria_1 = '$data[criteria_id]' AND  id_users = '$_SESSION[id]'");
                                        while ($getData = mysqli_fetch_assoc($kriteria)) : ?>
                                            <td><?= round($getData['nilai'], 2) ?></td>
                                        <?php endwhile ?>
                                        <?php $count = $conn->query("SELECT SUM(nilai) AS jumlah FROM tb_normalisasi_kriteria  WHERE criteria_1 = '$data[criteria_id]' AND  id_users = '$_SESSION[id]'");
                                        $banyak_kriteria = mysqli_fetch_assoc($conn->query("SELECT COUNT(criteria_id) AS banyak FROM tb_criteria"));
                                        while ($total = mysqli_fetch_assoc($count)) : ?>
                                            <td><?= round($total['jumlah'], 3) ?></td>
                                            <td><?= round($total['jumlah'] / $banyak_kriteria['banyak'], 3) ?></td>
                                        <?php endwhile ?>

                                    </tr>
                                <?php endwhile ?>

                                <tr>
                                    <td colspan="5">Max. Lambda</td>
                                    <?php
                                    $criteria1 = $conn->query("SELECT * FROM `tb_criteria_comparison` WHERE criteria_1 = priority AND id_users = '$_SESSION[id]'");
                                    $criteria2 = $conn->query("SELECT * FROM `tb_criteria_comparison` WHERE criteria_2 = priority AND id_users = '$_SESSION[id]'");
                                    $criteria_kiri = [];
                                    while ($getData = mysqli_fetch_assoc($criteria1)) {
                                        array_push($criteria_kiri, $getData['value']);
                                    }
                                    $criteria_kanan = [];
                                    while ($getData = mysqli_fetch_assoc($criteria2)) {
                                        array_push($criteria_kanan, $getData['value']);
                                    }
                                    $banyak_kriteria = mysqli_fetch_assoc($conn->query("SELECT COUNT(criteria_id) AS banyak FROM tb_criteria"));
                                    $perbandingan = [];
                                    $atas = 0;
                                    $bawah = 0;
                                    for ($i = 0; $i < $banyak_kriteria['banyak']; $i++) {
                                        for ($ia = 0; $ia < $banyak_kriteria['banyak']; $ia++) {
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
                                    for ($baris = 0; $baris < $banyak_kriteria['banyak']; $baris++) {
                                        $jumlah = 0;
                                        for ($kolom = 0; $kolom < $banyak_kriteria['banyak']; $kolom++) {
                                            $jumlah = $jumlah + $perbandingan[$kolom][$baris];
                                        }
                                        array_push($temp_jumlah, $jumlah);
                                    }
                                    $query = $conn->query("SELECT * FROM tb_criteria");
                                    $eigenVektornya = 0;
                                    $index = 0;
                                    while ($data = mysqli_fetch_assoc($query)) {
                                        $count = $conn->query("SELECT SUM(nilai) AS jumlah FROM tb_normalisasi_kriteria  WHERE criteria_1 = '$data[criteria_id]' AND  id_users = '$_SESSION[id]'");
                                        while ($total = mysqli_fetch_assoc($count)) {
                                            $eigenVektornya = $eigenVektornya + ($temp_jumlah[$index]) * (round($total['jumlah'] / $banyak_kriteria['banyak'], 3));
                                            $index++;
                                        }
                                    }
                                    $ci = ($eigenVektornya -  $banyak_kriteria['banyak']) / ($banyak_kriteria['banyak'] - 1)
                                    ?>
                                    <td><?= $eigenVektornya ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5">Konsistensi Index</td>
                                    <td><?= $ci ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5">Ratio</td>
                                    <?php $ratio = mysqli_fetch_assoc($conn->query("SELECT * FROM tb_ir WHERE ukuran_matriks = '$banyak_kriteria[banyak]'")); ?>
                                    <td><?= $ratio['nilai_ir'] ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5">Konsistensi Ratio</td>
                                    <?php $ratio = mysqli_fetch_assoc($conn->query("SELECT * FROM tb_ir WHERE ukuran_matriks = '$banyak_kriteria[banyak]'")); ?>
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
                        <div class="float-end">
                            <button type="button" class="btn btn-success waves-effect btn-label waves-light"><i class="dripicons-arrow-right label-icon"></i> Lanjut</button>
                        </div>
                    <?php else : ?>
                        <div class="float-end">
                            <a href="?page=perbandingan_kriteria&id_pengguna=<?= $_SESSION['id'] ?>" class="btn btn-danger waves-effect btn-label waves-light"><i class="dripicons-arrow-up label-icon"></i> Ubah nilai</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>