package mx.myam.mireparto.service

import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object ClientService {
    val interceptor = HttpLoggingInterceptor().apply {
        level = HttpLoggingInterceptor.Level.BODY
    }
    val client = Retrofit.Builder()
        //.baseUrl("https://esmeraldamayoreo.000webhostapp.com/ws/") //prod
        .baseUrl("http://192.168.1.100/miReparto/miReparto/ws/") //dev
        .addConverterFactory(GsonConverterFactory.create())
            //add interceptor all request
        .client(OkHttpClient.Builder().addInterceptor( interceptor ).build())
        .build()
    val apiClient = client.create(ApiClient::class.java)
}