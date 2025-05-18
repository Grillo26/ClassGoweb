<div>
   
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.3);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="color:red;">¡MODAL ABIERTO!</h2>
                    <h5 class="modal-title">Cambiar estado de la tutoría</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Nuevo estado</label>
                        <select wire:model="status" id="status" class="form-control">
                            @foreach($statusOptions as $key => $label)
                                <option value="{{ $key }}">{{ ucfirst($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="close">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="updateStatus">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div> 