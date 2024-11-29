<p class="text-primary">Notas:</p>
<?php foreach ($notas as $detalle) { ?><br>
    <div class="row gy-5">
        <div class="col">
            <input type="text" value="<?php echo $detalle['nota'] ?>" class="form-control">
        </div>
        <div class="col">
            <button type="button" class="btn btn-success">Actulizar nota</button>
        </div>
    </div>


<?php } ?>