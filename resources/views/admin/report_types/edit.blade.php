<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>編輯檢舉類型</x-h.h3>

            <!-- 表單內容 -->
            <form class="mt-8 space-y-6" action="{{ route('admin.report_types.update', $reportType->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <x-div.grid>
                    <x-tags for="name">Name:</x-tags>
                    <x-input.tags id="name" name="name" placeholder="請輸入修改後的Name"
                        value="{{ old('name', $reportType->name) }}" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="type">type:</x-tags>
                    <x-input.tags id="type" name="type" placeholder="輸入修改後的Type"
                        value="{{ old('type', $reportType->type) }}" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="order_column">Order_Column:</x-tags>
                    <x-input.tags id="order_column" name="order_column" placeholder="輸入修改後的Order Column"
                        value="{{ old('order_column', $reportType->order_column) }}" required>
                    </x-input.tags>
                    <x-input-error :messages="$errors->get('order_column')" class="mt-2" />
                </x-div.grid>

                <x-button.create-edit>
                    確定修改檢舉類型
                </x-button.create-edit>
            </form>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
