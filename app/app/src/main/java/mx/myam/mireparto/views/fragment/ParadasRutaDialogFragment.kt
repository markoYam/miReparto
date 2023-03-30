package mx.myam.mireparto.views.fragment

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.DialogFragment
import mx.myam.mireparto.R

class ParadasRutaDialogFragment: DialogFragment(){
    companion object {
        fun newInstance(): ParadasRutaDialogFragment {
            return ParadasRutaDialogFragment()
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val view = inflater.inflate(R.layout.paradas_ruta_dialog_fragment, container, false)

        return view
    }
    //define wrap content for dialog
    override fun onResume() {
        super.onResume()
        val params = dialog!!.window!!.attributes
        params.width = ViewGroup.LayoutParams.WRAP_CONTENT
        params.height = ViewGroup.LayoutParams.WRAP_CONTENT
        dialog!!.window!!.attributes = params as android.view.WindowManager.LayoutParams
    }
}