<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
</head>
<body style="font-family: Arial, sans-serif;">

    <h2>Hola, {{ $userName }}</h2>

    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
    <p>Haz clic en el siguiente botón para establecer una nueva contraseña:</p>

    <a href="{{ $resetUrl }}"
       style="display: inline-block; padding: 10px 20px; color: white; background-color: #007bff; text-decoration: none; border-radius: 5px;">
        Restablecer Contraseña
    </a>

    <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este correo electrónico de forma segura.</p>

    <p>Gracias,</p>
    <p>El equipo de MyFavorites</p>

</body>
</html>
