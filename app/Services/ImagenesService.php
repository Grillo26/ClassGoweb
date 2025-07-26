<?php
// filepath: app/Services/MailService.php

namespace App\Services;


class ImagenesService
{
    /**
     * Aquí puedes agregar métodos relacionados con la gestión de imágenes.
     * Por ejemplo, subir imágenes, eliminar imágenes, etc.
     */

    public function uploadImage($image)
    {
        // Lógica para subir una imagen
    }

    public function deleteImage($imageId)
    {
        // Lógica para eliminar una imagen
    }


    public function guardarqrEstudianteReserva($image):string
    {
        // Lógica para guardar el QR de estudiante en la reserva
        // Aquí puedes implementar la lógica específica para guardar el QR
        // por ejemplo, generando un nombre único y guardándolo en una carpeta específica.


          // Nombre único para evitar sobrescribir archivos
                    $fileName = uniqid() . '_' . $image->getClientOriginalName();
                    // Guarda el archivo en storage/app/public/uploads/bookings
                    $image->storeAs('uploads/bookings', $fileName, 'public');
                    // Copia el archivo a public/storage/uploads/bookings
                    $source = storage_path('app/public/uploads/bookings/' . $fileName);
                    $destination = public_path('storage/uploads/bookings/' . $fileName);
                    if (!file_exists(dirname($destination))) {
                        mkdir(dirname($destination), 0775, true);
                    }
                    copy($source, $destination);

        return 'uploads/bookings/' . $fileName;
    }
}