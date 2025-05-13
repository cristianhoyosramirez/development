<?php


namespace App\Models;

use CodeIgniter\Model;

class productosBorradosModel extends Model
{
    protected $table      = 'productos_borrados';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['codigointernoproducto', 'cantidad','fecha_eliminacion','hora_eliminacion','usuario_eliminacion','pedido','id_mesero'];

    public function getProductosBorrados($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT  
        eliminador.nombresusuario_sistema AS nombresusuario_sistema,
        producto.nombreproducto,
        fecha_eliminacion,
        hora_eliminacion,
        productos_borrados.codigointernoproducto,
        cantidad,
        pedido,
        mesero.nombresusuario_sistema AS mesero_nombre
    FROM 
        productos_borrados 
    INNER JOIN 
        usuario_sistema AS eliminador ON productos_borrados.usuario_eliminacion = eliminador.idusuario_sistema 
    INNER JOIN 
        usuario_sistema AS mesero ON productos_borrados.id_mesero = mesero.idusuario_sistema 
    INNER JOIN 
        producto ON productos_borrados.codigointernoproducto = producto.codigointernoproducto
    WHERE 
        fecha_eliminacion BETWEEN '$fecha_inicial' AND '$fecha_final';
    
        ");
        return $datos->getResultArray();
    }
    public function get_productos_borrados($id_pedido)
    {
        $datos = $this->db->query("
        SELECT 
        producto.nombreproducto,
        productos_borrados.codigointernoproducto,
        cantidad,
        fecha_eliminacion,
        hora_eliminacion,
        mesero.nombresusuario_sistema AS mesero,
        eliminador.nombresusuario_sistema AS usuario_eliminacion
    FROM 
        productos_borrados
    INNER JOIN 
        producto ON producto.codigointernoproducto = productos_borrados.codigointernoproducto
    INNER JOIN 
        usuario_sistema AS mesero ON productos_borrados.id_mesero = mesero.idusuario_sistema
    INNER JOIN 
        usuario_sistema AS eliminador ON productos_borrados.usuario_eliminacion = eliminador.idusuario_sistema
    WHERE 
        pedido = $id_pedido;
        ");
        return $datos->getResultArray();
    }

    public function get_fecha_inicial()
    {
        $datos = $this->db->query("
            SELECT MIN(fecha) AS fecha_inicial
            FROM pagos;

        ");
        return $datos->getResultArray();
    }
    public function get_fecha_final()
    {
        $datos = $this->db->query("
            SELECT MAX(fecha) AS fecha_final
            FROM pagos;

        ");
        return $datos->getResultArray();
    }

}
