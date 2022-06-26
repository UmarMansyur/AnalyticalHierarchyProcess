<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Lihat Hasil</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mt-lg-5">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Alternatif</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $query = $conn->query("SELECT * FROM tb_rangking LEFT JOIN tb_alternatif ON tb_rangking.id_alternatif = tb_alternatif.alternatif_id WHERE id_users = '$_SESSION[id]' ORDER BY nilai DESC");
                        $key = 1;
                        while($data = mysqli_fetch_assoc($query)) :
                    ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td><?=  $data['description']?></td>
                        <td><?= $data['nilai']?></td>
                    </tr>
                    <?php $key++; endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6">

    </div>
</div>