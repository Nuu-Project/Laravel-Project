function previewImage(input, number) {
    const preview = document.getElementById('preview' + number);
    const placeholder = document.getElementById('placeholder' + number);
    const deleteButton = document.getElementById('deleteButton' + number);
    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.querySelector('img').src = reader.result;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
        deleteButton.classList.remove('hidden');
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.querySelector('img').src = '#';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
        deleteButton.classList.add('hidden');
    }
}

// 頁面加載時初始化預覽
document.addEventListener('DOMContentLoaded', function () {
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById('preview' + i);
        const placeholder = document.getElementById('placeholder' + i);
        const deleteButton = document.getElementById('deleteButton' + i);
        if (preview.querySelector('img')?.src && preview.querySelector('img').src !== window.location.href + '#') {
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            deleteButton.classList.remove('hidden');
        }
    }
});

function deleteImage(productId, imageId, index) {
    if (imageId === null) {
        // 如果是新上傳的圖片，直接從 UI 中移除
        removeImage(index);
    } else {
        // 如果是已存在的圖片，發送 AJAX 請求刪除
        fetch(`/user/products/${productId}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    removeImage(index);
                } else {
                    console.error('刪除圖片失敗：' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

function removeImage(index) {
    const preview = document.getElementById(`preview${index}`);
    const placeholder = document.getElementById(`placeholder${index}`);
    const imageInput = document.getElementById(`image${index}`);
    const deleteButton = document.getElementById(`deleteButton${index}`);

    // 重置預覽圖
    preview.querySelector('img').src = '#';
    preview.classList.add('hidden');

    // 顯示佔位符
    placeholder.classList.remove('hidden');

    // 清空文件輸入
    imageInput.value = '';

    // 隱藏刪除按鈕
    deleteButton.classList.add('hidden');

    // 清除隱藏的 image_id 輸入
    const imageIdInput = document.querySelector(`input[name="image_ids[]"]:nth-of-type(${index + 1})`);
    if (imageIdInput) {
        imageIdInput.value = '';
    }
}
