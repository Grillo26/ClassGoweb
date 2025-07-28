
<div wire:ignore.self class="modal fade" id="modalPagoTutor" tabindex="-1"
    aria-labelledby="modalPagoTutorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPagoTutorLabel">Actualizar pago del tutor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <label for="paymentStatus">Estado:</label>
                <select wire:model="modalPaymentStatus" class="form-control" id="paymentStatus">
                    <option value="1">Pendiente</option>
                    <option value="2">Pagado</option>
                    <option value="3">Observado</option>
                    <option value="4">Cancelado</option>
                </select>
                <label for="paymentMethod" class="mt-3">Método de pago:</label>
                <select wire:model="modalPaymentMethod" class="form-control" id="paymentMethod">
                    <option value="qr">QR</option>
                    <option value="transferencia">Transferencia</option>
                </select>
                <label for="paymentMessage" class="mt-3">Mensaje:</label>
                <textarea wire:model="modalPaymentMessage" class="form-control" id="paymentMessage"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" wire:click="updatePayment">Guardar</button>
            </div>
        </div>
    </div>
</div>

