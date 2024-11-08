
<?php 
$this->load->view('fondo_empleados/email/template/header');
?>

<h3>Fondo Konecta !</h3>
<p>¡ Tu solicitud ha sido aprobada y ahora puedes comenzar a disfrutar de todos los beneficios que tenemos para ti !</p>

<a style="
background: #8464fb !important;
padding: 10px;
border-radius: 5px;
text-decoration: none;
color: #f3f3f3 !important;
font-size: 18px;" target="_blank" rel="noopener noreferrer" href="<?php echo Common::get_global_config('fondo_empleados::configuracion::url_portal_productos'); ?>">Visita nuestro portal de benficios</a>


<?php 
$this->load->view('fondo_empleados/email/template/footer');
?>