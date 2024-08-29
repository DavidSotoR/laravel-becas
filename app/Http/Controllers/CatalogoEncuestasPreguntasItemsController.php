<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CatalogoEncuestasPreguntasItems;

class CatalogoEncuestasPreguntasItemsController extends Controller
{
    public function lista($id_pregunta){
        $query = CatalogoEncuestasPreguntasItems::query();

        $query->where('id_pregunta', $id_pregunta);

        $lista = $query->get();
        return response()->json($lista);
    }
    public function nuevo($id_pregunta = 0){

        if(!$id_pregunta){
            return response()->json(["id_pregunta" => ["id de pregunta es requerido"]], 400);
        }

        $validator = Validator::make($request->all(),[
            'descripcion'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = CatalogoEncuestasPreguntasItems::create(array_merge(
                $validator->validate(),
                [
                    'id_pregunta' => $id_pregunta
                ]
            )
        );

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
    }

    public function editar(Request $request,$id = 0){

        $pregunta_item = CatalogoEncuestasPreguntasItems::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'descripcion'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = $pregunta_item->update($validator->validate());

        return response()->json(['message' => 'Elemento modificado', 'data' => $editar], 201);
    }

    public function eliminar($id){
        $elemento = CatalogoEncuestasPreguntasItems::where('id',$id)->delete();
        return response()->json($elemento);
    }
}
