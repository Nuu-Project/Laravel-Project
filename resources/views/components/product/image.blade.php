@props(['product'])

<div class="mt-4">
    @if ($product->media->isNotEmpty())
        @php
            $media = $product->getFirstMedia('images');
        @endphp
        @if ($media)
            <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}圖片" 
                width="1200" height="900" 
                style="aspect-ratio: 900 / 1200; object-fit: cover;"
                class="w-full rounded-md object-cover" />
        @else
            <div>沒圖片</div>
        @endif
    @else
        <div>沒有圖片</div>
    @endif
</div> 