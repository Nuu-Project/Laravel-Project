<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>新增檢舉類別</x-h.h3>

            <!-- 表單內容 -->
            <form id="reportCategoryForm" class="mt-8 space-y-6">

                <x-div.grid>
                    <x-tags for="name">Name:</x-tags>
                    <x-input.tags id="name" name="name" placeholder="輸入新的中文 name" required>
                    </x-input.tags>
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="slug[zh_TW]">Slug:</x-tags>
                    <x-input.tags id="slug" name="slug" placeholder="輸入新的中文 slug" required>
                    </x-input.tags>
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="type">type:</x-tags>
                    <x-input.tags id="type" name="type" placeholder="輸入新的中文 type" required>
                    </x-input.tags>
                </x-div.grid>

                <x-div.grid>
                    <x-tags for="order_column">Order_Column:</x-tags>
                    <x-input.tags id="order_column" name="order_column" placeholder="輸入新的Order Column" required>
                    </x-input.tags>
                </x-div.grid>

                <x-button.create-edit>
                    確定新增檢舉類別
                </x-button.create-edit>
            </form>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
