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
                                    // $query = $conn->query("INSERT INTO tb_criteria_comparison VALUES (NULL, $criteria_1, $criteria_2, $priority, $value)");
                                }
                                $perbandingan = $conn->query("SELECT * FROM tb_criteria_comparison");
                                $data_perbandingan = [];
                                while ($array = mysqli_fetch_assoc($perbandingan)) {
                                    array_push($data_perbandingan, $array);
                                }
                                $q = [];
                                for ($u=0; $u < 3; $u++) { 
                                    for ($w=0; $w < 3; $w++) { 
                                        if ($u == $w) {
                                            $q[$u][$w] = 1;
                                        } else {
                                            if ($data_perbandingan[$u]['criteria_1'] == $data_perbandingan[$u]['priority']) {
                                                $q[$u][$w] = 1/$data_perbandingan[$u]['value'];
                                                $q[$w][$u] = $data_perbandingan[$u]['value'];
                                            } else {
                                                $q[$w][$u] = 1/$data_perbandingan[$u]['value'];
                                                $q[$u][$w] = $data_perbandingan[$u]['value'];
                                            }
                                        }
                                    }
                                }
                                // echo $q[0][1] . ' ';
                                for ($baris=0; $baris < 3; $baris++) { 
                                    for ($kolom=0; $kolom < 3; $kolom++) { 
                                        echo $q[$baris][$kolom] . ' ';
                                    }
                                    echo "<br/>";
                                }
                                $jumlah_c1 = 0;
                                for ($coba=0; $coba < 3; $coba++) { 
                                    $jumlah_c1 = $jumlah_c1 + $q[$coba][0];
                                }
                                echo $jumlah_c1;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>