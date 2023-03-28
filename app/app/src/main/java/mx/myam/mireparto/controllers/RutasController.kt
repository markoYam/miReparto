package mx.myam.mireparto.controllers

import com.google.maps.model.LatLng
import kotlinx.coroutines.DelicateCoroutinesApi
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import mx.myam.mireparto.service.ClientService
import mx.myam.mireparto.service.response.ParadasResponse
import mx.myam.mireparto.service.response.RutasResponse
import okhttp3.RequestBody

class RutasController(val iRutasController: IRutasController) {

    @OptIn(DelicateCoroutinesApi::class)
    fun getRutas() {
        GlobalScope.launch(Dispatchers.IO) {
            try {
                val rutasResponse = ClientService.apiClient.getRutas().execute().body()
                if (rutasResponse?.idEstatus == 1) {
                    iRutasController.onSuccesRutas(rutasResponse)
                } else {
                    iRutasController.onErrorRutas(
                        rutasResponse?.mensaje ?: "No fue posible obtener la información"
                    )
                }
            } catch (e: Exception) {
                iRutasController.onErrorRutas(e.message.toString())
            }
        }

    }

    @OptIn(DelicateCoroutinesApi::class)
    fun getParadas(idRuta:Int){
        GlobalScope.launch(Dispatchers.IO) {
            try {
                val paradasResponse = ClientService.apiClient.getParadas(idRuta).execute().body()
                if (paradasResponse?.idEstatus == 1) {
                    iRutasController.onSuccesParadas(paradasResponse)
                } else {
                    iRutasController.onErrorParadas(
                        paradasResponse?.mensaje ?: "No fue posible obtener la información"
                    )
                }
            } catch (e: Exception) {
                iRutasController.onErrorParadas(e.message.toString())
            }
        }
    }

    fun actualizarEntrega(idParada: Int, status: Int) {
        GlobalScope.launch(Dispatchers.IO) {
            try {
                val x = RequestBody.create(okhttp3.MultipartBody.FORM, idParada.toString())
                val y = RequestBody.create(okhttp3.MultipartBody.FORM, status.toString())
                val actualizarEntregaResponse = ClientService.apiClient.actualizarEntrega(idParada, status,"actualizarRutas").execute().body()
                if (actualizarEntregaResponse?.idEstatus == 1) {
                    iRutasController.onSuccesActualizarEntrega(idParada, status)
                } else {
                    iRutasController.onErrorActualizarEntrega(
                        actualizarEntregaResponse?.mensaje ?: "No fue posible obtener la información"
                    )
                }
            } catch (e: Exception) {
                iRutasController.onErrorActualizarEntrega(e.message.toString())
            }
        }


    }

}

interface IRutasController {
    fun onSuccesRutas(rutas: RutasResponse)
    fun onErrorRutas(error: String)

    fun onSuccesParadas(paradas: ParadasResponse)
    fun onErrorParadas(error: String)

    fun onSuccesActualizarEntrega(idParada: Int, status: Int)
    fun onErrorActualizarEntrega(error: String)
}