<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserPayoutMethod;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function showQR($orderId)
    {
        \Log::info('🔍 Buscando orden con ID:', ['orderId' => $orderId]);
    
        // Convertir el ID a entero para evitar problemas con strings
        $orderId = (int) $orderId;
    
        // Obtener la orden sin `orderable`, ya que no existe en `Order`
        $order = Order::with('items')->where('id', $orderId)->first();
    
        if (!$order) {
            \Log::error('❌ Orden no encontrada', ['orderId' => $orderId]);
            abort(404, 'Orden no encontrada.');
        }
    
        \Log::info('✅ Orden encontrada', ['order' => $order->toArray()]);
    
        // Verificar si hay un OrderItem relacionado
        $orderItem = $order->items->first(); // Obtener el primer OrderItem
    
        if (!$orderItem || !$orderItem->orderable) {
            \Log::error('❌ No se encontró un item con orderable', ['orderId' => $orderId]);
            abort(404, 'No se encontró información del tutor.');
        }
    
        // Obtener el tutor desde el modelo orderable (como SlotBooking)
        $tutor = $orderItem->orderable->tutor ?? null;
    
        
        if (!$tutor) {
            \Log::error('❌ No se encontró un tutor asociado a esta orden', ['orderId' => $orderId]);
            abort(404, 'El tutor no está asociado con esta orden.');
        }
    
        \Log::info('✅ Tutor encontrado', ['tutor' => $tutor->toArray()]);
    
        // Buscar el código QR en `user_payout_methods`
        $qrImage = UserPayoutMethod::where('user_id', $tutor->user_id)
            ->where('payout_method', 'QR')
            ->value('img_qr');
    
        if (!$qrImage) {
            \Log::error('❌ No se encontró un QR para el tutor', ['tutor_id' => $tutor->id]);
            abort(404, 'El tutor no tiene un QR asignado.');
        }
    
        \Log::info('✅ QR encontrado', ['qrImage' => $qrImage]);
    
        // Construir la URL del QR almacenado en `storage`
        $qrImageUrl = asset('storage/' . $qrImage);
    
    
        return view('payment.pay-qr', [
            'order' => $order,
            'tutor' => $tutor,
            'qrImage' => $qrImageUrl
        ]);
    }
    
}
