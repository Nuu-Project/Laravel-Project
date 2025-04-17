@props(['product'])

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