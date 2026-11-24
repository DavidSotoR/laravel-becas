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
            font-family: 'MiFuentePersonalizada', Georgia, 'Times New Roman', serif;
            color: #262626;
            font-size: 10.5px;
            line-height: 1.45;
            background: #ffffff;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'MiFuentePersonalizada', Georgia, 'Times New Roman', serif;
            color: #4a0404;
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
            color: #78716c;
            text-transform: uppercase;
            border-bottom: 1.5px solid #b45309;
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
            color: #a8a29e;
            letter-spacing: 0.6px;
            border-top: 1px solid #e7e5e4;
            padding-top: 5px;
        }
        @page:first header {
            display: none;
        }

        /* PORTADA ACADÉMICA / INSTITUCIONAL (BURGUNDY / DORADO OCRE) */
        .portada-wrapper {
            height: 100%;
            padding: 25px 15px;
            border: 1px solid #d6d3d1;
            margin: 10px;
            background-color: #fafaf9;
            text-align: center;
        }
        .portada-inner-border {
            border: 2px solid #780016;
            padding: 30px 20px;
            height: 94%;
        }
        .portada-badge-institucional {
            display: inline-block;
            background-color: #780016;
            color: #fef3c7;
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: 5px 16px;
            border-radius: 2px;
            margin-bottom: 30px;
        }
        .portada-empresa {
            font-size: 12.5px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #78716c;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .portada-titulo {
            font-size: 23px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #780016;
            margin-bottom: 8px;
            line-height: 1.25;
        }
        .portada-subtitulo {
            font-size: 13px;
            color: #b45309;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        .portada-ornament {
            width: 90px;
            height: 2px;
            background-color: #b45309;
            margin: 0 auto 30px auto;
        }
        .portada-cliente-box {
            background-color: #ffffff;
            border: 1px solid #e7e5e4;
            border-top: 2px solid #b45309;
            padding: 12px 18px;
            max-width: 440px;
            margin: 0 auto 25px auto;
        }
        .portada-cliente-label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #78716c;
            font-weight: 600;
            margin-bottom: 3px;
        }
        .portada-cliente-nombre {
            font-size: 13.5px;
            font-weight: 700;
            color: #780016;
            text-transform: uppercase;
        }
        .portada-candidato-card {
            background-color: #ffffff;
            border: 1px solid #d6d3d1;
            border-left: 4px solid #780016;
            padding: 16px 20px;
            max-width: 440px;
            margin: 0 auto 40px auto;
        }
        .portada-candidato-tag {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #b45309;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .portada-candidato-val {
            font-size: 17px;
            font-weight: 700;
            color: #1c1917;
            text-transform: uppercase;
        }

        /* SECCIONES Y ENCABEZADOS DE PÁGINA */
        .seccion-header-strip {
            background-color: #780016;
            color: #ffffff;
            padding: 5px 12px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-left: 4px solid #b45309;
        }
        .seccion-sub-tag {
            font-size: 8.5px;
            letter-spacing: 1px;
            font-weight: 700;
            text-transform: uppercase;
            color: #780016;
            border-bottom: 1px solid #d6d3d1;
            padding-bottom: 3px;
            margin-bottom: 8px;
        }

        /* DICTAMEN BOX CON ESTILO ACADÉMICO */
        .dictamen-banner {
            background-color: #fefce8;
            border: 1px solid #fef08a;
            border-left: 4px solid #ca8a04;
            color: #854d0e;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        /* OBSERVACIONES */
        .observaciones-card {
            background-color: #fafaf9;
            border: 1px solid #e7e5e4;
            border-radius: 3px;
            padding: 12px 14px;
            font-size: 10px;
            line-height: 1.6;
            color: #292524;
            white-space: pre-wrap;
            min-height: 85px;
            text-align: justify;
            margin-bottom: 14px;
        }

        /* TABLA DE PARÁMETROS ACADÉMICA */
        .tabla-academica {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 14px;
        }
        .tabla-academica th {
            background-color: #780016;
            color: #fef3c7;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.8px;
            font-weight: 700;
            padding: 6px 10px;
            border: none;
        }
        .tabla-academica td {
            padding: 6px 10px;
            border-bottom: 1px solid #e7e5e4;
            color: #292524;
        }
        .tabla-academica tr:nth-child(even) td {
            background-color: #fafaf9;
        }
        .tabla-academica .fila-total td {
            background-color: #f5f5f4;
            color: #780016;
            font-weight: 800;
            border-top: 1.5px solid #780016;
            border-bottom: 2px solid #780016;
            font-size: 10.5px;
        }

        /* KPI WIDGETS ACADÉMICOS */
        .kpi-table {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 16px;
        }
        .kpi-box {
            width: 48%;
            background-color: #fafaf9;
            border: 1px solid #d6d3d1;
            border-top: 3px solid #b45309;
            padding: 10px 14px;
            text-align: center;
        }
        .kpi-lbl {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #78716c;
            margin-bottom: 4px;
        }
        .kpi-num {
            font-size: 20px;
            font-weight: 800;
            color: #780016;
            letter-spacing: 0.5px;
        }

        /* BLOQUES DE PREGUNTAS */
        .pregunta-item {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .pregunta-header {
            background-color: #f5f5f4;
            border-left: 3px solid #780016;
            padding: 5px 10px;
            margin-bottom: 6px;
        }
        .pregunta-titulo {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: #780016;
            margin: 0;
        }
        .respuestas {
            font-size: 9.5px;
            color: #292524;
        }

        /* TÍTULOS DE SECCIONES (ANEXOS / ANÁLISIS) */
        .seccion-titulo-pagina {
            background-color: #780016;
            color: #fef3c7;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-align: center;
            padding: 8px 12px;
            border-bottom: 3px solid #b45309;
            margin-bottom: 20px;
        }

        /* ============================================================
           TEMA MODERNO — Formulario de solicitud de beca
           Azul marino #13375E / celeste #DCE6F3 (ver imagen de referencia)
           ============================================================ */
        body {
            font-family: 'MiFuentePersonalizada', 'DejaVu Sans', sans-serif;
            color: #22344e;
            background: #ffffff;
        }
        h1, h2, h3, h4, h5, h6 { color: #13375e; }
        header {
            color: #5d728e;
            border-bottom: 2px solid #13375e;
            letter-spacing: 0.6px;
        }
        .footer {
            color: #8498b3;
            border-top: 1px solid #dce6f3;
        }

        /* Portada */
        .portada-wrapper {
            border: 1px solid #c3d2e8;
            background-color: #eef3fa;
            border-radius: 12px;
            /* Menor a 100% para que no se desborde a la hoja 2 */
            height: 88%;
        }
        .portada-inner-border {
            border: 1.5px solid #13375e;
            background-color: #ffffff;
            border-radius: 10px;
        }
        .portada-badge-institucional {
            background-color: #13375e;
            color: #ffffff;
            border-radius: 10px;
            letter-spacing: 2px;
        }
        .portada-empresa { color: #5d728e; }
        .portada-titulo { color: #13375e; }
        .portada-subtitulo { color: #3d6390; }
        .portada-ornament { background-color: #13375e; }
        .portada-cliente-box {
            background-color: #eef3fa;
            border: 1px solid #c3d2e8;
            border-top: 1px solid #c3d2e8;
            border-radius: 8px;
        }
        .portada-cliente-label { color: #5d728e; }
        .portada-cliente-nombre { color: #13375e; }
        .portada-candidato-card {
            background-color: #ffffff;
            border: 1px solid #c3d2e8;
            border-left: 1px solid #c3d2e8;
            border-radius: 8px;
        }
        .portada-candidato-tag { color: #3d6390; }
        .portada-candidato-val { color: #13375e; }

        /* Encabezados de sección: barra azul marino con número en círculo */
        .seccion-header-strip {
            background-color: #13375e;
            color: #ffffff;
            border-left: none;
            border-radius: 6px;
            padding: 7px 12px;
            font-size: 10.5px;
            letter-spacing: 0.4px;
            text-transform: none;
            margin-bottom: 10px;
        }
        .seccion-num {
            display: inline-block;
            width: 18px;
            height: 18px;
            line-height: 18px;
            text-align: center;
            background-color: #ffffff;
            color: #13375e;
            border-radius: 9px;
            font-size: 10px;
            font-weight: 800;
            margin-right: 7px;
        }
        .seccion-sub-tag {
            color: #13375e;
            border-bottom: 1px solid #dce6f3;
            text-transform: none;
            letter-spacing: 0.3px;
            font-size: 9.5px;
        }
        .seccion-titulo-pagina {
            background-color: #13375e;
            color: #ffffff;
            border-bottom: none;
            border-radius: 6px;
            letter-spacing: 0.6px;
        }

        /* Dictamen y observaciones */
        .dictamen-banner {
            background-color: #dce6f3;
            border: 1px solid #bccfe6;
            border-left: 1px solid #bccfe6;
            border-radius: 6px;
            color: #13375e;
            text-align: center;
        }
        .observaciones-card {
            background-color: #f7fafd;
            border: 1px solid #c3d2e8;
            border-radius: 6px;
            color: #22344e;
        }

        /* Tabla de ponderación */
        .tabla-academica th {
            background-color: #dce6f3;
            color: #13375e;
            letter-spacing: 0.4px;
            text-transform: none;
            font-size: 9.5px;
        }
        .tabla-academica td {
            border-bottom: 1px solid #dce6f3;
            color: #22344e;
        }
        .tabla-academica tr:nth-child(even) td { background-color: #f2f6fb; }
        .tabla-academica .fila-total td {
            background-color: #dce6f3;
            color: #13375e;
            border-top: 1px solid #bccfe6;
            border-bottom: 1px solid #bccfe6;
        }

        /* KPIs */
        .kpi-box {
            background-color: #eef3fa;
            border: 1px solid #c3d2e8;
            border-top: 1px solid #c3d2e8;
            border-radius: 8px;
        }
        .kpi-lbl { color: #5d728e; }
        .kpi-num { color: #13375e; }

        /* Tarjeta de cada pregunta (como las secciones 1-3 de la imagen) */
        .pregunta-item {
            border: 1px solid #c3d2e8;
            border-radius: 8px;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .pregunta-header {
            background-color: #13375e;
            border-left: none;
            border-radius: 7px 7px 0 0;
            padding: 7px 12px;
            margin-bottom: 0;
        }
        .pregunta-titulo {
            color: #ffffff;
            text-transform: none;
            letter-spacing: 0.3px;
            font-size: 10.5px;
        }
        .pregunta-body { padding: 10px 12px 12px 12px; }
        .respuestas { color: #22344e; }

        /* Hojas individuales: cada bloque inicia en su propia página.
           Solo salto antes, para no duplicar el salto del anexo/gráficas. */
        .hoja-individual {
            page-break-before: always;
        }
        .hoja-individual .pregunta-item { page-break-inside: avoid; }
        .hoja-dictamen .observaciones-card { min-height: 60px; }

        /* Campos tipo "caja" de la imagen de referencia */
        .campo {
            border: 1px solid #bccfe6;
            border-radius: 5px;
            padding: 4px 8px;
            background-color: #ffffff;
            min-height: 13px;
        }
        .campo-dinero {
            border: 1px solid #bccfe6;
            border-radius: 5px;
            padding: 4px 8px;
            background-color: #ffffff;
            text-align: right;
            min-height: 13px;
        }
        .campo-etiqueta {
            font-size: 8.5px;
            color: #5d728e;
            padding-bottom: 2px;
        }
        .subtitulo-columna {
            background-color: #dce6f3;
            color: #13375e;
            font-weight: 700;
            font-size: 9.5px;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .tabla-moderna { width: 100%; border-collapse: collapse; }
        .tabla-moderna td { padding: 2px 3px; vertical-align: top; }
        .encabezado-tabla td, .encabezado-tabla th {
            background-color: #dce6f3;
            color: #13375e;
            font-weight: 700;
            font-size: 9px;
            padding: 5px 8px;
            border-radius: 5px;
            text-align: center;
        }
        .encabezado-tabla .izquierda { text-align: left; }
        .fila-subtotal td {
            background-color: #dce6f3;
            color: #13375e;
            font-weight: 700;
            border-radius: 5px;
            padding: 5px 8px;
        }
        .fila-subtotal .campo-dinero, .fila-subtotal .campo {
            background-color: #dce6f3;
            border: 1px solid #dce6f3;
            font-weight: 700;
            color: #13375e;
        }
        .fila-total-moderno td {
            background-color: #dce6f3;
            color: #13375e;
            font-weight: 700;
            border-radius: 5px;
            padding: 5px 8px;
        }
        .total-destacado {
            color: #13375e;
            font-size: 13px;
            font-weight: 800;
            text-align: right;
        }
        .marca-opcion {
            display: inline-block;
            width: 14px;
            height: 14px;
            line-height: 14px;
            text-align: center;
            border: 1px solid #bccfe6;
            border-radius: 3px;
            color: #13375e;
            font-weight: 800;
            font-size: 8px;
            background-color: #ffffff;
        }
        .grafica-card, .foto-card {
            border: 1px solid #c3d2e8;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 10px;
        }

        /* Compatibilidad: cajas modernas para el marcado heredado de totales */
        .respuestas .border-bottom {
            border: 1px solid #bccfe6;
            border-radius: 5px;
            padding: 4px 8px;
            background-color: #ffffff;
        }
        .respuestas .border-secondary { border-color: #bccfe6; }
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
                    ASPIRANTE: <strong>{{$encuesta->estudio->candidato}}</strong>
                </td>
            </tr>
        </table>
    </header>

    <!-- PÁGINA 1: PORTADA ACADÉMICA / INSTITUCIONAL -->
    <div class="portada-wrapper">
        <div class="portada-inner-border">
            <div class="portada-badge-institucional">Estudio Confidencial</div>
            <div class="portada-empresa">Sinergia en Estudios Socioeconómicos</div>
            <h1 class="portada-titulo">Estudio Socioeconómico<br>de Becas</h1>
            <div class="portada-subtitulo">{{$encuesta->proyecto->nombre}}</div>
            <div class="portada-ornament"></div>

            <div class="portada-cliente-box">
                <div class="portada-cliente-label">Institución Educativa</div>
                <div class="portada-cliente-nombre">{{$encuesta->estudio->cliente->nombre}}</div>
            </div>

            <div class="portada-candidato-card">
                <div class="portada-candidato-tag">Expediente Familiar Evaluado</div>
                <div class="portada-candidato-val">{{$encuesta->estudio->candidato}}</div>
            </div>
        </div>

        <div class="footer">
            ESTUDIO EMITIDO CON FINES DE ASIGNACIÓN DE BECAS &bull; DOCUMENTO PRIVADO Y CONFIDENCIAL
        </div>
    </div>

    <!-- PÁGINA 2 (hoja individual): EVALUACIÓN, DICTAMEN Y PONDERACIÓN -->
    <div class="hoja-individual hoja-dictamen" style="padding: 5px 10px;">

        <div class="seccion-header-strip"><span class="seccion-num">1</span>Dictamen oficial del entrevistador</div>
        <div class="dictamen-banner">
            {{getClasificacionEncuesta($encuesta->preguntas)}}
        </div>

        @if($encuesta->estudio->aniadir_observaciones === 1)
        <div class="seccion-sub-tag">Resumen de la Entrevista</div>
        <div class="observaciones-card">{{$encuesta->estudio->observaciones}}</div>
        @endif

        <div class="seccion-header-strip" style="margin-top: 10px;"><span class="seccion-num">2</span>Detalle de evaluación y ponderación</div>
        <table style="width: 100%; margin-bottom: 10px; font-size: 10px;">
            <tr>
                <td style="width: 50%; padding: 3px 0;">
                    <span style="color: #5d728e; font-size: 8.5px; text-transform: uppercase; font-weight: 600;">Institución:</span><br>
                    <strong>{{$encuesta->estudio->cliente->nombre}}</strong>
                </td>
                <td style="width: 50%; padding: 3px 0;">
                    <span style="color: #5d728e; font-size: 8.5px; text-transform: uppercase; font-weight: 600;">Familia / Candidato:</span><br>
                    <strong>{{$encuesta->estudio->candidato}}</strong>
                </td>
            </tr>
            @if (isset($encuesta["hijo"]))
            <tr>
                <td colspan="2" style="padding: 3px 0;">
                    <span style="color: #5d728e; font-size: 8.5px; text-transform: uppercase; font-weight: 600;">Alumno Aspirante:</span><br>
                    <strong>{{$encuesta->hijo->nombre}}</strong>
                </td>
            </tr>
            @endif
        </table>

        <!-- Tabla de Ponderación de Parámetros -->
        <table class="tabla-academica">
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
                <td class="kpi-box">
                    <div class="kpi-lbl">Porcentaje Otorgado</div>
                    <div class="kpi-num">
                        {{ (isset($encuesta["hijo"])) ? $encuesta->hijo->porcentaje_otorgado.'%' : ($encuesta->estudio->porcentaje_otorgado ? $encuesta->estudio->porcentaje_otorgado.'%':'N/A')}}
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <!-- PÁGINA 3 (hoja individual): DESGLOSE DE PREGUNTAS -->
    @if ($encuesta->preguntas)
    <div class="hoja-individual hoja-preguntas">
        @foreach ($encuesta->preguntas as $pregunta)
            <div class="pregunta-item">
                <div class="pregunta-header">
                    <div class="pregunta-titulo">{{$pregunta->numero_pregunta}}.- {{$pregunta->pregunta}}</div>
                </div>
                <div class="pregunta-body">
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
    </div>
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
                        <div class="foto-card" style="display: inline-block; margin: 8px;">
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
        <div class="grafica-card" style="margin-bottom: 20px; display: inline-block;">
            {!! generarGraficaBar($parametro,$resultado) !!}
        </div>
        <br>
    @endforeach
    <br>
    <div class="grafica-card" style="margin-top: 10px; display: inline-block;">
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

function leerContenidoImagen($source){
    $data = @file_get_contents($source);

    if ($data === false || $data === '') {
        logger()->warning('No se pudo obtener el contenido de una imagen.');
        return null;
    }

    return $data;
}

function imagenReturn($path){
    $contents = storage_path('app/public/' . $path);
    $type = pathinfo($contents, PATHINFO_EXTENSION);
    $data = leerContenidoImagen($contents);

    if ($data === null) {
        return '';
    }

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
    return number_format((float) $num, 2, '.', ',');
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
            <table style="width: 100%;" class="tabla-moderna">
                <tr>
                    <td>
                        <div class="campo respuestas" style="min-height: 60px; line-height: 1.55; white-space: pre-wrap;">' . $respuesta . '</div>
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

    $html = '<table style="width: 100%;" class="tabla-moderna respuestas">';
    $html .= "  <tr class='encabezado-tabla'>
                    <td style='width: 30%;'></td>
                    <td></td>
                    <td style='width: 10%;'></td>
                    <td class='izquierda'>Años</td>
                    <td style='width: 30%;'></td>
                </tr>";
    foreach($parametros AS $parametro){
        $html .= "<tr>
                    <td style='width: 30%;'></td>
                    <td style='text-align: center;'><span class='marca-opcion'>".( $respuesta == $parametro['valor'] ? 'X' : '&nbsp;')."</span></td>
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
        <table style="width: 100%;" class="tabla-moderna respuestas">
            <tr class="encabezado-tabla">
                <td style="width: 40%;" class="izquierda">Nombre</td>
                <td style="width: 15%;">% Beca actual</td>
                <td style="width: 15%;">Cursar</td>
                <td style="width: 15%;">Promedio académico</td>
                <td style="width: 15%;">Promedio conducta</td>
            </tr>
    ';


    foreach ($formData as $index => $item) {
            $nombre             = isset($item['nombre']) ? htmlspecialchars($item['nombre']) : '&nbsp;';
            $respuesta          = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';
            $anio_cursar        = strlen($item['nombre']) ? $item['monto'] : '&nbsp;';
            $promedio_academico = strlen($item['nombre']) ? opcionesParametrosPromedioAcademico($item['padre_monto'], $parametros_promedio_academico) : '&nbsp;';
            $promedio_conducta  = strlen($item['nombre']) ? opcionesParametrosPromedioConducta($item['madre_monto'],   $parametros_promedio_conducta) : '&nbsp;';

            $html .= '
                <tr>
                    <td><div class="campo">'.$nombre.'</div></td>
                    <td><div class="campo" style="text-align: center;">'.$respuesta.'</div></td>
                    <td><div class="campo" style="text-align: center;">'.$anio_cursar .'</div></td>
                    <td><div class="campo" style="text-align: center;">'.$promedio_academico.'</div></td>
                    <td><div class="campo" style="text-align: center;">'.$promedio_conducta.'</div></td>
                </tr>
            ';
        }

    $html .= '</table>';
    return $html;
}
// 5 .-  Orfandad
function orfandad($formData,$parametros){

    $respuesta = isset($formData[0]['valor']) ? htmlspecialchars($formData[0]['valor']) : '';

    $html = '<table style="width: 100%;" class="tabla-moderna respuestas">';
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
                    <td style='text-align: center;'><span class='marca-opcion'>".( $respuesta == $parametro['valor'] ? 'X' : '&nbsp;')."</span></td>
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
        <table style="width: 100%;" class="tabla-moderna respuestas">
            <tr class="encabezado-tabla">
                <td style="width: 35%;">Parentesco</td>
                <td style="width: 65%;" class="izquierda">Nombre</td>
            </tr>
    ';

    foreach ($formData as $index => $item) {
        $parentesco = isset($item['parentesco']) ? htmlspecialchars($item['parentesco']) : '&nbsp;';
        $nombre = isset($item['nombre']) ? htmlspecialchars($item['nombre']) : '&nbsp;';

        $html .= '
            <tr>
                <td><div class="campo">' . $parentesco . '</div></td>
                <td><div class="campo">' . $nombre . '</div></td>
            </tr>
        ';
    }

    $html .= '</table>';
    return $html;
}
// 7 .-  Económicamente activo
function familiaEconomicameteActiva($formData) {

    $html = '<table style="width: 100%;" class="tabla-moderna respuestas">';

    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '&nbsp;';
        $vive = isset($item['vive']) && $item['vive'] ? 'Sí' : 'No';
        $activo = isset($item['activo']) && $item['activo'] ? 'Sí' : 'No';
        $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';
        $esFemenino = stripos($texto, 'MADRE') !== false;
        $lblActivo = $esFemenino ? '¿Activa laboralmente?' : '¿Activo laboralmente?';

        $html .= '
            <tr><td colspan="3" style="padding-top: 6px;"><div class="subtitulo-columna">' . ucfirst(strtolower($texto)) . '</div></td></tr>
            <tr>
                <td style="width: 18%;"><div class="campo-etiqueta">Vive</div><div class="campo">' . $vive . '</div></td>
                <td style="width: 32%;"><div class="campo-etiqueta">' . $lblActivo . '</div><div class="campo">' . $activo . '</div></td>
                <td style="width: 50%;"><div class="campo-etiqueta">Empresa / Ocupación</div><div class="campo">' . $respuesta . '</div></td>
            </tr>
        ';
    }

    $html .= '</table>';
    return $html;
}
// 8 .-  Ingreso mensual
function ingresoNetoMensual($formData) {
    $html = '
        <table style="width: 100%;" class="tabla-moderna respuestas">
            <tr class="encabezado-tabla">
                <td style="width: 28%;" class="izquierda">Concepto</td>
                <td style="width: 24%;">Padre</td>
                <td style="width: 24%;">Madre</td>
                <td style="width: 24%;">Otros</td>
            </tr>
    ';

    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $padreMonto = isset($item['padre_monto']) ? formatNumber($item['padre_monto']) : '0.00';
        $madreMonto = isset($item['madre_monto']) ? formatNumber($item['madre_monto']) : '0.00';
        $monto = isset($item['monto']) ? formatNumber($item['monto']) : '0.00';

        $html .= '
            <tr>
                <td><div style="padding: 3px 2px;">' . $texto . '</div></td>
                <td><div class="campo-dinero">$' . $padreMonto . '</div></td>
                <td><div class="campo-dinero">$' . $madreMonto . '</div></td>
                <td><div class="campo-dinero">$' . $monto . '</div></td>
            </tr>
        ';
    }

    // Subtotales por campo
    $subtotalPadre = formatNumber(sumaTotalporCampo('padre_monto', $formData));
    $subtotalMadre = formatNumber(sumaTotalporCampo('madre_monto', $formData));
    $subtotalOtros = formatNumber(sumaTotalporCampo('monto', $formData));

    $html .= '
        <tr class="fila-subtotal">
            <td>Sub Total</td>
            <td><div class="campo-dinero">$' . $subtotalPadre . '</div></td>
            <td><div class="campo-dinero">$' . $subtotalMadre . '</div></td>
            <td><div class="campo-dinero">$' . $subtotalOtros . '</div></td>
        </tr>
    ';

    // Total general
    $total = formatNumber(sumaTotales($formData));

    $html .= '
        <tr class="fila-total-moderno">
            <td>Total</td>
            <td><div class="total-destacado">$' . $total . '</div></td>
            <td></td>
            <td></td>
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

    $html = '<table style="width: 100%;" class="tabla-moderna respuestas">';

    if($activo == 'NO'){
    $html .= "  <tr>
                    <td style='width: 20%;'><div class='campo-etiqueta'>¿Cuenta con ahorros?</div><div class='campo' style='text-align: center;'>$activo</div></td>
                    <td></td>
                </tr>";
    }else{
    $html .= "  <tr>
                    <td style='width: 15%;'><div class='campo-etiqueta'>¿Cuenta con ahorros?</div><div class='campo' style='text-align: center;'>$activo</div></td>
                    <td style='width: 45%;'><div class='campo-etiqueta'>Describe</div><div class='campo'>$respuesta</div></td>
                    <td style='width: 40%;'><div class='campo-etiqueta'>Monto de ahorros o inversiones</div><div class='campo-dinero'>$$monto</div></td>
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
            $activo = isset($item['activo']) && $item['activo'] ? 'SI' : 'NO';
            $html  .= "<table style='width: 100%;' class='tabla-moderna respuestas'><tr><td style='width: 20%;'><div class='campo-etiqueta'>¿Cuenta con inversiones?</div><div class='campo' style='text-align: center;'>$activo</div></td><td></td></tr></table>";
        }
    }

    $html   .= '<table style="width: 100%;" class="tabla-moderna respuestas">';
    $html   .= "  <tr class='encabezado-tabla'>
                    <td style='width: 70%;' class='izquierda'>Describir</td>
                    <td style='width: 30%;'>Valor estimado</td>
                </tr>";
    foreach($formData AS $item){
        if($item["seccion"] == "inverciones"){
            $respuesta  = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';
            $monto      = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';
            $html .= "<tr>
                        <td><div class='campo'>".$respuesta."</div></td>
                        <td><div class='campo-dinero'>$".$monto."</div></td>
                    </tr>";
        }
    }

    $total = isset($totalParametros[-1]) ? $totalParametros[-1] : 0 ;;

    $html .= "
            <tr class='fila-total-moderno'>
                <td>Total</td>
                <td><div class='total-destacado'>$" . formatNumber($total) . "</div></td>
            </tr>
        </table>
    ";
    return $html;
}
    // 11 .-  Vehículos
function preguntaVeiculos($formData) {

    $html = '
        <table style="width: 100%;" class="tabla-moderna respuestas">
            <tr class="encabezado-tabla">
                <td style="width: 20%;">Tipo</td>
                <td style="width: 25%;">Marca / Modelo</td>
                <td style="width: 10%;">Año</td>
                <td style="width: 20%;">Propietario</td>
                <td style="width: 25%;">Valor aproximado</td>
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
                <td><div class='campo'>$tipo</div></td>
                <td><div class='campo'>$marcaModelo</div></td>
                <td><div class='campo' style='text-align: center;'>$anio</div></td>
                <td><div class='campo'>$propietario</div></td>
                <td><div class='campo-dinero'>$$monto</div></td>
            </tr>
        ";
    }

    // Total
    $totalMonto = formatNumber(sumaTotalporCampo('monto', $formData));
    $html .= "
        <tr class='fila-total-moderno'>
            <td colspan='4'>Total</td>
            <td><div class='total-destacado'>$$totalMonto</div></td>
        </tr>
    ";

    $html .= '</table>';
    return $html;
}
    // 12 .- Propiedades Hipotecarias / casa Habitación
function casaHabitacion($formData,$paramtroClasificacion,$totalParametros) {

    $html = '<table style="width: 100%;" class="tabla-moderna respuestas">';

    // Sección 'vivienda'

    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'vivienda') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

            $html .= "
                <tr>
                    <td style='width: 50%;'><div class='campo-etiqueta'>$texto</div><div class='campo'>$respuesta</div></td>
                    <td style='width: 50%;'></td>
                </tr>
            ";
        }
    }

    $html .= '</table>';

    // Sección 'valor'
    $html .= '<table style="width: 100%;" class="tabla-moderna respuestas">';
    $bloques = array();
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'renta') {
            $texto = htmlspecialchars($item['texto']);
            $monto = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';
            $bloques[] = "
                    <td style='width: 25%;'><div class='campo-etiqueta'>$texto</div><div class='campo-dinero'>$$monto</div></td>
                    <td style='width: 25%;'></td>
                    ";
        }
        if (isset($item['seccion']) && $item['seccion'] === 'valor') {
            $texto = htmlspecialchars($item['texto']);
            $monto = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';
            $bloques[] = "
                    <td style='width: 25%;'><div class='campo-etiqueta'>$texto</div><div class='campo-dinero'>$$monto</div></td>
                    <td style='width: 25%;'></td>
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
     $html .= '<table style="width: 100%;" class="tabla-moderna respuestas">';
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'header_otros') {
            $html .= "
            <tr>
                <td><div class='subtitulo-columna'>" . htmlspecialchars($item['texto']) . "</div></td>
            </tr>
            ";
        }
    }
    $html .= '</table>';

    // Sección 'body_otros'
    $html .= '<table style="width: 100%;" class="tabla-moderna respuestas">';
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'body_otros') {
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';
            $monto = isset($item['monto']) ? formatNumber($item['monto']) : '&nbsp;';

            $html .= "
                <tr>
                    <td style='width: 70%;'><div class='campo'>$respuesta</div></td>
                    <td style='width: 30%;'><div class='campo-dinero'>$$monto</div></td>
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
        <table style='width: 100%;' class='tabla-moderna respuestas'>
            <tr class='fila-subtotal'>
                <td style='width: 50%;'>Total</td>
                <td style='width: 50%;'><div class='total-destacado'>$" . formatNumber($totalB) . "</div></td>
            </tr>
            <tr class='fila-total-moderno'>
                <td>Gran total patrimonio</td>
                <td><div class='total-destacado'>$" . formatNumber($totalAB) . "</div></td>
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

            $html .= "<div class='subtitulo-columna respuestas' style='margin-bottom: 6px;'>".opcionSeleccionadaDLC($respuesta,$parametros)."</div>";
        }
    }
    // Sección 'descripcion'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'descripcion') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

            $html .= "<div style='width: 100%;' class='campo respuestas'>$respuesta</div>";
        }
    }

    return $html;
}
// 14 .-  Deudas
function deudasMensuales($formData) {

    $monto_total = 0;
    // Generar el encabezado de la tabla
    $html = '<table style="width: 100%;" class="tabla-moderna respuestas">';

    $html .= '<tr class="encabezado-tabla">';
    $html .=    '<td class="izquierda">Concepto</td>';
    $html .=    '<td style="width: 33%;">Mensualidad</td>';
    $html .=    '<td style="width: 33%;">Saldo</td>';
    $html .= '</tr>';

    // Iterar sobre el array de datos
    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $padreMonto = isset($item['padre_monto']) ? formatNumber($item['padre_monto']) : '';
        $monto = isset($item['monto']) ? formatNumber($item['monto']) : '';
        $monto_total += isset($item['monto']) ? $item['monto']  : 0;

        $html .= "
            <tr>
                <td><div style='padding: 3px 2px;'>$texto</div></td>
                <td><div class='campo-dinero'>$$padreMonto</div></td>
                <td><div class='campo-dinero'>$$monto</div></td>
            </tr>
        ";
    }

    $total = formatNumber($monto_total);

    $html .= "
            <tr class='fila-total-moderno'>
                <td>Total</td>
                <td></td>
                <td><div class='total-destacado'>$$total</div></td>
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
    $html = '<table style="width: 100%;" class="tabla-moderna respuestas">';

    // Iterar sobre los datos
    $bloques = array();
    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $padreMonto = isset($item['padre_monto']) ? formatNumber($item['padre_monto']) : '';

        $bloques[] = "
                <td style='width: 25%;'><div class='campo-etiqueta'>$texto</div><div class='campo-dinero'>$$padreMonto</div></td>
                <td style='width: 25%;'></td>
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
            <tr class='fila-total-moderno'>
                <td style='width: 50%;'>Total</td>
                <td style='width: 50%;'><div class='total-destacado'>$$total</div></td>
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
    $html = '<table style="width: 100%;" class="tabla-moderna respuestas">';

    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $activo = isset($item['activo']) ? ($item['activo'] ? 'Sí' : 'No') : '';
        $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';
        $esFemenino = stripos($texto, 'MADRE') !== false;
        $lblActivo = $esFemenino ? '¿Activa laboralmente?' : '¿Activo laboralmente?';

        $html .= '
            <tr><td colspan="2" style="padding-top: 6px;"><div class="subtitulo-columna">' . ucfirst(strtolower($texto)) . '</div></td></tr>
            <tr>
                <td style="width: 35%;"><div class="campo-etiqueta">' . $lblActivo . '</div><div class="campo">' . $activo . '</div></td>
                <td style="width: 65%;"><div class="campo-etiqueta">Empresa / Ocupación</div><div class="campo">' . $respuesta . '</div></td>
            </tr>
        ';
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
    $imageData = leerContenidoImagen($chartUrl);

    if ($imageData === null) {
        return;
    }

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
    $imageData = leerContenidoImagen($chartUrl);

    if ($imageData === null) {
        return;
    }

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
    $imageData = leerContenidoImagen($chartUrl);

    if ($imageData === null) {
        return;
    }

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
