<!-- resources/views/emails/test.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de correo electrónico</title>
</head>
<body>
    <h1>¡Bienvenido a nuestra plataforma!</h1>
    <p>Para verificar tu correo electrónico, por favor haz clic en el siguiente enlace:</p>
    <a href="{{ $verificationUrl }}">Verificar mi correo electrónico</a>
</body>
</html>
