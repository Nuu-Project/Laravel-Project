// 儲存已處理的圖片路徑
let processedImagePaths = new Array(5).fill(null);

// 為每個上傳元素創建Alpine.js元件
for (let i = 0; i < 5; i++) {
    window[`imageUploader${i}`] = function () {
        return {
            uploading: false,  // 是否正在上傳
            processing: false, // 是否正在處理
            progress: 0,       // 上傳進度
            error: false,      // 是否有錯誤
            errorMessage: '',  // 錯誤訊息
            success: false,    // 是否上傳成功
            imageIndex: i,     // 圖片索引

            // 開始上傳流程
            startUpload(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.reset();
                this.uploading = true;
                this.processing = true;

                // 檢查檔案類型
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    this.error = true;
                    this.errorMessage = '不支援的檔案格式';
                    this.uploading = false;
                    return;
                }

                // 檢查檔案大小 (不超過2MB)
                if (file.size > 2 * 1024 * 1024) {
                    this.error = true;
                    this.errorMessage = '處理失敗! 檔案大小不能超過2MB';
                    this.uploading = false;
                    return;
                }

                this.uploadFile(file);
            },

            // 執行實際上傳
            uploadFile(file) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/products/process-image');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                // 監聽上傳進度
                xhr.upload.addEventListener('progress', (event) => {
                    if (event.lengthComputable) {
                        this.progress = Math.round((event.loaded / event.total) * 100);
                    }
                });

                // 處理響應
                xhr.onload = () => {
                    if (xhr.status === 200) {
                        try {
                            const result = JSON.parse(xhr.responseText);

                            if (result.success) {
                                this.success = true;
                                this.processing = false;
                                processedImagePaths[this.imageIndex] = result.path;

                                // 顯示預覽圖片
                                this.showPreview(file);

                                // 延遲後隱藏進度條
                                setTimeout(() => {
                                    this.uploading = false;
                                }, 1000);
                            } else {
                                this.handleError('處理圖片時發生錯誤');
                            }
                        } catch (e) {
                            this.handleError('解析伺服器回應失敗');
                        }
                    } else if (xhr.status === 401 || xhr.status === 419) {
                        this.handleError('身份驗證已過期，請重新整理頁面後再試');
                    } else {
                        this.handleError(`上傳失敗 (${xhr.status})`);
                    }
                };

                xhr.onerror = () => {
                    this.handleError('網路錯誤，請檢查網路連線');
                };

                xhr.onabort = () => {
                    this.handleError('上傳已取消');
                };

                xhr.ontimeout = () => {
                    this.handleError('上傳超時，請稍後再試');
                };

                // 發送請求
                xhr.send(formData);

                // 建立進度模擬
                this.simulateProgressIfNeeded();
            },

            // 當後端不回報進度時，模擬進度
            simulateProgressIfNeeded() {
                let lastProgress = 0;
                const interval = setInterval(() => {
                    if (!this.uploading || this.error || this.progress >= 100) {
                        clearInterval(interval);
                        return;
                    }

                    // 如果進度停滯，則緩慢增加
                    if (this.progress === lastProgress) {
                        // 進度越高，增加越慢
                        const increment = this.progress < 30 ? 5 :
                            this.progress < 70 ? 3 :
                                this.progress < 90 ? 1 : 0.5;

                        if (this.progress < 95) {
                            this.progress = Math.min(95, this.progress + increment);
                        }
                    }

                    lastProgress = this.progress;
                }, 500);
            },

            // 顯示圖片預覽
            showPreview(file) {
                const preview = document.getElementById(`preview${this.imageIndex}`);
                const placeholder = document.getElementById(`placeholder${this.imageIndex}`);

                const reader = new FileReader();
                reader.onloadend = function () {
                    preview.querySelector('img').src = reader.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            },

            // 處理錯誤
            handleError(message) {
                this.error = true;
                this.errorMessage = message;
                this.success = false;
                this.processing = false;

                // 延遲後隱藏進度條
                setTimeout(() => {
                    this.uploading = false;
                }, 1500);
            },

            // 移除圖片
            removeImage() {
                const preview = document.getElementById(`preview${this.imageIndex}`);
                const placeholder = document.getElementById(`placeholder${this.imageIndex}`);
                const imageInput = document.getElementById(`image${this.imageIndex}`);

                // 重置UI
                preview.querySelector('img').src = '#';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                imageInput.value = '';

                // 清除已處理的圖片路徑
                processedImagePaths[this.imageIndex] = null;

                // 重置狀態
                this.reset();

                // 更新圖片順序
                updatePositions();
            },

            // 重置所有狀態
            reset() {
                this.uploading = false;
                this.processing = false;
                this.progress = 0;
                this.error = false;
                this.errorMessage = '';
                this.success = false;
            }
        }
    }
}

// 監聽表單提交
document.addEventListener('DOMContentLoaded', function () {
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function (e) {
            // 預防表單提交
            e.preventDefault();

            // 清除之前可能存在的錯誤提示區域
            const oldErrorContainer = document.querySelector('.frontend-error-container');
            if (oldErrorContainer) {
                oldErrorContainer.remove();
            }

            // 收集所有的驗證錯誤
            const errors = [];

            // 驗證所有必填欄位
            const requiredFields = {
                'name': '書名不能留空',
                'price': '價格不能留空',
                'description': '描述不能留空',
                'grade-input': '年級不能留空',
                'semester-input': '學期不能留空',
                'subject-input': '科目不能留空',
                'category-input': '課程類別不能留空'
            };

            // 檢查每個必填欄位
            Object.entries(requiredFields).forEach(([fieldId, errorMessage]) => {
                const field = document.getElementById(fieldId);
                if (!field || !field.value.trim()) {
                    errors.push(errorMessage);
                }
            });

            // 檢查圖片
            const validPaths = processedImagePaths.filter(path => path !== null);
            if (validPaths.length === 0) {
                errors.push('請至少上傳一張商品圖片');
            }

            // 如果有錯誤，顯示錯誤訊息
            if (errors.length > 0) {
                // 創建錯誤容器
                const errorContainer = document.createElement('div');
                errorContainer.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 frontend-error-container';
                errorContainer.setAttribute('role', 'alert');

                // 添加錯誤標題
                const errorTitle = document.createElement('strong');
                errorTitle.className = 'font-bold';
                errorTitle.textContent = '驗證錯誤！';
                errorContainer.appendChild(errorTitle);

                // 添加錯誤列表
                const errorList = document.createElement('ul');
                errorList.className = 'mt-2';
                errors.forEach(error => {
                    const errorItem = document.createElement('li');
                    errorItem.textContent = error;
                    errorList.appendChild(errorItem);
                });
                errorContainer.appendChild(errorList);

                // 將錯誤容器插入到表單前面
                const form = document.getElementById('productForm');
                form.parentNode.insertBefore(errorContainer, form);

                // 滾動到錯誤訊息
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            // 如果沒有錯誤，處理圖片路徑並提交表單
            const oldInputs = document.querySelectorAll('input[name^="encrypted_image_path"]');
            oldInputs.forEach(input => input.remove());

            // 添加有效的圖片路徑到表單
            validPaths.forEach(path => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'encrypted_image_path[]';
                input.value = path;
                this.appendChild(input);
            });

            // 更新圖片順序
            updatePositions();

            // 顯示提交中的訊息
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '提交中...';

            // 延遲一點時間再提交，確保所有處理都完成
            setTimeout(() => {
                // 取消監聽這個事件，避免無限遞迴
                this.removeEventListener('submit', arguments.callee);

                // 正式提交表單
                this.submit();
            }, 100);
        });
    }

    // 初始化拖曳功能
    initializeDragAndDrop();
    updatePositions();
});

// 拖曳功能
function initializeDragAndDrop() {
    const imageContainer = document.getElementById('imageContainer');
    if (!imageContainer) return;

    const items = imageContainer.getElementsByClassName('relative');
    let draggedItem = null;
    let dragPlaceholder = null;

    Array.from(items).forEach(item => {
        item.setAttribute('draggable', 'true');

        item.addEventListener('dragstart', function (e) {
            draggedItem = this;

            // 創建拖拽時的佔位元素，保持相同高度避免跳動
            dragPlaceholder = document.createElement('div');
            dragPlaceholder.className = 'relative h-[192px] border-2 border-dashed border-blue-300 rounded-lg bg-blue-50';

            e.dataTransfer.effectAllowed = 'move';
            this.classList.add('opacity-50');

            // 延遲設置拖拽圖像，確保能看到拖拽的視覺反饋
            setTimeout(() => {
                e.dataTransfer.setDragImage(this, 0, 0);
            }, 10);
        });

        item.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });

        item.addEventListener('drop', function (e) {
            e.preventDefault();
            if (this !== draggedItem) {
                const parent = this.parentNode;
                const allItems = [...parent.children];
                const draggedIndex = allItems.indexOf(draggedItem);
                const droppedIndex = allItems.indexOf(this);

                if (draggedIndex !== droppedIndex) {
                    // 使用佔位元素進行替換，保持布局穩定
                    parent.replaceChild(dragPlaceholder, draggedItem);
                    parent.replaceChild(draggedItem, this);
                    parent.replaceChild(this, dragPlaceholder);
                }

                updatePositions();
            }
        });

        item.addEventListener('dragend', function () {
            this.classList.remove('opacity-50');
            draggedItem = null;

            // 清理可能未被替換的佔位元素
            if (dragPlaceholder && dragPlaceholder.parentNode) {
                dragPlaceholder.parentNode.removeChild(dragPlaceholder);
            }
            dragPlaceholder = null;
        });
    });
}

function updatePositions() {
    const imageContainer = document.getElementById('imageContainer');
    const items = imageContainer.getElementsByClassName('relative');

    const orderData = Array.from(items).map((item, index) => {
        const imageInput = item.querySelector('input[type="file"]');
        const hasFiles = imageInput.files && imageInput.files.length > 0;
        return {
            id: hasFiles ? `new_${index}` : '',
            position: index,
            isNew: hasFiles
        };
    }).filter(item => item.id !== '');

    document.getElementById('imageOrder').value = JSON.stringify(orderData);
}
