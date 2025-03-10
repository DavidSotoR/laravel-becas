<?
use Illuminate\Support\Facades\Storage;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Estudio</title>
    <link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">
    <style>
        @page {
            margin: 50px 25px 100px; /* Ajusta el margen inferior para dar espacio al pie de página */
        }
        @font-face {
            font-family: 'MiFuentePersonalizada';
            src: url('public_path('/public/fonts/TT_Rounds_Neue_Trial_Regular.ttf')' ) format('truetype');/* GarbataTrial-Regular */
            font-weight: normal;
            font-style: normal;
        }
        header {
            position: fixed;
            top: -40px;
            left: 0;
            right: 0;
            height: 50px;
            font-size: 11px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }


        @font-face {
            font-family: 'MiFuentePersonalizada_2';
            src: url('/public/fonts/TT_Rounds_Neue_Trial_Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body,h5,h4,h3,h2,h1 {
            font-family: 'MiFuentePersonalizada', sans-serif;
        }
        .numeros {
            font-family: 'MiFuentePersonalizada_2, sans-serif';
            font-size: .9rem;
        }
        .page-break {
            page-break-before: always; /* O usa page-break-after: always; según sea necesario */
        }
        .text-before-page-break {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-top: 220px;
        }
        .no-page-break {
            page-break-inside: avoid; /* Evita que el contenido se corte */
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #555;
        }
        .respuestas{
            font-size:11px;
        }
        br{
            margin-top: 0px;
        }

        @page:first header { display: none; }
    </style>
</head>
<body>

    <!--<header>
        <p class="text-uppercase">{{$encuesta->estudio->cliente->nombre}} <br> FAMILIA {{$encuesta->estudio->candidato}}</p>
    </header>-->

    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <h5>SINERGIA EN ESTUDIOS SOCIOECONÓMICOS</h5>
                <br/>
                <p class="text-uppercase mt-5">ESTUDIO SOCIOECONÓMICO PARA BECAS<br>{{$encuesta->proyecto->nombre}}</p>
                <br/>
                <p class="text-uppercase">{{$encuesta->estudio->cliente->nombre}}</p>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <p>RESULTADO CONFIDENCIAL</p>
                <br/>
                <p>FAMILIA</p>
                <br/>
                <table style="width: 100%">
                    <tr>
                    <td style="width: 20%"></td>
                    <td>
                        <div class="text-center text-uppercase border-bottom pb-2">
                            {{$encuesta->estudio->candidato}}
                        </div>
                    </td>
                    <td style="width: 20%"></td>
                    </tr>
                </table>
                <div class="footer">
                    <i>EL COLEGIO ES RESPONSABLE DE LA INFORMACIÓN Y CONTENIDO DEL PRESENTE ESTUDIO</i>
                </div>
                <div class="page-break"></div>  <!--Salto de página -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <br/>
                <p class="text-uppercase mt-5">COMENTARIO DEL ENTREVISTADOR</p>
                <br/>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 10%"></td>
                        <td>
                            <div class="text-center text-uppercase ">
                                {{getClasificacionEncuesta($encuesta->preguntas)}}
                            </div>
                        </td>
                        <td style="width: 10%"></td>
                    </tr>
                </table>
                <br/>
                @if($encuesta->estudio->aniadir_observaciones === 1 )
                <table style="width: 100%">
                    <tr>
                        <td style="width: 10%"></td>
                        <td>RESUMEN</td>
                        <td style="width: 10%"></td>
                    </tr>
                </table>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 10%"></td>
                        <td class="text-start  border" style="height: 230px">
                            <div
                            style="
                                width: 100%;
                                min-height: 200px;
                                padding: 5px;
                                line-height: 1.55;
                                white-space: pre-wrap;
                                font-size:12px;
                            ">{{$encuesta->estudio->observaciones}}</div>
                        </td>
                        <td style="width: 10%"></td>
                    </tr>
                </table>
                <br>
                @endif

                <br>
                <br>
                <p class="text-uppercase">{{$encuesta->estudio->cliente->nombre}}</p>
                <p class="text-uppercase">{{$encuesta->estudio->candidato}}</p>

                @if (isset($encuesta["hijo"]))
                <p class="text-uppercase">{{$encuesta->hijo->nombre}}</p>
                @endif

                <table style="width: 100%; font-size: 12px;">
                    @foreach ($encuesta->parametros as $parametro)
                    <tr>
                        <td style="width: 20%"></td>
                        <td style="width: 30%" class="text-uppercase">{{$parametro->nombre}}</td>
                        <td style="width: 5%"></td>
                        <td style="width: 10%" class="border-bottom text-center">{{$parametro->puntos->valor ?? ($parametro->puntos["valor"] ?? '' )}}</td>
                        <td style="width: 5%"></td>
                        <td style="width: 20%"></td>
                    </tr>
                    @endforeach

                    <tr>
                        <td style="width: 20%"></td>
                        <td style="width: 30%">TOTAL</td>
                        <td style="width: 5%"></td>
                        <td style="width: 10%" class="border-bottom text-center">{{totalPuntosParametros($encuesta)}}</td>
                        <td style="width: 5%"></td>
                        <td style="width: 20%"></td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 15%"></td>
                        <td style="width: 15%">PORCENTAJE SUGERIDO</td>
                        <td style="width: 5%"  class="border text-center">{{porcentajeSugerido($encuesta)}}</td>
                        <td style="width: 5%"></td>
                        <td style="width: 15%">PORCENTAJE OTORGADO</td>
                        <td style="width: 5%"  class="border text-center">{{ (isset($encuesta["hijo"])) ? $encuesta->hijo->porcentaje_otorgado.'%' : ($encuesta->estudio->porcentaje_otorgado ? $encuesta->estudio->porcentaje_otorgado.'%':'')}}</td>
                        <td style="width: 15%"></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
    <div class="page-break"></div>  <!--Salto de página -->

    @if ($encuesta->preguntas)
        @foreach ($encuesta->preguntas as $pregunta)
            <div class="mt-1 mb-1 ms-5 me-5 no-page-break">
                <div class="pt-3">
                    <p style="font-size: 1rem" class="text-uppercase fw-bolder">{{$pregunta->numero_pregunta}}.- {{$pregunta->pregunta}}</p>
                </div>
                {!!
                    preguntaPorTipoPregunta(
                        $pregunta->id_catalogo_encuestas_preguntas_tipo,
                        $pregunta->respuestas,
                        $pregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion,
                        $encuesta->total_parametros,
                        ($pregunta->parametros ?? array()),
                        ($pregunta->parametros_promedio_academico ?? array()),
                        ($pregunta->parametros_promedio_conducta ?? array()),
                    )
                !!}
            </div>
        @endforeach
    @endif

    @if ($encuesta->imagenes)
    <div>

        @foreach ($encuesta->imagenes as $index => $imagenes)

            @if (count($imagenes->documentos) AND ($imagenes->nombre == "CASA HABITACION" || $imagenes->nombre == "AUTOMOVILES"))

                <div class="page-break"></div>  <!--Salto de página -->
                <h2>{{$imagenes->nombre}}</h2>
                <br><br>

                @foreach (mostrarImagenes($imagenes->documentos) AS $img)
                    {!! $img !!}
                @endforeach

            @endif

        @endforeach

    </div>
    @endif

</body>
</html>


<?php

/*
Metodos
*/

function totalPuntosParametros($encuesta){
    // Extraer los parámetros de la encuesta, manejando si es un objeto o un array
    $parametros = is_array($encuesta)
        ? ($encuesta->parametros ?? [])
        : ($encuesta['parametros'] ?? []);

    // Convertir a colección para facilitar el manejo
    return collect($parametros)
        ->reduce(function ($total, $parametro) {
            // Obtener el valor, manejando si es objeto o array
            $valor = $parametro->puntos->valor ??  ($parametro->puntos['valor'] ?? 0);

            // Convertir el valor a entero
            $valorInt = intval($valor, 10);

            // Sumar al total si es un número finito
            return $total + (is_finite($valorInt) ? $valorInt : 0);
        }, 0);
}

function grandTotalPuntosParametros($encuesta){
        // Extraer los parámetros de la encuesta, manejando si es un objeto o un array
        $parametros = is_array($encuesta)
            ? ($encuesta->parametros ?? [])
            : ($encuesta['parametros'] ?? []);

        // Convertir a colección para facilitar el manejo
        return collect($parametros)
            ->reduce(function ($total, $parametro) {
                // Obtener el valor, manejando si es objeto o array
                $valor = is_object($parametro)
                    ? ($parametro->puntos_maximo ?? 0)
                    : ($parametro['puntos_maximo'] ?? 0);

                // Convertir el valor a entero
                $valorInt = intval($valor, 10);

                // Sumar al total si es un número finito
                return $total + (is_finite($valorInt) ? $valorInt : 0);
            }, 0);
}
function porcentajeSugerido($encuesta){
        $puntos = totalPuntosParametros($encuesta);
        $total_puntos = grandTotalPuntosParametros($encuesta);

        $porcentaje = $total_puntos > 0 ? ($puntos * 100) / $total_puntos : 0;

        $bloquesDe20 = floor($porcentaje / 20);

        // Calculamos el descuento: cada bloque de 20% equivale a un 5% de descuento
        $descuento = $bloquesDe20 * 5;

        // Aseguramos que el descuento máximo sea 25%
        $descuentoFinal = min($descuento, 25);

        return $descuentoFinal . '%';
}

function imagenReturn($path){
    $contents = storage_path('app/public/' . $path);
    $type = pathinfo($contents, PATHINFO_EXTENSION);
    $data = file_get_contents($contents);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return "<img src=\"$base64\" width=\"350\" height=\"350\"/>";
}

function mostrarImagenes($lista_documentos){
    $imagenes = array();

    foreach($lista_documentos AS $documento){
        $img = imagenReturn($documento->directorio);
        $imagenes[] = $img;
    }

    return $imagenes;
}

function formatNumber($num) {
    return number_format($num, 0, '', ',');
}

function sumaTotalporCampo($campo,$formData) {

    //$formData = is_array($formData) ? $formData : (array) $formData;

    $total = 0;
    foreach($formData AS $index => $item){
        $value = isset($item[$campo]) ? floatval($item[$campo]) : 0;
        $total+= (is_nan($value) ? 0 : $value);
    }

    return $total;

    /*return array_reduce($formData, function($acc, $item) use ($campo) {
        $value = isset($item[$campo]) ? floatval($item[$campo]) : 0;
        return $acc + (is_nan($value) ? 0 : $value);
    }, 0);*/
}


function sumaTotales($formData) {
    $total = 0;
    $total += sumaTotalporCampo('padre_monto', $formData);
    $total += sumaTotalporCampo('madre_monto', $formData);
    $total += sumaTotalporCampo('monto', $formData);
    return $total;
}

function sumaTotalporCampoSeccion($campo, $seccion, $formData) {

    //$formData = is_array($formData) ? $formData : (array) $formData;

    $total = 0;
    foreach($formData AS $index => $item){
        if (isset($item['seccion']) && $item['seccion'] === $seccion) {
            $value = isset($item[$campo]) ? floatval($item[$campo]) : 0;
            $total+= (is_nan($value) ? 0 : $value);
        }
    }

    return $total;


    /*return array_reduce($formData, function($acc, $item) use ($campo, $seccion) {
        $value = 0;

        if (isset($item['seccion']) && $item['seccion'] === $seccion) {
            $value = isset($item[$campo]) ? floatval($item[$campo]) : 0;
        }

        return $acc + (is_nan($value) ? 0 : $value);
    }, 0);*/
}

function sumaTotalesSeccion($seccion, $formData) {
    $total = 0;
    $total += sumaTotalporCampoSeccion('padre_monto', $seccion, $formData);
    $total += sumaTotalporCampoSeccion('madre_monto', $seccion, $formData);
    $total += sumaTotalporCampoSeccion('monto', $seccion, $formData);
    return $total;
}
/*
Datos espesificos de preguntas
*/
function getClasificacionEncuesta($lista_preguntas) {
    $lista_preguntas_validadas = [];

    // Verificar si es un JSON y convertirlo a array
    if (is_string($lista_preguntas)) {
        $lista_preguntas_validadas = json_decode($lista_preguntas, true);

        // Validar que la conversión fue exitosa
        if (!is_array($lista_preguntas_validadas)) {
            return '';
        }
    } elseif (is_object($lista_preguntas)) {
        // Convertir objeto a array
        $lista_preguntas_validadas = json_decode(json_encode($lista_preguntas), true);
    } else {
        $lista_preguntas_validadas = $lista_preguntas;
    }

    // **Verificar que es un array antes de filtrar**
    if (!is_array($lista_preguntas_validadas)) {
        return '';
    }

    // **Filtrar preguntas con id_catalogo_encuestas_preguntas_tipo == 13**
    $pregunta = array_values(array_filter($lista_preguntas_validadas, function ($p) {
        return isset($p['id_catalogo_encuestas_preguntas_tipo']) && $p['id_catalogo_encuestas_preguntas_tipo'] == 13;
    }));

    // Si no hay preguntas, retornar vacío
    if (empty($pregunta)) {
        return '';
    }

    // Obtener el primer elemento válido
    $pregunta = $pregunta[0];

    // **Verificar que la pregunta tenga respuestas**
    if (!isset($pregunta['respuestas']) || !is_array($pregunta['respuestas'])) {
        return '';
    }

    // **Filtrar respuestas donde 'seccion' == 13**
    $respuesta = array_values(array_filter($pregunta['respuestas'], function ($p) {
        return isset($p['seccion']) && $p['seccion'] == 'clasificacion';
    }));

    // **Obtener la primera respuesta válida**
    $respuesta = !empty($respuesta) ? $respuesta[0] : [];

    // **Validar si existe la clave 'respuesta'**
    $texto_respuesta = isset($respuesta['respuesta']) ? htmlspecialchars($respuesta['respuesta']) : '';

    // **Retornar la respuesta final**
    return $texto_respuesta != '' ? opcionSeleccionadaDLC($texto_respuesta, $pregunta['parametros']) : '';
}
/*
Manejadar de tipo de preguntas
*/
// 1 .-  Pregunta abierta
function preguntaAbierta($formData) {

    $html = '';

    foreach ($formData as $index => $item) {
        $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '';

        $html .= '
            <table style="width: 100%;"">
                <tr>
                    <td style="border:solid 1px #000;"">
                        <div
                            class="respuestas"
                            style="
                                width: 100%;
                                min-height: 100px;
                                padding: 5px;
                                line-height: 1.55;
                                white-space: pre-wrap;
                            "
                        >' . $respuesta . '</div>
                    </td>
                </tr>
            </table>
        ';
    }

    return  $html;
}
// 2 .-  Lista selección múltiple
// 3 .-  Antigüedad en colegio
function antiguedadEnColegio($formData,$parametros){
    $respuesta = isset($formData[0]['valor']) ? htmlspecialchars($formData[0]['valor']) : '';

    $html = '<table style="width: 100%;" class="respuestas">';
    $html .= "  <tr>
                    <td style='width: 30%;'></td>
                    <td></td>
                    <td style='width: 10%;'></td>
                    <td>AÑOS</td>
                    <td style='width: 30%;'></td>
                </tr>";
    foreach($parametros AS $parametro){
        $html .= "<tr>
                    <td style='width: 30%;'></td>
                    <td class='border-bottom text-center'>".( $respuesta == $parametro['valor'] ? 'X' : '')."</td>
                    <td style='width: 10%;'></td>
                    <td>".$parametro['limiten_inferior']." - ".($parametro['limite_superior'] ? $parametro['limite_superior'] : 'O MAS' )."</td>
                    <td style='width: 30%;'></td>
                </tr>";
    }
    $html .= '</table>';
    return $html;
}
// 4 .-  Número de Hijos
function opcionesParametrosPromedioAcademico($promedio_academico, $parametrosPromedioAcademico)
{
    // Asegurarse de que $parametrosPromedioAcademico es una colección
    $parametros = collect($parametrosPromedioAcademico);

    // Buscar el parámetro que coincide con el promedio académico
    $parametro = $parametros->first(function ($item) use ($promedio_academico) {
        return $item['valor'] == $promedio_academico;
    });

    // Retornar 'limiten_inferior' o un espacio en blanco si no existe
    return isset($parametro['limiten_inferior']) ? $parametro['limiten_inferior'] : "\u{00A0}";
}

function opcionesParametrosPromedioConducta($promedio_conducta, $parametrosPromedioConducta)
{
    // Asegurarse de que $parametrosPromedioConducta es una colección
    $parametros = collect($parametrosPromedioConducta);

    // Buscar el parámetro que coincide con el promedio de conducta
    $parametro = $parametros->first(function ($item) use ($promedio_conducta) {
        return  $item['valor'] ==  $promedio_conducta;
    });

    // Retornar 'limiten_inferior' o un espacio en blanco si no existe
    return isset($parametro['limiten_inferior']) ? $parametro['limiten_inferior'] : "\u{00A0}";
}

function numeroDeHijos($formData,$parametros_promedio_academico,$parametros_promedio_conducta){

    $html = '
        <table style="width: 100%;" class="respuestas">
            <tr>
                <td style="width: 50%;">NOMBRE</td>
                <td style="width: 10%;" class="text-center">% BECA ACTUAL</td>
                <td style="width: 10%;" class="text-center">CURSAR</td>
                <td style="width: 10%;">PROMEDIO ACADEMICO</td>
                <td style="width: 10%;">PROMEDIO CONDUCTA</td>
            </tr>
    ';


    foreach ($formData as $index => $item) {
            $nombre             = isset($item['nombre']) ? htmlspecialchars($item['nombre']) : '&nbsp;';
            $respuesta          = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';
            $anio_cursar        = strlen($item['nombre']) ? $item['monto'] : '&nbsp;';
            $promedio_academico = strlen($item['nombre']) ? opcionesParametrosPromedioAcademico($item['padre_monto'], $parametros_promedio_academico) : '&nbsp;';
            $promedio_conducta  = strlen($item['nombre']) ? opcionesParametrosPromedioConducta($item['madre_monto'],   $parametros_promedio_conducta) : '&nbsp;';

            $html .= '
                <tr class="text-start">
                    <td style="width: 40%;" class="p-1"><div class="border-bottom">'.$nombre.'</div></td>
                    <td style="width: 10%;" class="text-center p-1"><div class="border-bottom">'.$respuesta.'</div></td>
                    <td style="width: 10%;" class="text-center p-1"><div class="border-bottom">'.$anio_cursar .'</div></td>
                    <td style="width: 15%;" class="text-center p-1"><div class="border-bottom">'.$promedio_academico.'</div></td>
                    <td style="width: 15%;" class="text-center p-1"><div class="border-bottom">'.$promedio_conducta.'</div></td>

                </tr>
            ';
        }

    $html .= '</table>';
    return $html;
}
// 5 .-  Orfandad
function orfandad($formData,$parametros){

    $respuesta = isset($formData[0]['valor']) ? htmlspecialchars($formData[0]['valor']) : '';

    $html = '<table style="width: 100%;" class="respuestas">';
    $html .= "  <tr>
                    <td style='width: 30%;'></td>
                    <td></td>
                    <td style='width: 10%;'></td>
                    <td style='width: 20%;'></td>
                    <td style='width: 30%;'></td>
                </tr>";
    foreach($parametros AS $parametro){
        $html .= "<tr>
                    <td style='width: 30%;'></td>
                    <td class='border-bottom text-center'>".( $respuesta == $parametro['valor'] ? 'X' : '')."</td>
                    <td style='width: 5%;'></td>
                    <td style='width: 25%;'>".$parametro['texto']."</td>
                    <td style='width: 30%;'></td>
                </tr>";
    }
    $html .= '</table>';
    return $html;
}
// 6 .-  Dependientes Económicos
function dependientesEconomicamente($formData) {

    $html = '
        <table style="width: 100%;" class="respuestas">
            <tr>
                <td  style="width: 15%;"></td>
                <td   class="pb-1  text-center">PARENTESCO</td>
                <td  class="pb-1 text-center">NOMBRE</td>
                <td  style="width: 10%;"></td>
            </tr>
    ';

    foreach ($formData as $index => $item) {
        $parentesco = isset($item['parentesco']) ? htmlspecialchars($item['parentesco']) : '&nbsp;';
        $nombre = isset($item['nombre']) ? htmlspecialchars($item['nombre']) : '&nbsp;';

        $html .= '
            <tr class="text-start">
                <td></td>
                <td >
                    <div style="
                        padding-top: 5px;
                        border-bottom: 1px solid #020202;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    ">
                        ' . $parentesco . '
                    </div>
                </td>
                <td >
                    <div style="
                        padding-top: 5px;
                        border-bottom: 1px solid #020202;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    ">
                        ' . $nombre . '
                    </div>
                </td>
                <td></td>
            </tr>
        ';
    }

    $html .= '</table>';
    return $html;
}
// 7 .-  Económicamente activo
function familiaEconomicameteActiva($formData) {


    $html = '
        <table style="width: 100%;" class="respuestas">
            <tr>
                <td style="width: 20%;"></td>
                <td style="width: 15%;">VIVE</td>
                <td style="width: 15%;">ACTIVO LABORALMENTE</td>
                <td >EMPRESA</td>
            </tr>
    ';

    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '&nbsp;';
        $vive = isset($item['vive']) && $item['vive'] ? 'Sí' : 'No';
        $activo = isset($item['activo']) && $item['activo'] ? 'Sí' : 'No';
        $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']).'&nbsp;' : '&nbsp;';

        $html .= '
            <tr>
                <td>' . $texto . '</td>
                <td style="padding: 5px;">
                    <span class="border-bottom">' . $vive . '</span>
                </td>
                <td style="padding: 5px;">
                   ' . $activo . '
                </td>
                <td style="padding: 5px;">
                    <div style="
                        border-bottom: 1px solid lightgray;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    ">
                        ' . $respuesta . '
                    </div>
                </td>
            </tr>
        ';
    }

    $html .= '</table>';
    return $html;
}
// 8 .-  Ingreso mensual
function ingresoNetoMensual($formData) {
    $html = '
        <table style="width: 100%;" class="respuestas">
            <tr class="row">
                <td style="width: 25%;" class="p-1"></td>
                <td style="width: 25%;" class="p-1">PADRE</td>
                <td style="width: 25%;" class="p-1">MADRE</td>
                <td style="width: 25%;" class="p-1">OTROS</td>
            </tr>
    ';

    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $padreMonto = isset($item['padre_monto']) ? formatNumber($item['padre_monto']) : '';
        $madreMonto = isset($item['madre_monto']) ? formatNumber($item['madre_monto']) : '';
        $monto = isset($item['monto']) ? formatNumber($item['monto']) : '';

        $html .= '
            <tr id="pes-' . htmlspecialchars($index) . '" class="row text-start">
                <td class="col-sm-3 p-1"><div>' . $texto . '</div></td>
                <td class="col-sm-3 p-1">
                    <div class="border-bottom border-secondary text-end">
                        $' . $padreMonto . '
                    </div>
                </td>
                <td class="col-sm-3 p-1">
                    <div class="border-bottom border-secondary text-end">
                        $' . $madreMonto . '
                    </div>
                </td>
                <td class="col-sm-3 p-1">
                    <div class="border-bottom border-secondary text-end">
                        $' . $monto . '
                    </div>
                </td>
            </tr>
        ';
    }

    // Subtotales por campo
    $subtotalPadre = formatNumber(sumaTotalporCampo('padre_monto', $formData));
    $subtotalMadre = formatNumber(sumaTotalporCampo('madre_monto', $formData));
    $subtotalOtros = formatNumber(sumaTotalporCampo('monto', $formData));

    $html .= '
        <tr class="row text-start">
            <td class="col-sm-3 p-1"><div><b>SUB TOTAL</b></div></td>
            <td class="col-sm-3 p-1">
                <div class="border-bottom border-secondary text-end">
                    $' . $subtotalPadre . '
                </div>
            </td>
            <td class="col-sm-3 p-1">
                <div class="border-bottom border-secondary text-end">
                    $' . $subtotalMadre . '
                </div>
            </td>
            <td class="col-sm-3 p-1">
                <div class="border-bottom border-secondary text-end">
                    $' . $subtotalOtros . '
                </div>
            </td>
        </tr>
    ';

    // Total general
    $total = formatNumber(sumaTotales($formData));

    $html .= '
        <tr class="row text-start">
            <td class="col-sm-3 p-1"><b>TOTAL</b></td>
            <td class="col-sm-3 p-1">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 10%;">$</td>
                        <td style="width: 90%;" class="text-end border-bottom border-secondary">' . $total . '</td>
                    </tr>
                </table>
            </td>
        </tr>
    ';

    $html .= '</table>';
    return $html;
}
    // 9 .-  Ahorro
function ahorro($formData){
    $datos      = isset($formData[0]) ? $formData[0] : array();
    $activo     = isset($datos['activo']) && $datos['activo'] ? 'SI' : 'NO';
    $respuesta  = isset($datos['respuesta']) ? htmlspecialchars($formData[0]['respuesta']) : '&nbsp;';
    $monto      = isset($datos['monto']) ? formatNumber($datos['monto']) : '&nbsp;';

    $html = '<table style="width: 100%;" class="respuestas">';

    if($activo == 'NO'){
    $html .= "  <tr>
                    <td style='width: 5%;' class='border-bottom text-center'>$activo</td>
                    <td style='width: 10%;'></td>
                    <td style='width: 10%;'></td>
                    <td style='width: 30%;'></td>
                    <td style='width: 20%;'></td>
                </tr>";
    }else{
    $html .= "  <tr>
                    <td style='width: 5%;'class='border-bottom text-center'>$activo</td>
                    <td style='width: 10%;'>DESCRIBE</td>
                    <td style='width: 10%;'class='border-bottom text-center'>$respuesta</td>
                    <td style='width: 30%;'>MONTO DE AHORROS O INVERCIONES</td>
                    <td style='width: 20%;'class='border-bottom text-center'>$$monto</td>
                </tr>";
    }
    $html .= '</table>';

    return $html;
}
    // 10 .-  Inversiones
function inverciones($formData,$totalParametros){
    $html = '';
    foreach($formData AS $item){
        if($item["seccion"] == "activa"){
            $activo = isset($datos['activo']) && $datos['activo'] ? 'SI' : 'NO';
            $html  .= "<p style='width: 5%;'class='respuestas border-bottom text-center'>$activo</p>";
        }
    }

    $html   .= "<br>";
    $html   .= '<table style="width: 100%;" class="respuestas">';
    $html   .= "  <tr>
                    <td style='width: 75%;'>DESCRIBIR</td>
                    <td style='width: 5%;'></td>
                    <td style='width: 20%;'>VALOR ESTIMADO</td>
                </tr>";
    foreach($formData AS $item){
        if($item["seccion"] == "inverciones"){
            $respuesta  = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';
            $monto      = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';
            $html .= "<tr>
                        <td class='border-bottom'>".$respuesta."</td>
                        <td ></td>
                        <td class='border-bottom text-end'>$".$monto."</td>
                    </tr>";
        }
    }
    $html .= '</table>';

    $total = isset($totalParametros[-1]) ? $totalParametros[-1] : 0 ;;

    // Total A + B
    $html .= "
        <table style='width: 100%;' class='respuestas'>
            <tr class='text-start'>
                <td style='width: 25%;' class='p-1'><b>TOTAL:</b></td>
                <td style='width: 25%;' class='p-1'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='width: 10%;'>$</td>
                                <td style='width: 90%;' class='text-end border-bottom border-secondary'>" . formatNumber($total) . "</td>
                            </tr>
                        </table>
                </td>
                <td style='width: 25%;' class='p-1'></td>
                <td style='width: 25%;' class='p-1'></td>
            </tr>
        </table>
    ";
    return $html;
}
    // 11 .-  Vehículos
function preguntaVeiculos($formData) {

    $html = '
        <table style="width: 100%;" class="respuestas">
            <tr>
                <td style="width: 20%;">TIPO</td>
                <td style="width: 20%;">MARCA / MODELO</td>
                <td style="width: 10%;">AÑO</td>
                <td >PROPIETARIO</td>
                <td style="width: 20%;">VALOR APROXIMADO</td>
            </tr>
    ';

    foreach ($formData as $index => $item) {
        $tipo = isset($item['tipo']) ? htmlspecialchars($item['tipo']) : '&nbsp;';
        $marcaModelo = isset($item['marca_modelo']) ? htmlspecialchars($item['marca_modelo']) : '&nbsp;';
        $anio = isset($item['anio']) ? htmlspecialchars($item['anio']) : '&nbsp;';
        $propietario = isset($item['propietario']) ? htmlspecialchars($item['propietario']) : '&nbsp;';
        $monto = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';

        $html .= "
            <tr>
                <td class='p-1'>
                    <div class='border-bottom border-secondary'>$tipo</div>
                </td>
                <td class='p-1'>
                    <div class='border-bottom border-secondary'>$marcaModelo</div>
                </td>
                <td class='p-1'>
                    <div class='border-bottom border-secondary'>$anio</div>
                </td>
                <td class='p-1'>
                    <div class='border-bottom border-secondary'>$propietario</div>
                </td>
                <td class='p-1 text-end'>
                    <div class='border-bottom border-secondary'>$monto</div>
                </td>
            </tr>
        ";
    }

    // Total
    $totalMonto = formatNumber(sumaTotalporCampo('monto', $formData));
    $html .= "
        <tr>
            <td class='col-sm-3'><b>TOTAL:</b></td>
            <td class='col-sm-3 p-1 text-start'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='width: 10%;'>$</td>
                                <td style='width: 90%;' class='text-end border-bottom border-secondary'>$totalMonto</td>
                            </tr>
                        </table>
            </td>
        </tr>
    ";

    $html .= '</table>';
    return $html;
}
    // 12 .- Propiedades Hipotecarias / casa Habitación
function casaHabitacion($formData,$paramtroClasificacion,$totalParametros) {

    $html = '<table style="width: 100%;" class="respuestas">';

    // Sección 'vivienda'

    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'vivienda') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

            $html .= "
                <tr>
                    <td style='width: 25%;'class='text-start'>
                        <div class='p-1 text-start'>$texto</div>
                    </td>
                    <td style='width: 25%;' class='text-start'>
                        <div class='p-1 border-bottom'>$respuesta</div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            ";
        }
    }

    $html .= '</table>';

    // Sección 'valor'
    $html .= '<table style="width: 100%;" class="respuestas">';
    $bloques = array();
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'renta') {
            $texto = htmlspecialchars($item['texto']);
            $monto = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';
            $bloques[] = "
                    <td style='width: 25%;' class='p-1 text-start'>$texto</td>
                    <td style='width: 25%;' class='p-1 text-start'>
                        <table style='width: 100%;' >
                            <tr>
                                <td style='width: 10%;'>$</td>
                                <td style='width: 90%;'>
                                    <div class='border-bottom text-end'>$monto</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    ";
        }
        if (isset($item['seccion']) && $item['seccion'] === 'valor') {
            $texto = htmlspecialchars($item['texto']);
            $monto = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';
            $bloques[] = "
                    <td style='width: 25%;' class='p-1 text-start'>$texto</td>
                    <td style='width: 25%;' class='p-1 text-start'>
                        <table style='width: 100%;' >
                            <tr>
                                <td style='width: 10%;'>$</td>
                                <td style='width: 90%;'>
                                    <div class='border-bottom text-end'>$monto</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    ";
        }
    }

    $no_bloques = count($bloques) -1;
    for($i = 0; $i <= $no_bloques;$i+=2){
        $pre_html = "";

        if($i==$no_bloques){
            $pre_html .= $bloques[$i]."
                <tr class='text-start'>
                    <td style='width: 25%;' class='p-1 text-start'></td>
                    <td style='width: 25%;' class='p-1 text-start'>
                </tr>
                ";
        }else{
            $pre_html .= $bloques[$i].$bloques[($i+1)];
        }

        $html .= "<tr class='text-start'>$pre_html</tr>";
    }

    $html .= '</table>';

    // Sección 'header_otros'
     $html .= '<table style="width: 100%;" class="mt-2 respuestas">';
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'header_otros') {
            $html .= "
            <tr>
                <td class='p-1 text-start'>" . htmlspecialchars($item['texto']) . "</td>
            </tr>
            ";
        }
    }
    $html .= '</table>';

    // Sección 'body_otros'
    $html .= '<table style="width: 100%;" class="respuestas">';
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'body_otros') {
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';
            $monto = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';

            $html .= "
                <tr class='text-start'>
                    <td class='col-sm-9 p-1 border-bottom'>$respuesta</td>
                    <td class='col-sm-3 p-1 text-start'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='width: 10%;'>$</td>
                                <td style='width: 90%;' class='border-bottom text-end'>$monto</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            ";
        }
    }
    $html .= '</table>';

    // Total B
    $totalValor = sumaTotalesSeccion('valor', $formData);
    $totalBodyOtros = sumaTotalesSeccion('body_otros', $formData);
    $totalB =  $totalValor + $totalBodyOtros;

    $totalAB = isset($totalParametros[$paramtroClasificacion]) ? $totalParametros[$paramtroClasificacion] : 0 ;;

    $html .= "
        <table style='width: 100%;' class='respuestas'>
            <tr class='text-start'>
                <td style='width: 25%;' class='p-1'><b>TOTAL:</b></td>
                <td style='width: 25%;' class='p-1'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='width: 10%;'>$</td>
                                <td style='width: 90%;' class='text-end border-bottom border-secondary'>" . formatNumber($totalB) . "</td>
                            </tr>
                        </table>
                </td>
                <td style='width: 25%;' class='p-1'></td>
                <td style='width: 25%;' class='p-1'></td>
            </tr>
        </table>
    ";

    // Total A + B
    $html .= "
        <table style='width: 100%;' class='respuestas'>
            <tr class='text-start'>
                <td style='width: 25%;' class='p-1'><b>GRAN TOTAL PATRIMONIO:</b></td>
                <td style='width: 25%;' class='p-1'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='width: 10%;'>$</td>
                                <td style='width: 90%;' class='text-end border-bottom border-secondary'>" . formatNumber($totalAB) . "</td>
                            </tr>
                        </table>
                </td>
                <td style='width: 25%;' class='p-1'></td>
                <td style='width: 25%;' class='p-1'></td>
            </tr>
        </table>
    ";

    return $html;
}
// 13 .- Distribución de la casa
function opcionSeleccionadaDLC($valor,$lista_parametros){

    $parametros = collect($lista_parametros);

    // Buscar el parámetro que coincide con el promedio de conducta
    $parametro = $parametros->first(function ($item) use ($valor) {
        return  $item['valor'] ==  $valor;
    });

    // Retornar 'limiten_inferior' o un espacio en blanco si no existe
    return isset($parametro['texto']) ? $parametro['texto'] : "\u{00A0}";
}
function distribucionDeLaCasa($formData,$parametros) {
    // Función para generar el HTML de cada sección
    $html = '';

    // Salto de línea

    // Sección 'clasificacion'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'clasificacion') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : ';';

            $html .= "<div class='respuestas'>".opcionSeleccionadaDLC($respuesta,$parametros)."</div><br>";
        }
    }
    // Sección 'descripcion'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'descripcion') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

            $html .= "<div style='width: 100%;' class='respuestas'>$respuesta</div>";
        }
    }

    return $html;
}
// 14 .-  Deudas
function deudasMensuales($formData) {

    $monto_total = 0;
    // Generar el encabezado de la tabla
    $html = '<table style="width: 100%;" class="respuestas">';

    $html .= '<tr>';
    $html .=    '<td class="p-1">CONCEPTO</td>';
    $html .=    '<td style="width: 33%;" class="p-1">MENSUALIDAD</td>';
    $html .=    '<td style="width: 33%;" class="p-1">SALDO</td>';
    $html .= '</tr>';

    // Iterar sobre el array de datos
    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $padreMonto = isset($item['padre_monto']) ? number_format($item['padre_monto'], 0) : '';
        $monto = isset($item['monto']) ? number_format($item['monto'], 0) : '';
        $monto_total += isset($item['monto']) ? $item['monto']  : 0;

        $html .= "
            <tr class='text-start'>
                <td class='p-1'>$texto</td>
                <td class='p-1'>
                    <table style='width: 100%;'>
                        <tr>
                            <td style='width: 10%;'>$</td>
                            <td style='width: 90%;' class='text-end border-bottom border-secondary'>$padreMonto</td>
                        </tr>
                    </table>
                </td>
                <td class='p-1'>
                    <table style='width: 100%;'>
                        <tr>
                            <td style='width: 10%;'>$</td>
                            <td style='width: 90%;' class='text-end border-bottom border-secondary'>$monto</td>
                        </tr>
                    </table>
                </td>
            </tr>
        ";
    }

    $html .= '</table>';

    $total = formatNumber($monto_total);

    $html .= "
            <table style='width: 100%;' class='respuestas'>
                <tr class='text-start'>
                    <td style='width: 25%;' class='p-1'><b>TOTAL:<b></td>
                    <td style='width: 25%;' class='p-1'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='width: 10%;'>$</td>
                                <td style='width: 90%;' class='text-end border-bottom border-secondary'>$total</td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                </tr>
            </table>
        ";


    return $html;
}
// 15 .-  Gastos familiares
function gastosFamiliaresMensuales($formData) {
    /*<table style="width: 100%; font-size:12px;">
            <tr>
                <td  style="width: 15%;"></td>
                <td   class="p-1  text-center">PARENTESCO</td>
                <td  class="p-1 text-center">NOMBRE</td>
                <td  style="width: 10%;"></td>
            </tr>*/
    // Iniciar el HTML de la tabla
    $html = '<table style="width: 100%;" class="respuestas">';

    // Iterar sobre los datos
    $bloques = array();
    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $padreMonto = isset($item['padre_monto']) ? number_format($item['padre_monto'], 0) : '';

        $bloques[] = "
                <td style='width: 25%;' class=''>$texto</td>
                <td style='width: 25%;' class='pe-2'>
                    <table style='width: 100%;'>
                        <tr>
                            <td style='width: 10%;'>$</td>
                            <td style='width: 90%;' class='text-end border-bottom border-secondary'>$padreMonto</td>
                        </tr>
                    </table>
                </td>
        ";
    }
    $no_bloques = count($bloques) -1;
    for($i = 0; $i <= $no_bloques;$i+=2){
        $pre_html = "";

        if($i==$no_bloques){
            $pre_html .= $bloques[$i]."
                <tr class='text-start'>
                    <td style='width: 25%;' class=' text-start'></td>
                    <td style='width: 25%;' class='pe-2 text-start'>
                </tr>
                ";
        }else{
            $pre_html .= $bloques[$i].$bloques[($i+1)];
        }

        $html .= "<tr class='text-start'>$pre_html</tr>";
    }

    $total = formatNumber(sumaTotales($formData));

    $html .= "
            <tr class='text-start'>
                <td style='width: 25%;' class='p-1'><b>TOTAL:<b></td>
                <td style='width: 25%;' class='p-1'>
                    <table style='width: 100%;'>
                        <tr>
                            <td style='width: 10%;'>$</td>
                            <td style='width: 90%;' class='text-end border-bottom border-secondary'>$total</td>
                        </tr>
                    </table>
                </td>
            </tr>
        ";


    $html .= '</table>';
    return $html;
}

// 16 .-  Situación Especial
// 17 .-  Cursos cicles escolares
// 18 .-  Salto de Hoja
// 19 .-  Espacio en blanco
// 20 .- Actualemte con empleo
function actualmenteConEmpleo($formData) {
    // Iniciar el HTML de la tabla
    $html = '<table style="width: 100%;" class="respuestas">';
    $html .= '
        <tr>
            <td style="width: 25%;"></td>
            <td style="width: 25%;">ACTIVO LABORALMENTE</td>
            <td >EMPRESA</td>
        </tr>';

    // Iterar sobre los datos
    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $activo = isset($item['activo']) ? ($item['activo'] ? 'Sí' : 'No') : '';
        $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '';

        $html .= "
            <tr>
                <td >$texto</td>
                <td class='p-1'>
                    <p class='border-bottom'>$activo</p>
                </td>
                <td class='p-1'>
                    <div class='border-bottom border-secondary'>$respuesta</div>
                </td>
            </tr>
        ";
    }

    $html .= '</table>';
    return $html;
}

function setDefaultValue($idPreguntaTipo, $idEstudio, $idPregunta) {
    $formData = [];

    switch ($idPreguntaTipo) {
        // 1 .- Pregunta abierta
        case 1:
            $formData = [
                [
                    'id_encuesta' => '',
                    'id_servicio_estudio' => $idEstudio,
                    'id_catalogo_encuestas_pregunta' => $idPregunta,
                    'id_item' => '',
                    'parentesco' => '',
                    'nombre' => '',
                    'texto' => '',
                    'respuesta' => '',
                    'vive' => false,
                    'activo' => false,
                    'padre_monto' => '',
                    'madre_monto' => '',
                    'monto' => '',
                    'valor' => '',
                    'tipo' => '',
                    'marca_modelo' => '',
                    'anio' => '',
                    'propietario' => ''
                ]
            ];
            break;

        case 6:
            $formData = array_fill(0, 5, [
                'id_encuesta' => '',
                'id_servicio_estudio' => $idEstudio,
                'id_catalogo_encuestas_pregunta' => $idPregunta,
                'id_item' => '',
                'parentesco' => '',
                'nombre' => '',
                'texto' => '',
                'respuesta' => '',
                'vive' => false,
                'activo' => false,
                'padre_monto' => '',
                'madre_monto' => '',
                'monto' => '',
                'valor' => '',
                'tipo' => '',
                'marca_modelo' => '',
                'anio' => '',
                'propietario' => ''
            ]);
            break;

        case 20:
        case 7:
            $formData = [
                [
                    'id_encuesta' => '',
                    'id_servicio_estudio' => $idEstudio,
                    'id_catalogo_encuestas_pregunta' => $idPregunta,
                    'id_item' => '',
                    'parentesco' => '',
                    'texto' => 'PADRE',
                    'respuesta' => '',
                    'vive' => false,
                    'activo' => false,
                    'padre_monto' => '',
                    'madre_monto' => '',
                    'monto' => '',
                    'valor' => '',
                    'tipo' => '',
                    'marca_modelo' => '',
                    'anio' => '',
                    'propietario' => ''
                ],
                [
                    'id_encuesta' => '',
                    'id_servicio_estudio' => $idEstudio,
                    'id_catalogo_encuestas_pregunta' => $idPregunta,
                    'id_item' => '',
                    'parentesco' => '',
                    'texto' => 'MADRE',
                    'respuesta' => '',
                    'vive' => false,
                    'activo' => false,
                    'padre_monto' => '',
                    'madre_monto' => '',
                    'monto' => '',
                    'valor' => '',
                    'tipo' => '',
                    'marca_modelo' => '',
                    'anio' => '',
                    'propietario' => ''
                ],
                [
                    'id_encuesta' => '',
                    'id_servicio_estudio' => $idEstudio,
                    'id_catalogo_encuestas_pregunta' => $idPregunta,
                    'id_item' => '',
                    'parentesco' => '',
                    'texto' => 'OTRO',
                    'respuesta' => '',
                    'vive' => false,
                    'activo' => false,
                    'padre_monto' => '',
                    'madre_monto' => '',
                    'monto' => '',
                    'valor' => '',
                    'tipo' => '',
                    'marca_modelo' => '',
                    'anio' => '',
                    'propietario' => ''
                ]
            ];
            break;

        case 8:
            $formData = [
                ['texto' => 'INGRESO NETO'],
                ['texto' => 'BONOS DE DESPENSA'],
                ['texto' => 'VALES DE GASOLINA'],
                ['texto' => 'COMISIONES POR VENTAS'],
                ['texto' => 'AGUINALDO'],
                ['texto' => 'BONO DE PRODUCTIVIDAD'],
                ['texto' => 'FONDO DE AHORRO'],
                ['texto' => 'UTILIDADES PRIMA'],
                ['texto' => 'VACACIONAL'],
                ['texto' => 'RENTA QUE RECIBA'],
                ['texto' => 'AYUDA QUE RECIBA']
            ];

            foreach ($formData as &$item) {
                $item = [
                    'id_encuesta' => '',
                    'id_servicio_estudio' => $idEstudio,
                    'id_catalogo_encuestas_pregunta' => $idPregunta,
                    'id_item' => '',
                    'parentesco' => '',
                    'texto' => $item['texto'],
                    'respuesta' => '',
                    'vive' => false,
                    'activo' => false,
                    'padre_monto' => '',
                    'madre_monto' => '',
                    'monto' => '',
                    'valor' => '',
                    'tipo' => '',
                    'marca_modelo' => '',
                    'anio' => '',
                    'propietario' => ''
                ];
            }
            break;

        case 11:
            $formData = array_fill(0, 5, [
                'id_encuesta' => '',
                'id_servicio_estudio' => $idEstudio,
                'id_catalogo_encuestas_pregunta' => $idPregunta,
                'id_item' => '',
                'parentesco' => '',
                'nombre' => '',
                'texto' => '',
                'respuesta' => '',
                'vive' => false,
                'activo' => false,
                'padre_monto' => '',
                'madre_monto' => '',
                'monto' => '',
                'valor' => '',
                'tipo' => '',
                'marca_modelo' => '',
                'anio' => '',
                'propietario' => ''
            ]);
            break;

        case 12:
            $formData = [
                ['texto' => 'SITUACION DE LA VIVIENDA', 'seccion' => 'vivienda'],
                ['texto' => 'MONTO DE LA RENTA O MENSUALIDAD', 'seccion' => 'valor'],
                ['texto' => 'VALOR APROXIMADO', 'seccion' => 'valor'],
                ['texto' => 'METROS DE CONTRUCCION', 'seccion' => 'construccion'],
                ['texto' => 'METRO DE TERRENO', 'seccion' => 'construccion'],
                ['texto' => 'ESPESIFICAR SI CUENTA CON TROA CASA HABITACION, TERRENO, DEPARTAMENTO, LOCALES, ETC.', 'seccion' => 'header_otros'],
                ['texto' => '', 'seccion' => 'body_otros'],
                ['texto' => '', 'seccion' => 'body_otros'],
                ['texto' => '', 'seccion' => 'body_otros']
            ];

            foreach ($formData as &$item) {
                $item = [
                    'id_encuesta' => '',
                    'id_servicio_estudio' => $idEstudio,
                    'id_catalogo_encuestas_pregunta' => $idPregunta,
                    'id_item' => '',
                    'parentesco' => '',
                    'nombre' => '',
                    'texto' => $item['texto'],
                    'seccion' => $item['seccion'],
                    'respuesta' => '',
                    'vive' => false,
                    'activo' => false,
                    'padre_monto' => '',
                    'madre_monto' => '',
                    'monto' => '',
                    'valor' => '',
                    'tipo' => '',
                    'marca_modelo' => '',
                    'anio' => '',
                    'propietario' => ''
                ];
            }
            break;


        case 13:
            $formData = [
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'PATIO', 'seccion' => 'seleccionable', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'SALA', 'seccion' => 'seleccionable', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'COMEDOR', 'seccion' => 'seleccionable', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'COCINA', 'seccion' => 'seleccionable', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'RECAMARAS', 'seccion' => 'seleccionable', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'BAÑOS', 'seccion' => 'seleccionable', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'PANTALLA DE TV', 'seccion' => 'seleccionable', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                // Añadir los demás elementos del arreglo como en el código original
                // ...
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'CLASIFICACION', 'seccion' => 'clasificacion', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'DESCRIBIR LO OBSERVADO', 'seccion' => 'descripcion', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => '']
            ];
            break;
        case 14:
            $formData = [
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'CREDITO HIPOTECARIO', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                ['id_encuesta' => '', 'id_servicio_estudio' => $idEstudio, 'id_catalogo_encuestas_pregunta' => $idPregunta, 'id_item' => '', 'parentesco' => '', 'nombre' => '', 'texto' => 'CREDITO AUTOMOTRIZ', 'respuesta' => '', 'vive' => false, 'activo' => false, 'padre_monto' => '', 'madre_monto' => '', 'monto' => '', 'valor' => '', 'tipo' => '', 'marca_modelo' => '', 'anio' => '', 'propietario' => ''],
                // Añadir los demás elementos del arreglo como en el código original
                // ...
            ];
            break;
        case 15:
            $formData = [
                // Aquí agregas las entradas del caso 15
            ];
            break;
        default:
            $formData = [];
            break;
    }

    return $formData;
}

function preguntaPorTipoPregunta(
        $idPreguntaTipo,
        $respuestas,
        $paramtroClasificacion,
        $totalParametros,
        $parametros,
        $parametros_promedio_academico,
        $parametros_promedio_conducta
    ) {
    switch($idPreguntaTipo) {
        // 1 .- Pregunta abierta
        case 1:
            return preguntaAbierta($respuestas);
            break;

        // 2 .- Lista selección múltiple
        // 3 .- Antigüedad en colegio
        case 3:
            return antiguedadEnColegio($respuestas,$parametros);
            break;
        // 4 .- Número de Hijos
        case 4:
            return numeroDeHijos($respuestas,$parametros_promedio_academico,$parametros_promedio_conducta);
            break;
        // 5 .- Orfandad
        case 5:
            return orfandad($respuestas,$parametros);
            break;
        // 6 .- Dependientes Económicos
        case 6:
            return dependientesEconomicamente($respuestas);
            break;

        // 7 .- Económicamente activo
        case 7:
            return familiaEconomicameteActiva($respuestas);
            break;

        // 8 .- Ingreso mensual
        case 8:
            return ingresoNetoMensual($respuestas);
            break;

        // 9 .- Ahorro
        case 9:
            return ahorro($respuestas);
            break;
        // 10 .- Inversiones
        case 10:
            return inverciones($respuestas,$totalParametros);
            break;
        // 11 .- Vehículos
        case 11:
            return preguntaVeiculos($respuestas);
            break;

        // 12 .- Propiedades Hipotecarias / casa Habitación
        case 12:
            return casaHabitacion($respuestas,$paramtroClasificacion,$totalParametros);
            break;

        // 13 .- Distribución de la casa
        case 13:
            return distribucionDeLaCasa($respuestas,$parametros);

        // 14 .- Deudas
        case 14:
            return deudasMensuales($respuestas);
            break;

        // 15 .- Gastos familiares
        case 15:
            return gastosFamiliaresMensuales($respuestas);
            break;

        // 16 .- Situación Especial
        // 17 .- Cursos ciclos escolares
        // 18 .- Salto de Hoja
        // 19 .- Espacio en blanco
        // 20 .- Actualmente con empleo
        case 20:
            return actualmenteConEmpleo($respuestas);
            break;

        default:
            return preguntaAbierta($respuestas);
            break;
    }
}
?>
