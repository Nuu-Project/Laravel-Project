@props(['product'])
@php
    use App\Enums\Tagtype;
@endphp

<div class="rounded-lg border bg-white text-card-foreground shadow-sm p-6" data-v0-t="card">
    <div class="space-y-2">
        <h4 class="font-semibold text-xl">商品名稱:{{ $product->name }}</h4>
        <div>
            <x-h.h1-small>用戶名稱:{{ $product->user->name }}</x-h.h1-small>
        </div>
        <div>
            <x-h.h1-small>
                目前狀態: {{ $product->status->name() }}
            </x-h.h1-small>
        </div>
    </div>
    <div class="mt-4">
        <div class="text-2xl font-bold">${{ $product->price }}</div>
        <h1 class="font-semibold text-sm mt-2">上架時間:
            {{ $product->updated_at->format('Y/m/d') }}</h1>
        <p class="font-semibold text-sm mt-2">{{ $product->description }}</p>
        <div class="mt-4">
            @if ($product->media->isNotEmpty())
                @php
                    $media = $product->getFirstMedia('images');
                @endphp
                @if ($media)
                    <img src="{{ $media->getUrl() }}" alt="這是圖片" width="1200" height="900"
                        style="aspect-ratio: 900 / 1200; object-fit: cover;" class="w-full rounded-md object-cover" />
                @else
                    <div>沒圖片</div>
                @endif
            @else
                <div>沒有圖片</div>
            @endif
        </div>
        <div class="flex items-center justify-between mb-8">
            <x-h.h6>年級 :
                <x-span.font-semibold>
                    @php
                        $gradeTag = $product->tags->firstWhere('type', Tagtype::Grade->value);
                        $semesterTag = $product->tags->firstWhere('type', Tagtype::Semester->value);
                    @endphp
                    {{ $gradeTag ? $gradeTag->name : '無' }}
                    {{ $semesterTag ? $semesterTag->name : '學期:無' }}
                </x-span.font-semibold>
            </x-h.h6>
            <x-h.h6>課程 :
                <x-span.font-semibold>
                    @php
                        $categoryTag = $product->tags->firstWhere('type', Tagtype::Category->value);
                    @endphp
                    {{ $categoryTag ? $categoryTag->name : '無' }}
                </x-span.font-semibold>
            </x-h.h6>
        </div>
    </div>
    <div class="flex justify-center space-x-4 mt-6">
        <form action="{{ route('user.products.inactive', $product->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <x-button.status :status="$product->status" />
        </form>
        <a href="{{ route('user.products.edit', $product->id) }}">
            <x-button.blue-short>
                編輯
            </x-button.blue-short>
        </a>
        <form action="{{ route('user.products.destroy', $product->id) }}" method="POST"
            onsubmit="return confirm('確定要刪除這個商品嗎？');">
            @csrf
            @method('DELETE')
            <x-button.red-short>
                刪除
            </x-button.red-short>
        </form>
    </div>
</div>
