<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>RESULTADOS ESTUDIO SOCIOECONOMICO - SINERGIA</title>
</head>
<body>
    <h3>Buen dia Familia: {{ $data['candidato'] }}</h3>
    <p>Por el presente se le informa que los resultados de su estudio socioeconomico ha sido concluido.</p>
    <p>El colegio: <span style="font-weight: bold">{{ $data['cliente'] }}</span> le ha asignado el siguiente porcentaje para su beca.</p>
    @if($data['hijo'])
        <p>Alumno: <span style="font-weight: bold">{{ $data['hijo_dato'] }}</span></p>
    @endif
    <p style="font-weight: bold">Porcentaje Otorgado: {{ $data['porcentaje_otorgado'] }} %</p>
</body>
</html>