<?php

namespace App\Models;

use CodeIgniter\Model;

class productoPedidoModel extends Model
{
    protected $table      = 'producto_pedido';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'numero_de_pedido',
        'cantidad_producto',
        'nota_producto',
        'valor_unitario',
        'impresion_en_comanda',
        'cantidad_entregada',
        'valor_total',
        'se_imprime_en_comanda',
        'codigo_categoria',
        'codigointernoproducto',
        'numero_productos_impresos_en_comanda',
        'id_impresora',
        'idUsuario',
        'fecha',
        'hora',

    ];

    public function producto_pedido($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
              producto_pedido.id as id,
              cantidad_producto,
              producto.nombreproducto,
              producto_pedido.codigointernoproducto AS id_producto,
              valor_unitario,
              nota_producto,
              cantidad_entregada,
              valor_total,
              producto.codigointernoproducto,
              nota_producto,
              producto_pedido.id as id_tabla_producto,
              numero_productos_impresos_en_comanda
        FROM
              producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        WHERE
              numero_de_pedido = '$numero_pedido'
        ORDER BY producto_pedido.id desc
        
        ");
        return $datos->getResultArray();
    }

    public function id_categoria($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
             DISTINCT codigo_categoria
        FROM
             producto_pedido where numero_de_pedido='$numero_pedido' 
        ");
        return $datos->getResultArray();
    }



    public function id_categoria_2($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
             DISTINCT codigo_categoria
        FROM
             producto_pedido where numero_de_pedido='$numero_pedido'  order by codigo_categoria asc
        ");
        return $datos->getResultArray();
    }


    public function productos_pedido($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido'  and se_imprime_en_comanda='true' and  numero_productos_impresos_en_comanda < cantidad_producto  order by id asc;
        ");
        return $datos->getResultArray();
    }
    public function productos_borrar($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido' ;
        ");
        return $datos->getResultArray();
    }
    public function productos_pedido_comanda($numero_pedido, $codigo_categoria)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido'  and se_imprime_en_comanda='true' and  numero_productos_impresos_en_comanda < cantidad_producto and codigo_categoria='$codigo_categoria' order by id asc;
        ");
        return $datos->getResultArray();
    }
    public function productos_pedido_comanda_todos($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido'  and se_imprime_en_comanda='true' and  numero_productos_impresos_en_comanda < cantidad_producto  order by id asc;
        ");
        return $datos->getResultArray();
    }

    public function reimprimir_comanda($numero_pedido, $codigo_categoria)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido'  and se_imprime_en_comanda='true' and codigo_categoria='$codigo_categoria'   order by id asc;
        ");
        return $datos->getResultArray();
    }
    public function reimprimir_comanda_todo($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido'  and se_imprime_en_comanda='true'    order by id asc;
        ");
        return $datos->getResultArray();
    }




    public function productos_pedido_pos($numero_pedido, $id_categoria)
    {
        $datos = $this->db->query("
        SELECT
            id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido_pos.codigointernoproducto
        FROM
            producto_pedido_pos
        INNER JOIN producto ON producto_pedido_pos.codigointernoproducto = producto.codigointernoproducto
        where pk_pedido_pos='$numero_pedido' and id_categoria='$id_categoria' and se_imprime_en_comanda='true'  order by id asc;
        ");
        return $datos->getResultArray();
    }

    public function productos_pedido_pos_sin_pedido($id_pedido)
    {
        $datos = $this->db->query("
        SELECT
            cantidad_producto,
            valor_unitario,
            valor_total,
            codigointernoproducto
        FROM
        producto_pedido_pos
    WHERE
        se_imprime_en_comanda = 'TRUE' AND impreso_en_comanda = 'FALSE' AND pk_pedido_pos = $id_pedido
        ");
        return $datos->getResultArray();
    }


    public function reimprimir_productos_pedido($numero_pedido, $id_categoria)
    {
        $datos = $this->db->query("
        SELECT
                producto_pedido.id,
                producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido' and codigo_categoria='$id_categoria' and se_imprime_en_comanda='true'  order by id asc;
        ");
        return $datos->getResultArray();
    }
    public function productos_del_pedido_para_facturar($numero_pedido)
    {
        $datos = $this->db->query("
          SELECT
              producto_pedido.id as id,
              cantidad_producto,
              nota_producto,
              valor_unitario,
              valor_total,
              producto.codigointernoproducto,
              producto.nombreproducto
          FROM
              producto_pedido
          INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
          WHERE
              numero_de_pedido = '$numero_pedido'
              order by id desc
          ");
        return $datos->getResultArray();
    }

    public function pre_factura($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
        producto_pedido.id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido' order by id asc;
        ");
        return $datos->getResultArray();
    }

    public function cantidad_producto($numero_pedido, $codigo_interno_producto)
    {
        $datos = $this->db->query("
        SELECT
            cantidad_producto 
        FROM 
            producto_pedido 
        WHERE  numero_de_pedido=$numero_pedido and codigointernoproducto='$codigo_interno_producto';
        ");
        return $datos->getResultArray();
    }

    public function actualizar_cantidad_producto($numero_pedido, $codigo_interno_producto, $cantidad, $nota_producto, $valor_total)
    {
        $datos = $this->db->query("
        UPDATE producto_pedido
        SET  cantidad_producto=$cantidad, nota_producto='$nota_producto',valor_total=$valor_total
        WHERE numero_de_pedido=$numero_pedido and codigointernoproducto='$codigo_interno_producto';
        ");
        return $datos;
    }

    public function validar_pedido($numero_pedido)
    {
        $datos = $this->db->query("
     SELECT COUNT(id) as total
    FROM producto_pedido
    WHERE cantidad_producto IS NULL
    AND numero_de_pedido = $numero_pedido;
        ");
        return $datos->getResultArray();
    }

    public function detalle_pedido($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
            producto.nombreproducto,
            valor_unitario,
            cantidad_producto,
            cantidad_entregada,
            valor_total,
            nota_producto
        FROM
            producto_pedido
        INNER JOIN producto ON producto.codigointernoproducto = producto_pedido.codigointernoproducto
        WHERE
            numero_de_pedido = '$numero_pedido'
        ORDER BY
        producto_pedido.id ASC
        ");
        return $datos->getResultArray();
    }
    public function editar_producto_pedido($id_tabla_producto_pedido)
    {
        $datos = $this->db->query("
        SELECT  
            cantidad_producto,
            nota_producto, 
            valor_unitario, 
            valor_total, 
            producto_pedido.codigointernoproducto,
            producto.nombreproducto
        FROM producto_pedido 
        inner join producto  on producto_pedido.codigointernoproducto=producto.codigointernoproducto
        where producto_pedido.id=$id_tabla_producto_pedido;
        ");
        return $datos->getResultArray();
    }
    public function editar_y_eliminar_producto_pedido($id_tabla_producto_pedido)
    {
        $datos = $this->db->query("
        SELECT  
            cantidad_producto,
            nota_producto, 
            valor_unitario, 
            valor_total, 
            producto_pedido.codigointernoproducto,
            producto.nombreproducto
        FROM producto_pedido 
        inner join producto  on producto_pedido.codigointernoproducto=producto.codigointernoproducto
        where producto_pedido.id=$id_tabla_producto_pedido ;
        ");
        return $datos->getResultArray();
    }
    public function editar_y_eliminar_producto_pedido_pos($id_tabla_producto_pedido)
    {
        $datos = $this->db->query("
        SELECT  
            cantidad_producto,
            nota_producto, 
            valor_unitario, 
            valor_total, 
            producto_pedido_pos.codigointernoproducto,
            producto.nombreproducto
        FROM producto_pedido_pos
        inner join producto  on producto_pedido_pos.codigointernoproducto=producto.codigointernoproducto
        where  producto_pedido_pos.id=$id_tabla_producto_pedido
        ");
        return $datos->getResultArray();
    }

    public function editar_item_pedido($id_tabla_producto_pedido)
    {
        $datos = $this->db->query("
        SELECT  
            cantidad_producto,
            nota_producto, 
            valor_unitario, 
            valor_total, 
            producto_pedido.codigointernoproducto,
            producto.nombreproducto
        FROM producto_pedido 
        inner join producto  on producto_pedido.codigointernoproducto=producto.codigointernoproducto
        where id=$id_tabla_producto_pedido ;
        ");
        return $datos->getResultArray();
    }

    public function no_se_imprime_en_comanda($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
        id,
        impresion_en_comanda
    FROM
        producto_pedido
    WHERE
        numero_de_pedido = $numero_pedido AND se_imprime_en_comanda = 'f'
        ");
        return $datos->getResultArray();
    }
    public function no_se_imprime_en_comanda_pos($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
        id,
        se_imprime_en_comanda
    FROM
        producto_pedido_pos
    WHERE
        pk_pedido_pos = $numero_pedido AND se_imprime_en_comanda = 'f'
        ");
        return $datos->getResultArray();
    }
    public function producto_pedido_imp($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
            id,
            codigointernoproducto
        FROM
            producto_pedido
        WHERE
            numero_de_pedido = $numero_pedido
        ");
        return $datos->getResultArray();
    }
    public function valor_total($numero_pedido, $id)
    {
        $datos = $this->db->query("
        SELECT
        valor_total,
        cantidad_producto
    FROM
        producto_pedido
    WHERE
        numero_de_pedido = $numero_pedido AND id = $id
        ");
        return $datos->getResultArray();
    }


    public function imprimir_productos_pedido($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido'  and se_imprime_en_comanda='true'  and impresion_en_comanda='false' order by id asc;
        ");
        return $datos->getResultArray();
    }

    public function set_producto_pedido($id_producto)
    {
        $datos = $this->db->query("
        SELECT nombreproducto,cantidad_producto,nota_producto,valor_unitario,valor_total
        FROM   producto_pedido
        INNER JOIN producto
        ON producto.codigointernoproducto = producto_pedido.codigointernoproducto
        WHERE  producto_pedido.id = $id_producto 
        ");
        return $datos->getResultArray();
    }

    public function total_pedido($id_pedido)
    {
        $datos = $this->db->query("
      SELECT SUM (valor_total) AS total
      FROM producto_pedido
      WHERE numero_de_pedido = $id_pedido
        ");
        return $datos->getResultArray();
    }



    public function insertar(
        $ultimo_id_pedido,
        $valor_unitario,
        $se_imprime_en_comanda,
        $codigo_categoria,
        $codigo_interno_producto,
        $cantidad,
        $idUser,
        $fecha,
        $hora,
        $nota
    ) {

        $data = [
            'numero_de_pedido' => $ultimo_id_pedido,
            'cantidad_producto' => $cantidad,
            'nota_producto' => $nota,
            'valor_unitario' => $valor_unitario,
            'impresion_en_comanda' => false,
            'cantidad_entregada' => 0,
            'valor_total' => $valor_unitario * $cantidad,
            'se_imprime_en_comanda' => $se_imprime_en_comanda,
            'codigo_categoria' => $codigo_categoria,
            'codigointernoproducto' => $codigo_interno_producto,
            'numero_productos_impresos_en_comanda' => 0,
            'idUsuario' => $idUser,
            'fecha' => $fecha,
            'hora' => $hora
        ];

        $productos = $this->db->table('producto_pedido');
        $productos->insert($data);

        return $this->db->insertID();
    }


    function actualizacion_cantidad_producto($id, $datos)
    {

        $productos = $this->db->table('producto_pedido');
        $productos->set('valor_total', $datos['valor_total']);
        $productos->set('cantidad_producto', $datos['cantidad_producto']);
        $productos->where('id', $id);
        $productos->update();
        return $productos;
    }

    public function getCategorias($pedido)
    {
        $datos = $this->db->query("
            SELECT DISTINCT codigo_categoria from producto_pedido WHERE numero_de_pedido=$pedido
        ");
        return $datos->getResultArray();
    }

    public function productos_impresora($id_impresora)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where producto_pedido.id_impresora=$id_impresora  and se_imprime_en_comanda='true' and  numero_productos_impresos_en_comanda < cantidad_producto  order by id asc;
        ");
        return $datos->getResultArray();
    }
    public function GetProductosimpresora($id_impresora)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where id_impresora=$id_impresora  and se_imprime_en_comanda='true' and  numero_productos_impresos_en_comanda < cantidad_producto  order by id desc;
        ");
        return $datos->getResultArray();
    }
    public function productosReImpresion($id_impresora, $pedido)
    {
        $datos = $this->db->query("
        SELECT
             producto_pedido.id as id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido.codigointernoproducto,
             numero_productos_impresos_en_comanda
        FROM
             producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where id_impresora=$id_impresora  and se_imprime_en_comanda='true' and numero_de_pedido = $pedido order by id desc;
        ");
        return $datos->getResultArray();
    }
}
