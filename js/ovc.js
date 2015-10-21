function guardar_contacto()
{
    if (validad_contacto()) {
        $("#xAccion").val("guardar_contacto");
        $("#frmContacto").submit();
    }
}

function validad_contacto()
{
    var valido = true;
    var msg = "";

    if ($("#txtNombre").val() === "")
    {
        valido = false;
        msg += "Ingrese el campo Nombre completo.\n";
    }
    if ($("#txtEmail").val() === "")
    {
        valido = false;
        msg += "Ingrese el campo Email.\n";
    }
    if ($("#txtComentario").val() === "")
    {
        valido = false;
        msg += "Ingrese el campo comentario.\n";
    }

    if (!valido)
    {
        alert(msg);
    }

    return valido;
}