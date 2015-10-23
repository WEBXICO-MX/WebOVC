var map;
function initialize()
{
    //var coordenadas = new google.maps.LatLng(18.023869, -92.920003);
    var coordenadas = new google.maps.LatLng(17.989252, -92.939047);
    var opt = {zoom: 12, center: coordenadas, mapTypeId: google.maps.MapTypeId.ROADMAP};
    var map = new google.maps.Map(document.getElementById('google_maps'), opt);
    var marker = new google.maps.Marker({position: coordenadas, animation: google.maps.Animation.DROP, icon: "img/Map-Marker-Push-Pin-1-Left-Pink-icon.png"});
    marker.setMap(map);
    var text = "<h1>Grupo HISA</h1><ul><li>Col. Jose Maria Pino Suarez</li><li>Av. Ramon Mendoza No. 412-04</li><li>C.P. 86029</li><li>Villahermosa, Tabasco</li><li>Tel: (993) 3 57 25 05, Cel: 9931 57 44 41</li></ul>";
    var info = new google.maps.InfoWindow({content: text});
    google.maps.event.addListener(marker, 'click', function () {
        info.open(map, marker);
    });
}
google.maps.event.addDomListener(window, 'load', initialize);