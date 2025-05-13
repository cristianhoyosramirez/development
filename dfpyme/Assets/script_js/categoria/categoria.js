$(document).ready(function(){  
    var url = document.getElementById("url").value;
    function fetch_data()
    {
        $.ajax({
            url: url + "/" + "producto/agregar_productos_x_categoria",
            method:"POST",
            dataType:"json",
            success:function(data)
            {
                var html = '';
                for(var count = 0; count < data.length; count++)
                {
                    html += '<tr>';
                    html += '<td><input type="checkbox" id="'+data[count].id+'" data-nombres="'+data[count].nombres+'" data-direccion="'+data[count].direccion+'" data-genero="'+data[count].genero+'" data-area="'+data[count].area+'" data-edad="'+data[count].edad+'" data-estado="'+data[count].estado+'" class="check_box"  /></td>';
                    html += '<td>'+data[count].nombres+'</td>';
                    html += '<td>'+data[count].direccion+'</td>';
                    html += '<td>'+data[count].genero+'</td>';
                    html += '<td>'+data[count].area+'</td>';
                    html += '<td>'+data[count].edad+'</td>';
                    html += '<td>'+data[count].estado+'</td></tr>';
                }
                $('tbody').html(html);
            }
        });
    }

    fetch_data();

    $(document).on('click', '.check_box', function(){
        var html = '';
        if(this.checked)
        {
            html = '<td><input type="checkbox" id="'+$(this).attr('id')+'" data-nombres="'+$(this).data('nombres')+'" data-direccion="'+$(this).data('direccion')+'" data-genero="'+$(this).data('genero')+'" data-area="'+$(this).data('area')+'" data-edad="'+$(this).data('edad')+'" data-estado="'+$(this).data('estado')+'" class="check_box" checked /></td>';
            html += '<td><input type="text" name="nombres[]" class="form-control" value="'+$(this).data("nombres")+'" /></td>';
            html += '<td><input type="text" name="direccion[]" class="form-control" value="'+$(this).data("direccion")+'" /></td>';
            html += '<td><select name="genero[]" id="genero_'+$(this).attr('id')+'" class="form-control"><option value="'+$(this).data("genero")+'">'+$(this).data("genero")+'</option>  <option value="Masculino">Masculino</option><option value="Femenino">Femenino</option></select></td>';
            html += '<td><input type="text" name="area[]" class="form-control" value="'+$(this).data("area")+'" /></td>';
            html += '<td><input type="text" name="edad[]" class="form-control" value="'+$(this).data("edad")+'" /></td>';
			html += '<td><input type="text" name="estado[]" class="form-control" value="'+$(this).data("estado")+'" /><input type="hidden" name="hidden_id[]" value="'+$(this).attr('id')+'" /></td>';
        }
        else
        {
            html = '<td><input type="checkbox" id="'+$(this).attr('id')+'" data-nombres="'+$(this).data('nombres')+'" data-direccion="'+$(this).data('direccion')+'" data-genero="'+$(this).data('genero')+'" data-area="'+$(this).data('area')+'" data-edad="'+$(this).data('edad')+'" data-estado="'+$(this).data('estado')+'" class="check_box" /></td>';
            html += '<td>'+$(this).data('nombres')+'</td>';
            html += '<td>'+$(this).data('direccion')+'</td>';
            html += '<td>'+$(this).data('genero')+'</td>';
            html += '<td>'+$(this).data('area')+'</td>';
            html += '<td>'+$(this).data('edad')+'</td>';            
            html += '<td>'+$(this).data('estado')+'</td>';            
        }
        $(this).closest('tr').html(html);
        $('#genero_'+$(this).attr('id')+'').val($(this).data('genero'));
    });

    $('#update_form').on('submit', function(event){
        event.preventDefault();
        if($('.check_box:checked').length > 0)
        {
            $.ajax({
                url:"ActualizacionMultiple.php",
                method:"POST",
                data:$(this).serialize(),
                success:function()
                {
                    alert('Registro(s) Actualizado(s).');
                    fetch_data();
                }
            })
        }
    });

}); 