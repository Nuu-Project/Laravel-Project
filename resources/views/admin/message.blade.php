<x-template-layout>
    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-side-bar />

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <x-navbar-admin />

            <!-- 主要內容 -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium mb-6">留言管理</h3>

                    <!-- Reviews 搜索部分 -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h2 id="reviews-title" class="text-xl font-semibold text-gray-900">Reviews</h2>
                            <div>
                                <label for="search-reviews" class="sr-only">搜索留言</label>
                                <input type="text" id="search-reviews"
                                    class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm"
                                    placeholder="請輸入用戶名稱...">
                            </div>
                        </div>

                        <!-- Reviews 列表 -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg" id="reviews-table">
                            <div class="overflow-x-auto">
                                <x-table-message :chirps="$chirps->items()">
                                </x-table-message>
                            </div>

                            <!-- 分頁導航 -->
                            <div class="px-6 py-4 border-t border-gray-200">
                                {{ $chirps->links() }}
                            </div>
                        </div>

                        <!-- 無搜尋結果時顯示 -->
                        <div id="no-results" class="text-center py-4 hidden">
                            <p class="text-gray-500">沒有找到相關用戶的評論</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-template-layout>
