<?php

namespace App\Models;

use CodeIgniter\Model;

class facturaVentaModel extends Model
{
    protected $table      = 'factura_venta';
    // Uncomment below if you want a
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'numerofactura_venta', 'nitcliente', 'idusuario_sistema',
        'idcaja', 'idestado', 'fecha_factura_venta', 'horafactura_venta', 'descuentofactura_venta',
        'fechalimitefactura_venta', 'aplica_descuento', 'estado', 'serie', 'id_resolucion_dian',
        'observaciones_generales', 'fk_usuario_mesero', 'fk_mesa', 'valor_factura', 'numero_pedido',
        'saldo', 'fecha_y_hora_factura_venta', 'id_apertura'
    ];



    public function facturas_por_rango_de_fechas($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT
            id,
            nitcliente,
	        numerofactura_venta,
	        fecha_factura_venta,
            horafactura_venta,
            fk_mesa,
            numero_pedido
        FROM
            factura_venta
        WHERE
            fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final';
         ");
        return $datos->getResultArray();
    }
    public function ventas_de_contado($fecha)
    {
        $datos = $this->db->query("
        SELECT
        MIN(id) as id_inicial,
        MAX(id) as id_final
    FROM
        factura_venta
    WHERE
        fecha_factura_venta = '$fecha' AND idestado = 1
         ");
        return $datos->getResultArray();
    }
    public function ventas_contado($id_inicial, $id_final)
    {
        $datos = $this->db->query("
        SELECT
        SUM(total) as total_contado
    FROM
        producto_factura_venta
    WHERE
        id_factura BETWEEN $id_inicial AND $id_final;
         ");
        return $datos->getResultArray();
    }
    public function ventas_contado_electronicas($id_inicial, $id_final)
    {
        $datos = $this->db->query("
        SELECT
        SUM(valor) as total_contado
    FROM
        pagos
    WHERE
        id BETWEEN $id_inicial AND $id_final and id_estado=8;
         ");
        return $datos->getResultArray();
    }
    public function ventas_de_credito($fecha)
    {
        $datos = $this->db->query("
        SELECT
        MIN(id) as id_inicial,
        MAX(id) as id_final
    FROM
        factura_venta
    WHERE
        fecha_factura_venta = '$fecha' AND idestado = 2
         ");
        return $datos->getResultArray();
    }
    public function estado_factura($id_factura)
    {
        $datos = $this->db->query("
        SELECT
            estado.idestado,
            descripcionestado,
            fechalimitefactura_venta
        FROM
            factura_venta
        INNER JOIN estado ON factura_venta.idestado = estado.idestado
        WHERE
        factura_venta.id=$id_factura
         ");
        return $datos->getResultArray();
    }
    public function facturas_venta($fecha_inicial, $fecha_final, $tipo_documento)
    {
        $datos = $this->db->query("
        SELECT
        fecha_factura_venta,
        numerofactura_venta,
        horafactura_venta,
        factura_venta.nitcliente AS nitcliente,
        estado.descripcionestado,
        cliente.nombrescliente,
        factura_venta.valor_factura,
        fechalimitefactura_venta,
        saldo,
        factura_venta.id,
        cliente.id,
        SUM(valor_factura) AS valor_facturas,
        SUM(saldo) AS saldo_pendiente
    FROM
        factura_venta
    INNER JOIN estado ON factura_venta.idestado = estado.idestado
    INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
    WHERE
        fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' AND factura_venta.idestado = $tipo_documento
    GROUP BY
        fecha_factura_venta,
        numerofactura_venta,
        horafactura_venta,
        factura_venta.nitcliente,
        estado.descripcionestado,
        cliente.nombrescliente,
        factura_venta.valor_factura,
        fechalimitefactura_venta,
        saldo,
        factura_venta.id,
        cliente.id
         ");
        return $datos->getResultArray();
    }
    public function valor_facturas_venta($fecha_inicial, $fecha_final, $tipo_documento)
    {
        $datos = $this->db->query("
        SELECT
            sum(valor_factura) as valor_facturas
        FROM
            factura_venta
        WHERE
        fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' AND factura_venta.idestado = $tipo_documento;
         ");
        return $datos->getResultArray();
    }
    public function encabezado_facturas_venta($id_factura)
    {
        $datos = $this->db->query("
        SELECT
        fecha_factura_venta,
        numerofactura_venta,
        horafactura_venta,
        nitcliente
    FROM
        factura_venta
    WHERE
        id = $id_factura order by id
         ");
        return $datos->getResultArray();
    }

    function detalle_de_ventas($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT
            id,
            fecha_factura_venta,
            horafactura_venta,
            numerofactura_venta,
            fechalimitefactura_venta,
            idestado,
            valor_factura
            
        FROM
            factura_venta
        WHERE
        fecha_y_hora_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
           
            ");
        return $datos->getResultArray();
    }
    function detalle_de_ventas_sin_cierre($fecha_apertura, $fecha_actual)
    {

        $datos = $this->db->query("
        SELECT
            id,
            fecha_factura_venta,
            horafactura_venta,
            numerofactura_venta,
            fechalimitefactura_venta,
            idestado,
            valor_factura
            
        FROM
            factura_venta
        WHERE
        fecha_y_hora_factura_venta BETWEEN '$fecha_apertura' AND '$fecha_actual' 
        order by id desc
           
            ");
        return $datos->getResultArray();
    }
    function consulta_producto($id_factura)
    {

        $datos = $this->db->query("
        SELECT
            cliente.nombrescliente,
            usuario_sistema.nombresusuario_sistema,
            caja.numerocaja
        FROM
            factura_venta
    INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
    INNER JOIN usuario_sistema ON factura_venta.idusuario_sistema = usuario_sistema.idusuario_sistema
    INNER JOIN caja ON factura_venta.idcaja = caja.idcaja
    WHERE
        factura_venta.id = $id_factura
           
            ");
        return $datos->getResultArray();
    }

    function registro_inicial($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT Min(numerofactura_venta) AS regitro_inicial
        FROM   factura_venta
        WHERE  fecha_y_hora_factura_venta BETWEEN
               '$fecha_inicial' AND '$fecha_final' 
           
            ");
        return $datos->getResultArray();
    }

    function registro_final($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT Max(numerofactura_venta) AS regitro_final
        FROM   factura_venta
        WHERE  fecha_y_hora_factura_venta BETWEEN
               '$fecha_inicial' AND '$fecha_final' 
           
            ");
        return $datos->getResultArray();
    }

    function total_registros($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT count(numerofactura_venta) AS total_registros
        FROM   factura_venta
        WHERE  fecha_y_hora_factura_venta BETWEEN
               '$fecha_inicial' AND '$fecha_final' 
           
            ");
        return $datos->getResultArray();
    }

    public function venta_contado($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        select sum(valor_factura) as total_ventas_contado from factura_venta where fecha_y_hora_factura_venta between '$fecha_inicial' and '$fecha_final' and idestado=1
         ");
        return $datos->getResultArray();
    }
    public function venta_credito($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        select sum(valor_factura) as total_ventas_credito from factura_venta where fecha_y_hora_factura_venta between '$fecha_inicial' and '$fecha_final' and idestado=2
         ");
        return $datos->getResultArray();
    }


    function factura_venta(
        $numero_factura,
        $nit_cliente,
        $id_usuario,
        $estado,
        $hora,
        $fecha,
        $serie,
        $id_resolucion,
        $observaciones,
        $fk_usuario_mesero,
        $saldo,
        $valor_total,
        $fk_mesa,
        $numero_pedido,
        $fecha_y_hora,
        $descuento,
        $propina,
        $id_apertura

    ) {

        $data = [
            'numerofactura_venta' =>  $numero_factura,
            'nitcliente' => $nit_cliente,
            'idusuario_sistema' => $id_usuario,
            'idcaja' => 1,
            'idestado' => $estado,
            'fecha_factura_venta' => $fecha,
            'horafactura_venta' => $hora,
            'descuentofactura_venta' => 0,
            'fechalimitefactura_venta' => $fecha,
            'aplica_descuento' => false,
            'estado' => true,
            'serie' => $serie,
            'id_resolucion_dian' => $id_resolucion,
            'observaciones_generales' => $observaciones,
            'fk_usuario_mesero' => $fk_usuario_mesero,
            'saldo' => $saldo,
            'valor_factura' => $valor_total,
            'fk_mesa' => $fk_mesa,
            'numero_pedido' => $numero_pedido,
            'fecha_y_hora_factura_venta' => $fecha_y_hora,
            'descuento' => $descuento,
            'propina' => $propina,
            'id_apertura' => $id_apertura

        ];

        $factura_venta = $this->db->table('factura_venta');
        $factura_venta->insert($data);
        return $this->db->insertID();
    }

    function por_defecto($fecha)
    {
        $datos = $this->db->query("
            SELECT
                factura_venta.id,
                factura_venta.idestado,
                factura_venta.nitcliente,
                cliente.nombrescliente,
                descripcionestado,
                numerofactura_venta,
                fecha_factura_venta,
                fechalimitefactura_venta,
                valor_factura,
                saldo
            FROM
                factura_venta
            INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
            INNER JOIN estado ON estado.idestado = factura_venta.idestado
            WHERE
                fecha_factura_venta = '$fecha'
         ");
        return $datos->getResultArray();
    }
    function valor_venta($fecha)
    {
        $datos = $this->db->query("
        SELECT
        SUM(valor_factura) as valor_venta
    FROM
        factura_venta
    WHERE
        fecha_factura_venta = '$fecha'
         ");
        return $datos->getResultArray();
    }
    function valor_saldo($fecha)
    {
        $datos = $this->db->query("
        SELECT
        SUM(saldo) as saldo_venta
    FROM
        factura_venta
    WHERE
        fecha_factura_venta = '$fecha'
         ");
        return $datos->getResultArray();
    }

    function consultar_por_fecha($fecha_inicial, $fecha_final, $id_estado)
    {
        $datos = $this->db->query("
        SELECT
            factura_venta.nitcliente
        FROM
            factura_venta
        WHERE
            fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' AND idestado = $id_estado
         ");
        return $datos->getResultArray();
    }
    function saldo_factura($fecha_inicial, $fecha_final, $id_estado)
    {
        $datos = $this->db->query("
        SELECT
            sum(saldo) as saldo
        FROM
            factura_venta
        WHERE
            fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' AND idestado = $id_estado
         ");
        return $datos->getResultArray();
    }
    function total_saldo_factura($fecha_inicial, $fecha_final, $id_estado)
    {
        $datos = $this->db->query("
        SELECT
            sum(saldo) as saldo
        FROM
            factura_venta
        WHERE
            fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' AND idestado = $id_estado
         ");
        return $datos->getResultArray();
    }

    function busqueda_documento($documento)
    {
        $datos = $this->db->query("
        SELECT
        factura_venta.nitcliente,
        cliente.nombrescliente,
        estado.descripcionestado,
        numerofactura_venta fecha_factura_venta,
        horafactura_venta,
        fechalimitefactura_venta,
        valor_factura,
        factura_venta.id,
        factura_venta.idestado,
        numerofactura_venta
    FROM
        factura_venta
    INNER JOIN cliente ON cliente.nitcliente = factura_venta.nitcliente
    INNER JOIN estado ON estado.idestado = factura_venta.idestado
    WHERE
        numerofactura_venta = '$documento'
         ");
        return $datos->getResultArray();
    }

   
}
