

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GIS Kependudukan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap core CSS -->
    <link href="https://gis.dispendukcapil.grobogan.go.id/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    
    <!-- Label -->
    <link rel="stylesheet" href="https://gis.dispendukcapil.grobogan.go.id/maps/dist/leaflet.label.css" />
  <!--Posisi User -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://gis.dispendukcapil.grobogan.go.id/maps/dist/L.Control.Locate.min.css" />
    
    <style>
        html,body {
            width: 100%;
            height: 100%;
        }
        section.map {
            display: flex;
            height: 100%;
            width: 100%;
            flex-wrap: wrap;
        }
        #azniirkh-map {
            height: 100%;
            flex: 1 50%;
        }
       #azniirkh-legend {
            height: 100%;
            flex: 1.25;
            overflow: auto;
        }
        @media (max-width: 767px) {
            #azniirkh-map {
                flex: 1 100%;
                height: 100%;
            }
            #azniirkh-legend {
                flex: 0 100%;
                height: 0%;
            }
        }

        /* CSS Label */
        .textLabelclass{
            white-space:nowrap;
            font-weight: 300;
            text-shadow: 0 0 0.1em black, 0 0 0.1em black,
                    0 0 0.1em black,0 0 0.1em black,0 0 0.1em;
            color: yellow;
        }
        .my-label {
            position: absolute;
            width:70px;
            font-size:10px;
        }

/*Legend */
        .leaflet-control-attribution { display:none!important}
		.info {
			padding: 6px 8px;
			font: 14px/16px Arial, Helvetica, sans-serif;
			background: white;
			background: rgba(255,255,255,0.8);
			box-shadow: 0 0 15px rgba(0,0,0,0.2);
			border-radius: 5px;
		}
		.info h4 {
			margin: 0 0 5px;
			color: #777;
		}
		.legend {
			text-align: left;
			line-height: 18px;
			color: #555;
		}
		.legend i {
			width: 18px;
			height: 18px;
			float: left;
			margin-right: 8px;
			opacity: 0.7;
		}

   
    </style>

    <!-- Custom styles for this template -->
    <link href="https://gis.dispendukcapil.grobogan.go.id/maps/css/map.css" rel="stylesheet">
    <link href="https://gis.dispendukcapil.grobogan.go.id/vendor/leaflet/leaflet.css" rel="stylesheet">


  <!--  <link rel="stylesheet" href="//leaflet.github.io/Leaflet.label/leaflet.label.css" /> -->
</head>
<body oncontextmenu="return false;">

<section class="map">
    <div id="azniirkh-map"></div>

</section>



  <!--<script src="https://gis.dispendukcapil.grobogan.go.id/assets/libs/leaflet/leaflet-src.js"></script>
  <script src="https://gis.dispendukcapil.grobogan.go.id/assets/src/Label.js"></script>
	<script src="https://gis.dispendukcapil.grobogan.go.id/assets/src/BaseMarkerMethods.js"></script>
	<script src="https://gis.dispendukcapil.grobogan.go.id/assets/src/Marker.Label.js"></script>
	<script src="https://gis.dispendukcapil.grobogan.go.id/assets/src/CircleMarker.Label.js"></script>
	<script src="https://gis.dispendukcapil.grobogan.go.id/assets/src/Path.Label.js"></script>
	<script src="https://gis.dispendukcapil.grobogan.go.id/assets/src/Map.Label.js"></script>
    <script src="https://gis.dispendukcapil.grobogan.go.id/assets/src/FeatureGroup.Label.js"></script> -->


    <script src="https://gis.dispendukcapil.grobogan.go.id/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="https://gis.dispendukcapil.grobogan.go.id/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://gis.dispendukcapil.grobogan.go.id/vendor/leaflet/leaflet.js"></script>
    <script src="https://gis.dispendukcapil.grobogan.go.id/vendor/turf/turf.min.js"></script>
    <script src="https://gis.dispendukcapil.grobogan.go.id/maps/js/gis.js"></script>
    <script src="https://gis.dispendukcapil.grobogan.go.id/maps/js/jquery-features.js"></script>

    <!-- Label -->
   <!-- <script src="https://gis.dispendukcapil.grobogan.go.id/assets/libs/leaflet/leaflet-src.js"></script> -->

   <script src="https://gis.dispendukcapil.grobogan.go.id/maps/src/L.Control.Locate.js"></script>

    <script>
  
//  var map = L.map('azniirkh-map').setView([-7.0846256,110.9150882], 11);
var  base_url ="https://gis.dispendukcapil.grobogan.go.id/";

//L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
 //   attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//}).addTo(map);

//L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}{r}.png', {
//    attribution: '&copy; <a href="https://wikimediafoundation.org/wiki/Maps_Terms_of_Use">Wikimedia</a>'
//}).addTo(map);


var kantor_desa = L.layerGroup();
var kantor_kec = L.layerGroup();
var batas = L.layerGroup();
var myFeatureGroup_desa = L.featureGroup().addTo(kantor_desa).on("click", groupClick);
var myFeatureGroup_kec = L.featureGroup().addTo(kantor_kec);

var kantor = L.layerGroup();

function createCustomIconDesa (feature, latlng) {
  let myIcon1 = L.icon({
    iconUrl: base_url+'maps/images/desa.png',
    //shadowUrl: 'my-icon.png',
    iconSize:     [35, 40], // width and height of the image in pixels
    shadowSize:   [35, 20], // width, height of optional shadow image
    iconAnchor:   [12, 12], // point of the icon which will correspond to marker's location
    shadowAnchor: [12, 6],  // anchor point of the shadow. should be offset
    popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
  })
  return L.marker(latlng, { icon: myIcon1 })
}

 $.getJSON(base_url+"maps/geojson/kantor_desa.geojson", function(data){
  geoLayer = L.geoJson(data, {
        pointToLayer: createCustomIconDesa,
        onEachFeature: function(feature, layer){
          //var kode = feature.properties.kode;
         var coord = feature.geometry.coordinates;
      
        
         L.marker([coord[1],coord[0]])
                      //.addTo(myFeatureGroup_desa)
                      .bindPopup(feature.properties.REMARK);

                      layer.bindPopup(feature.properties.REMARK);
          
                      
                
          
        }
      })
      
      .addTo(kantor_desa);
      map.removeLayer(kantor_desa);
  });

  function createCustomIcon (feature, latlng) {
  let myIcon = L.icon({
    iconUrl: base_url+'maps/images/kecamatan.png',
    //shadowUrl: 'my-icon.png',
    iconSize:     [35, 40], // width and height of the image in pixels
    shadowSize:   [35, 20], // width, height of optional shadow image
    iconAnchor:   [12, 12], // point of the icon which will correspond to marker's location
    shadowAnchor: [12, 6],  // anchor point of the shadow. should be offset
    popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
  })
  return L.marker(latlng, { icon: myIcon })
}

// create an options object that specifies which function will called on each feature
let myLayerOptions = {
  pointToLayer: createCustomIcon
}

  $.getJSON(base_url+"maps/geojson/kantor_kec.geojson", function(data){
      geoLayer = L.geoJson(data, {
        pointToLayer: createCustomIcon,
        onEachFeature: function(feature, layer){
          //var kode = feature.properties.kode;
          var kode = feature.properties.NAMOBJ;
      
          $.getJSON(base_url+"home/kecamatan/"+kode, function(data){
            //alert(data);
            for (var i = 0; i < data.length; i++) {
            var nama_desa = data[i].nama_kec;

            var info_bidang="<div class='media' style='width:500px;'>";
            info_bidang+="<div class='media-left'>";
            info_bidang+="<img src='https://gis.dispendukcapil.grobogan.go.id/maps/upload/Kecamatan/"+nama_desa+".jpg' class='media-object' style='width:120px'>";
            info_bidang+="</div>";
            info_bidang+="<div class='media-body' style='margin-left:10px;'>";
            info_bidang+="<p style='margin:0px; font-size:14px;'><b>Kecamatan "+nama_desa+"</b></p>";
            info_bidang+="<p style='margin-top:10px;margin-bottom:0px; font-size:11px;'>Luas Wilayah : "+data[i].luas+" Km2</p>";
            info_bidang+="<p style='margin:0px; font-size:11px;'>Jumlah Penduduk : "+data[i].jml_penduduk+" Jiwa</p>";
            info_bidang+="<p style='margin:0px; font-size:11px;'>Kepadatan : "+(data[i].jml_penduduk/data[i].luas).toFixed(2)+" Jiwa/Km2</p>";
            info_bidang+="<p style='margin:0px; font-size:11px;'>Jumlah KK : "+data[i].jml_kk+" KK</p>";
            info_bidang+="<a href='https://gis.dispendukcapil.grobogan.go.id/home/detailkec/"+kode+"' style=' font-size:12px' target:'_blank'>Detail  >></a>";
            info_bidang+="</div>";
            info_bidang+="</div>";


    /*        var info_bidang="<h5 style='text-align:center'>&nbsp;&nbsp;&nbsp;Kecamatan "+nama_desa+"&nbsp;&nbsp;&nbsp;</h5>";
            
            info_bidang+="<div id='' style='overflow-y:scroll; overflow-x:hidden; height:200px;'>";
            
            info_bidang+="Luas Wilayah          : "+data[i].luas+" Km2<br>";
            info_bidang+="Jumlah Penduduk       : "+data[i].jml_penduduk+" Jiwa<br>";
            info_bidang+="Kepadatan Penduduk    : "+data[i].kepadatan+" Jiwa/Km2<br>";
            info_bidang+="Jumlah KK             : "+data[i].jml_kk+" KK<br>";
            info_bidang+="Wajib KTP             : "+data[i].wajib_ktp+" Orang";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Islam                 : "+data[i].islam+"<br>";
            info_bidang+="Kristen               : "+data[i].kristen+"<br>";
            info_bidang+="Katholik              : "+data[i].katholik+"<br>";
            info_bidang+="Hindu                 : "+data[i].hindu+"<br>";
            info_bidang+="Budha                 : "+data[i].budha+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Laki-laki             : "+data[i].lk+"<br>";
            info_bidang+="Perempuan             : "+data[i].pr+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Belum Kawin           : "+data[i].blm_kawin+"<br>";
            info_bidang+="Kawin                 : "+data[i].kawin+"<br>";
            info_bidang+="Cerai Hidup           : "+data[i].cerai_hidup+"<br>";
            info_bidang+="Cerai Mati            : "+data[i].cerai_mati+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Umur 0-4 th           : "+data[i].u0+"<br>";
            info_bidang+="Umur 5-9 th           : "+data[i].u5+"<br>";
            info_bidang+="Umur 10-14 th         : "+data[i].u10+"<br>";
            info_bidang+="Umur 15-19 th         : "+data[i].u15+"<br>";
            info_bidang+="Umur 20-24 th         : "+data[i].u20+"<br>";
            info_bidang+="Umur 25-29 th         : "+data[i].u25+"<br>";
            info_bidang+="Umur 30-34 th         : "+data[i].u30+"<br>";
            info_bidang+="Umur 35-39 th         : "+data[i].u35+"<br>";
            info_bidang+="Umur 40-44 th         : "+data[i].u40+"<br>";
            info_bidang+="Umur 45-49 th         : "+data[i].u45+"<br>";
            info_bidang+="Umur 50-54 th         : "+data[i].u50+"<br>";
            info_bidang+="Umur 55-59 th         : "+data[i].u55+"<br>";
            info_bidang+="Umur 60-64 th         : "+data[i].u60+"<br>";
            info_bidang+="Umur 65-69 th         : "+data[i].u65+"<br>";
            info_bidang+="Umur 70-74 th         : "+data[i].u70+"<br>";
            info_bidang+="Umur 75 th lebih      : "+data[i].u75+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Tidak/Blm Sekolah     : "+data[i].blm_sekolah+"<br>";
            info_bidang+="Belum Tamat SD        : "+data[i].blm_tmt_sd+"<br>";
            info_bidang+="Tamat SD              : "+data[i].sd+"<br>";
            info_bidang+="SLTP                  : "+data[i].sltp+"<br>";
            info_bidang+="SLTA                  : "+data[i].slta+"<br>";
            info_bidang+="D1 dan D2             : "+data[i].d12+"<br>";
            info_bidang+="D3                    : "+data[i].d3+"<br>";
            info_bidang+="D4/S1                 : "+data[i].s1+"<br>";
            info_bidang+="S2                    : "+data[i].s2+"<br>";
            info_bidang+="S3                    : "+data[i].s3+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Belum Bekerja         : "+data[i].blm_bekerja+"<br>";
            info_bidang+="Aparatur Negara       : "+data[i].aparatur+"<br>";
            info_bidang+="Tenaga Pengajar       : "+data[i].pengajar+"<br>";
            info_bidang+="Wiraswasta            : "+data[i].wiraswasta+"<br>";
            info_bidang+="Petani                : "+data[i].petani+"<br>";
            info_bidang+="Nelayan               : "+data[i].nelayan+"<br>";
            info_bidang+="Agamawan              : "+data[i].agamawan+"<br>";
            info_bidang+="Pelajar               : "+data[i].pelajar+"<br>";
            info_bidang+="Tenaga Kesehatan      : "+data[i].kesehatan+"<br>";
            info_bidang+="Pensiunan             : "+data[i].pensiunan+"<br>";
            info_bidang+="Lainnya               : "+data[i].lainnya+"<br>";
            info_bidang+="</div>";
             
                                      

              info_bidang+="<div style='width:100%;text-align:left;margin-top:10px;margin-bottom:20px;'><a> Sumber : Data DKB Semester I 2021</a></div>";
              info_bidang+="<div style = 'text-align: center;'><a href='https://gis.dispendukcapil.grobogan.go.id/home/detail/"+kode+"' class='btn btn-info' role='button'>DETAIL</a></div>";

          */
          layer.bindPopup(info_bidang,{
              mazWidth : 330,
              
              closeButton : true,
              offset : L.point(0, -20)
          });

          layer.on('click', function(){
              layer.openPopup();
          });

          layer.bindTooltip(nama_desa, {permanent: false, className: "my-label", offset: [0, 0] });
        }
          });

          
         
        }
      })
      
      .addTo(kantor_kec);
      //map.removeLayer(kantor_kec;
  });


  $.getJSON(base_url+"maps/geojson/desa1.geojson", function(data){
      geoLayer = L.geoJson(data, {
        style: function(feature) {
          var kecamatan  = feature.properties.WADMKC;
         
                        if (feature.properties.WADMKC === "Kedungjati") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#f54242"
                            }
                        } else if (feature.properties.WADMKC === "Tanggungharjo") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#f5d742"
                            }
                        }  else if (feature.properties.WADMKC === "Gubug") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#010112"
                            }
                          }  else if (feature.properties.WADMKC === "Godong") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#2e26d1"
                            }
                          }  else if (feature.properties.WADMKC === "Karangrayung") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#8a6345"
                            }
                          }  else if (feature.properties.WADMKC === "Tegowanu") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#0cebe3"
                            }
                          }  else if (feature.properties.WADMKC === "Brati") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#2d0ceb"
                            }
                          }  else if (feature.properties.WADMKC === "Klambu") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#a39e08"
                            }
                          }  else if (feature.properties.WADMKC === "Penawangan") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#f51165"
                            }
                          }  else if (feature.properties.WADMKC === "Purwodadi") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#db9039"
                            }
                          }  else if (feature.properties.WADMKC === "Grobogan") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#078f07"
                            }
                          }  else if (feature.properties.WADMKC === "Toroh") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#057f87"
                            }
                          }  else if (feature.properties.WADMKC === "Geyer") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#92990b"
                            }
                          }  else if (feature.properties.WADMKC === "Tawangharjo") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#6840ed"
                            }
                          }  else if (feature.properties.WADMKC === "Pulokulon") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#c93667"
                            }
                          }  else if (feature.properties.WADMKC === "Wirosari") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#c7d909"
                            }
                          }  else if (feature.properties.WADMKC === "Gabus") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#038724"
                            }
                          }  else if (feature.properties.WADMKC === "Kradenan") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.2,
                              fillColor: "#111d8c"
                            }
                        } else if (feature.properties.WADMKC === "Ngaringan") {
                            return {
                              weight: 2,
                              opacity: 1,
                              color: 'red',
                              dashArray: '3',
                              fillOpacity: 0.1,
                              fillColor: "#42f551"
                            };
                        }

        } 
        , 
        onEachFeature: function(feature, layer){
          //var kode = feature.properties.kode;
          var kode = feature.properties.KDPPUM;
      
          $.getJSON(base_url+"home/desa/"+kode, function(data){
            //alert(data);
            for (var i = 0; i < data.length; i++) {
            var nama_desa = data[i].nama_desa;

            var info_bidang="<h5 style='text-align:center'>&nbsp;&nbsp;&nbsp;"+data[i].sebutan+" "+nama_desa+"&nbsp;&nbsp;&nbsp;</h5>";
            
            info_bidang+="<div id='' style='overflow-y:scroll; overflow-x:hidden; height:200px;'>";
            info_bidang+="<b>Kecamatan          : "+data[i].nama_kec+"</b><br>";
            info_bidang+="Luas Wilayah          : "+data[i].luas+" Km2<br>";
            info_bidang+="Jumlah Penduduk       : "+data[i].jml_penduduk+" Jiwa<br>";
            info_bidang+="Kepadatan Penduduk    : "+(data[i].jml_penduduk/data[i].luas).toFixed(2)+" Jiwa/Km2<br>";
            info_bidang+="Jumlah KK             : "+data[i].jml_kk+" KK<br>";
            info_bidang+="Wajib KTP             : "+data[i].wajib_ktp+" Orang";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Islam                 : "+data[i].islam+"<br>";
            info_bidang+="Kristen               : "+data[i].kristen+"<br>";
            info_bidang+="Katholik              : "+data[i].katholik+"<br>";
            info_bidang+="Hindu                 : "+data[i].hindu+"<br>";
            info_bidang+="Budha                 : "+data[i].budha+"<br>";
            info_bidang+="Konghucu              : "+data[i].konghucu+"<br>";
            info_bidang+="Aliran Kepercayaan    : "+data[i].kepercayaan+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Laki-laki             : "+data[i].lk+"<br>";
            info_bidang+="Perempuan             : "+data[i].pr+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Belum Kawin           : "+data[i].blm_kawin+"<br>";
            info_bidang+="Kawin                 : "+data[i].kawin+"<br>";
            info_bidang+="Cerai Hidup           : "+data[i].cerai_hidup+"<br>";
            info_bidang+="Cerai Mati            : "+data[i].cerai_mati+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Umur 0-4 th           : "+data[i].u0+"<br>";
            info_bidang+="Umur 5-9 th           : "+data[i].u5+"<br>";
            info_bidang+="Umur 10-14 th         : "+data[i].u10+"<br>";
            info_bidang+="Umur 15-19 th         : "+data[i].u15+"<br>";
            info_bidang+="Umur 20-24 th         : "+data[i].u20+"<br>";
            info_bidang+="Umur 25-29 th         : "+data[i].u25+"<br>";
            info_bidang+="Umur 30-34 th         : "+data[i].u30+"<br>";
            info_bidang+="Umur 35-39 th         : "+data[i].u35+"<br>";
            info_bidang+="Umur 40-44 th         : "+data[i].u40+"<br>";
            info_bidang+="Umur 45-49 th         : "+data[i].u45+"<br>";
            info_bidang+="Umur 50-54 th         : "+data[i].u50+"<br>";
            info_bidang+="Umur 55-59 th         : "+data[i].u55+"<br>";
            info_bidang+="Umur 60-64 th         : "+data[i].u60+"<br>";
            info_bidang+="Umur 65-69 th         : "+data[i].u65+"<br>";
            info_bidang+="Umur 70-74 th         : "+data[i].u70+"<br>";
            info_bidang+="Umur 75 th lebih      : "+data[i].u75+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Tidak/Blm Sekolah     : "+data[i].blm_sekolah+"<br>";
            info_bidang+="Belum Tamat SD        : "+data[i].blm_tmt_sd+"<br>";
            info_bidang+="Tamat SD              : "+data[i].sd+"<br>";
            info_bidang+="SLTP                  : "+data[i].sltp+"<br>";
            info_bidang+="SLTA                  : "+data[i].slta+"<br>";
            info_bidang+="D1 dan D2             : "+data[i].d12+"<br>";
            info_bidang+="D3                    : "+data[i].d3+"<br>";
            info_bidang+="D4/S1                 : "+data[i].s1+"<br>";
            info_bidang+="S2                    : "+data[i].s2+"<br>";
            info_bidang+="S3                    : "+data[i].s3+"<br>";
            info_bidang+="<hr style='margin :4px'>";
            info_bidang+="Belum Bekerja         : "+data[i].blm_bekerja+"<br>";
            info_bidang+="Ibu Rumah Tangga      : "+data[i].irt+"<br>";
            info_bidang+="Pelajar               : "+data[i].pelajar+"<br>";
            info_bidang+="Pensiunan             : "+data[i].pensiunan+"<br>";
            info_bidang+="PNS                   : "+data[i].pns+"<br>";
            info_bidang+="TNI                   : "+data[i].tni+"<br>";
            info_bidang+="Polri              : "+data[i].polri+"<br>";
            info_bidang+="Perdagangan               : "+data[i].perdagangan+"<br>";
            info_bidang+="Petani      : "+data[i].petani+"<br>";
            info_bidang+="Peternak             : "+data[i].peternak+"<br>";
            info_bidang+="Industri               : "+data[i].industri+"<br>";
            info_bidang+="Konstruksi               : "+data[i].konstruksi+"<br>";
            info_bidang+="Transportasi               : "+data[i].transportasi+"<br>";
            info_bidang+="Swasta               : "+data[i].swasta+"<br>";
            info_bidang+="BUMN               : "+data[i].bumn+"<br>";
            info_bidang+="BUMD               : "+data[i].bumd+"<br>";
            info_bidang+="Honorer               : "+data[i].honorer+"<br>";
            info_bidang+="Buruh               : "+data[i].buruh+"<br>";
            info_bidang+="Buruh Tani               : "+data[i].buruh_tani+"<br>";
            info_bidang+="Buruh Peternakan               : "+data[i].buruh_peternakan+"<br>";
            info_bidang+="Pembantu               : "+data[i].pembantu+"<br>";
            info_bidang+="Tukang               : "+data[i].tukang+"<br>";
            info_bidang+="Penata Rias               : "+data[i].penata_rias+"<br>";
            info_bidang+="Penata Busana               : "+data[i].penata_busana+"<br>";
            info_bidang+="Penata Rambut               : "+data[i].penata_rambut+"<br>";
            info_bidang+="Mekanik               : "+data[i].mekanik+"<br>";
            info_bidang+="Seniman               : "+data[i].seniman+"<br>";
            info_bidang+="Tabib               : "+data[i].tabib+"<br>";
            info_bidang+="Perancang Busana               : "+data[i].perancang_busana+"<br>";
            info_bidang+="Imam Masjid               : "+data[i].imam_mesjid+"<br>";
            info_bidang+="Pendeta               : "+data[i].pendeta+"<br>";
            info_bidang+="Pastor               : "+data[i].pastor+"<br>";
            info_bidang+="Wartawan               : "+data[i].wartawan+"<br>";
            info_bidang+="Ustadz               : "+data[i].ustadz+"<br>";
            info_bidang+="Juru Masak               : "+data[i].juru_masak+"<br>";
            info_bidang+="Promotor Acara               : "+data[i].promotor_acara+"<br>";
            info_bidang+="Bupati               : "+data[i].bupati+"<br>";
            info_bidang+="Wakil Bupati               : "+data[i].wakil_bupati+"<br>";
            info_bidang+="Anggota DPRD Prov               : "+data[i].anggota_dprd_prov+"<br>";
            info_bidang+="Anggota DPRD Kab               : "+data[i].anggota_dprd_kab+"<br>";
            info_bidang+="Dosen               : "+data[i].dosen+"<br>";
            info_bidang+="Guru               : "+data[i].guru+"<br>";
            info_bidang+="Pengacara               : "+data[i].pengacara+"<br>";
            info_bidang+="Notaris               : "+data[i].notaris+"<br>";
            info_bidang+="Arsitek               : "+data[i].arsitek+"<br>";
            info_bidang+="Akuntan               : "+data[i].akuntan+"<br>";
            info_bidang+="Konsultan               : "+data[i].konsultan+"<br>";
            info_bidang+="Dokter               : "+data[i].dokter+"<br>";
            info_bidang+="Bidan               : "+data[i].bidan+"<br>";
            info_bidang+="Perawat               : "+data[i].perawat+"<br>";
            info_bidang+="Apoteker               : "+data[i].apoteker+"<br>";
            info_bidang+="Psikolog               : "+data[i].psikolog+"<br>";
            info_bidang+="Penyiar               : "+data[i].penyiar+"<br>";
            info_bidang+="Peneliti               : "+data[i].peneliti+"<br>";
            info_bidang+="Sopir               : "+data[i].sopir+"<br>";
            info_bidang+="Pedagang               : "+data[i].pedagang+"<br>";
            info_bidang+="Perangkat Desa               : "+data[i].perangkat_desa+"<br>";
            info_bidang+="Kepala Desa               : "+data[i].kepala_desa+"<br>";
            info_bidang+="Biarawati               : "+data[i].biarawati+"<br>";
            info_bidang+="Wiraswasta               : "+data[i].wiraswasta+"<br>";
            info_bidang+="Lainnya               : "+data[i].lainnya+"<br>";

			
            info_bidang+="</div>";
              //info_bidang+="<table style='width:100%'>"
              //                    "<tr>"
              //                        "<td>"+data[i].jml_penduduk+"</td>"
              //                        "<td>"+data[i].jml_penduduk+"</td>"
              //                        "<td>"+data[i].jml_penduduk+"</td>"
              //                    "</tr>"
               //             "</table>";
                                      

               info_bidang+="<div style='width:100%;text-align:left;margin-top:10px;margin-bottom:20px;'><a> Sumber : Data DKB Semester I 2022</a></div>";
              info_bidang+="<div style = 'text-align: center;'><a href='https://gis.dispendukcapil.grobogan.go.id/home/detaildesa/"+kode+"' class='btn btn-danger' role='button'>DETAIL</a></div>";

          
          layer.bindPopup(info_bidang,{
              mazWidth : 230,
              
              closeButton : true,
              offset : L.point(0, -20)
          });

          layer.on('click', function(){
              layer.openPopup();
          });

          layer.bindTooltip(nama_desa, {permanent: false, className: "my-label", offset: [0, 0] });
        }
          });

          
         
        }
      })
      
      .addTo(batas);
  });





  function groupClick(event){
    //alert("Click on marker " + event.layer.id);
  }


$.getJSON(base_url+"home/bangunan_json", function(data){
    $.each(data, function(i, field){
     // alert(data[i].bangunan_nama)
   var v_lat=parseFloat(data[i].bangunan_lat);
   var v_long=parseFloat(data[i].bangunan_long);

   var icon_bangunan = L.icon({
                  iconUrl: base_url+'maps/images/icon.png',
                  iconSize:[30,30]
            }); 

   bangunanMarker = L.marker([v_long,v_lat],{icon:icon_bangunan})
                      .addTo(kantor)
                      .bindPopup(data[i].bangunan_nama);
                      //.addTo(cities);
           
    
    //bangunanMarker.id = data[i].bangunan_id;

	//L.marker([v_lat, v_long]).bindPopup('This is Littleton, CO.').addTo(cities),
	//L.marker([39.74, -104.99]).bindPopup('This is Denver, CO.').addTo(cities),
	//L.marker([39.73, -104.8]).bindPopup('This is Aurora, CO.').addTo(cities),
	//L.marker([39.77, -105.23]).bindPopup('This is Golden, CO.').addTo(cities);
    
    });
    }); 

	//var mbAttr1 = 'Map data &copy; <a href="https://wikimediafoundation.org/wiki/Maps_Terms_of_Use">Wikimedia</a>', 
		
  //mbAttr1 = 'https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}{r}.png';
  //var mbAttr1='Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors';
    // mbUrl1='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

     // please replace this with your own mapbox token!
var token = 'pk.eyJ1IjoibWFjaGFzaW4iLCJhIjoiY2sybXdpaWU3MDdzcjNtbnZoZTU2YXBhbiJ9.8NjrKNa-zr84vICT2biRKA';
var mbUrl1 = 'https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}@2x?access_token=' + token;
var mbAttr1 = 'Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors. Tiles from <a href="https://www.mapbox.com">Mapbox</a>.';


    var mbAttr2 = 'Map data &copy; <a href="Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community', 
		
		mbUrl2 = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';

	var grayscale   = L.tileLayer(mbUrl1, {id: 'mapbox.light', attribution: mbAttr1}),
		streets  = L.tileLayer(mbUrl2, {id: 'mapbox.streets',   attribution: mbAttr2});

	var map = L.map('azniirkh-map', {
		center: [-7.0846256,110.9150882],
		zoom: 11,
		layers: [grayscale, kantor_desa,kantor_kec, batas]
	});

	var baseLayers = {
		"Default": grayscale,
		"Satelite": streets
	};

	var overlays = {
		"Kantor Desa": kantor_desa,
    "Kantor Kecamatan": kantor_kec,
    "Batas Desa": batas,
    "Kampung KB": kantor
	};

	L.control.layers(baseLayers, overlays).addTo(map);


// add location control to global name space for testing only
// on a production site, omit the "lc = "!
lc = L.control.locate({
    strings: {
        title: "Posisi Anda"
    }
}).addTo(map);

      
		function getColor(d) {
			return d > 87  ? '#800026' :
			       d > 75  ? '#BD0026' :
			       d > 62  ? '#E31A1C' :
			       d > 50  ? '#FC4E2A' :
			       d > 37  ? '#FD8D3C' :
			       d > 25  ? '#FEB24C' :
			       d > 12  ? '#FED976' :
                        '#800026';//'#FFEDA0';
        }
       
		function style(feature) {
         //   var kode = feature.properties.KDPPUM;
           //var jumlah_penduduk 
           //$sql="select jml_penduduk from desa where kd_desa = '012007'");//$.getJSON(base_url+"home/penduduk/012007");//+kode;
           //$tes = $db->query("jml_penduduk from desa where kd_desa = '012007'")->result();
        //   var jumlah =  $.getJSON(base_url+"home/penduduk/"+kode, function(data){
            var kode = feature.properties.KDPPUM;
         var jumlah = $.get(base_url+"home/penduduk/"+kode, { name: 'value' },
            function(data) {
               // alert(data);
               
            }, 'json'); // Set the type to ‘json’!

			return {
				weight: 2,
				opacity: 1,
				color: 'red',
				dashArray: '3',
				fillOpacity: 0.1,
				fillColor: getColor(jumlah)
                    
                   // $.getJSON(base_url+"home/jml_penduduk/"+kode))
         
                        //var kode = '012007';//feature.properties.KDPPUM;
                        
                     //  base_url+"home/jml_penduduk/012007"
                       // )
      }
		    }

		function highlightFeature(e) {
			var layer = e.target;
			layer.setStyle({
				weight: 5,
				color: '#666',
				dashArray: '',
				fillOpacity: 0.7
			});
			if (!L.Browser.ie && !L.Browser.opera) {
				layer.bringToFront();
			}
			info.update(layer.feature.properties);
		}
		var geojson;
		function resetHighlight(e) {
			geojson.resetStyle(e.target);
			info.update();
		}
		function zoomToFeature(e) {
			map.fitBounds(e.target.getBounds());
		}
		function onEachFeature(feature, layer) {
			layer.on({
				mouseover: highlightFeature,
				mouseout: resetHighlight,
				click: zoomToFeature
			});
		}
		

    function onMarkerClickPopupChart(e) {
  var index = this.index;
  $("#pcc").html(
    "You clicked marker: " +
      index +
      "<br/><a id='link' href='#' onclick='showChart(" +
      index +
      ");'>show chart</a><br/><div id='pc' class='pc'></div>"
  );
}
		//map.attributionControl.addAttribution('Population data &copy; <a href="http://census.gov/">USBureau</a>');
	/*	var legend = L.control({position: 'bottomright'});
		legend.onAdd = function (map) {
			var div = L.DomUtil.create('div', 'info legend'),
				//grades = [0, 10, 20, 50, 100, 200, 500, 1000],
				grades = [0, 12, 25, 37, 50, 62, 75, 87], //pretty break untuk 8
				labels = [],
				from, to;
			for (var i = 0; i < grades.length; i++) {
				from = grades[i];
				to = grades[i + 1];
				labels.push(
					'<i style="background:' + getColor(from + 1) + '"></i> ' +
					from + (to ? '&ndash;' + to : '+'));
			}
			div.innerHTML = '<h4>Legenda:</h4><br>'+labels.join('<br>');
			return div;
		};
		legend.addTo(map);  */



    </script>

   
</body>
</html>
