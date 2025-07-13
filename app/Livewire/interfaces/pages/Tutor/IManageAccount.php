<?php

use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\WithPagination;




/**
 * Componente Livewire para la gestión de cuenta del tutor
 * 
 * Maneja los métodos de pago, imágenes QR, balances y retiros
 * del tutor en la plataforma ClassGo
 * 
 * @package App\Livewire\Pages\Tutor\ManageAccount
 * @author ClassGo Team
 */

interface IManageAccount
{

    use WithPagination;
    use WithFileUploads;

    // ================================
    // SERVICIOS PRIVADOS
    // ================================

    /**
     * Inicializa los servicios necesarios
     * 
     * @return void
     */
    public function boot();

    /**
     * Monta el componente e inicializa los datos del tutor
     * Carga información de ganancias, métodos de pago y configuraciones iniciales
     * 
     * @return void
     */
    public function mount():void;

    /**
     * Event listener para refrescar los datos de pago
     * 
     * @return void
     */
    #[On('refresh-payouts')]
    public function refresh()
    {
        $this->loadData();
    }

    /**
     * Renderiza la vista del componente con datos actualizados
     * 
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.app')]
    public function render();

    /**
     * Carga todos los datos necesarios para la gestión de cuenta
     * 
     * @return void
     */
    public function loadData();

    /**
     * Actualiza la imagen QR del método de pago del tutor
     * Maneja la validación, subida de archivos y actualización en base de datos
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function updateQR();

    /**
     * Actualiza el listener de fecha seleccionada y recarga los datos
     * 
     * @param string|Carbon $date Fecha seleccionada en formato string o Carbon
     * @return void
     */
    public function updatedSelectedDate($date);

    /**
     * Actualiza la información de pago del usuario
     * Maneja diferentes métodos de pago incluyendo QR
     * 
     * @return void
     */
    public function updatePayout();
    /**
     * Elimina un método de pago del usuario
     * 
     * @return void
     */
    public function removePayout();

    /**
     * Actualiza el estado de un método de pago específico
     * 
     * @param string $method Método de pago a actualizar
     * @return void
     */
    public function updateStatus($method);

    /**
     * Abre el modal de configuración de pago y prepara el formulario
     * 
     * @param string $method Método de pago seleccionado
     * @param string $id ID del modal a mostrar
     * @return void
     */
    public function openPayout($method, $id);

    // ================================
    // MÉTODOS PRIVADOS HELPER
    // ================================

    /**
     * Valida que existe una imagen QR antes de procesar
     * 
     * @return bool True si la validación es exitosa
     */
    private function validateQRImage(): bool;

    /**
     * Verifica si el sitio está en modo demo y maneja la restricción
     * 
     * @param array $modalsToClose Modales a cerrar si es sitio demo
     * @return bool True si es sitio demo (y debe terminar la ejecución)
     */
    private function isDemoSiteRestricted(array $modalsToClose = []): bool;

    /**
     * Maneja la subida de imagen QR al servidor
     * 
     * @param bool $includeStoragePrefix Si incluir el prefijo 'storage/' en la ruta
     * @return string Ruta de la imagen subida
     * @throws \Exception Si falla la subida
     */
    private function handleQRImageUpload(bool $includeStoragePrefix = false): string;

    /**
     * Elimina la imagen QR anterior del usuario si existe
     * 
     * @return void
     */
    private function deleteExistingQRImage(): void;

    /**
     * Actualiza o crea el método de pago QR en la base de datos
     * 
     * @param string $imagePath Ruta de la imagen QR
     * @return void
     * @throws \Exception Si falla la actualización
     */
    private function updateQRPayoutMethod(string $imagePath): void;

    /**
     * Muestra mensaje de éxito al usuario
     * 
     * @param string $message Mensaje a mostrar
     * @return void
     */
    private function showSuccessMessage(string $message): void;

    /**
     * Muestra mensaje de error al usuario
     * 
     * @param string $message Mensaje de error a mostrar
     * @return void
     */
    private function showErrorMessage(string $message): void;

    /**
     * Cierra un modal específico
     * 
     * @param string $modalId ID del modal a cerrar
     * @return void
     */
    private function closeModal(string $modalId): void;

    /**
     * Resetea el formulario y cierra múltiples modales
     * 
     * @param array $modalIds IDs de los modales a cerrar
     * @return void
     */
    private function resetFormAndCloseModals(array $modalIds):void;
    
    
}