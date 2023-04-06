package mx.myam.mireparto.service

import android.database.Observable
import mx.myam.mireparto.models.RepartidorGps
import mx.myam.mireparto.service.request.ActualizarEntregaRequest
import mx.myam.mireparto.service.request.ParadasRequest
import mx.myam.mireparto.service.response.BaseResponse
import mx.myam.mireparto.service.response.ParadasResponse
import mx.myam.mireparto.service.response.RutasResponse
import okhttp3.RequestBody
import retrofit2.Call
import retrofit2.Callback
import retrofit2.http.Body
import retrofit2.http.Field
import retrofit2.http.FormUrlEncoded
import retrofit2.http.GET
import retrofit2.http.Multipart
import retrofit2.http.POST
import retrofit2.http.PUT
import retrofit2.http.Part
import retrofit2.http.Query

interface ApiClient {
    @GET("rutas.php")
    fun getRutas(@Query("idRepartidor")idRepartidor:Int,@Query("action")accion:String): Call<RutasResponse>

    @POST("paradas.php")
    fun getParadas(@Body idRuta:ParadasRequest,@Query("action")accion:String): Call<ParadasResponse>

    @POST("paradas.php")
    fun actualizarEntrega(@Body data: ActualizarEntregaRequest, @Query("action")accion:String): Call<BaseResponse>

    //@FormUrlEncoded
    @POST("ubicaciones.php")
    fun registerLocationUser(@Query("action")accion:String,@Body repartidorGps: RepartidorGps): Call<BaseResponse>
}

object Actions {
    const val GET_BY_REPARTIDOR = "getByRepartidor"
    const val GET_BY_RUTA = "getByRuta"
    const val UPDATE_ENTREGA = "updateEstatus"
    const val REGISTER_GPS = "registerLocationUser"
}