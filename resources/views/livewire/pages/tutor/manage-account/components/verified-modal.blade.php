@props([
    'modalId' => 'verifiedModal',
    'title' => '¡Correo verificado!',
    'message' => 'Tu correo electrónico ha sido verificado exitosamente. ¡Bienvenido a ClassGo!',
    'shareMessage' => '¡Me he registrado en ClassGo!',
    'showSocialShare' => true
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center">
                <p>{{ $message }}</p>
                
                @if($showSocialShare)
                    <hr>
                    <p>¿Quieres compartir tu logro en tus redes sociales?</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                           target="_blank" 
                           class="btn btn-outline-primary" 
                           title="Compartir en Facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareMessage) }}%20{{ urlencode(request()->fullUrl()) }}" 
                           target="_blank" 
                           class="btn btn-outline-info" 
                           title="Compartir en X">
                            <i class="fab fa-x-twitter"></i> X
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($shareMessage) }}%20{{ urlencode(request()->fullUrl()) }}" 
                           target="_blank" 
                           class="btn btn-outline-success" 
                           title="Compartir en WhatsApp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Omitir</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
