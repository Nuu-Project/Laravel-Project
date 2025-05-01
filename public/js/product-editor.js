const processedImagePaths = new Array(5).fill(null);


// 頁面載入時檢查現有圖片和初始化功能
document.addEventListener('DOMContentLoaded', function () {
    // 初始化每個上傳位置的現有圖片狀態
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');

        if (img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '') {
            preview.classList.remove('hidden');
            const placeholder = document.getElementById(`placeholder${i}`);
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        }
    }

    // 初始化拖曳排序
    initDragAndDrop();

    // 初始化圖片順序
    updateImageOrder();
});

// 為每個上傳元素創建Alpine.js元件
for (let i = 0; i < 5; i++) {
    window[`imageUploader${i}`] = function() {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
        const hasExistingImg = img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '';

        const hasExistingImage = img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '';

        return {
            uploading: false,
            processing: false,
            progress: 0,
            error: false,
            errorMessage: '',
            success: false,
            imageIndex: i,
            hasExistingImage: hasExistingImage,

            startUpload(event) {
                const file = event.target.files[0];
                if (!file) return;

                console.log(`開始上傳新圖片到位置 ${this.imageIndex}`);

                this.reset();
                this.uploading = true;
                this.processing = true;

                if (!this.validateFile(file)) return;

                this.markExistingForDeletion();

                this.uploadFile(file);
            },

            validateFile(file) {
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    this.handleError('不支援的檔案格式');
                    return false;
                }

                if (file.size > 2 * 1024 * 1024) {
                    this.handleError('檔案大小不能超過2MB');
                    return false;
                }

                return true;
            },

            markExistingForDeletion() {
                const imageIdInput = document.querySelectorAll('input[name="image_ids[]"]')[this.imageIndex];
                if (!imageIdInput || !imageIdInput.value) return;

                const existingId = parseInt(imageIdInput.value);
                if (isNaN(existingId)) return;

                const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
                if (!deletedIds.includes(existingId)) {
                    deletedIds.push(existingId);
                    document.getElementById('deletedImageIds').value = JSON.stringify(deletedIds);
                    console.log(`標記圖片 ID:${existingId} 為刪除`);
                }
            },


            uploadFile(file) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                formData.append('position', this.imageIndex);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/products/process-image');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                xhr.upload.addEventListener('progress', (event) => {
                    if (event.lengthComputable) {
                        this.progress = Math.round((event.loaded / event.total) * 100);
                    }
                });

                xhr.onload = () => {
                    if (xhr.status === 200) {
                        try {
                            const result = JSON.parse(xhr.responseText);

                            if (result.success) {
                                this.success = true;
                                this.processing = false;

                                const position = (result.position !== undefined && result.position >= 0)
                                    ? result.position
                                    : this.imageIndex;

                                processedImagePaths[position] = result.path;
                                console.log(`保存新圖片路徑到位置 ${position}: ${result.path}`);

                                this.showPreview(file);

                                setTimeout(() => {
                                    this.uploading = false;
                                }, 1000);
                            } else {
                                this.handleError('處理圖片時發生錯誤');
                            }
                        } catch (e) {
                            this.handleError('解析伺服器回應失敗');
                        }
                    } else {
                        this.handleError(`上傳失敗 (${xhr.status})`);
                    }
                };

                xhr.onerror = () => this.handleError('網路錯誤');
                xhr.onabort = () => this.handleError('上傳已取消');
                xhr.ontimeout = () => this.handleError('上傳超時');

                xhr.send(formData);

                this.simulateProgressIfNeeded();
            },

            simulateProgressIfNeeded() {
                let lastProgress = 0;
                const interval = setInterval(() => {
                    if (!this.uploading || this.error || this.progress >= 100) {
                        clearInterval(interval);
                        return;
                    }

                    if (this.progress === lastProgress) {
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

            showPreview(file) {
                const preview = document.getElementById(`preview${this.imageIndex}`);
                const placeholder = document.getElementById(`placeholder${this.imageIndex}`);

                const reader = new FileReader();
                reader.onloadend = () => {
                    const img = preview.querySelector('img');
                    img.src = reader.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    this.hasExistingImage = true;
                    console.log(`顯示預覽圖片 - 位置 ${this.imageIndex}`);
                };
                reader.readAsDataURL(file);
            },


            handleError(message) {
                this.error = true;
                this.errorMessage = message;
                this.success = false;
                this.processing = false;
                console.error(`錯誤 (位置 ${this.imageIndex}): ${message}`);

                setTimeout(() => {
                    this.uploading = false;
                }, 1500);
            },


            removeImage(productId, imageId) {
                const currentPosition = this.imageIndex;
                console.log(`開始移除位置 ${currentPosition} 的圖片 (ID: ${imageId})`);

                if (imageId && imageId !== 'null') {
                    const numId = parseInt(imageId);
                    if (!isNaN(numId)) {
                        const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
                        if (!deletedIds.includes(numId)) {
                            deletedIds.push(numId);
                            document.getElementById('deletedImageIds').value = JSON.stringify(deletedIds);
                            console.log(`圖片 ID:${numId} 已添加到刪除列表`);
                        }
                    }
                }

                const imageIdInput = document.querySelectorAll('input[name="image_ids[]"]')[currentPosition];
                if (imageIdInput) {
                    imageIdInput.value = '';
                    console.log(`位置 ${currentPosition} 的 image_ids[] 已清空`);
                }

                const preview = document.getElementById(`preview${currentPosition}`);
                const placeholder = document.getElementById(`placeholder${currentPosition}`);
                const imageInput = document.getElementById(`image${currentPosition}`);

                if (preview) {
                    const img = preview.querySelector('img');
                    if (img) img.src = '#';
                    preview.classList.add('hidden');
                }


                if (placeholder) {
                    placeholder.classList.remove('hidden');
                }


                if (imageInput) {
                    imageInput.value = '';
                }

                processedImagePaths[currentPosition] = null;
                console.log(`位置 ${currentPosition} 的圖片路徑已清除`);

                this.reset();
                this.hasExistingImage = false;

                safeUpdateAlpineState(currentPosition, false);

                console.log(`位置 ${currentPosition} 的圖片已成功移除`);
            },


            reset() {
                this.uploading = false;
                this.processing = false;
                this.progress = 0;
                this.error = false;
                this.errorMessage = '';
                this.success = false;
            }
        };
    };
}

// 監聽表單提交
document.addEventListener('DOMContentLoaded', function() {
    // 確保已有圖片正確顯示
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');

        if (img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '') {
            preview.classList.remove('hidden');

            const placeholder = document.getElementById(`placeholder${i}`);
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        }
    }

    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // 過濾掉 null 值獲取有效路徑
            const validPaths = processedImagePaths.filter(path => path !== null);

            // 獲取未被刪除的現有圖片ID
            const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
            const existingImageInputs = document.querySelectorAll('input[name="image_ids[]"]');
            let hasExistingImages = false;

            existingImageInputs.forEach(input => {
                if (input.value && !deletedIds.includes(parseInt(input.value))) {
                    hasExistingImages = true;
                }
            });

            // 如果沒有任何圖片，則顯示錯誤
            if (validPaths.length === 0 && !hasExistingImages) {
                alert('請至少上傳一張商品圖片');
                return;
            }

            // 移除所有舊的隱藏輸入欄位
            this.querySelectorAll('input[name="encrypted_image_path[]"]').forEach(input => input.remove());

            // 為每個處理過的圖片創建隱藏的輸入欄位
            validPaths.forEach((path, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'encrypted_image_path[]';
                input.value = path;
                this.appendChild(input);

                // 添加位置
                const posInput = document.createElement('input');
                posInput.type = 'hidden';
                posInput.name = 'image_positions[]';
                posInput.value = processedImagePaths.indexOf(path);
                this.appendChild(posInput);
            });

            // 更新圖片順序
            updatePositions();

            // 提交表單
            this.submit();
        });
    }

    // 初始化拖曳功能和更新圖片順序
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

        item.addEventListener('dragstart', function(e) {
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
        preview.addEventListener('dragover', function(e) {
            e.preventDefault();
        });

        item.addEventListener('drop', function (e) {
        preview.addEventListener('drop', function(e) {
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

        item.addEventListener('dragend', function() {
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

// 更新圖片順序
function updatePositions() {
    const imageContainer = document.getElementById('imageContainer');
    if (!imageContainer) return;

    const items = imageContainer.getElementsByClassName('relative');
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');

    const orderData = Array.from(items).map((item, index) => {
        // 檢查是否為現有圖片
        const imageIdInput = item.querySelector('input[name="image_ids[]"]');
        const imageId = imageIdInput ? imageIdInput.value : null;

        // 檢查是否為新上傳的圖片
        const fileInput = item.querySelector('input[type="file"]');
        const hasNewUpload = fileInput && fileInput.hasAttribute('data-has-new-upload');
        const position = index;

        // 如果有現有圖片且未被刪除
        if (imageId && !deletedIds.includes(parseInt(imageId))) {
            return {
                id: imageId,
                position: position,
                isNew: false
            };
        }
        // 如果是新上傳的圖片
        else if (processedImagePaths[index] || hasNewUpload) {
            return {
                id: `new_${index}`,
                position: position,
                isNew: true
            };
        }

        return null;
    }).filter(item => item !== null);

    document.getElementById('imageOrder').value = JSON.stringify(orderData);
}

// 初始化拖曳排序
function initDragAndDrop() {
    console.log('初始化拖曳排序功能...');

    const imageContainer = document.getElementById('imageContainer');
    if (!imageContainer) {
        console.error('找不到圖片容器!');
        return;
    }

    const items = imageContainer.querySelectorAll('.relative');
    let draggedItem = null;

    items.forEach(item => {
        // 設置拖曳屬性
        item.setAttribute('draggable', 'true');

        // 拖曳開始
        item.addEventListener('dragstart', e => {
            draggedItem = item;
            setTimeout(() => {
                item.classList.add('opacity-50');
            }, 0);
        });

        // 拖曳進入
        item.addEventListener('dragover', e => {
            e.preventDefault();
        });

        // 放下
        item.addEventListener('drop', e => {
            e.preventDefault();
            if (item !== draggedItem) {
                // 獲取所有項目
                const allItems = Array.from(imageContainer.querySelectorAll('.relative'));

                // 獲取索引
                const fromIndex = allItems.indexOf(draggedItem);
                const toIndex = allItems.indexOf(item);

                console.log(`拖曳排序: 從位置${fromIndex}到位置${toIndex}`);

                // 執行排序
                if (fromIndex < toIndex) {
                    imageContainer.insertBefore(draggedItem, item.nextElementSibling);
                } else {
                    imageContainer.insertBefore(draggedItem, item);
                }
            }
        });

        // 拖曳結束
        item.addEventListener('dragend', e => {
            item.classList.remove('opacity-50');
            draggedItem = null;
        });
    });
}

// 更新圖片順序
function updateImageOrder() {
    const orderData = [];

    // 獲取所有圖片ID輸入
    const imageIdInputs = document.querySelectorAll('input[name="image_ids[]"]');
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');

    // 構建圖片順序數據
    imageIdInputs.forEach((input, index) => {
        // 只考慮有值且未被刪除的現有圖片
        if (input.value && !deletedIds.includes(parseInt(input.value))) {
            orderData.push({
                id: input.value,
                position: index
            });
        } else if (processedImagePaths[index]) {
            // 新上傳的圖片，使用標記
            orderData.push({
                id: `new_${index}`,
                position: index,
                isNew: true
            });
        }
    });

    // 將順序數據寫入隱藏輸入
    document.getElementById('imageOrder').value = JSON.stringify(orderData);
    console.log('圖片順序:', orderData);
}

// 檢查是否有至少一張有效圖片
function hasAtLeastOneValidImage() {
    // 檢查是否有新上傳的圖片
    const hasNewImages = Object.keys(processedImagePaths).length > 0;

    // 檢查是否有未刪除的現有圖片
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
    const hasExistingImages = Array.from(document.querySelectorAll('input[name="image_ids[]"]'))
        .some(input => {
            if (!input.value) return false;
            return !deletedIds.includes(parseInt(input.value));
        });

    // 額外檢查是否有顯示在預覽中的圖片
    const hasVisibleImages = Array.from({ length: 5 }, (_, i) => {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
        return preview && !preview.classList.contains('hidden') &&
               img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '';
    }).some(Boolean);

    return hasNewImages || hasExistingImages || hasVisibleImages;
}

// 只注冊一個DOMContentLoaded事件
document.addEventListener('DOMContentLoaded', function() {
    // 強制顯示已有圖片
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');

        if (img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '') {
            preview.classList.remove('hidden');
            const placeholder = document.getElementById(`placeholder${i}`);
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        }
    }

    // 修改表單提交
    const form = document.getElementById('productForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // 阻止默認提交
            e.preventDefault();

            // 處理圖片資料
            addProcessedImagePathsToForm(this);

            // 檢查是否有有效圖片
            if (!hasAtLeastOneValidImage()) {
                alert('請至少上傳一張商品圖片');
                return;
            }

            // 提交表單
            this.submit();
        });
    }

    // 初始化拖曳功能
    initDragAndDrop();

    // 初始化圖片順序
    updateImageOrder();

    console.log('頁面初始化完成');
});
