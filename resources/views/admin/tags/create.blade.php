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
                    <label
                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        for="name[zh_TW]">
                        Name :
                    </label>
                    <input
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        id="name" name="name" placeholder="請輸入新的中文 name" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <label
                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        for="slug[zh_TW]">
                        Slug :
                    </label>
                    <input
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        id="slug" name="slug" placeholder="輸入新的中文 slug" required />
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <label
                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        for="type">
                        Type:
                    </label>
                    <input
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        id="type" name="type" placeholder="輸入新的中文 type" />
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <label
                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        for="order_column">
                        Order Column:
                    </label>
                    <input
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        id="order_column" name="order_column" placeholder="輸入新的Order Column" />
                    <x-input-error :messages="$errors->get('order_column')" class="mt-2" />
                </div>

                <x-button.create-edit>
                    確定新增標籤
                </x-button.create-edit>
            </form>
        </div>
    </main>
</x-template-admin-layout>
