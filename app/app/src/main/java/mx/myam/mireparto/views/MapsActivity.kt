package mx.myam.mireparto.views

import android.app.ProgressDialog
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.graphics.Color
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.net.Uri
import android.os.Bundle
import android.util.Base64
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.widget.AdapterView
import android.widget.ArrayAdapter
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.afollestad.materialdialogs.MaterialDialog
import com.google.android.gms.maps.*
import com.google.android.gms.maps.model.*
import com.google.android.material.snackbar.Snackbar
import com.google.maps.DirectionsApi
import com.google.maps.GeoApiContext
import com.google.maps.internal.PolylineEncoding
import com.google.maps.model.LatLng
import com.google.maps.model.TravelMode
import kotlinx.coroutines.*
import mx.myam.mireparto.helper.BitmapUtils
import mx.myam.mireparto.R
import mx.myam.mireparto.controllers.IRutasController
import mx.myam.mireparto.controllers.RutasController
import mx.myam.mireparto.databinding.ActivityMapsBinding
import mx.myam.mireparto.models.Paradas
import mx.myam.mireparto.models.RepartidorGps
import mx.myam.mireparto.models.Rutas
import mx.myam.mireparto.service.response.ParadasResponse
import mx.myam.mireparto.service.response.RutasResponse
import mx.myam.mireparto.views.fragment.ParadasRutaDialogFragment
import java.security.MessageDigest
import java.security.NoSuchAlgorithmException
import kotlin.math.atan2
import kotlin.math.cos
import kotlin.math.sin
import kotlin.math.sqrt


class MapsActivity : AppCompatActivity(), OnMapReadyCallback, IRutasController {

    private lateinit var mMap: GoogleMap
    private lateinit var binding: ActivityMapsBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        MapsInitializer.initialize(this, MapsInitializer.Renderer.LATEST) {
            //println(it.name)
        }
        binding = ActivityMapsBinding.inflate(layoutInflater)
        setContentView(binding.root)
        configureView()
        ctrl.getRutas()

        // Obtain the SupportMapFragment and get notified when the map is ready to be used.
        val mapFragment = supportFragmentManager
            .findFragmentById(R.id.map) as SupportMapFragment
        mapFragment.getMapAsync(this)

    }

    private fun configureView() {
        binding.spinnerRutas.onItemSelectedListener = object : AdapterView.OnItemSelectedListener {
            override fun onItemSelected(
                parent: AdapterView<*>?,
                view: View?,
                position: Int,
                id: Long
            ) {
                val ruta = lsRutas[position]
                ctrl.getParadas(ruta.idRuteo)
            }

            override fun onNothingSelected(parent: AdapterView<*>?) {
                // TODO Auto-generated method stub
            }
        }

        binding.chipCancelar.setOnClickListener {
            val idParada = binding.relativeLayoutMarkerDetails.tag.toString().toInt()
            ctrl.actualizarEntrega(idParada, 10)
        }
        binding.chipEntregado.setOnClickListener {
            val idParada = binding.relativeLayoutMarkerDetails.tag.toString().toInt()
            ctrl.actualizarEntrega(idParada, 9)
        }
        binding.chipReagendar.setOnClickListener {
            val idParada = binding.relativeLayoutMarkerDetails.tag.toString().toInt()
            ctrl.actualizarEntrega(idParada, 8)
        }

        binding.btnMenu.setOnClickListener {
            ParadasRutaDialogFragment.newInstance().show(supportFragmentManager, "ParadasRutaDialogFragment")
        }

        binding.imgNavigate.setOnClickListener {
            val idParada = binding.relativeLayoutMarkerDetails.tag.toString().toInt()
            val parada = lsParadas.find { it.idParada == idParada }
            if (parada != null) {
                val uri = "google.navigation:q=${parada.latitud},${parada.longitud}"
                val intent = Intent(Intent.ACTION_VIEW, Uri.parse(uri))
                intent.setPackage("com.google.android.apps.maps")
                startActivity(intent)
            }
        }

    }

    private fun configureMap() {
        mMap.uiSettings.isZoomControlsEnabled = true
        mMap.uiSettings.isZoomGesturesEnabled = true
        mMap.uiSettings.isScrollGesturesEnabled = true
        mMap.uiSettings.isRotateGesturesEnabled = true
        mMap.uiSettings.isTiltGesturesEnabled = true
        mMap.uiSettings.isCompassEnabled = true
        mMap.uiSettings.isMyLocationButtonEnabled = true
        mMap.uiSettings.isMapToolbarEnabled = true
    }


    private fun generateIcon(number: String, status: Int): BitmapDescriptor {
        val markerView = LayoutInflater.from(this).inflate(R.layout.marker_with_number, null)
        val numeroTextView = markerView.findViewById<TextView>(R.id.numero)
        numeroTextView.text = number
        when (status) {
            8 -> {
                numeroTextView.background = getDrawable(R.drawable.circle_gray)
            }
            9 -> {
                numeroTextView.background = getDrawable(R.drawable.circle_green)
            }
            else -> {
                numeroTextView.background = getDrawable(R.drawable.circle_red)
            }
        }
        val iconBitmap = BitmapUtils.getBitmapFromView(markerView)
        val icono = BitmapDescriptorFactory.fromBitmap(iconBitmap)
        return icono
    }

    private fun generateIconLocation(): BitmapDescriptor {
        val markerView = LayoutInflater.from(this).inflate(R.layout.marker_location, null)
        val iconBitmap = BitmapUtils.getBitmapFromView(markerView)
        val icono = BitmapDescriptorFactory.fromBitmap(iconBitmap)
        return icono
    }

    private fun changeThemeMaps() {
        val success = mMap.setMapStyle(
            MapStyleOptions.loadRawResourceStyle(
                this, R.raw.style_json
            )
        )
        if (!success) {
            Log.e("MapsActivity", "Style parsing failed.")
        }
    }

    private fun getMaxDistance(origin: LatLng): LatLng {
        var maxDistance = 0.0
        var maxDistanceLatLng = LatLng(0.0, 0.0)

        for (i in lsParadas.indices) {
            val latLng = LatLng(lsParadas[i].latitud, lsParadas[i].longitud)
            val distance = computeDistanceBetween(origin, latLng)
            lsParadas[i].distance = distance
            if (distance > maxDistance) {
                maxDistance = distance
                maxDistanceLatLng = latLng
            }
        }

        lsParadas.sortBy { it.distance }

        return maxDistanceLatLng
    }

    val lsMarkers = mutableListOf<Marker>()

    private fun computeDistanceBetween(origin: LatLng, destino: LatLng): Double {
        val R = 6371 // Radius of the earth in km
        val dLat = Math.toRadians(destino.lat - origin.lat)  // deg2rad below
        val dLon = Math.toRadians(destino.lng - origin.lng)
        val a =
            sin(dLat / 2) * sin(dLat / 2) +
                    cos(Math.toRadians(origin.lat)) * cos(Math.toRadians(destino.lat)) *
                    sin(dLon / 2) * Math.sin(dLon / 2)
        val c = 2 * atan2(sqrt(a), sqrt(1 - a))
        val d = R * c // Distance in km
        return d
    }

    @OptIn(DelicateCoroutinesApi::class)
    private fun actualizarMarkerParadas() {
        val apiKey = getString(R.string.api_key)
        val geoApiContext = GeoApiContext.Builder()
            .apiKey(apiKey)
            .build()

        GlobalScope.launch(Dispatchers.IO) {
            try {
                var destino = LatLng(0.0, 0.0)
                var latLngUsuario = LatLng(0.0, 0.0)

                if (locationUser != null) {
                    runOnUiThread {
                        latLngUsuario =
                            LatLng(
                                locationUser?.latitude ?: 0.0,
                                locationUser?.longitude ?: 0.0
                            )
                        destino = getMaxDistance(latLngUsuario)
                    }
                }

                val paradas = lsParadas.map { LatLng(it.latitud, it.longitud) }.toMutableList()
                //val item = locations.toTypedArray();
                //clear all map
                withContext(Dispatchers.Main) {
                    mMap.clear()
                    lsParadas.forEach {
                        val icon = generateIcon(it.idParada.toString(), it.idEstatus)
                        val mk = mMap.addMarker(
                            MarkerOptions().position(
                                com.google.android.gms.maps.model.LatLng(
                                    it.latitud,
                                    it.longitud
                                )
                            ).icon(icon).snippet(it.idParada.toString())
                        )

                        mk?.tag = it.idParada

                        if (mk != null)
                            lsMarkers.add(mk)
                    }
                }


                val request = DirectionsApi.newRequest(geoApiContext)
                    .mode(TravelMode.DRIVING)
                    .origin(latLngUsuario)
                    .destination(destino)
                    .optimizeWaypoints(true)
                    .waypoints(*paradas.toTypedArray())

                val response = request.await()

                val polylineOptions = PolylineOptions()
                    .width(12f)
                    .color(Color.RED)

                if (response.routes.isNotEmpty()) {
                    val route = response.routes[0]
                    val legs = route.legs

                    for (leg in legs) {
                        val steps = leg.steps

                        for (step in steps) {
                            val polyline = step.polyline
                            val decodedPoints = PolylineEncoding.decode(polyline.encodedPath)

                            for (point in decodedPoints) {
                                val latLng = LatLng(point.lat, point.lng)
                                withContext(Dispatchers.Main) {
                                    val lt = com.google.android.gms.maps.model.LatLng(
                                        latLng.lat,
                                        latLng.lng
                                    )
                                    //mMap.addMarker(MarkerOptions().position(com.google.android.gms.maps.model.LatLng(latLng.lat, latLng.lng)))
                                    polylineOptions.add(lt)
                                }
                            }
                        }
                    }

                    //Acualizar para obtener la ubicacion del usuario
                    //val locations2 = locations.map { com.google.android.gms.maps.model.LatLng(it.lat, it.lng) }
                    // val polylineOptions = PolylineOptions()
                    //    .width(5f)
                    //    .color(Color.BLUE)
                    //   .addAll(locations2)

                    withContext(Dispatchers.Main) {
                        mMap.addPolyline(polylineOptions)
                        //val latLng = com.google.android.gms.maps.model.LatLng(paradas[0].lat, paradas[0].lng)
                        val latLng =
                            com.google.android.gms.maps.model.LatLng(
                                markerLocation!!.position.latitude,
                                markerLocation!!.position.longitude
                            )
                        mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(latLng, 14f))
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
                Log.e("MainActivity", "Error al obtener la ruta: ${e.localizedMessage}")
            }
        }
    }

    /**
     * Manipulates the map once available.
     * This callback is triggered when the map is ready to be used.
     * This is where we can add markers or lines, add listeners or move the camera. In this case,
     * we just add a marker near Sydney, Australia.
     * If Google Play services is not installed on the device, the user will be prompted to install
     * it inside the SupportMapFragment. This method will only be triggered once the user has
     * installed Google Play services and returned to the app.
     */
    override fun onMapReady(googleMap: GoogleMap) {
        mMap = googleMap
        // getSha1()
        configureMap()
        //changeThemeMaps()
        //mockData()
        requestPermissionLocation()
        mMap.setOnMarkerClickListener(object : GoogleMap.OnMarkerClickListener {
            override fun onMarkerClick(marker: Marker): Boolean {
                binding.relativeLayoutMarkerDetails.tag = null

                if (marker.snippet == "Mi ubicación") {
                    return false
                }

                val idParada = marker.snippet?.toInt() ?: -1
                if (idParada == -1) {
                    return false
                }


                val parada = lsParadas.find { it.idParada == idParada }
                if (parada != null) {
                    binding.relativeLayoutMarkerDetails.tag = idParada
                    binding.relativeLayoutMarkerDetails.visibility = View.VISIBLE
                    binding.textViewMarkerTitle.text =
                        "Parada #${parada.idParada} - ${parada.cliente}"

                    val adapter: ArrayAdapter<*>

                    val productos = parada.productos.map { "x${it.dcCantidad.toInt()} - ${it.nbProducto}" }.toMutableList()

                    adapter = ArrayAdapter(
                        this@MapsActivity,
                        android.R.layout.simple_list_item_1,
                        productos
                    )
                    binding.listViewMarkerProducts.adapter = adapter
                    binding.textViewMarkerDescription.text = parada.comentarios
                    binding.textViewTotal.text = "$" + parada.productos.sumOf {x-> x.dcCantidad * x.dcPrecioVenta }.toString()
                } else {
                    clearDetail()
                }
                return false
            }
        })
        mMap.setOnMapClickListener {
            binding.relativeLayoutMarkerDetails.tag
            binding.relativeLayoutMarkerDetails.visibility = View.GONE
        }
        // Add a marker in Sydney and move the camera
        //val sydney = LatLng(-34.0, 151.0)
        //mMap.addMarker(MarkerOptions().position(sydney).title("Marker in Sydney"))
        // mMap.moveCamera(CameraUpdateFactory.newLatLng(sydney))
    }

    private fun getSha1() {
        //get sha 1
        try {
            val info = packageManager.getPackageInfo(
                "mx.myam.mireparto",
                PackageManager.GET_SIGNATURES
            )
            for (signature in info.signatures) {
                val md = MessageDigest.getInstance("SHA")
                md.update(signature.toByteArray())
                Log.d("KeyHash:", Base64.encodeToString(md.digest(), Base64.DEFAULT))
                //log sha 1
                Log.e("KeyHash_1", md.digest().toString())
            }
        } catch (e: PackageManager.NameNotFoundException) {
            e.printStackTrace()

        } catch (e: NoSuchAlgorithmException) {
            e.printStackTrace()

        }

    }

    private fun requestPermissionLocation() {
        if (checkSelfPermission(android.Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            requestPermissions(arrayOf(android.Manifest.permission.ACCESS_FINE_LOCATION), 1)
        } else {
            mMap.isMyLocationEnabled = true
        }


        val locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        val provider = LocationManager.GPS_PROVIDER
        locationManager.requestLocationUpdates(provider, 0, 5f, locationListener)
        val location = locationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER)
        if (location != null) {
            val latLng = LatLng(location.latitude, location.longitude)
            // Aquí irá el código para mostrar el icono
            updateMarkerLocation(location)
            //move camera
            val latLng2 =
                com.google.android.gms.maps.model.LatLng(location.latitude, location.longitude)
            mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(latLng2, 14f))
        }
    }

    private val locationListener = object : LocationListener {
        override fun onLocationChanged(location: Location) {
            // Aquí irá el código para actualizar la ubicación en el mapa
            updateMarkerLocation(location)
        }
    }

    fun updateMarkerLocation(location: Location) {
        locationUser = location
        if (markerLocation != null) {
            markerLocation?.remove()
        }
        markerLocation = mMap.addMarker(markerLocationOp.icon(generateIconLocation()))
        Log.e("Location", "Latitud: ${location.latitude} Longitud: ${location.longitude}")
        val latLng = com.google.android.gms.maps.model.LatLng(location.latitude, location.longitude)

        markerLocation?.position = latLng
        markerLocation?.rotation = location.bearing

        val data = RepartidorGps()
        data.idRepartidor = 1
        data.latitud = location.latitude
        data.longitud = location.longitude

        //ctrl.registerLocationUser(data)
    }

    private var locationUser: Location? = null
    private var markerLocation: com.google.android.gms.maps.model.Marker? = null
    private var markerLocationOp = MarkerOptions()
        .position(com.google.android.gms.maps.model.LatLng(20.209052, -89.292277))
        .snippet("Mi ubicación")


    override fun onStart() {
        super.onStart()

    }

    val ctrl = RutasController(this)

    var lsRutas: MutableList<Rutas> = mutableListOf()
    var lsParadas: MutableList<Paradas> = mutableListOf()

    override fun onSuccesRutas(rutas: RutasResponse) {
        Log.e("MainActivity", "Rutas: ${rutas.data?.size}")

        lsRutas = rutas.data?.toMutableList() ?: mutableListOf()

        val lsItems = rutas.data?.map { it.folio } ?: emptyList()
        val adapterSpinner = ArrayAdapter(this, android.R.layout.simple_spinner_item, lsItems)

        GlobalScope.launch(Dispatchers.Main) {
            withContext(Dispatchers.Main) {
                adapterSpinner.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
                binding.spinnerRutas.adapter = adapterSpinner
            }
        }
    }

    override fun onErrorRutas(error: String) {
        Log.e("MainActivity", "Error al obtener la ruta: ${error}")
        Snackbar.make(binding.root, error, Snackbar.LENGTH_LONG).show()
    }

    override fun onSuccesParadas(paradas: ParadasResponse) {
        Log.e("MainActivity", "Paradas: ${paradas.data?.size}")
        lsParadas = paradas.data?.toMutableList() ?: mutableListOf()
        actualizarMarkerParadas()
    }

    override fun onErrorParadas(error: String) {
        // Snackbar background red
        GlobalScope.launch(Dispatchers.Main) {
            withContext(Dispatchers.Main) {
                mMap.clear()
                markerLocation = null
                clearDetail()
            }
        }


        Snackbar.make(binding.root, error, Snackbar.LENGTH_LONG).show()
    }

    override fun onSuccesActualizarEntrega(idParada: Int, status: Int) {
        //Snackbar.make(binding.root, "Actualizada con éxito", Snackbar.LENGTH_LONG).show()
        //show material dialog
        runOnUiThread {
            MaterialDialog(this).show {
                title(text = "Atención")
                message(text = "Entrega actualizada con éxito")
                positiveButton(text = "Aceptar") {
                    //do something
                    dismiss()
                    val index = lsParadas.indexOfFirst { it.idParada == idParada }
                    if(index != -1) {
                        val icon = generateIcon(lsParadas[index].idParada.toString(), status)

                        val indexMarker = lsMarkers.indexOfFirst { it.tag == idParada }
                        if(indexMarker != -1) {
                            lsMarkers[indexMarker].setIcon(icon)
                        }
                    }
                    clearDetail()

                }
            }

        }

    }

    override fun onErrorActualizarEntrega(error: String) {
        //Snackbar.make(binding.root, error, Snackbar.LENGTH_LONG).show()
        //show material dialog

        runOnUiThread {
            MaterialDialog(this).show {
                title(text = "Atención")
                message(text = "Error al actualizar la entrega")
                positiveButton(text = "Aceptar") {
                    //do something
                    dismiss()
                }
            }
        }

    }

    override fun isLoading(isLoading: Boolean) {
        if(isLoading) {
            showProgressDialog()
        } else {
            progressDialog?.dismiss()
        }
    }

    private fun showProgressDialog() {
        progressDialog = ProgressDialog(this)
        progressDialog?.setMessage("Cargando... Por favor espere")
        progressDialog?.setCancelable(false)
        progressDialog?.show()
    }

    var progressDialog: ProgressDialog? = null

    fun clearDetail() {
        binding.relativeLayoutMarkerDetails.tag = null
        binding.relativeLayoutMarkerDetails.visibility = View.GONE
        binding.textViewMarkerTitle.text = ""
        binding.textViewMarkerDescription.text = ""
        binding.listViewMarkerProducts.adapter = null
        binding.textViewTotal.text = ""
    }
}