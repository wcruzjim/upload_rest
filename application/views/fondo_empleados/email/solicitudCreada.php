
<?php 
$this->load->view('fondo_empleados/email/template/header');
?>

<h3>Fondo Konecta !</h3>
<p>¡Hemos recibido tu solicitud !. Para continuar el proceso de registro por favor envía foto de la cedula por ambos lados, y una foto nítida, en lo posible en un fondo blanco y recortada.</p>

<p>Recuerda que para el proceso de la firma electrónica te estará llegando un mensaje de WhatsApp para finalizar el proceso</p>

<a style="
background: #8464fb !important;
padding: 10px;
border-radius: 5px;
text-decoration: none;
color: #f3f3f3 !important;
font-size: 18px;" target="_blank" rel="noopener noreferrer" href="<?php echo Common::get_global_config('fondo_empleados::configuracion::url_formulario_registro'); ?>">Diligenciar formulario</a>

<p style="opacity: 0">-</p>
<p> Serás notificado una vez nuestro equipo reciba y valide tus documentos. </p>



<?php 
$this->load->view('fondo_empleados/email/template/footer');
?>