package mx.myam.mireparto.models

import com.google.gson.annotations.SerializedName

data class Rutas (
    val idRuteo: Int,
    val fecha: String,
    @SerializedName("Repartidor")
    val repartidor: String
)