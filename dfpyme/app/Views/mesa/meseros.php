<div class="container">
    <div class="row">
        <div class="col-12 col-md-10">
        </div>
        <div class="col-12 col-md-2 mt-2 mt-md-0 text-md-start">
            <button type="button" class="btn btn-outline-yellow" onclick="agregar_mesero()">Agregar mesero</button>
        </div>
    </div>

</div>

<div class="my-3"></div>


<div class="row">
    <?php $count = 0; ?>
    <?php foreach ($meseros as $detalle) : ?>
        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mesero-item" onclick="meseros(<?php echo $detalle['idusuario_sistema'] ?>)" class="cursor-pointer">
            <a href="#" class="card card-link card-link-pop bg-primary-lt">
                <div class="card-body text-center"><?php echo $detalle['nombresusuario_sistema']; ?></div>
            </a>
        </div>
        <?php $count++; ?>
        <?php if ($count % 4 == 0) : ?> <!-- Insert a line break after every 4 buttons on small screens -->
            <div class="w-100 d-md-none"></div>
        <?php elseif ($count % 6 == 0) : ?> <!-- Insert a line break after every 6 buttons on medium screens -->
            <div class="w-100 d-lg-none"></div>
        <?php endif; ?>
    <?php endforeach ?>
</div>