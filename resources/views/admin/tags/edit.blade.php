<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-main.flex-container>
        <div class="container mx-auto px-6 py-8">
            <x-h.h3>編輯標籤</x-h.h3>

            <!-- 這裡放置原有的表單內容 -->
            <form class="mt-8 space-y-6" action="{{ route('admin.tags.update', $tag->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- 用於更新資料 -->

                <!-- Name 欄位 -->
                <div class="grid gap-2">
                    <x-label.tags for="name">Name:</x-label.tags>
                    <x-input.tags id="name" name="name" placeholder="請輸入修改後的Name"
                        value="{{ old('name', $tag->name) }}" required></x-input.tags>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Slug 欄位 -->
                <div class="grid gap-2">
                    <x-label.tags for="slug">Slug:</x-label.tags>
                    <x-input.tags id="slug" name="slug" placeholder="輸入修改後的Slug"
                        value="{{ old('slug', $tag->slug) }}" required></x-input.tags>
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <!-- Type 欄位 -->
                <div class="grid gap-2">
                    <x-label.tags for="type">Type:</x-label.tags>
                    <x-input.tags id="type" name="type" placeholder="輸入修改後的Type"
                        value="{{ old('type', $tag->type) }}" required></x-input.tags>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <!-- Order_column 欄位 -->
                <div class="grid gap-2">
                    <x-label.tags for="order_column">Order Column:</x-label.tags>
                    <x-input.tags id="order_column" name="order_column" placeholder="輸入修改後的Order Column"
                        value="{{ old('order_column', $tag->order_column) }}" required></x-input.tags>
                    <x-input-error :messages="$errors->get('order_column')" class="mt-2" />
                </div>

                <!-- 提交按鈕 -->
                <x-button.create-edit>
                    確定修改標籤
                </x-button.create-edit>
            </form>
        </div>
    </x-main.flex-container>
</x-template-admin-layout>
