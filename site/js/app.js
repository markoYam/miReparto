// Define un estilo personalizado para el mapa
var customStyle = [
    {
      featureType: "water",
      elementType: "geometry",
      stylers: [
        { hue: "#0088ff" },
        { saturation: 30 },
        { lightness: -10 }
      ]
    },
    {
      featureType: "landscape",
      elementType: "geometry",
      stylers: [
        { hue: "#00ff22" },
        { saturation: 40 },
        { lightness: -5 }
      ]
    },
    {
      featureType: "road",
      elementType: "geometry",
      stylers: [
        { hue: "#ff0000" },
        { saturation: -100 },
        { lightness: 50 }
      ]
    }
  ];
// Crea un objeto Map
var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 40.416775, lng: -3.703790}, // Centro en Madrid
    zoom: 10, // Nivel de zoom
styles: customStyle // Estilo personalizado
  });

// Obtiene la ubicación del usuario
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      // Obtiene las coordenadas de latitud y longitud del usuario
      var userLocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      // Centra el mapa en la ubicación del usuario
      map.setCenter(userLocation);
      // Crea un marcador en la ubicación del usuario
      var userMarker = new google.maps.Marker({
        position: userLocation,
        map: map,
        title: "Tu ubicación"
      });
    });
  }
  
  // Lista de ubicaciones
  var locations = [
    {lat: 20.209052, lng:-89.292277}, // Madrid
    {lat: 20.208428, lng:-89.289509}, // Barcelona
    {lat: 20.206213, lng:-89.281162}, // Barcelona
    
    {lat: 20.204683, lng:-89.295925    } // Sevilla
  ];
  
  // Crea un marcador para cada ubicación
  for (var i = 0; i < locations.length; i++) {
    /*new google.maps.Marker({
      position: locations[i],
      map: map
    });*/
  }
  
  // Obtiene la mejor ruta entre los marcadores
  var waypoints = locations.slice(1, -1).map(function(location) {
    return {location: location};
  });
  
  var directionsService = new google.maps.DirectionsService();
  var directionsRenderer = new google.maps.DirectionsRenderer();
  
  directionsRenderer.setMap(map);

  directionsService.route({
    origin: locations[0],
    destination: locations[locations.length - 1],
    waypoints: waypoints,
    optimizeWaypoints: true,
    travelMode: 'DRIVING',
    drivingOptions: {
        departureTime: new Date(), // Hora de salida (ahora)
        trafficModel: google.maps.TrafficModel.BEST_GUESS // Modelo de tráfico (mejor estimación)
      }
  }, function(response, status) {
    if (status === 'OK') {
      //directionsRenderer.setDirections(response);
      var lastLeg = response.routes[0].legs[response.routes[0].legs.length - 1];
        
      var lastDestination = lastLeg.end_location;
      console.log(lastDestination);

      directionsService.route({
        origin: locations[0],
        destination: lastDestination,
        waypoints: waypoints,
        optimizeWaypoints: true,
        travelMode: 'DRIVING'
      }, function(response2, status) {
        if (status === 'OK') {
          directionsRenderer.setDirections(response2);
         /// var lastLeg = response.routes[0].legs[response.routes[0].legs.length - 1];
         // var lastDestination = lastLeg.end_location;
          //console.log(lastDestination);
        } else {
          window.alert('Directions request failed due to ' + status);
        }
      });
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });

  


  