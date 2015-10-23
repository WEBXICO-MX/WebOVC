var map;
function initialize()
{
    //var coordenadas = new google.maps.LatLng(18.023869, -92.920003);
    var coordenadas = new google.maps.LatLng(17.989252, -92.939047);
    var opt = {zoom: 12, center: coordenadas, mapTypeId: google.maps.MapTypeId.ROADMAP};
    var map = new google.maps.Map(document.getElementById('google_maps'), opt);
    var marker = new google.maps.Marker({position: coordenadas, animation: google.maps.Animation.DROP, icon: "img/Map-Marker-Push-Pin-1-Left-Pink-icon.png"});
    marker.setMap(map);
    var text = "<h1>Grupo HISA</h1><ul><li>Villahermosa, Tabasco</li><li>C.P. 86000</li><li>Correo: <a href='mailto:grupohisa96@gmail.com'>grupohisa96@gmail.com</a></li></ul><p>Capacitación, Conferencias, Consultoría, Congresos, Conciertos ,Logistica, Expociciones y obras de Teatro</p>";
    var info = new google.maps.InfoWindow({content: text});
    google.maps.event.addListener(marker, 'click', function () {
        info.open(map, marker);
    });
}
google.maps.event.addDomListener(window, 'load', initialize);