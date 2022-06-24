<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Daftar Kriteria</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-4">
        <div class="float-end">
            <button type="button" class="btn btn-dark waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#insertCriteria">
                <i class="bx bx-plus font-size-16 align-middle me-2"></i> Tambah
            </button>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Table Kriteria</h4>
                <p class="card-title-desc">Kriteria dapat ditambahkan dengan mengklik tombol tambah disamping atas</p>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle" style="width: 5%;">No</th>
                                <th class="align-middle" style="width: 65%;">Nama Kriteria</th>
                                <th class="align-middle text-center" colspan="2">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $query = $conn->query("SELECT * FROM tb_criteria");
                            while ($getData = mysqli_fetch_assoc($query)) :
                            ?>
                                <tr class="tr">
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $getData['description'] ?></td>
                                    <td class="text-center">
                                        <a href="" id="editKriteria" data-criteriaid = "<?= $getData['criteria_id'] ?>"  data-description = "<?= $getData['description'] ?>" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editCriteria"><i class="fas fa-pen"></i></a>
                                        <script>
                                            const element = document.querySelector("#editKriteria");
                                            element.addEventListener("click", function(){
                                                const idkrit = document.getElementById('id_kriteria').setAttribute('value', element.dataset.criteriaid);
                                                const criteria = document.getElementById('edit_kriteria').setAttribute('value', element.dataset.description);
                                            });
                                        </script>
                                    </td>
                                    <td class="text-center">
                                        <a href="?page=daftar_kriteria&id=<?= $getData['criteria_id'] ?>" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></a>
                                        <?php
                                        if(isset($_GET['id'])) {
                                            $query = $conn->query("DELETE FROM tb_criteria WHERE criteria_id = '$_GET[id]'");
                                            if($query) {
                                                echo "<script>
                                                window.location.href='index.php?page=daftar_kriteria';
                                                </script>";
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php $i++;
                            endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="insertCriteria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-3 px-4">
                        <h5 class="modal-title" id="modal-title">Tambah Kriteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form class="needs-validation" method="POST" action="">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kriteria</label>
                                        <input class="form-control" placeholder="Nama Kriteria" type="text" name="nama_kriteria" id="nama_kriteria" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success" id="btn-save-event" name="simpan">Simpan</button>
                                </div>
                                <?php
                                if (isset($_POST['simpan'])) {
                                    $kriteria = mysqli_escape_string($conn, $_POST['nama_kriteria']);
                                    $query = $conn->query("INSERT INTO tb_criteria VALUES(NULL, '$kriteria')");
                                    if ($query) {
                                        echo "<script>
                                            alert('Mantap');
                                            window.location.href='index.php?page=daftar_kriteria';
                                        </script>";
                                    }
                                }
                                ?>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editCriteria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-3 px-4">
                        <h5 class="modal-title" id="modal-title">Update Kriteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form class="needs-validation" method="POST" action="">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kriteria</label>
                                        <input class="form-control" placeholder="Nama Kriteria" type="text" name="edit_kriteria" id="edit_kriteria" required="">
                                        <input type="hidden" name="id_kriteria" id="id_kriteria">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success" id="btn-save-event" name="update">Simpan</button>
                                </div>
                                <?php
                                if (isset($_POST['update'])) {
                                    $kriteria = mysqli_escape_string($conn, $_POST['edit_kriteria']);
                                    $query = $conn->query("UPDATE tb_criteria SET tb_criteria.description =  '$kriteria' WHERE criteria_id =  '$_POST[id_kriteria]'");
                                    if ($query) {
                                        echo "<script>
                                            alert('Mantap');
                                            window.location.href='index.php?page=daftar_kriteria';
                                        </script>";
                                    }
                                }
                                ?>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>