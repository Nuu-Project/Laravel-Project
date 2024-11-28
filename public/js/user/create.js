// 圖片預覽功能
function previewImage(input, number) {
    const preview = document.getElementById('preview' + number);
    const placeholder = document.getElementById('placeholder' + number);
    const deleteButton = document.getElementById('deleteButton' + number);

    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function() {
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

// 移除圖片功能
function removeImage(number) {
    const input = document.getElementById('image' + number);
    const preview = document.getElementById('preview' + number);
    const placeholder = document.getElementById('placeholder' + number);
    const deleteButton = document.getElementById('deleteButton' + number);

    input.value = '';
    preview.querySelector('img').src = '#';
    preview.classList.add('hidden');
    placeholder.classList.remove('hidden');
    deleteButton.classList.add('hidden');
}

// 表單驗證和提交處理
document.addEventListener('DOMContentLoaded', function() {
    // 初始化所有圖片預覽
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const placeholder = document.getElementById(`placeholder${i}`);
        const deleteButton = document.getElementById(`deleteButton${i}`);

        if (preview.querySelector('img')?.src &&
            preview.querySelector('img').src !== window.location.href + '#') {
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            deleteButton.classList.remove('hidden');
        }
    }

    // 表單提交處理
    document.getElementById('productForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        // 1. 驗證表單
        const requiredFields = ['name', 'price', 'description'];
        const requiredSelects = ['grade', 'semester', 'category'];
        let allFieldsFilled = true;

        // 檢查一般輸入欄位
        for (const field of requiredFields) {
            if (!document.getElementById(field).value) {
                allFieldsFilled = false;
                break;
            }
        }

        // 檢查下拉選單
        for (const select of requiredSelects) {
            if (!document.getElementById(select).value) {
                allFieldsFilled = false;
                break;
            }
        }

        // 檢查第一張圖片
        const firstImage = document.getElementById('image0').files.length > 0;

        if (!allFieldsFilled) {
            alert('請填寫所有必填欄位！');
            return;
        }

        if (!firstImage) {
            alert('請至少上傳一張商品圖片！');
            return;
        }

        try {
            // 2. 禁用提交按鈕，防止重複提交
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;

            // 3. 提交表單
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            // 4. 處理回應
            if (response.ok) {
                // 5. 成功後才顯示成功訊息
                alert('商品已成功刊登！');
                // 6. 重定向到商品列表頁面
                window.location.href = '/user/products';
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || '刊登失敗，請稍後再試');
            }
        } catch (error) {
            // 處理錯誤
            alert(error.message);
            // 重新啟用提交按鈕
            submitButton.disabled = false;
        }
    });

    // 價格輸入限制：只能輸入正整數
    document.getElementById('price').addEventListener('input', function(e) {
        let value = this.value;
        // 移除非數字字符
        value = value.replace(/[^0-9]/g, '');
        // 移除前導零
        value = value.replace(/^0+/, '');
        // 如果是空字符串，設為0
        if (value === '') {
            value = '0';
        }
        // 限制最大值
        if (parseInt(value) > 99999) {
            value = '99999';
        }
        this.value = value;
    });

    // 拖放功能
    const dropZones = document.querySelectorAll('label[for^="image"]');
    dropZones.forEach(zone => {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-500');
        });

        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500');
        });

        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500');

            const fileInput = document.getElementById(this.getAttribute('for'));
            const files = e.dataTransfer.files;

            if (files.length > 0) {
                fileInput.files = files;
                const number = this.getAttribute('for').replace('image', '');
                previewImage(fileInput, number);
            }
        });
    });
});
