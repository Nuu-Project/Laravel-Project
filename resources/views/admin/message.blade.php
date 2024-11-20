<x-head-layout />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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
                                <table class="min-w-full divide-y divide-gray-200">
                                    <x-message-table />
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($chirps as $chirp)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img class="h-10 w-10 rounded-full"
                                                                src="{{ asset('images/account.png') }}"
                                                                alt="{{ $chirp->user->name }}">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $chirp->user->name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $chirp->product->name ?? 'No associated product' }}</td>
                                                <td
                                                    class="px-6 py-4 text-sm text-gray-500 max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg">
                                                    <div class="message-container">
                                                        <span class="message-content">{{ $chirp->message }}</span>
                                                        @if (mb_strlen($chirp->message) > 15)
                                                            <button
                                                                class="expand-btn ml-2 text-blue-500 hover:text-blue-700">
                                                                <svg class="w-4 h-4 inline-block transform transition-transform duration-200"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $chirp->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if ($chirp->product)
                                                        <form
                                                            action="{{ route('products.chirps.destroy', ['product' => $chirp->product->id, 'chirp' => $chirp->id]) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-600 hover:text-red-900"
                                                                onclick="return confirm('{{ __('確定要刪除這條評論嗎？') }}')">Delete</button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400">No action</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-reviews');
            const reviewsTable = document.getElementById('reviews-table');
            const noResults = document.getElementById('no-results');
            const reviewsList = document.querySelector('.overflow-x-auto tbody');
            const rows = reviewsList.querySelectorAll('tr');

            // 頁面載入時直接顯示表格
            reviewsTable.style.display = 'block';
            noResults.classList.add('hidden');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let hasResults = false;

                rows.forEach(row => {
                    const userName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();

                    if (userName.includes(searchTerm)) {
                        row.style.display = '';
                        hasResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (hasResults) {
                    reviewsTable.style.display = 'block';
                    noResults.classList.add('hidden');
                } else {
                    reviewsTable.style.display = 'block';
                    noResults.classList.remove('hidden');
                }
            });

            // 更新留言展開/收合功能
            function truncateText(text, length) {
                const characters = Array.from(text);
                if (characters.length <= length) return text;

                let count = 0;
                let result = '';

                for (let char of characters) {
                    const charWidth = /[a-zA-Z0-9]/.test(char) ? 1 : 2;
                    if (count + charWidth > length) break;
                    count += charWidth;
                    result += char;
                }

                return result;
            }

            document.querySelectorAll('.message-container').forEach(container => {
                const content = container.querySelector('.message-content');
                const expandBtn = container.querySelector('.expand-btn');
                const fullText = content.textContent;

                if (expandBtn) {
                    content.textContent = truncateText(fullText, 15);

                    expandBtn.addEventListener('click', function(e) {
                        e.preventDefault(); // 防止事件冒泡
                        const isExpanded = this.classList.contains('expanded');

                        if (isExpanded) {
                            content.textContent = truncateText(fullText, 15);
                            content.classList.remove('expanded');
                            this.querySelector('svg').classList.remove('rotate-180');
                        } else {
                            content.textContent = fullText;
                            content.classList.add('expanded');
                            this.querySelector('svg').classList.add('rotate-180');
                        }

                        this.classList.toggle('expanded');
                    });
                }
            });

            // 監聽視窗大小變化，確保響應式效果
            window.addEventListener('resize', function() {
                document.querySelectorAll('.message-content').forEach(content => {
                    if (!content.classList.contains('expanded')) {
                        const fullText = content.getAttribute('data-full-text') || content
                            .textContent;
                        content.textContent = truncateText(fullText, 15);
                    }
                });
            });
        });
    </script>
</x-head-layout>
