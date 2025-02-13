<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>新增標籤</x-h.h3>

            <!-- 這裡放置原有的表單內容 -->
            <form id="tagForm" class="mt-8 space-y-6" action="{{ route('admin.tags.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <x-div.grid>
                    <x-tags for="name[zh_TW]">Name:</x-tags>
                    <x-input.tags id="name" name="name" placeholder="輸入新的中文 name" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="slug[zh_TW]">Slug:</x-tags>
                    <x-input.tags id="slug" name="slug" placeholder="輸入新的中文 slug" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="type">Type:</x-tags>
                    <x-input.tags id="type" name="type" placeholder="輸入新的中文 type" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="order_column">Order Column:</x-tags>
                    <x-input.tags id="order_column" name="order_column" placeholder="輸入新的Order Column" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('order_column')" class="mt-2" />
                </x-div.grid>

                <x-button.create-edit>
                    確定新增標籤
                </x-button.create-edit>
            </form>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
