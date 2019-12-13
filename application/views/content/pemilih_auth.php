<div class="container">
    <h4 class="text-center mt-4 text-dark">Selamat Datang <?= $usernama ?> <br> Silahkan Lakukan Pemilihan</h4>
    <div class="row justify-content-center mt-1">
        
        <?php foreach($tema as $t) { ?>
        <div class="col-4">
            <div class="card o-hidden border-1 shadow-lg my-4">
                <div class="card-body text-center 
                    <?php 
                        $nilai = $t['tema_id'];
                        echo $nilai % 2 ? 'bg-dark text-white' : 'text-dark';
                    ?>
                ">
                    <h4 class="card-title">Surat Suara<br><?= $t['tema_nama'] ?></h4>
                    <hr>
                    <img src="<?= base_url('/assets/img/'.$t['tema_logo']) ?>" alt="" class="img-thumbnail" width="150">
                    <?php
                    $query = $this->db->get_where('tb_suara', ['pemilih_id' => $user, 'tema_id' => $t['tema_id']]);
                    $cek = $query->num_rows();
                    if($cek > 0) {
                        echo "<br>Sudah Memilih";
                    } else {
                    ?>
                    <hr>
                    <a href="<?= base_url('PemilihAuth/pemilihMemilih/'.$t['tema_id']) ?>" class="btn btn-lg btn-primary">Buka</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php if($hasilCount == $countTema) { ?>
        <div class="text-center">
            <a href="<?= base_url('PemilihAuth/logout') ?>" class="btn btn-lg btn-success">Selesai</a>
        </div>
    <?php } ?>
</div>