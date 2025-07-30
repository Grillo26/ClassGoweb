<div wire:ignore.self class="modal fade" id="modalEstadoTutoria" tabindex="-1"
        aria-labelledby="modalEstadoTutoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEstadoTutoriaLabel">Cambiar estado de la tutoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <select wire:model="modalStatus" class="form-control">
                        <option value="aceptado">Aceptado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="no_completado">No completado</option>
                        <option value="rechazado">Observado</option>
                        <option value="completado">Completado</option>
                        <option value="cursando">Cursando</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="updateStatus">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>