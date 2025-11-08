<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>NUEVO USUARIO SINERGIA</title>
</head>
<body>
    <h3>Buen dia Familia: {{ $data['candidato'] ?? 'SIN DATO' }}</h3>
    <p>Se a creado un nuevo usuario para usted en nuestro sistema de <a href="{{ url('/')}}">SINERGIA</a>.</p>
    <p>Ingrese con los datos siguientes y de de alta los datos requeridos para continuar su proceso de alta en el proceso de becas.</p>
    <p>Usuario: <span style="font-weight: bold">{{ $data['email'] ?? 'SIN DATO'}}</span></p>
    <p>Pasword Temporal: <span style="font-weight: bold">{{ $data['password_temporal'] ?? 'SIN DATO' }}</span></p>
</body>
</html>