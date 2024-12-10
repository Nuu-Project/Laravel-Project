<x-template-layout>


        <div class="flex h-screen bg-gray-100">
            <x-side-bar />

            <!-- 主要內容區 -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- 頂部導航欄 -->
                <x-navbar-admin />

            <!-- 主要內容 -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-6 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium">編輯標籤</h3>

                    <!-- 這裡放置原有的表單內容 -->
                    <form class="mt-8 space-y-6" action="{{ route('admin.tags.update', $tag->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- 用於更新資料 -->

                        <!-- Name 欄位 -->
                        <div class="grid gap-2">
                            <label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="name">
                                Name:
                            </label>
                            <input
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                id="name" name="name" placeholder="請輸入修改後的Name"
                                value="{{ old('name', $tag->name) }}" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Slug 欄位 -->
                        <div class="grid gap-2">
                            <label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="slug">
                                Slug:
                            </label>
                            <input
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                id="slug" name="slug" placeholder="輸入修改後的Slug"
                                value="{{ old('slug', $tag->slug) }}" />
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <!-- Type 欄位 -->
                        <div class="grid gap-2">
                            <label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="type">
                                Type:
                            </label>
                            <input
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                id="type" name="type" placeholder="輸入修改後的Type"
                                value="{{ old('type', $tag->type) }}" />
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Order_column 欄位 -->
                        <div class="grid gap-2">
                            <label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="order_column">
                                Order Column:
                            </label>
                            <input
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                id="order_column" name="order_column" placeholder="輸入修改後的Order Column"
                                value="{{ old('order_column', $tag->order_column) }}" />
                            <x-input-error :messages="$errors->get('order_column')" class="mt-2" />
                        </div>

                            <!-- 提交按鈕 -->
                            <x-button-create-edit>
                                確定修改標籤
                            </x-button-create-edit>
                        </form>
                    </div>
                </main>
            </div>
        </div>
</x-template-layout>

