function switchTab(tabName) {
    // 隱藏所有內容
    document.getElementById('product-content').classList.add('hidden');
    document.getElementById('message-content').classList.add('hidden');
    document.getElementById('product-search').classList.add('hidden');
    document.getElementById('message-search').classList.add('hidden');

    // 重設所有標籤樣式
    document.getElementById('product-tab').classList.remove('active');
    document.getElementById('message-tab').classList.remove('active');
    document.getElementById('product-tab').classList.add('bg-gray-100');
    document.getElementById('message-tab').classList.add('bg-gray-100');

    // 顯示選中的內容並設置標籤樣式
    document.getElementById(tabName + '-content').classList.remove('hidden');
    document.getElementById(tabName + '-search').classList.remove('hidden');
    document.getElementById(tabName + '-tab').classList.add('active');
    document.getElementById(tabName + '-tab').classList.remove('bg-gray-100');
    document.getElementById(tabName + '-tab').classList.add('bg-white');
}
