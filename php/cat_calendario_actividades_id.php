<?php
require_once 'class/CalendarioActividad.php';
require_once 'class/Actividad.php';
require_once 'class/Municipio.php';
$ca = NULL;
$msg = "";


if (isset($_GET['id'])) {
    $ca = new CalendarioActividad((int) $_GET['id']);
} else {
    $ca = new CalendarioActividad();
    $msg = "Lo sentimos, su busqueda no tiene resultados";
}

if ($ca->getCve_calendario() > 0) {
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo($ca->getCve_actividad()->getNombre()); ?></h4>
    </div>
    <div class="modal-body">
        <div class="te" style="list-style-position: inside;">
            <p><b>Fecha inicio:</b> <?php echo($ca->getFecha_inicio()); ?></p>
            <p><b>Fecha fin:</b> <?php echo($ca->getFecha_fin()); ?></p>
            <p><b>Lugar:</b> <?php echo($ca->getLugar()); ?></p>
            <p><b>Estado:</b> <?php echo($ca->getCve_municipio()->getCve_estado()->getNombre()); ?></p>
            <p><b>Municipio:</b> <?php echo($ca->getCve_municipio()->getNombre()); ?></p>
            <p><b>Precio:</b> <?php echo($ca->getPrecio()); ?></p>
            <p><b>Cupo máximo:</b> <?php echo($ca->getCupo_maximo()); ?></p>
            <p><b>Observaciones:</b> <?php echo($ca->getObservaciones()); ?></p>
            <p><b>Fecha alta:</b> <?php echo($ca->getFecha_alta()); ?></p>
            <p><b>Activo:</b> <?php echo($ca->getActivo() ? "Si":"No"); ?></p><br/><br/>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    </div>
    <?php
} else {
    ?>    
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Aviso importante</h4>
    </div>
    <div class="modal-body">
        <div class="te"><?php echo($msg); ?></div>
    </div> 
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    </div>
    <?php
}