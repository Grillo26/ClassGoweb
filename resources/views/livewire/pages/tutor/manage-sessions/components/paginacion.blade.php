<div class="am-pagination"
    style="margin-top: 20px; display: flex; justify-content: center; align-items: center; gap: 10px;">
    <!-- Botón Anterior -->
    <button wire:click="previousPage" @if($currentPage==1) disabled @endif class="am-btn"
        style="padding: 8px 15px; @if($currentPage == 1) opacity: 0.5; @endif">
        <i class="am-icon-arrow-left"></i>
        {{ __('general.previous') }}
    </button>

    <!-- Números de página -->
    <div class="am-pagination-numbers" style="display: flex; gap: 5px;">
        @for($i = 1; $i <= $totalPages; $i++) <button wire:click="goToPage({{ $i }})" class="am-btn"
            style="padding: 8px 15px; @if($currentPage == $i) background-color: #004558; color: white; @endif">
            {{ $i }}
            </button>
            @endfor
    </div>

    <!-- Botón Siguiente -->
    <button wire:click="nextPage" @if($currentPage==$totalPages) disabled @endif class="am-btn"
        style="padding: 8px 15px; @if($currentPage == $totalPages) opacity: 0.5; @endif">
        {{ __('general.next') }}
        <i class="am-icon-arrow-right"></i>
    </button>
</div>