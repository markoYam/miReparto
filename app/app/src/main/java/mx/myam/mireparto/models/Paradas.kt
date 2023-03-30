package mx.myam.mireparto.models

import com.google.android.gms.maps.model.Marker

data class Paradas (
    val idParada: Int,
    val idRuta: String,
    val fecha: String,
    val idEstatus: Int,
    val latitud: Double,
    val longitud: Double,
    val comentarios: String,
    val cliente: String,
    var distance:Double,
    var marker:Marker,
    val feActualizacion: String,
    var productos:List<ProductosParadas> = listOf()
)
