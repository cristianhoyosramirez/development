<?php

namespace App\Libraries;

class data_table
{
    /**
     * Calcular imuestos
     * @param   $cod(igo_interno
     * 
     */
    public function row_data_table($id_estado, $id_factura)
    {

        if ($id_estado == 8) {
            $status = model('facturaElectronicaModel')->select('id_status')->where('id', $id_factura)->first();

            if ($status['id_status'] == 1) {  //Si el estado es 1 es por que se no se envio 
                $sub_array[] = '<a  class="btn btn-outline-dark btn-icon " title="Documento no enviado " onclick="sendInvoice(' . $id_factura . ')" >
       
                            
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-cancel">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 19h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5" />
                            <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M17 21l4 -4" />
                            <path d="M3 7l9 6l9 -6" />
                          </svg>       
                            
                            </a> 

        <a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_electronica(' . $id_factura . ')" >
        <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>
        

    <a  class="btn bg-outline-muted-lt btn-icon " title="Ver detalle" onclick="detalle_f_e(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
    
    
    <a  class="btn bg-outline-red-lt btn-icon " title="Eliminar" onclick="eliminar_f_e(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
  <path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" stroke-width="0" fill="currentColor" />
  <path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" stroke-width="0" fill="currentColor" />
</svg></a>
    
    
    
    ';
            }
            if ($status['id_status'] == 2) { //Dian Aceptado 

                $pdf_url = model('facturaElectronicaModel')->select('pdf_url')->where('id', $id_factura)->first();

                $sub_array[] = '
        

        <a  class="btn btn-outline-success btn-icon " title="Imprimir factura de venta " onclick="imprimir_electronica(' . $id_factura . ')" >
        <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>
        

    <a  class="btn bg-muted-outline-lt btn-icon " title="Ver detalle" onclick="detalle_f_e(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
    
    
<a href="' . $pdf_url['pdf_url'] . '" target="_blank" class="cursor-pointer">
<img title="Descargar pdf" src="' . base_url() . '/Assets/img/pdf.png" width="40" height="40" />
</a>';
            }

            if ($status['id_status'] == 3) {  //Dian Rechazado 
                $sub_array[] = '<a  class="btn btn-outline-red btn-icon " title="Documento rechazado" onclick="sendInvoice(' . $id_factura . ')" >
       
                            
                <!-- Download SVG icon from http://tabler-icons.io/i/receipt-off -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16m2 -2h10a2 2 0 0 1 2 2v10m0 4.01v1.99l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" /><line x1="11" y1="7" x2="15" y2="7" /><line x1="9" y1="11" x2="11" y2="11" /><line x1="13" y1="15" x2="15" y2="15" /><line x1="15" y1="11" x2="15" y2="11.01" /><line x1="3" y1="3" x2="21" y2="21" /></svg>     
                            
                            </a> 

        <a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_electronica(' . $id_factura . ')" >
        <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>
        

    <a  class="btn bg-outline-muted-lt btn-icon " title="Ver detalle" onclick="detalle_f_e(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>';
            }
            if ($status['id_status'] == 4) {  //Dian Rechazado 
                $sub_array[] = '<a  class="btn btn-outline-yellow btn-icon " title="Documento con error " onclick="sendInvoiceDian(' . $id_factura . ')" >
       
                            
                <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
	<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                            
                            </a> 

        <a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_electronica(' . $id_factura . ')" >
        <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>
        

    <a  class="btn bg-outline-muted-lt btn-icon " title="Ver detalle" onclick="detalle_f_e(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>';
            }
        }


        if ($id_estado  == 2 ) {  //Cuando es a credito 
            $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(' . $id_factura . ')" >
        <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>  
    <a  class="btn bg-muted-outline-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
    <a onclick="abono_credito(' . $id_factura . ')"  title="Realizar pago documento " class="btn btn-outline-primary  btn-icon" ><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg> </a> 
     ';
        }

        if ($id_estado  == 1 || $id_estado  == 6) {
            $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(' . $id_factura . ')" >
        <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>  
    <a  class="btn bg-muted-outline-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
     ';
        }
        if ($id_estado  == 7) {
            $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(' . $id_factura . ')" >
        <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>  
    <a  class="btn bg-muted-outline-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>

    <a  class="btn bg-muted-outline-yellow btn-icon " title="Pasar a factura " onclick="pasar_a_factura(' . $id_factura . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
    <!-- Download SVG icon from http://tabler-icons.io/i/switch-horizontal -->
	<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="16 3 20 7 16 11" /><line x1="10" y1="7" x2="20" y2="7" /><polyline points="8 13 4 17 8 21" /><line x1="4" y1="17" x2="13" y2="17" /></svg></a>

     ';
        }




        return $sub_array;
    }
}
