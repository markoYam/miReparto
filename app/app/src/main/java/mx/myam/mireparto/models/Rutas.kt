package mx.myam.mireparto.models

import com.google.gson.annotations.SerializedName

data class Rutas (
    val idRuteo: Int,
    val fecha: String,
    @SerializedName("Folio")
    val folio: String,
    val idRepartidor: Int,
    val idEstatus: Long,
    val feFin: Any? = null
)