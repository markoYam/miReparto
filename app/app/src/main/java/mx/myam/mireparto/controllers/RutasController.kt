package mx.myam.mireparto.controllers

import com.google.maps.model.LatLng
import kotlinx.coroutines.DelicateCoroutinesApi
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import mx.myam.mireparto.models.RepartidorGps
import mx.myam.mireparto.service.Actions
import mx.myam.mireparto.service.ClientService
import mx.myam.mireparto.service.request.ActualizarEntregaRequest
import mx.myam.mireparto.service.request.ParadasRequest
import mx.myam.mireparto.service.response.ParadasResponse
import mx.myam.mireparto.service.response.RutasResponse
import okhttp3.RequestBody

class RutasController(val iRutasController: IRutasController) {

    @OptIn(DelicateCoroutinesApi::class)
    fun getRutas() {
        iRutasController.isLoading(true)
        GlobalScope.launch(Dispatchers.IO) {
            try {
                val rutasResponse = ClientService.apiClient.getRutas(1, Actions.GET_BY_REPARTIDOR).execute().body()
                iRutasController.isLoading(false)
                if (rutasResponse?.idEstatus == 1) {
                    iRutasController.onSuccesRutas(rutasResponse)
                } else {
                    iRutasController.onErrorRutas(
                        rutasResponse?.mensaje ?: "No fue posible obtener la información"
                    )
                }
            } catch (e: Exception) {
                e.printStackTrace()
                iRutasController.isLoading(false)
                iRutasController.onErrorRutas(e.message.toString())
            }
        }

    }


    @OptIn(DelicateCoroutinesApi::class)
    fun getParadas(idRuta:Int){
        iRutasController.isLoading(true)
        GlobalScope.launch(Dispatchers.IO) {
            try {
                val paradasResponse = ClientService.apiClient.getParadas(ParadasRequest(idRuta),Actions.GET_BY_RUTA).execute().body()
                iRutasController.isLoading(false)
                if (paradasResponse?.idEstatus == 1) {
                    iRutasController.onSuccesParadas(paradasResponse)
                } else {
                    iRutasController.onErrorParadas(
                        paradasResponse?.mensaje ?: "No fue posible obtener la información"
                    )
                }
            } catch (e: Exception) {
                e.printStackTrace()
                iRutasController.isLoading(false)
                iRutasController.onErrorParadas(e.message.toString())
            }
        }
    }

    @OptIn(DelicateCoroutinesApi::class)
    fun actualizarEntrega(idParada: Int, status: Int) {
        iRutasController.isLoading(true)
        GlobalScope.launch(Dispatchers.IO) {
            try {
                val actualizarEntregaResponse = ClientService.apiClient.actualizarEntrega(
                    ActualizarEntregaRequest(idParada,status,""),Actions.UPDATE_ENTREGA).execute().body()
                iRutasController.isLoading(false)
                if (actualizarEntregaResponse?.idEstatus == 1) {
                    iRutasController.onSuccesActualizarEntrega(idParada, status)
                } else {
                    iRutasController.onErrorActualizarEntrega(
                        actualizarEntregaResponse?.mensaje ?: "No fue posible obtener la información"
                    )
                }
            } catch (e: Exception) {
                e.printStackTrace()
                iRutasController.isLoading(false)
                iRutasController.onErrorActualizarEntrega(e.message.toString())
            }
        }
    }

    @OptIn(DelicateCoroutinesApi::class)
    fun registerLocationUser(repartidorGps: RepartidorGps) {
        //iRutasController.isLoading(true)
        GlobalScope.launch(Dispatchers.IO) {
            try {
                val actualizarEntregaResponse = ClientService.apiClient.registerLocationUser(Actions.REGISTER_GPS,repartidorGps).execute().body()
               // iRutasController.isLoading(false)
                if (actualizarEntregaResponse?.idEstatus == 1) {
                    iRutasController.onSuccesActualizarEntrega(1, 1)
                } else {
                    iRutasController.onErrorActualizarEntrega(
                        actualizarEntregaResponse?.mensaje ?: "No fue posible obtener la información"
                    )
                }
            } catch (e: Exception) {
                e.printStackTrace()
               // iRutasController.isLoading(false)
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

    fun isLoading(isLoading: Boolean)
}