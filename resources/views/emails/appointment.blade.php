<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Confirmación de Cita</title>
</head>
<body>
    <h1>Confirmación de Cita</h1>
    <p>Hola {{ $nombreAlumno }},</p>
    <p>Tu cita ha sido agendada con éxito para la fecha <strong>{{ $fechaCita }}</strong> en el horario de <strong>{{ $horaInicio }} a {{ $horaFin }}</strong>.</p>
    <p>Con el tutor: <strong>{{ $nombreTutor }}</strong></p>
    <br>
    <p>Gracias por confiar en nuestro centro educativo.</p>
    <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Logo Centro Educativo" width="150" />
</body>
</html>
