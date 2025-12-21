<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CAMBIO CONTRASEÑA USUARIO SINERGIA</title>
</head>
<body>
    <h3>Buen dia, {{ $data['nombre'] ?? 'SIN DATO' }}</h3>
    <p>Se le ha actualizado su contraseña en nuestro sistema de <a href="{{ url('/')}}">SINERGIA</a>.</p>
    <p>Ingrese con los datos siguientes y posterior cambie la contraseña</p>
    <p>Usuario: <span style="font-weight: bold">{{ $data['email'] ?? 'SIN DATO'}}</span></p>
    <p>Pasword Temporal: <span style="font-weight: bold">{{ $data['password_temporal'] ?? 'SIN DATO' }}</span></p>
</body>
</html>