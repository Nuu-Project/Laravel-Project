@php
    use App\Enums\ProductStatus;
@endphp

<x-template-layout>



    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-side-bar />

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <x-navbar-admin />

            <!-- 主要內容 -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {{-- 添加提示訊息顯示 --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <h3 class="text-gray-700 text-3xl font-medium mb-6">商品管理</h3>

                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <x-check-table />
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $product->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->created_at->format('Y/m/d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->updated_at->format('Y/m/d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->reportables_count }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium flex flex-row items-center space-x-2">
                                                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                                    <button
                                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">前往</button>
                                                </a>
                                                <form
                                                    action="{{ route('admin.products.demote', ['product' => $product->id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button
                                                        class="px-3 py-1 {{ $product->status === ProductStatus::Active ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded">
                                                        {{ $product->status === ProductStatus::Active ? '下架' : '上架' }}
                                                    </button>
                                                </form>
                                                <a
                                                    href="{{ route('admin.reports.index', ['filter[reportable_id]' => $product->id]) }}"><button
                                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 report-button">檢舉詳情</button></a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->status->label() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            var reportButton = document.getElementById('reportbutton');
            if (reportButton) {
                reportButton.addEventListener('click', function() {
                    // 假設這是從資料庫獲取的檢舉資料
                    var reportData = [{
                            reason: '不當內容',
                            customreason: '名稱極度不雅',
                            date: '2023-05-20'
                        },
                        {
                            reason: '侵犯版權',
                            customreason: '名稱觸犯到版權了',
                            date: '2023-05-21'
                        },
                        {
                            reason: '虛假資訊',
                            customreason: '商品描述與實體不符',
                            date: '2023-05-22'
                        }
                    ];

                    var reportContent = reportData.map(function(report) {
                        return `<div class="mb-2 p-2 bg-gray-100 rounded">
                                <p class="font-bold">${report.reason}</p>
                                <p class="font-bold">${report.customreason}</p>
                                <p class="text-sm text-gray-600">檢舉日期：${report.date}</p>
                            </div>`;
                    }).join('');

                    Swal.fire({
                        title: '檢舉詳情',
                        html: `<div class="max-h-60 overflow-y-auto">
                              ${reportContent}
                           </div>`,
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: '確定',
                        customClass: {
                            container: 'swal-wide',
                            popup: 'swal-tall'
                        }
                    });
                });
            }
        });
    </script>
</x-template-layout>
