<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombreAlumno;
    public $fechaCita;
    public $horaInicio;
    public $horaFin;
    public $nombreTutor;

    public function __construct($nombreAlumno, $fechaCita, $horaInicio, $horaFin, $nombreTutor)
    {
        $this->nombreAlumno = $nombreAlumno;
        $this->fechaCita = $fechaCita;
        $this->horaInicio = $horaInicio;
        $this->horaFin = $horaFin;
        $this->nombreTutor = $nombreTutor;
    }

    public function build()
    {
        return $this->subject('Confirmación de cita - Centro Educativo Down')
                    ->view('emails.appointment');
    }
}
