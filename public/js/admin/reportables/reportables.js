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

    // 在點擊位置添加貓爪印特效
    addPawPrint(event, tabName);
}

// 添加貓爪印特效
function addPawPrint(e, tabName) {
    const pawPrints = ['🐾', '🐾', '🐾'];

    for (let i = 0; i < 3; i++) {
        setTimeout(() => {
            const paw = document.createElement('span');
            paw.innerText = pawPrints[Math.floor(Math.random() * pawPrints.length)];
            paw.classList.add('paw-print');

            // 為商品檢舉詳情添加灰色布偶貓爪印
            if (tabName === 'product') {
                paw.classList.add('ragdoll');
            }

            // 計算位置，在點擊附近隨機位置顯示
            const x = e.clientX + (Math.random() * 40 - 20);
            const y = e.clientY + (Math.random() * 40 - 20);

            paw.style.left = x + 'px';
            paw.style.top = y + 'px';

            document.body.appendChild(paw);

            // 動畫結束後移除元素
            setTimeout(() => {
                paw.remove();
            }, 800);
        }, i * 100);
    }
}

// 為所有貓咪標籤添加鼠標懸停特效
document.addEventListener('DOMContentLoaded', function() {
    const catTabs = document.querySelectorAll('.cat-tab');
    catTabs.forEach(tab => {
        tab.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.cat-icon');
            if (icon) {
                icon.style.transform = 'rotate(15deg)';
            }
        });

        tab.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.cat-icon');
            if (icon && !this.classList.contains('active')) {
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });
});
