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
    </style>
</head>
<body>

    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <br/>
                <br/>
                <br/>
                <h5>SINERGIA EN ESTUDIOS SOCIOECONÓMICOS</h5>
                <br/>
                <p class="text-uppercase mt-5">ESTUDIO SOCIOECONÓMICO PARA BECA CICLO {{$encuesta->proyecto->nombre}}</p>
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

    @if ($encuesta->preguntas)
        @foreach ($encuesta->preguntas as $pregunta)
            <div class="mt-3 mb-3 ms-5 me-5 no-page-break">
                <div class="pt-5">
                    <p style="font-size: 1rem" class="text-uppercase fw-bolder">{{$pregunta->numero_pregunta}}.- {{$pregunta->pregunta}}</p>
                </div>
                {!!
                    preguntaPorTipoPregunta(
                        $pregunta->id_catalogo_encuestas_preguntas_tipo,
                        $pregunta->respuestas,
                        $pregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion,
                        $encuesta->total_parametros
                    )
                !!}
            </div>
        @endforeach
    @endif


</body>
</html>


<?php

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

// 1 .-  Pregunta abierta
function preguntaAbierta($formData) {

    $html = '';

    foreach ($formData as $index => $item) {
        $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '';

        $html .= '
            <table style="width: 100%;"">
                <tr>
                    <td style="width: 50px;"></td>
                    <td style="border:solid 1px #000;"">
                        <div
                            style="
                                width: 100%;
                                min-height: 200px;
                                padding: 5px;
                                line-height: 1.55;
                                white-space: pre-wrap;

                                font-size:12px;
                            "
                        >' . $respuesta . '</div>
                    </td>
                    <td style="width: 50px;"></td>
                </tr>
            </table>
        ';
    }

    return  $html;
}

    // 2 .-  Lista selección múltiple
    // 3 .-  Antigüedad en colegio
    // 4 .-  Número de Hijos
    // 5 .-  Orfandad
    // 6 .-  Dependientes Económicos
function dependientesEconomicamente($formData) {

    $html = '
        <table style="width: 100%; font-size:12px;">
            <tr>
                <td  style="width: 15%;"></td>
                <td   class="p-1  text-center">PARENTESCO</td>
                <td  class="p-1 text-center">NOMBRE</td>
                <td  style="width: 10%;"></td>
            </tr>
    ';

    foreach ($formData as $index => $item) {
        $parentesco = isset($item['parentesco']) ? htmlspecialchars($item['parentesco']) : '&nbsp;';
        $nombre = isset($item['nombre']) ? htmlspecialchars($item['nombre']) : '&nbsp;';

        $html .= '
            <tr class="text-start">
                <td></td>
                <td class="p-1">
                    <div style="
                        padding: 5px;
                        border-bottom: 1px solid #020202;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    ">
                        ' . $parentesco . '
                    </div>
                </td>
                <td class="p-1">
                    <div style="
                        padding: 5px;
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
        <table style="width: 100%; font-size:12px;">
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
        $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

        $html .= '
            <tr>
                <td>' . $texto . '</td>
                <td class="p-1">
                    ' . $vive . '
                </td>
                <td class="p-1">
                   ' . $activo . '
                </td>
                <td class="p-1">
                    <div style="
                        padding: 5px;
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
        <table style="width: 100%; font-size:12px;">
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
                <div class="border-bottom border-secondary text-end">
                    $' . $total . '
                </div>
            </td>
        </tr>
    ';

    $html .= '</table>';
    return $html;
}
    // 9 .-  Ahorro
    // 10 .-  Inversiones
    // 11 .-  Vehículos
function preguntaVeiculos($formData) {

    $html = '
        <table style="width: 100%; font-size:12px;">
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
                <table style='width: 100%;' class='border-bottom border-secondary'>
                    <tr>
                        <td style='width: 10%;'>$</td>
                        <td text-end'>$totalMonto</td>
                    </tr>
                </table>
            </td>
        </tr>
    ";

    $html .= '</table>';
    return $html;
}
/*
function distribucionDeLaCasa($formData) {
    $html = '<div class="row">';

    // Sección 'seleccionable'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'seleccionable') {
            $texto = htmlspecialchars($item['texto']);
            $activo = isset($item['activo']) && $item['activo'] ? "Sí" : "No";

            $html .= "
                <div class='row col-sm-3' id='pes-" . htmlspecialchars($index) . "'>
                    <div class='col-8 p-1 text-start'>$texto</div>
                    <div class='col-4 p-1'>
                        <div class='form-check form-switch'><span>$activo</span></div>
                    </div>
                </div>
            ";
        }
    }

    $html .= '<div class="sol-12"><br /></div>';

    // Sección 'clasificacion'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'clasificacion') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

            $html .= "
                <div class='row col-12' id='pes-" . htmlspecialchars($index) . "'>
                    <div class='col-4 p-1 text-start'>$texto</div>
                    <div class='col-8 p-1'>
                        <div class='border-bottom border-secondary'>$respuesta</div>
                    </div>
                </div>
            ";
        }
    }

    // Sección 'descripcion'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'descripcion') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

            $html .= "
                <div class='row col-12' id='pes-" . htmlspecialchars($index) . "'>
                    <div class='col-sm-3 p-1 text-start'>$texto</div>
                    <div class='col-sm-9 p-1'>
                        <div class='border-bottom border-secondary' style='white-space: pre-wrap; min-height: 200px;'>$respuesta</div>
                    </div>
                </div>
            ";
        }
    }

    $html .= '</div>';
    return $html;
}*/
// 12 .- Propiedades Hipotecarias / casa Habitación
function casaHabitacion($formData,$paramtroClasificacion,$totalParametros) {

    $html = '<table style="width: 100%; font-size:12px;">';

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
    $html .= '<table style="width: 100%; font-size:12px;">';
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
     $html .= '<table style="width: 100%; font-size:12px;" class="mt-2">';
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
    $html .= '<table style="width: 100%; font-size:12px;">';
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
        <table style='width: 100%; font-size:12px;'>
            <tr class='text-start'>
                <td style='width: 25%;' class='p-1'><b>B) TOTAL:</b></td>
                <td style='width: 25%;' class='p-1'>
                    <div class='border-bottom border-secondary'>$" . formatNumber($totalB) . "</div>
                </td>
                <td style='width: 25%;' class='p-1'></td>
                <td style='width: 25%;' class='p-1'></td>
            </tr>
        </table>
    ";

    // Total A + B
    $html .= "
        <table style='width: 100%; font-size:12px;'>
            <tr class='text-start'>
                <td style='width: 25%;' class='p-1'><b>A + B TOTAL:</b></td>
                <td style='width: 25%;' class='p-1'>
                    <div class='border-bottom border-secondary'>$" . formatNumber($totalAB) . "</div>
                </td>
                <td style='width: 25%;' class='p-1'></td>
                <td style='width: 25%;' class='p-1'></td>
            </tr>
        </table>
    ";

    return $html;
}
// 13 .- Distribución de la casa
function distribucionDeLaCasa($formData) {
    // Función para generar el HTML de cada sección
    $html = '<div class="row">';

    // Sección 'seleccionable'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'seleccionable') {
            $texto = htmlspecialchars($item['texto']);

            $activo = isset($item['monto']) ? ($item['monto'] ? formatNumber($item['monto']) : 'No') : 'No';

            $html .= "
                <div class='row col-sm-3' id='pes-" . htmlspecialchars($index) . "'>
                    <div class='col-8 p-1 text-start'>$texto</div>
                    <div class='col-4 p-1'>
                        <div class='form-check form-switch'>
                            <span>$activo</span>
                        </div>
                    </div>
                </div>
            ";
        }
    }

    // Salto de línea
    $html .= "<div class='sol-12'><br /></div>";

    // Sección 'clasificacion'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'clasificacion') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

            $html .= "
                <div class='row col-12' id='pes-" . htmlspecialchars($index) . "'>
                    <div class='col-4 p-1 text-start'>$texto</div>
                    <div class='col-8 p-1'>
                        <div class='border-bottom border-secondary'>$respuesta</div>
                    </div>
                </div>
            ";
        }
    }

    // Sección 'descripcion'
    foreach ($formData as $index => $item) {
        if (isset($item['seccion']) && $item['seccion'] === 'descripcion') {
            $texto = htmlspecialchars($item['texto']);
            $respuesta = isset($item['respuesta']) ? htmlspecialchars($item['respuesta']) : '&nbsp;';

            $html .= "
                <div class='row col-12' id='pes-" . htmlspecialchars($index) . "'>
                    <div class='col-sm-3 p-1 text-start'>$texto</div>
                    <div class='col-sm-9 p-1'>
                        <div style='width: 100%; height: 200px; padding: 5px; border: none; border-bottom: 1px solid #ced4da; overflow-y: auto; white-space: pre-wrap;'>
                            $respuesta
                        </div>
                    </div>
                </div>
            ";
        }
    }

    $html .= '</div>';
    return $html;
}
// 14 .-  Deudas
function deudasMensuales($formData) {

    // Generar el encabezado de la tabla
    $html = '<table style="width: 100%; font-size:12px;">';

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
    $html = '<table style="width: 100%; font-size:12px;"">';

    // Iterar sobre los datos
    $bloques = array();
    foreach ($formData as $index => $item) {
        $texto = isset($item['texto']) ? htmlspecialchars($item['texto']) : '';
        $padreMonto = isset($item['padre_monto']) ? number_format($item['padre_monto'], 0) : '';

        $bloques[] = "
                <td style='width: 25%;' class='p-1'>$texto</td>
                <td style='width: 25%;' class='p-1'>
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
                    <td style='width: 25%;' class='p-1 text-start'></td>
                    <td style='width: 25%;' class='p-1 text-start'>
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
    $html = '<table style="width: 100%; font-size:12px;"">';
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
                    $activo
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

function preguntaPorTipoPregunta($idPreguntaTipo,$respuestas,$paramtroClasificacion,$totalParametros) {
    switch($idPreguntaTipo) {
        // 1 .- Pregunta abierta
        case 1:
            return preguntaAbierta($respuestas);
            break;

        // 2 .- Lista selección múltiple
        // 3 .- Antigüedad en colegio
        // 4 .- Número de Hijos
        // 5 .- Orfandad
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
        // 10 .- Inversiones
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
            return distribucionDeLaCasa($respuestas);

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
