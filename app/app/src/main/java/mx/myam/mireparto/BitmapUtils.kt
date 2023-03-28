package mx.myam.mireparto

import android.graphics.Bitmap
import android.graphics.Canvas
import android.view.View

object BitmapUtils {
    /**
     * Convierte una vista en un mapa de bits.
     *
     * @param view La vista que se va a convertir.
     * @return El mapa de bits generado a partir de la vista.
     */
    fun getBitmapFromView(view: View): Bitmap {
        view.measure(
            View.MeasureSpec.makeMeasureSpec(0, View.MeasureSpec.UNSPECIFIED),
            View.MeasureSpec.makeMeasureSpec(0, View.MeasureSpec.UNSPECIFIED)
        )
        val width = view.measuredWidth
        val height = view.measuredHeight
        val bitmap = Bitmap.createBitmap(width, height, Bitmap.Config.ARGB_8888)
        val canvas = Canvas(bitmap)
        view.layout(0, 0, width, height)
        view.draw(canvas)
        return bitmap
    }

}
