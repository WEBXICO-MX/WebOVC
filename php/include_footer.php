<!-- /.row -->
<div class="row">
    <div class="col-md-6">

        <form role="form-inline" action="php/contacto_guardar.php" method="post" name="frmContacto" id="frmContacto">
            <div class="form-group">
                <label for="txtNombre">Nombre completo:</label>
                <input type="hidden" id="xAccion" name="xAccion" value="0">
                <input type="text" class="form-control" id="txtNombre" name="txtNombre">
            </div>
            <div class="form-group">
                <label for="txtEmail">Email:</label>
                <input type="email" class="form-control" id="txtEmail" name="txtEmail">
            </div>
             <div class="form-group">
                <label for="txtTel">Tel:</label>
                <input type="text" class="form-control" id="txtTel" name="txtTel">
             </div>
            <div class="form-group">
                <label for="txtComentario">Comentario(s):</label>
                <textarea class="form-control" rows="3" id="txtComentario" name="txtComentario"></textarea>
            </div>
            <button type="button" class="btn btn-primary" onclick="guardar_contacto()">Submit</button>
        </form>

    </div>
    <div class="col-md-6">
        <div id="google_maps" style="width: 500px; height: 500px;">Cargando Google Maps ...</div>
    </div>
</div>
<hr>

<!-- Footer -->
<footer>
    <div class="row">
        <div class="col-md-12">
            <p>Av. Ramon Mendoza No. 412-04, Col. Jose Maria Pino Suarez, C.P. 86029,Villahermosa, Tabasco, Tel: (993) 3 57 25 05, Cel: 9931 57 44 41</p>
            <p>Copyright &copy; grupohisa.hol.es 2015</p>
        </div>
    </div>
</footer>