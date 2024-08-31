<?php

namespace App\Http\Controllers;

use FFMpeg\FFMpeg;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use FFMpeg\Format\Video\WebM;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\Notification;

class VideoConverter extends Controller
{
    public function converter(Request $request) {
        try {
            // Crear una instancia de FFMpeg
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => env('FFMPEG_BINARIES'), // Ruta al binario de ffmpeg
                'ffprobe.binaries' => env('FFPROBE_BINARIES'), // Ruta al binario de ffprobe
                'timeout'          => 3600, // El tiempo máximo de ejecución en segundos
                'ffmpeg.threads'   => 12,   // Número de hilos que ffmpeg debería usar
            ]);

            // Abrir el archivo de video
            $video = $ffmpeg->open($request->file('video'));

            // Crear un objeto de formato WebM
            $format = new WebM();

            // Define el codec de video, por ejemplo, libvpx
            $format->setVideoCodec('libvpx');

            // Ajustar la tasa de bits de video
            $format->setKiloBitrate(1000);

            // Define la ruta de salida del archivo hacia el escritorio del usuario
            // $format->on('progress', function ($percentage) {
            //     // Actualizar el progreso de la conversión
            //     Notification::title('Progreso de la conversión')
            //         ->message('Converting video: ' . $percentage . '%')
            //         ->event(\App\Events\ProgressEvent::class)
            //         ->show();
            // });

            $extension = $request->file('video')->getClientOriginalExtension();

            // Nombre del archivo original sin la extensión
            $filename = Str::of($request->file('video')->getClientOriginalName())->basename('.' . $extension);

            // si no existe el directorio, crearlo
            if (!Storage::disk('documents')->exists('Video Converter')) {
                Storage::disk('documents')->makeDirectory('Video Converter');
            }

            // Path de salida con el nombre del archivo original sin la extensión
            $output = Storage::disk('documents')->path('Video Converter/' . $filename . '.webm');

            // Crear un nuevo nombre de archivo para el archivo de salida
            $video->save($format, $output);

            //Abrir carpeta de salida en el explorador de archivos
            exec('open ' . Storage::disk('documents')->path('Video Converter'));

            Notification::title('Video convertido!')
                ->message('Video convertido con éxito!')
                ->show();

            // Redireccionar a la página anterior
            return response()->json(['ok' => 'Video convertido con éxito!']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return new \Exception($e->getMessage());
        }
    }
}
