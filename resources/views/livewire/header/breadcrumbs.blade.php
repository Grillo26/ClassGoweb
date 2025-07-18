@unless($breadcrumbs->isEmpty())
    <ol class="am-breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)

            @if(!is_null($breadcrumb->url) && !$loop->last)
                <li><a style="color: black;font-size: 20px" href="{{ $breadcrumb->url }}" wire:navigate.remove>{{ $breadcrumb->title }}</a></li>
                <li>
                    <em style="color: black; font-size: 20px;">/</em>
                </li>
            @else
                <li class="active" ><span style="color:#219EBC ;font-size: 20px">{{ $breadcrumb->title }}</span></li>
            @endif

        @endforeach
    </ol>
@endunless
