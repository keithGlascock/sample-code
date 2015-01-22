// Declare variables for later use
var map;
var PetMarker;
var MapBounds;

GEvent.addDomListener(window, 'load', loadMap);
GEvent.addDomListener(window, 'unload', GUnload);



function miss() {alert('test')};

function loadMap()
{
  // loadMap: intialize the API and load the map onto the page

  // Get the map container div
  var mapDiv = document.getElementById('map');

  // Confirm browser compatibility with the Maps API
  if (!GBrowserIsCompatible())
  {
    mapDiv.innerHTML = 'Sorry, your browser isn\'t compatible with Google Maps.';
  }
  else
  {
    // Intialize the core map object, include Google Earth maptype
    map = new GMap2(mapDiv,
      {mapTypes: [G_NORMAL_MAP, G_SATELLITE_MAP, G_HYBRID_MAP, G_PHYSICAL_MAP]});

    //Initialize the map boundaries object
    MapBounds = new GLatLngBounds();

    // Add the standard map controls, moving the scale control to the upper right
    map.addControl(new GScaleControl());
    map.addControl(new GOverviewMapControl());
    map.addControl(new GMapTypeControl());
    map.addControl(new GLargeMapControl());
    petJSON();

    // Now set the starting map viewport after adding the markers to determine the map boundaries
    map.setCenter(MapBounds.getCenter(), map.getBoundsZoomLevel(MapBounds), G_PHYSICAL_MAP);

  }
};
   
// Add a marker to the map for a pet
function addPetData(lat, long, id, descrip, type, date, status)
{
   var coordinates = new GLatLng(lat, long);
   PetMarker = new GMarker(coordinates);
   PetMarker.bindInfoWindow(document.getElementById(id), {maxWidth: 300});
     
   // The marker may fall outside the current map boundaries, so extend them
   map.addOverlay(PetMarker);
   MapBounds.extend(coordinates);

   // Add a sidebar entry for a pet under lost, found, or sighted
   var petEntry = document.createElement('li');
   petEntry.innerHTML = descrip + '/' + type + '/' + date;
   document.getElementById(status).appendChild(petEntry);

   // Clicking on the sidebar pet entry triggers a click on its associated marker
   //petEntry.onclick = function () { GEvent.trigger(PetMarker, 'click') };

   // Create a DOM listner for each Pet report
   GEvent.addDomListener(petEntry, 'click', petEntryCallback(PetMarker));

};

function petEntryCallback(thisMarker)
{
  return function() {GEvent.trigger(thisMarker, 'click')};
};
