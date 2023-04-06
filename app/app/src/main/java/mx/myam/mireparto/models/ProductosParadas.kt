package mx.myam.mireparto.models

data class ProductosParadas(val idProducto: Long,
                       val nbProducto: String,
                       val desProducto: String,
                       val dcPrecioCompra: Double,
                       val dcPrecioVenta: Double,
                       val dcComision: Long,
                       val dcCantidad: Double,
                       val idParada: Long,
                       val idRuta: Long,
                       val idEstatus: Long,
                       val nbEstatus: String)

