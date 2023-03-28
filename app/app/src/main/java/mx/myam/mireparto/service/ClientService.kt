package mx.myam.mireparto.service

import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object ClientService {
    val client = Retrofit.Builder()
        .baseUrl("http://192.168.1.100/miReparto/miReparto/ws/")
        .addConverterFactory(GsonConverterFactory.create())
        .build()
    val apiClient = client.create(ApiClient::class.java)
}