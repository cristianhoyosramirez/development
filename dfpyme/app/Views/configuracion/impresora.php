<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="hr-text text-green "><span class="h3 text-green"> Administración de impresoras </span> </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-3"></div>

                <div class="col-3" onclick="abrir_cajon()">
                <input type="hidden" value="<?php echo base_url() ?>" id="url">
                    <a href="#" class="card card-link text-center">

                        <div class="card-body"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                            </svg>Abrir cajon mondero </div>
                    </a>
                </div>
                <div class="col-3">
                    <a href="#" class="card card-link">
                        <div class="card-body"><!-- Download SVG icon from http://tabler-icons.io/i/file-invoice -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <line x1="9" y1="7" x2="10" y2="7" />
                                <line x1="9" y1="13" x2="15" y2="13" />
                                <line x1="13" y1="17" x2="15" y2="17" />
                            </svg>Prueba de impresión
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/abrir_cajon.js"></script>


     



<?= $this->endSection('content') ?>