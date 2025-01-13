<x-template-admin-layout>

    <!-- 主要內容 -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-6 py-8">
            <x-h.h3>新增標籤</x-h.h3>

            <!-- 這裡放置原有的表單內容 -->
            <form id="tagForm" class="mt-8 space-y-6" action="{{ route('admin.tags.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="grid gap-2">
                    <x-label.tags for="name[zh_TW]">Name:</x-label.tags>
                    <x-input.tags id="name" name="name" placeholder="輸入新的中文 name" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <x-label.tags for="slug[zh_TW]">Slug:</x-label.tags>
                    <x-input.tags id="slug" name="slug" placeholder="輸入新的中文 slug" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <x-label.tags for="type">Type:</x-label.tags>
                    <x-input.tags id="type" name="type" placeholder="輸入新的中文 type" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <x-label.tags for="order_column">Order Column:</x-label.tags>
                    <x-input.tags id="order_column" name="order_column" placeholder="輸入新的Order Column" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('order_column')" class="mt-2" />
                </div>

                <x-button.create-edit>
                    確定新增標籤
                </x-button.create-edit>
            </form>
        </div>
    </main>
</x-template-admin-layout>
