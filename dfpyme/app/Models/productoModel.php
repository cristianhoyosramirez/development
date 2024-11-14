<?php

namespace App\Models;

use CodeIgniter\Model;

class productoModel extends Model
{
    protected $table      = 'producto';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    // protected $allowedFields = ['id', 'nombreproducto', 'codigointernoproducto'];
    protected $allowedFields = [
        'codigointernoproducto',
        'codigobarrasproducto',
        'referenciaproducto',
        'nombreproducto',
        'descripcionproducto',
        'codigocategoria',
        'idmarca',
        'utilidadporcentualproducto',
        'valorventaproducto',
        'aplicaprecioporcentaje',
        'idiva',
        'unidadventaproducto',
        'cantidadminimaproducto',
        'cantidadmaximaproducto',
        'estadoproducto',
        'aplicatalla',
        'aplicacolor',
        'cantidad_decimal',
        'precio_costo',
        'descto_mayor',
        'descto_distribuidor',
        'idiva_temp',
        'utilidad_2',
        'utilidad_3',
        'codigo_2',
        'codigo_3',
        'codigo_4',
        'codigo_5',
        'codigo_6',
        'codigo_7',
        'descto_3',
        'impoconsumo',
        'inicial',
        'id_tipo_inventario',
        'id_ico_producto',
        'aplica_ico',
        'se_imprime',
        'aplica_descuento',
        'id_impuesto_saludable',
        'valor_impuesto_saludable',
        'id_subcategoria',
        'favorito',
        'precio_3'
    ];

    public function autoComplete($valor)
    {

        $datos = $this->db->query("
        SELECT
        categoria.codigocategoria,
         codigointernoproducto,
          codigobarrasproducto,
          id_tipo_inventario,
          nombreproducto,
          valorventaproducto,
          aplica_descuento,
          precio_costo
      FROM
          public.producto
      INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
      WHERE
          codigobarrasproducto ilike '%$valor%' 
          OR codigointernoproducto ilike '%$valor%'
          OR nombreproducto ilike '%$valor%'
           AND permitir_categoria = 'true' and estadoproducto='true'
              
          
        ");
        return $datos->getResultArray();
    }

    public function autoCompletePro($valor)
    {

        $datos = $this->db->query("
        SELECT
        categoria.codigocategoria,
         codigointernoproducto,
          codigobarrasproducto,
          id_tipo_inventario,
          nombreproducto,
          valorventaproducto,
          aplica_descuento,
          precio_costo
      FROM
          public.producto
      INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
      WHERE
          codigobarrasproducto ilike '%$valor%' 
          OR codigointernoproducto ilike '%$valor%'
          OR nombreproducto ilike '%$valor%'
           and estadoproducto='true'
              
          
        ");
        return $datos->getResultArray();
    }

    public function inventario($valor)
    {

        $datos = $this->db->query("
        SELECT
            categoria.codigocategoria,
            codigointernoproducto,
            codigobarrasproducto,
            id_tipo_inventario,
            nombreproducto,
            valorventaproducto,
            aplica_descuento
        FROM
          public.producto
        INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
        WHERE
          codigobarrasproducto ilike '%$valor%' 
          OR codigointernoproducto ilike '%$valor%'
          OR nombreproducto ilike '%$valor%'
        ");
        return $datos->getResultArray();
    }


    public function get_productos($valor)
    {

        $datos = $this->db->query("
        SELECT
            categoria.codigocategoria,
            codigointernoproducto,
            codigobarrasproducto,
            id_tipo_inventario,
            nombreproducto,
            valorventaproducto,
            aplica_descuento
        FROM
            public.producto
        INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
        WHERE
          codigobarrasproducto ilike '%$valor%' 
          OR codigointernoproducto ilike '%$valor%'
          OR nombreproducto ilike '%$valor%'
          AND permitir_categoria = 'true' and estadoproducto='true'
        ");
        return $datos->getResultArray();
    }


    public function producto_pedido_pos($valor)
    {

        $datos = $this->db->query("
        SELECT
        codigointernoproducto,
        nombreproducto,
        valorventaproducto,
        categoria.permitir_categoria
       FROM
         public.producto
       INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
       WHERE
         nombreproducto ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true' 
       OR 
         nombreproducto ilike '%$valor%' and id_tipo_inventario = '1'  AND permitir_categoria = 'true' 
         OR 
         nombreproducto ilike '%$valor%' and id_tipo_inventario = '1'  AND permitir_categoria = 'true' 
         or codigobarrasproducto ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigobarrasproducto ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'
         
         or codigointernoproducto ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigointernoproducto ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_2 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_2 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_3 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_3 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_4 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_4 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_5 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_5 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_6 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_6 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_7 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_7 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'
         ");
        return $datos->getResultArray();
    }
    public function al_por_mayor($valor)
    {

        $datos = $this->db->query("
        SELECT
        codigointernoproducto,
        nombreproducto,
        valorventaproducto-((valorventaproducto* descto_mayor)/100) as valorventaproducto,
        categoria.permitir_categoria
       FROM
         public.producto
       INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
       WHERE
         nombreproducto ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true' 
       OR 
         nombreproducto ilike '%$valor%' and id_tipo_inventario = '1'  AND permitir_categoria = 'true' 
         or codigobarrasproducto ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigobarrasproducto ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'
         
         or codigointernoproducto ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigointernoproducto ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_2 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_2 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_3 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_3 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_4 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_4 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_5 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_5 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_6 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_6 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

         or codigo_7 ilike '%$valor%' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
         or codigo_7 ilike '%$valor%' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'
        ");
        return $datos->getResultArray();
    }

    public function getValorVentaProducto($id)
    {
        $datos = $this->db->query("
          SELECT 
             VALORVENTAPRODUCTO
          FROM PRODUCTO
            WHERE CODIGOINTERNOPRODUCTO = '$id'
        ");
        return $datos->getResultArray();
    }

    public function getTipoInventario($codigo)
    {
        $datos = $this->db->query("
          SELECT 
             id_tipo_inventario
          FROM PRODUCTO
            WHERE CODIGOINTERNOPRODUCTO = '$codigo'
        ");
        return $datos->getResultArray();
    }

    public function tipoInventario($id_categoria)
    {
        $datos = $this->db->query("
        SELECT
        id,
        codigointernoproducto,
        nombreproducto,
        valorventaproducto,
        estadoproducto,
        codigocategoria,
        id_tipo_inventario
    FROM
        producto
    WHERE
        codigocategoria = '$id_categoria' AND estadoproducto = 'true' AND id_tipo_inventario = '1' OR codigocategoria = '$id_categoria' AND estadoproducto = 'true' AND id_tipo_inventario = '3'
    ORDER BY
        nombreproducto ASC
        ");
        return $datos->getResultArray();
    }
    public function buscar_producto_por_codigo_de_barras($codigointernoproducto)
    {
        $datos = $this->db->query("
        SELECT
             codigobarrasproducto,
             codigointernoproducto,
             nombreproducto,
             valorventaproducto,
             categoria.permitir_categoria    
        FROM
            public.producto
        INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
        WHERE
            codigointernoproducto = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigointernoproducto = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'
        OR   
            codigobarrasproducto = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigobarrasproducto = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'  

        OR 
            codigo_2 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_2 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'  

        OR 
            codigo_3 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_3 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

        OR 
            codigo_4 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_4 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

        OR 
            codigo_5 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_5 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

        OR 
            codigo_6 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_6 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

        OR 
            codigo_7 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_7 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'
        ");
        return $datos->getResultArray();
    }
    public function buscar_producto_por_codigo_de_barras_al_por_mayor($codigointernoproducto)
    {
        $datos = $this->db->query("
        SELECT
             codigobarrasproducto,
             codigointernoproducto,
             nombreproducto,
             valorventaproducto-((valorventaproducto* descto_mayor)/100) as valorventaproducto,
             categoria.permitir_categoria    
        FROM
            public.producto
        INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
        WHERE
            codigointernoproducto = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigointernoproducto = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'
        OR   
            codigobarrasproducto = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigobarrasproducto = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'  

        OR 
            codigo_2 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_2 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'  

        OR 
            codigo_3 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_3 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

        OR 
            codigo_4 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_4 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

        OR 
            codigo_5 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_5 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

        OR 
            codigo_6 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_6 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'

        OR 
            codigo_7 = '$codigointernoproducto' AND id_tipo_inventario = '1' AND permitir_categoria = 'true'
        OR  codigo_7 = '$codigointernoproducto' AND id_tipo_inventario = '3' AND permitir_categoria = 'true'
        ");
        return $datos->getResultArray();
    }

    public function actualizar_factura_venta()
    {
        $datos = $this->db->query("
        select id from factura_venta WHERE id BETWEEN 26370 AND 35684;
        ");
        return $datos->getResultArray();
    }

    public function listado_de_precios($id_producto)
    {
        $datos = $this->db->query("
        SELECT
            valorventaproducto AS precio_al_detal,
            valorventaproducto -((valorventaproducto * descto_mayor) / 100) AS precio_al_por_mayor
        FROM
            producto
        WHERE
            codigointernoproducto = '$id_producto'
        ");
        return $datos->getResultArray();
    }
    public function producto()
    {
        $datos = $this->db->query("
        SELECT
            codigointernoproducto,
            nombreproducto,
            valorventaproducto,
            categoria.nombrecategoria
        FROM
            producto
        Inner join categoria on producto.codigocategoria = categoria.codigocategoria
        ");
        return $datos->getResultArray();
    }

    public function producto_categoria($id_categoria)
    {
        $datos = $this->db->query("
        SELECT
            id,
            codigointernoproducto,
            nombreproducto,
            valorventaproducto,
            estadoproducto,
            codigocategoria,
            id_tipo_inventario
        FROM
            producto
        WHERE
            codigocategoria = '$id_categoria' AND estadoproducto = 'true' AND id_tipo_inventario = '1'  or id_tipo_inventario = '3'
        ");
        return $datos->getResultArray();
    }

    public function productos()
    {
        $datos = $this->db->query("
        SELECT 
        categoria.nombrecategoria,
        producto.nombreproducto,
        producto.codigointernoproducto,
        inventario.cantidad_inventario,
        producto.valorventaproducto,
        producto.precio_costo
    FROM 
        producto
    INNER JOIN 
        categoria ON producto.codigocategoria = categoria.codigocategoria
    INNER JOIN 
        inventario ON producto.codigointernoproducto = inventario.codigointernoproducto
    where estadoproducto='true'
    ORDER BY 
        categoria.nombrecategoria ASC,
        producto.nombreproducto ASC;
    

        ");
        return $datos->getResultArray();
    }

    public function total_inventario()
    {
        $datos = $this->db->query("
        SELECT sum(precio_costo * inventario.cantidad_inventario) AS total_inventario
        FROM producto
        INNER JOIN inventario ON inventario.codigointernoproducto = producto.codigointernoproducto
   
        ");
        return $datos->getResultArray();
    }


    function get_todos_productos()
    {

        $datos = $this->db->query("
        SELECT 
        nombrecategoria,
        nombreproducto,
        codigointernoproducto,
        valorventaproducto,
        precio_costo
        
        FROM producto
        INNER JOIN categoria ON producto.codigocategoria=categoria.codigocategoria WHERE estadoproducto='true'
   
        ");
        return $datos->getResultArray();
    }
    function get_productos_borrados()
    {

        $datos = $this->db->query("
            SELECT * FROM  producto where estadoproducto = 'false'
   
        ");
        return $datos->getResultArray();
    }
    function getIdProducto($codigointernoproducto)
    {

        $datos = $this->db->query("
           select id from producto where codigointernoproducto = '$codigointernoproducto'
   
        ");
        return $datos->getResultArray();
    }
}
