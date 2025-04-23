<x-template-admin-layout>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>編輯標籤</x-h.h3>

            <form class="mt-8 space-y-6" action="{{ route('admin.tags.update', $tag->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <x-div.grid>
                    <x-tags for="name">Name:</x-tags>
                    <x-input.tags id="name" name="name" placeholder="請輸入修改後的Name"
                        value="{{ old('name', $tag->name) }}" required></x-input.tags>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="slug">Slug:</x-tags>
                    <x-input.tags id="slug" name="slug" placeholder="輸入修改後的Slug"
                        value="{{ old('slug', $tag->slug) }}" required></x-input.tags>
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="type">Type:</x-tags>
                    <x-input.tags id="type" name="type" placeholder="輸入修改後的Type"
                        value="{{ old('type', $tag->type) }}" required></x-input.tags>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="order_column">Order Column:</x-tags>
                    <x-input.tags id="order_column" name="order_column" placeholder="輸入修改後的Order Column"
                        value="{{ old('order_column', $tag->order_column) }}" required></x-input.tags>
                    <x-input-error :messages="$errors->get('order_column')" class="mt-2" />
                </x-div.grid>

                <x-button.submit>
                    確定修改標籤
                </x-button.submit>
            </form>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
