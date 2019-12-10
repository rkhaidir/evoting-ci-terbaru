<div class="container">
    <h4 class="text-center mt-4 text-dark">Surat Suara <?= $tema['tema_nama'] ?></h4>
    <div class="row justify-content-center mt-1">
        
        <?php foreach($calon as $c) { ?>
        <div class="col-md-4">
            <div class="card o-hidden border-0 shadow-sm my-5">
                <div class="card-body text-center">
                    <h4 class="card-title"><?= $c['calon_ketua_nourut'] ?></h4>
                    <a href="<?= base_url('PemilihAuth/memilih/'.$c['calon_ketua_id']) ?>" class="pilih" data="<?=$c['calon_ketua_nama'] ?>"><img src="<?= base_url('/assets/img/'.$c['calon_ketua_foto']) ?>" alt="" class="img-thumbnail rounded-circle img-hover" width="200"></a>
                    <hr>
                    <h4 class="card-title"><?= $c['calon_ketua_nama'] ?></h4>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>