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
        'precio_3',
        'kit',
        'id_impresora',
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

        public function getProductos($valor)
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
    precio_costo,
    kit
FROM
    public.producto
INNER JOIN categoria ON producto.codigocategoria = categoria.codigocategoria
WHERE
    id_tipo_inventario IN (1, 3)  -- 🔹 Filtra solo productos con id_tipo_inventario 1 o 4
    AND estadoproducto = 'true'   -- 🔹 Filtra productos activos
    AND (
        codigobarrasproducto ILIKE '%$valor%' 
        OR codigointernoproducto ILIKE '%$valor%'
        OR nombreproducto ILIKE '%$valor%'
    );

              
          
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
     id_tipo_inventario IN (1, 4) 
AND id_tipo_inventario NOT IN (3)
    AND estadoproducto = 'true'   -- 🔹 Filtra productos activos
    AND (
        codigobarrasproducto ILIKE '%$valor%' 
        OR codigointernoproducto ILIKE '%$valor%'
        OR nombreproducto ILIKE '%$valor%'
    );

              
          
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
    id_tipo_inventario IN (1, 4)  --  Filtro principal
    AND (
        codigobarrasproducto ILIKE '%$valor%' 
        OR codigointernoproducto ILIKE '%$valor%'
        OR nombreproducto ILIKE '%$valor%'
    );

        ");
        return $datos->getResultArray();
    }

    public function GetInventarioSalida($valor)
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
    id_tipo_inventario IN (1, 3)  --  Filtro principal
    AND (
        codigobarrasproducto ILIKE '%$valor%' 
        OR codigointernoproducto ILIKE '%$valor%'
        OR nombreproducto ILIKE '%$valor%'
    );

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
    function getTipoProducto($id)
    {

        $datos = $this->db->query("
           SELECT 
                tipo_inventario.nombre,
                producto.id_tipo_inventario,
                tipo_inventario.descripcion
           FROM 
                tipo_inventario 
            INNER JOIN 
                producto 
            ON 
                producto.id_tipo_inventario = tipo_inventario.id 
            WHERE 
                producto.id = $id;
        ");
        return $datos->getResultArray();
    }
    function getIngredientes($codigo)
    {

        $datos = $this->db->query("
SELECT 
    producto.nombreproducto, 
    inventario.cantidad_inventario, 
    producto_fabricado.cantidad AS cantidad_receta,
    prod_proceso AS codigointernoproducto,
    precio_costo,
    SUM(precio_costo * producto_fabricado.cantidad) AS costo_producto
FROM 
    producto_fabricado
INNER JOIN 
    producto 
    ON producto_fabricado.prod_proceso = producto.codigointernoproducto
INNER JOIN 
    inventario 
    ON inventario.codigointernoproducto = producto_fabricado.prod_proceso
WHERE 
    producto_fabricado.prod_fabricado = '$codigo'
GROUP BY 
    producto.nombreproducto, 
    inventario.cantidad_inventario, 
    producto_fabricado.cantidad, 
    prod_proceso, 
    precio_costo;

        ");
        return $datos->getResultArray();
    }

    function IvaProducto()
    {

        $datos = $this->db->query("
           SELECT 
            nombreproducto, 
            codigointernoproducto, 
            conceptoiva,
            valoriva
        FROM 
            producto
        INNER JOIN 
            iva 
        ON 
            producto.idiva = iva.idiva
        WHERE 
            aplica_ico = 'false';
        ");
        return $datos->getResultArray();
    }
    function IncProducto()
    {

        $datos = $this->db->query("
           SELECT 
            nombreproducto, 
            codigointernoproducto, 
            valor_ico,
            codigointernoproducto
        FROM 
            producto
        INNER JOIN 
            ico_consumo
        ON 
            producto.id_ico_producto = ico_consumo.id_ico
        WHERE aplica_ico = 'true';
        ");
        return $datos->getResultArray();
    }

    /**
     * Obtiene una lista de productos con su respectivo inventario.
     *
     * Esta función realiza una consulta a la base de datos en las tablas producto unida con inventario  para obtener 
     * información de los productos junto con su cantidad en inventario, 
     * considerando únicamente ciertos tipos de inventario (1, 4, 2). 
     * 1 = producto venta , 2= ?? , 4 = insumos.
     * Los resultados se ordenan alfabéticamente por el nombre del producto.
     *
     * @return array Retorna un arreglo asociativo con los siguientes campos:
     *               - id (int): ID del producto.
     *               - codigointernoproducto (string): Código interno del producto.
     *               - nombreproducto (string): Nombre del producto.
     *               - cantidad_inventario (int): Cantidad disponible en el inventario.
     *
     * @throws DatabaseException Si ocurre algún error en la ejecución de la consulta.
     */
    function ProductoInventario()
    {

        $datos = $this->db->query("
    SELECT 
    producto.id,
    producto.codigointernoproducto,
    producto.nombreproducto,
    inventario.cantidad_inventario
FROM 
    producto
INNER JOIN 
    inventario 
    ON producto.codigointernoproducto = inventario.codigointernoproducto
WHERE 
    id_tipo_inventario IN (1, 4, 2)
ORDER BY 
    producto.nombreproducto ASC;

        ");
        return $datos->getResultArray();
    }
    function getInventario()
    {

        $datos = $this->db->query("
SELECT 
    categoria.nombrecategoria,
    producto.id,
    producto.codigointernoproducto,
    producto.nombreproducto,
    inventario.cantidad_inventario,
    REPLACE(TO_CHAR(precio_costo, 'FM999,999,999'), ',', '.') AS costo_unitario,  -- Reemplazamos las comas por puntos
    REPLACE(TO_CHAR(precio_costo * cantidad_inventario, 'FM999,999,999'), ',', '.') AS costo_producto  -- Reemplazamos las comas por puntos
FROM 
    producto
INNER JOIN 
    inventario 
    ON producto.codigointernoproducto = inventario.codigointernoproducto
INNER JOIN 
    categoria 
    ON producto.codigocategoria = categoria.codigocategoria
WHERE 
    id_tipo_inventario IN (1, 4, 2,3)
ORDER BY 
    categoria.nombrecategoria ASC,
    producto.nombreproducto ASC;
        ");
        return $datos->getResultArray();
    }
    function ExcelInventario()
    {

        $datos = $this->db->query("
SELECT 
    categoria.nombrecategoria,
    producto.id,
    producto.codigointernoproducto,
    producto.nombreproducto,
    inventario.cantidad_inventario,
    precio_costo AS costo_unitario, 
    (precio_costo * cantidad_inventario) AS costo_producto  
FROM 
    producto
INNER JOIN 
    inventario 
    ON producto.codigointernoproducto = inventario.codigointernoproducto
INNER JOIN 
    categoria 
    ON producto.codigocategoria = categoria.codigocategoria
WHERE 
    id_tipo_inventario IN (1, 4, 2)
ORDER BY 
    categoria.nombrecategoria ASC,
    producto.nombreproducto ASC
        ");
        return $datos->getResultArray();
    }

    function getTotalReceta($codigo)
    {

        $datos = $this->db->query("
SELECT   
    sum(precio_costo * producto_fabricado.cantidad) as costo_total
FROM 
    producto_fabricado 
INNER JOIN 
    producto 
ON 
    producto_fabricado.prod_proceso = producto.codigointernoproducto 
WHERE 
    producto_fabricado.prod_fabricado = '$codigo'
   
   
        ");
        return $datos->getResultArray();
    }
    function getProducto($codigo)
    {

        $datos = $this->db->query("
SELECT 
    id,
    producto.codigointernoproducto,
    nombreproducto,
    cantidad_inventario
   
FROM 
    producto
    inner join inventario on inventario.codigointernoproducto=producto.codigointernoproducto
WHERE 
    (producto.codigointernoproducto ILIKE '%$codigo%' OR nombreproducto ILIKE '%$codigo%')
    AND id_tipo_inventario IN (1, 2, 4);
        ");
        return $datos->getResultArray();
    }
    function TotalInv()
    {

        $datos = $this->db->query("
SELECT 
    sum(precio_costo * cantidad_inventario) as costo_total
FROM 
    producto
INNER JOIN 
    inventario 
    ON producto.codigointernoproducto = inventario.codigointernoproducto
WHERE 
    id_tipo_inventario IN (1, 4, 2,3)
        ");
        return $datos->getResultArray();
    }
    function TotalInvCat($codigo)
    {

        $datos = $this->db->query("
SELECT 
    sum(precio_costo * cantidad_inventario) as costo_total
FROM 
    producto
INNER JOIN 
    inventario 
    ON producto.codigointernoproducto = inventario.codigointernoproducto
WHERE 
    codigocategoria='$codigo'
        ");
        return $datos->getResultArray();
    }
    function CostoProducto($codigo)
    {

        $datos = $this->db->query("
        SELECT 
            precio_costo,valorventaproducto
        FROM 
            producto

        WHERE 
            codigointernoproducto='$codigo'
        ");
        return $datos->getResultArray();
    }

    function Getinv($valor)
    {

        $datos = $this->db->query("
        SELECT 
    categoria.nombrecategoria,
    producto.id,
    producto.codigointernoproducto,
    producto.nombreproducto,
    inventario.cantidad_inventario,
    REPLACE(TO_CHAR(precio_costo, 'FM999,999,999'), ',', '.') AS costo_unitario,  -- Reemplazamos las comas por puntos
    REPLACE(TO_CHAR(precio_costo * cantidad_inventario, 'FM999,999,999'), ',', '.') AS costo_producto  -- Reemplazamos las comas por puntos
FROM 
    producto
INNER JOIN 
    inventario 
    ON producto.codigointernoproducto = inventario.codigointernoproducto
INNER JOIN 
    categoria 
    ON producto.codigocategoria = categoria.codigocategoria
WHERE 
    id_tipo_inventario IN (1, 4, 2,3)  AND (producto.nombreproducto ILIKE '%$valor%' OR producto.codigointernoproducto ILIKE '%$valor%')
ORDER BY 
    categoria.nombrecategoria ASC,
    producto.nombreproducto ASC;
        ");
        return $datos->getResultArray();
    }
    function GetinvCategoria($valor)
    {

        $datos = $this->db->query("
 SELECT 
    categoria.nombrecategoria,
    producto.id,
    producto.codigointernoproducto,
    producto.nombreproducto,
    inventario.cantidad_inventario,
    REPLACE(TO_CHAR(precio_costo, 'FM999,999,999'), ',', '.') AS costo_unitario,  -- Reemplazamos las comas por puntos
    REPLACE(TO_CHAR(precio_costo * cantidad_inventario, 'FM999,999,999'), ',', '.') AS costo_producto  -- Reemplazamos las comas por puntos
FROM 
    producto
INNER JOIN 
    inventario 
    ON producto.codigointernoproducto = inventario.codigointernoproducto
INNER JOIN 
    categoria 
    ON producto.codigocategoria = categoria.codigocategoria
WHERE 
    id_tipo_inventario IN (1, 4, 2,3)  AND producto.codigocategoria='$valor'
ORDER BY 
    categoria.nombrecategoria ASC,
    producto.nombreproducto ASC
        ");
        return $datos->getResultArray();
    }

    function GetRecetas($valor)
    {

        $datos = $this->db->query("
        SELECT codigointernoproducto, nombreproducto,precio_costo,valorventaproducto
        FROM producto 
        WHERE id_tipo_inventario = 3 
        AND (nombreproducto ILIKE '%$valor%' OR codigointernoproducto ILIKE '%$valor%');

        ");
        return $datos->getResultArray();
    }

    function GetInsumos($valor)
    {

        $datos = $this->db->query("
        SELECT codigointernoproducto, nombreproducto,precio_costo,valorventaproducto 
        FROM producto 
        WHERE id_tipo_inventario = 4 
        AND (nombreproducto ILIKE '%$valor%' OR codigointernoproducto ILIKE '%$valor%');

        ");
        return $datos->getResultArray();
    }
    function GetAllInsumos()
    {

        $datos = $this->db->query("
        SELECT codigointernoproducto, nombreproducto,precio_costo,valorventaproducto 
        FROM producto 
        WHERE id_tipo_inventario = 4 order by nombreproducto asc ;

        ");
        return $datos->getResultArray();
    }
    function GetReceta($valor)
    {

        $datos = $this->db->query("
      select nombreproducto from producto where codigointernoproducto='$valor'

        ");
        return $datos->getResultArray();
    }
    function GetValVenta($valor)
    {

        $datos = $this->db->query("
            select valorventaproducto from producto where codigointernoproducto='$valor'
        ");
        return $datos->getResultArray();
    }
    function GetCostoUnitario($valor)
    {

        $datos = $this->db->query("
            select precio_costo from producto where codigointernoproducto='$valor'
        ");
        return $datos->getResultArray();
    }

    function productosVenta()
    {

        $datos = $this->db->query("
           SELECT  id,nombreproducto,codigointernoproducto,
           valorventaproducto
FROM producto 
WHERE id_tipo_inventario IN (1, 3) 
AND id_tipo_inventario NOT IN (4)
order by nombreproducto asc;
        ");
        return $datos->getResultArray();
    }
}
