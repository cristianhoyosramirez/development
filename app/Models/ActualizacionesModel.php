<?php

namespace App\Models;

use CodeIgniter\Model;

class ActualizacionesModel extends Model
{
    public function add_colum_url()
    {
        $datos = $this->db->query("
            DO $$
            BEGIN
            IF NOT EXISTS (
            SELECT 1 
            FROM information_schema.columns 
            WHERE table_name = 'configuracion_pedido' 
            AND column_name = 'url'
        ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN url VARCHAR(50);
        END IF;
        END $$;

         ");
        return $datos->getResultArray();
    }

    function add_colum_altura()
    {

        $datos = $this->db->query("
           DO $$
            BEGIN
            IF NOT EXISTS (
            SELECT 1 
            FROM information_schema.columns 
            WHERE table_name = 'configuracion_pedido' 
            AND column_name = 'altura'
            ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN altura INT;
    END IF;
END $$;


         ");
        return $datos->getResultArray();
    }
    function add_colum_codigo()
    {

        $datos = $this->db->query("
       DO $$
        BEGIN
        IF NOT EXISTS (
        SELECT 1 
        FROM information_schema.columns 
        WHERE table_name = 'configuracion_pedido' 
        AND column_name = 'codigo_pantalla'
        ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN codigo_pantalla boolean;
        ALTER TABLE configuracion_pedido ALTER COLUMN codigo_pantalla SET DEFAULT false;
        COMMENT ON COLUMN configuracion_pedido.codigo_pantalla IS 'Este campo me permite ver en pantalla al momento de llamar los productos si concateno el codigo con el nombre del producto';

    END IF;
    END $$;



         ");
        return $datos->getResultArray();
    }
    function add_colum_nombre_comercial()
    {

        $datos = $this->db->query("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM information_schema.columns 
                    WHERE table_name = 'medio_pago' 
                    AND column_name = 'nombre_comercial'
                ) THEN
                    ALTER TABLE medio_pago ADD COLUMN nombre_comercial character varying(100);
                END IF;
            END $$;
         ");
        return $datos->getResultArray();
    }
    function update_nombre_comercial()
    {

        $datos = $this->db->query("
        UPDATE medio_pago SET nombre_comercial = 'INSTRUMENTO NO DEFINIDO' WHERE codigo = '1';
        UPDATE medio_pago SET nombre_comercial = 'EFECTIVO' WHERE codigo = '10';
        UPDATE medio_pago SET nombre_comercial = 'REVERSIÓN CRÉDITO AHORRO' WHERE codigo = '11';
        UPDATE medio_pago SET nombre_comercial = 'REVERSIÓN DÉBITO AHORRO' WHERE codigo = '12';
        UPDATE medio_pago SET nombre_comercial = 'CRÉDITO AHORRO' WHERE codigo = '13';
        UPDATE medio_pago SET nombre_comercial = 'DÉBITO AHORRO' WHERE codigo = '14';
        UPDATE medio_pago SET nombre_comercial = 'BOOKENTRY CRÉDITO' WHERE codigo = '15';
        UPDATE medio_pago SET nombre_comercial = 'BOOKENTRY DÉBITO' WHERE codigo = '16';
        UPDATE medio_pago SET nombre_comercial = 'CRÉDITO PAGO NEGOCIO CORPORATIVO (CTP)' WHERE codigo = '19';
        UPDATE medio_pago SET nombre_comercial = 'CRÉDITO ACH' WHERE codigo = '2';
        UPDATE medio_pago SET nombre_comercial = 'CHEQUE' WHERE codigo = '20';
        UPDATE medio_pago SET nombre_comercial = 'PROYECTO BANCARIO' WHERE codigo = '21';
        UPDATE medio_pago SET nombre_comercial = 'NOTA CAMBIARIA ESPERANDO ACEPTACIÓN' WHERE codigo = '24';
        UPDATE medio_pago SET nombre_comercial = 'CHEQUE CERTIFICADO' WHERE codigo = '25';
        UPDATE medio_pago SET nombre_comercial = 'CHEQUE LOCAL' WHERE codigo = '26';
        UPDATE medio_pago SET nombre_comercial = 'DÉBITO ACH' WHERE codigo = '3';
        UPDATE medio_pago SET nombre_comercial = 'TRANSFERENCIA DÉBITO' WHERE codigo = '31';
        UPDATE medio_pago SET nombre_comercial = 'PAGO Y DEPÓSITO PRE ACORDADO (PPD)' WHERE codigo = '34';
        UPDATE medio_pago SET nombre_comercial = 'PAGO NEGOCIO CORPORATIVO AHORROS CRÉDITO (CTP)' WHERE codigo = '37';
        UPDATE medio_pago SET nombre_comercial = 'REVERSIÓN DÉBITO DE DEMANDA ACH' WHERE codigo = '4';
        UPDATE medio_pago SET nombre_comercial = 'CONSIGNACIÓN BANCARIA' WHERE codigo = '42';
        UPDATE medio_pago SET nombre_comercial = 'NOTA CAMBIARIA' WHERE codigo = '44';
        UPDATE medio_pago SET nombre_comercial = 'TRANSFERENCIA CRÉDITO BANCARIO' WHERE codigo = '45';
        UPDATE medio_pago SET nombre_comercial = 'TRANSFERENCIA DÉBITO INTERBANCARIO' WHERE codigo = '46';
        UPDATE medio_pago SET nombre_comercial = 'TRANSFERENCIA DÉBITO BANCARIA' WHERE codigo = '47';
        UPDATE medio_pago SET nombre_comercial = 'TARJETA DE CRÉDITO' WHERE codigo = '48';
        UPDATE medio_pago SET nombre_comercial = 'TARJETA DÉBITO' WHERE codigo = '49';
        UPDATE medio_pago SET nombre_comercial = 'REVERSIÓN CRÉDITO DE DEMANDA ACH' WHERE codigo = '5';
        UPDATE medio_pago SET nombre_comercial = 'POSTGIRO' WHERE codigo = '50';
        UPDATE medio_pago SET nombre_comercial = 'PAGO COMERCIAL URGENTE' WHERE codigo = '52';
        UPDATE medio_pago SET nombre_comercial = 'PAGO TESORERÍA URGENTE' WHERE codigo = '53';
        UPDATE medio_pago SET nombre_comercial = 'CRÉDITO DE DEMANDA ACH' WHERE codigo = '6';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA' WHERE codigo = '60';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR' WHERE codigo = '61';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR, AVALADA POR EL BANCO' WHERE codigo = '62';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR, AVALADA POR UN TERCERO' WHERE codigo = '63';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR EL BANCO' WHERE codigo = '64';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR UN BANCO AVALADA POR OTRO BANCO' WHERE codigo = '65';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA' WHERE codigo = '66';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR UN TERCERO AVALADA POR UN BANCO' WHERE codigo = '67';
        UPDATE medio_pago SET nombre_comercial = 'DÉBITO DE DEMANDA ACH' WHERE codigo = '7';
        UPDATE medio_pago SET nombre_comercial = 'BONOS' WHERE codigo = '71';
        UPDATE medio_pago SET nombre_comercial = 'VALES' WHERE codigo = '72';
        UPDATE medio_pago SET nombre_comercial = 'RETIRO DE UNA NOTA POR EL ACREEDOR SOBRE UN TERCERO' WHERE codigo = '77';
        UPDATE medio_pago SET nombre_comercial = 'RETIRO DE UNA NOTA POR EL ACREEDOR SOBRE UN TERCERO AVALADA POR UN BANCO' WHERE codigo = '78';
        UPDATE medio_pago SET nombre_comercial = 'CLEARING NACIONAL O REGIONAL' WHERE codigo = '9';
        UPDATE medio_pago SET nombre_comercial = 'GIRO REFERENCIADO' WHERE codigo = '93';
        UPDATE medio_pago SET nombre_comercial = 'GIRO URGENTE' WHERE codigo = '94';
        UPDATE medio_pago SET nombre_comercial = 'GIRO FORMATO ABIERTO' WHERE codigo = '95';
        UPDATE medio_pago SET nombre_comercial = 'MÉTODO DE PAGO SOLICITADO NO USADO' WHERE codigo = '96';
        UPDATE medio_pago SET nombre_comercial = 'CLEARING ENTRE PARTNERS' WHERE codigo = '97';
         ");
        
    }

    function add_column_ruta()
    {

        $datos = $this->db->query("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM information_schema.columns 
                    WHERE table_name = 'forma_pago' 
                    AND column_name = 'ruta'
                ) THEN
                    ALTER TABLE forma_pago ADD COLUMN ruta character varying(100);
                END IF;
            END $$;
         ");
        return $datos->getResultArray();
    }
}
