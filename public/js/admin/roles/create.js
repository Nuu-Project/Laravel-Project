document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('cancelBtn').addEventListener('click', function() {
        // 從按鈕的 data-url 屬性獲取路由
        const url = this.getAttribute('data-url');
        window.location.href = url;
    });
}); 