@php
    use App\Enums\Tagtype;
@endphp

<x-template-user-layout>
    <script src="{{ asset('js/product-uploader.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <x-div.grid>
                <x-div.grid>
                    <x-h.h1-middle>新增刊登商品</x-h.h1-middle>
                    <x-p.text-muted>請依照下順序進行填寫，照片上傳張數最多五張。</x-p.text-muted>
                    <x-p.text-muted>圖片最左邊將會是商品首圖。</x-p.text-muted>
                </x-div.grid>

                @if ($errors->any())
                    <x-div.red role="alert">
                        <strong>驗證錯誤！</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-div.red>
                @endif

                <form class="grid gap-6" action="{{ route('user.products.store') }}" method="POST"
                    enctype="multipart/form-data" id="productForm">
                    @csrf
                    <input type="hidden" name="imageOrder" id="imageOrder">
                    <x-div.grid>
                        <x-label.form for="name">
                            書名
                        </x-label.form>
                        <x-input.tags id="name" name="name" placeholder="請輸入書名" maxlength="50"
                            value="{{ old('name') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form for="price">
                            價格 (不可重複修改)
                        </x-label.form>
                        <x-input.tags id="price" name="price" placeholder="輸入價格" type="number"
                            value="{{ old('price') }}" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form-sm for="grade">年級</x-label.form-sm>
                        <x-select.form id="grade" name="grade">
                            <option value="">選擇適用的年級...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Grade->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('grade') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form-sm for="semester">學期</x-label.form-sm>
                        <x-select.form id="semester" name="semester">
                            <option value="">選擇學期...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Semester->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('semester') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form-sm for="subject">科目</x-label.form-sm>
                        <x-select.form id="subject" name="subject">
                            <option value="">選擇科目...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Subject->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('subject') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form-sm for="category">課程類別</x-label.form-sm>
                        <x-select.form id="category" name="category">
                            <option value="">選擇課程類別...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Category->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('category') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <div class="space-y-2">
                            <x-input-label for="description" :value="__('商品描述')" />
                            <x-input.textarea id="description" name="description" placeholder="請輸入商品描述" rows="4"
                                maxlength="1000" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form for="image">
                            上傳圖片
                        </x-label.form>
                        <div id="imageContainer"
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @for ($i = 0; $i < 5; $i++)
                                <x-product.image-uploader :index="$i" />
                            @endfor
                        </div>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                    </x-div.grid>
                    <x-button.submit>
                        刊登商品
                    </x-button.submit>
                </form>
            </x-div.grid>
        </x-div.container>
    </x-flex-container>
    </div>
    </div>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</x-template-user-layout>
