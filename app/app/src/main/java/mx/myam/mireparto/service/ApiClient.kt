package mx.myam.mireparto.service

import android.database.Observable
import mx.myam.mireparto.service.response.BaseResponse
import mx.myam.mireparto.service.response.ParadasResponse
import mx.myam.mireparto.service.response.RutasResponse
import okhttp3.RequestBody
import retrofit2.Call
import retrofit2.Callback
import retrofit2.http.GET
import retrofit2.http.Multipart
import retrofit2.http.PUT
import retrofit2.http.Part
import retrofit2.http.Query

interface ApiClient {
    @GET("rutas.php")
    fun getRutas(): Call<RutasResponse>

    @GET("paradas.php")
    fun getParadas(@Query("idRuta") idRuta:Int): Call<ParadasResponse>

    @PUT("paradas.php")
    fun actualizarEntrega(@Query("idParada") idParada: Int, @Query("idEstatus") idEstatus:Int,@Query("accion")accion:String): Call<BaseResponse>
}