<!DOCTYPE html>
<html lang="en">

<head>
    <x-head />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-body">
    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
    <x-admin-link />

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <x-navbar-admin />

            <!-- 主要內容 -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium mb-6">檢舉詳情</h3>
                    
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">商品名稱</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">檢舉原因</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">自定義原因</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">檢舉日期</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">範例商品名稱</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">不當內容</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">名稱極度不雅</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-05-20</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">範例商品名稱</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">侵犯版權</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">名稱觸犯到版權了</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-05-21</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">範例商品名稱</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">虛假資訊</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">商品描述與實體不符</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-05-22</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
