<?
use Illuminate\Support\Facades\Storage;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Estudio Socioeconómico</title>
    <link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">
    <style>
        @page {
            margin: 45px 35px 45px 35px;
        }
        @font-face {
            font-family: 'MiFuentePersonalizada';
            src: url('/public/fonts/TT_Rounds_Neue_Trial_Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'MiFuentePersonalizada_2';
            src: url('/public/fonts/TT_Rounds_Neue_Trial_Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'MiFuentePersonalizada', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 10.5px;
            line-height: 1.45;
            background: #ffffff;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'MiFuentePersonalizada', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            font-weight: 700;
        }
        .numeros {
            font-family: 'MiFuentePersonalizada_2', sans-serif;
            font-size: .9rem;
        }
        .page-break {
            page-break-before: always;
        }
        .no-page-break {
            page-break-inside: avoid;
        }

        /* Encabezado y Pie de página fijos */
        header {
            position: fixed;
            top: -30px;
            left: 0;
            right: 0;
            height: 25px;
            font-size: 8px;
            letter-spacing: 0.8px;
            color: #64748b;
            text-transform: uppercase;
            border-bottom: 1.5px solid #0284c7;
            padding-bottom: 4px;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 25px;
            text-align: center;
            font-size: 7.5px;
            color: #94a3b8;
            letter-spacing: 0.6px;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
        @page:first header {
            display: none;
        }

        /* PORTADA CORPORATIVA PREMIUM (AZUL MARINO / SLATE / CIAN) */
        .portada-wrapper {
            height: 100%;
            padding: 20px 10px;
        }
        .portada-top-bar {
            width: 100%;
            height: 6px;
            background-color: #0f172a;
            border-bottom: 3px solid #0284c7;
            margin-bottom: 30px;
        }
        .portada-badge-confidencial {
            display: inline-block;
            background-color: #0f172a;
            color: #38bdf8;
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 4px;
            margin-bottom: 35px;
        }
        .portada-empresa {
            font-size: 13px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #475569;
            font-weight: 600;
            margin-bottom: 14px;
        }
        .portada-titulo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 10px;
            line-height: 1.2;
        }
        .portada-subtitulo {
            font-size: 13px;
            color: #0284c7;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }
        .portada-accent-line {
            width: 70px;
            height: 3px;
            background-color: #0284c7;
            margin: 0 auto 35px auto;
            border-radius: 2px;
        }
        .portada-cliente-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0284c7;
            padding: 12px 20px;
            max-width: 480px;
            margin: 0 auto 30px auto;
            text-align: left;
        }
        .portada-cliente-label {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 3px;
        }
        .portada-cliente-nombre {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .portada-candidato-card {
            background-color: #ffffff;
            border: 1.5px solid #0f172a;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 22px 28px;
            max-width: 480px;
            margin: 0 auto 50px auto;
            text-align: center;
        }
        .portada-candidato-tag {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #0284c7;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .portada-candidato-val {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.8px;
            color: #0f172a;
            text-transform: uppercase;
        }

        /* SECCIONES Y ENCABEZADOS DE PÁGINA */
        .seccion-header-strip {
            background-color: #0f172a;
            color: #ffffff;
            padding: 6px 12px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 12px;
            border-left: 4px solid #0284c7;
        }
        .seccion-sub-tag {
            font-size: 8.5px;
            letter-spacing: 1px;
            font-weight: 700;
            text-transform: uppercase;
            color: #475569;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }

        /* DICTAMEN BOX CON ESTILO EJECUTIVO */
        .dictamen-banner {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid #16a34a;
            color: #166534;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        /* OBSERVACIONES */
        .observaciones-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 12px 14px;
            font-size: 10px;
            line-height: 1.6;
            color: #334155;
            white-space: pre-wrap;
            min-height: 85px;
            text-align: justify;
            margin-bottom: 14px;
        }

        /* TABLA DE PARÁMETROS CORPORATIVA */
        .tabla-corporate {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 14px;
        }
        .tabla-corporate th {
            background-color: #0f172a;
            color: #f8fafc;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.8px;
            font-weight: 700;
            padding: 7px 10px;
            border: none;
        }
        .tabla-corporate td {
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
        }
        .tabla-corporate tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .tabla-corporate .fila-total td {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            border-top: 1.5px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            font-size: 10.5px;
        }

        /* KPI WIDGETS CORPORATIVOS */
        .kpi-table {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 16px;
        }
        .kpi-box {
            width: 48%;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-top: 3px solid #0284c7;
            padding: 10px 14px;
            text-align: center;
        }
        .kpi-lbl {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 4px;
        }
        .kpi-num {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        /* BLOQUES DE PREGUNTAS */
        .pregunta-item {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .pregunta-header {
            background-color: #f1f5f9;
            border-left: 3px solid #0284c7;
            padding: 5px 10px;
            margin-bottom: 6px;
        }
        .pregunta-titulo {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: #0f172a;
            margin: 0;
        }
        .respuestas {
            font-size: 9.5px;
            color: #1e293b;
        }

        /* TÍTULOS DE SECCIONES (ANEXOS / ANÁLISIS) */
        .seccion-titulo-pagina {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-align: center;
            padding: 8px 12px;
            border-bottom: 3px solid #0284c7;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <header>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left; width: 50%;">
                    <strong>{{$encuesta->estudio->cliente->nombre}}</strong> &bull; {{$encuesta->proyecto->nombre}}
                </td>
                <td style="text-align: right; width: 50%;">
                    EXPEDIENTE: <strong>{{$encuesta->estudio->candidato}}</strong>
                </td>
            </tr>
        </table>
    </header>

    <!-- PÁGINA 1: PORTADA CORPORATIVA PREMIUM -->
    <div class="portada-wrapper">
        <div class="portada-top-bar"></div>
        <div style="text-align: center;">
            <div class="portada-badge-confidencial">Documento Confidencial</div>
            <div class="portada-empresa">Sinergia en Estudios Socioeconómicos</div>
            <h1 class="portada-titulo">Estudio Socioeconómico<br>para Becas</h1>
            <div class="portada-subtitulo">{{$encuesta->proyecto->nombre}}</div>
            <div class="portada-accent-line"></div>
        </div>

        <div class="portada-cliente-box">
            <div class="portada-cliente-label">Institución Solicitante</div>
            <div class="portada-cliente-nombre">{{$encuesta->estudio->cliente->nombre}}</div>
        </div>

        <div class="portada-candidato-card">
            <div class="portada-candidato-tag">Familia / Candidato Evaluado</div>
            <div class="portada-candidato-val">{{$encuesta->estudio->candidato}}</div>
        </div>

        <div class="footer">
            ESTE DOCUMENTO CONTIENE INFORMACIÓN CONFIDENCIAL Y PRIVILEGIADA &bull; SINERGIA ESTUDIOS SOCIOECONÓMICOS
        </div>
        <div class="page-break"></div>
    </div>

    <!-- PÁGINA 2: EVALUACIÓN, DICTAMEN Y PONDERACIÓN -->
    <div style="padding: 5px 10px;">

        <div class="seccion-header-strip">Dictamen Ejecutivo del Entrevistador</div>
        <div class="dictamen-banner">
            {{getClasificacionEncuesta($encuesta->preguntas)}}
        </div>

        @if($encuesta->estudio->aniadir_observaciones === 1)
        <div class="seccion-sub-tag">Resumen de la Entrevista</div>
        <div class="observaciones-card">{{$encuesta->estudio->observaciones}}</div>
        @endif

        <div class="seccion-header-strip" style="margin-top: 10px;">Detalle de Evaluación y Ponderación</div>
        <table style="width: 100%; margin-bottom: 10px; font-size: 10px;">
            <tr>
                <td style="width: 50%; padding: 3px 0;">
                    <span style="color: #64748b; font-size: 8.5px; text-transform: uppercase; font-weight: 600;">Institución:</span><br>
                    <strong>{{$encuesta->estudio->cliente->nombre}}</strong>
                </td>
                <td style="width: 50%; padding: 3px 0;">
                    <span style="color: #64748b; font-size: 8.5px; text-transform: uppercase; font-weight: 600;">Familia / Candidato:</span><br>
                    <strong>{{$encuesta->estudio->candidato}}</strong>
                </td>
            </tr>
            @if (isset($encuesta["hijo"]))
            <tr>
                <td colspan="2" style="padding: 3px 0;">
                    <span style="color: #64748b; font-size: 8.5px; text-transform: uppercase; font-weight: 600;">Alumno Aspirante:</span><br>
                    <strong>{{$encuesta->hijo->nombre}}</strong>
                </td>
            </tr>
            @endif
        </table>

        <!-- Tabla de Ponderación de Parámetros -->
        <table class="tabla-corporate">
            <thead>
                <tr>
                    <th style="width: 75%; text-align: left;">Parámetro Evaluado</th>
                    <th style="width: 25%; text-align: right;">Puntaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($encuesta->parametros as $parametro)
                <tr>
                    <td>{{$parametro->nombre}}</td>
                    <td style="text-align: right; font-weight: 700;">
                        {{$parametro->puntos->valor ?? ($parametro->puntos["valor"] ?? '0')}}
                    </td>
                </tr>
                @endforeach
                <tr class="fila-total">
                    <td>TOTAL PUNTOS</td>
                    <td style="text-align: right; font-size: 11.5px;">{{totalPuntosParametros($encuesta)}}</td>
                </tr>
            </tbody>
        </table>

        <!-- KPIs de Porcentajes -->
        <table class="kpi-table">
            <tr>
                <td class="kpi-box">
                    <div class="kpi-lbl">Porcentaje Sugerido</div>
                    <div class="kpi-num">{{porcentajeSugerido($encuesta)}}</div>
                </td>
                <td style="width: 4%;"></td>
                <td class="kpi-box" style="border-top-color: #0f172a;">
                    <div class="kpi-lbl">Porcentaje Otorgado</div>
                    <div class="kpi-num">
                        {{ (isset($encuesta["hijo"])) ? $encuesta->hijo->porcentaje_otorgado.'%' : ($encuesta->estudio->porcentaje_otorgado ? $encuesta->estudio->porcentaje_otorgado.'%':'N/A')}}
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <!-- PÁGINA 3 EN ADELANTE: DESGLOSE DE PREGUNTAS -->
    @if ($encuesta->preguntas)
    <div class="page-break"></div>
        @foreach ($encuesta->preguntas as $pregunta)
            <div class="pregunta-item">
                <div class="pregunta-header">
                    <div class="pregunta-titulo">{{$pregunta->numero_pregunta}}.- {{$pregunta->pregunta}}</div>
                </div>
                <div style="padding-left: 6px; padding-right: 6px;">
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
            </div>
        @endforeach
    @endif

    <!-- ANEXO FOTOGRÁFICO -->
    @if ($encuesta->imagenes)
    <div>
        @foreach ($encuesta->imagenes as $index => $imagenes)
            @if (count($imagenes->documentos) AND ($imagenes->nombre == "CASA HABITACION" || $imagenes->nombre == "AUTOMOVILES"))
                <div class="page-break"></div>
                <div class="seccion-titulo-pagina">ANEXO FOTOGRÁFICO: {{$imagenes->nombre}}</div>

                <div style="text-align: center; margin-top: 15px;">
                    @foreach (mostrarImagenes($imagenes->documentos) AS $img)
                        <div style="display: inline-block; margin: 8px; border: 1px solid #cbd5e1; padding: 6px; background-color: #f8fafc; border-radius: 4px;">
                            {!! $img !!}
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- ANÁLISIS DE DATOS Y GRÁFICAS -->
    <div class="page-break"></div>
    <div class="seccion-titulo-pagina">ANÁLISIS DE DATOS Y DISTRIBUCIÓN</div>

    <div style="text-align: center; margin-top: 15px;">
    @foreach ( $encuesta["lista_parametros"] as $parametro )
        @php
            $resultado = filterIDresultados($encuesta['parametros'], $parametro["id"])
        @endphp
        <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #e2e8f0; display: inline-block; background-color: #ffffff; border-radius: 4px;">
            {!! generarGraficaBar($parametro,$resultado) !!}
        </div>
        <br>
    @endforeach
    <br>
    <div style="margin-top: 10px; padding: 10px; border: 1px solid #e2e8f0; display: inline-block; background-color: #ffffff; border-radius: 4px;">
        {!! generarGraficaDistribucionDelGasto($encuesta["distribucion_del_gasto"]) !!}
    </div>
    </div>
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
// Graficas de reporte
function labels($dataSelected) {
    if (isset($dataSelected['items']) && is_array($dataSelected['items'])) {
        return array_map(function ($item) {
            $inferior = $item['limiten_inferior'] < 1 ? 'O MENOS' : formatNumber($item['limiten_inferior']);
            $superior = $item['limite_superior'] === '0' ? 'O MAS' : formatNumber($item['limite_superior']);
            return "$inferior - $superior";
        }, $dataSelected['items']);
    }
    return [];
}

function dataChart($dataSelected) {
    return [
        'labels' => labels($dataSelected),
        'datasets' => [
            [
                'label' => $dataSelected['nombre'] ?? '',
                'data' => labelsData($dataSelected),
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
            ]
        ]
    ];
}

function labelsData($dataSelected) {
    if (isset($dataSelected['items']) && is_array($dataSelected['items'])) {
        return array_map(function ($item) {
            return $item['total_estudios'];
        }, $dataSelected['items']);
    }
    return [];
}

function generarGrafica(){
    $type = 'bar';
    $titulo = 'NIVEL DE LIQUIDEZ';
    $data = [
        'labels' => ['O MENOS - 40,000','40,001 - 45,000','45,001 - 50,000','50,001 - 55,000','55,001 - 60,000','60,001 - 65,000','65,001 - 70,000','70,001 - 75,000','75,001 - O MAS'],
        'datasets' => [[
            'label' => $titulo,
            'data' => [48,1,1,0,1,0,0,0,0],
            'backgroundColor' => [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                ],
            'borderColor' => [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                ],
        ]],
    ];
    // Generar la URL de la gráfica
    $chartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode([
        'type' => $type,
        'data' => $data,
    ]));
    // Obtener la imagen de la URL
    $imageData = file_get_contents($chartUrl);
    $imageBase64 = 'data:image/png;base64,' . base64_encode($imageData);

    echo "<img src='$imageBase64' alt='Chart' style='width: 400px; height: auto;' />";
}
function filterIDresultados($resultados, $id) {
    foreach ($resultados as $resultado) {
        if ($resultado['id'] == $id) {
            return $resultado;
        }
    }
    return null;
}
function generarGraficaBar($dataSet, $resultado = null) {
    $type = 'bar';
    $titulo = $dataSet['nombre'];

    // Preparar las etiquetas y datos
    $labels = [];
    $valores = [];
    $backgroundColor = [];
    $borderColor = [];

    // Valor a resaltar (si existe)
    $valorResaltar = null;
    $limiteInferiorResaltar = null;
    $limiteSuperiorResaltar = null;

    // Si tenemos un resultado para resaltar
    if ($resultado !== null && isset($resultado['puntos'])) {
        $valorResaltar = $resultado['puntos']['valor'];
        $limiteInferiorResaltar = $resultado['puntos']['limiten_inferior'];
        $limiteSuperiorResaltar = $resultado['puntos']['limite_superior'];
    }

    // Generar colores base
    $baseColor = isset($dataSet['color']) ? $dataSet['color'] : '#0080ff';
    $baseColor = str_replace('#', '', $baseColor);

    // Convertir el color hexadecimal a RGB
    $r = hexdec(substr($baseColor, 0, 2));
    $g = hexdec(substr($baseColor, 2, 2));
    $b = hexdec(substr($baseColor, 4, 2));

    // Color de resaltado
    $highlightColor = "rgba(255, 0, 0, 0.6)"; // Rojo semi-transparente
    $highlightBorder = "rgba(255, 0, 0, 1)";  // Rojo sólido

    foreach($dataSet['items'] as $item) {
        // Crear etiqueta según los límites
        if ($item['limiten_inferior'] == 0 && $item['limite_superior'] > 0) {
            $label = "0 - " . number_format($item['limite_superior'], 0, '.', ',');
        } elseif ($item['limite_superior'] == 0 && $item['limiten_inferior'] > 0) {
            $label = number_format($item['limiten_inferior'], 0, '.', ',') . " - O MÁS";
        } else {
            $label = number_format($item['limiten_inferior'], 0, '.', ',') . " - " . number_format($item['limite_superior'], 0, '.', ',');
        }

        $labels[] = $label;
        $valores[] = intval($item['valor']);

        // Determinar si este elemento coincide con el valor a resaltar
        $debeResaltar = false;
        if ($valorResaltar !== null) {
            // Comparamos por valor primero
            if ($item['valor'] === $valorResaltar) {
                // Luego validamos por rangos para confirmar
                if (($item['limiten_inferior'] == $limiteInferiorResaltar && $item['limite_superior'] == $limiteSuperiorResaltar) ||
                    // Otra forma de validar: verificar si el valor de sumatoria está dentro del rango
                    ($resultado['puntos']['sumatoria'] >= $item['limiten_inferior'] &&
                     ($item['limite_superior'] == 0 || $resultado['puntos']['sumatoria'] <= $item['limite_superior']))) {
                    $debeResaltar = true;
                }
            }
        }

        if ($debeResaltar) {
            $backgroundColor[] = $highlightColor;
            $borderColor[] = $highlightBorder;
        } else {
            $backgroundColor[] = "rgba($r, $g, $b, " . (0.8 - (count($backgroundColor) * 0.05)) . ")";
            $borderColor[] = "rgba($r, $g, $b, 1)";
        }
    }

    // Agregar un indicador del valor real en el título si está disponible
    $tituloCompleto = strtoupper($titulo);
    if ($resultado !== null && isset($resultado['puntos']['sumatoria'])) {
        $tituloCompleto .= " - VALOR: " . number_format($resultado['puntos']['sumatoria'], 0, '.', ',');
    }

    $data = [
        'labels' => $labels,
        'datasets' => [[
            'label' => $titulo,
            'data' => $valores,
            'backgroundColor' => $backgroundColor,
            'borderColor' => $borderColor,
            'borderWidth' => 1
        ]],
    ];

    // Generar la URL de la gráfica
    $chartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode([
        'type' => $type,
        'data' => $data,
        'options' => [
            'title' => [
                'display' => true,
                'text' => $tituloCompleto
            ],
            'scales' => [
                'yAxes' => [[
                    'ticks' => [
                        'beginAtZero' => true
                    ]
                ]]
            ],
            'plugins' => [
                'datalabels' => [
                    'anchor' => 'end',
                    'align' => 'top',
                    'formatter' => '(value) => value + " pts"',
                    'font' => [
                        'weight' => 'bold'
                    ]
                ]
            ]
        ]
    ]));

    // Obtener la imagen de la URL
    $imageData = file_get_contents($chartUrl);
    $imageBase64 = 'data:image/png;base64,' . base64_encode($imageData);

    echo "<img src='$imageBase64' alt='Chart' style='width: 420px; height: auto;' />";
}
function generarGraficaDistribucionDelGasto($dataSet){
    $dataLabels = array_map(function($item) { return strtoupper($item['categoria']);}, $dataSet);
    $dataMontos = array_map(function($item) { return intval($item['total']);}, $dataSet);
    //number_format(intval($item['total']), 0, '.', ',')

    $type = 'bar';
    $titulo = 'DISTRIBUCION DEL GASTO';
    $data = [
        'labels' => $dataLabels,
        'datasets' => [
            [
                'label' => '',
                'data' => $dataMontos,
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                ],
                'borderColor' => [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                ],
            ],
        ],
    ];
    // Generar la URL de la gráfica
    $chartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode([
        'type' => $type,
        'data' => $data,
        'options' => [
            'title' => [
                'display' => true,
                'text' => $titulo
            ],
            'scales' => [
                'yAxes' => [[
                    'ticks' => [
                        'beginAtZero' => true
                    ]
                ]]
            ],
            'plugins' => [
                'datalabels' => [
                    'anchor' => 'end',
                    'align' => 'top',
                    'formatter' => '(value) => value + " pts"',
                    'font' => [
                        'weight' => 'bold'
                    ]
                ]
            ]
        ]
    ]));
    // Obtener la imagen de la URL
    $imageData = file_get_contents($chartUrl);
    $imageBase64 = 'data:image/png;base64,' . base64_encode($imageData);

    echo "<img src='$imageBase64' alt='Chart' style='width: 420px; height: auto;' />";
}
// Función para determinar el tipo de pregunta y generar el HTML correspondiente
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
