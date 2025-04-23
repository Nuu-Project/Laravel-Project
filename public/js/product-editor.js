// 用於存儲已處理的圖片路徑
const processedImagePaths = new Array(5).fill(null);

/**
 * 頁面初始化
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('圖片編輯器初始化...');
    
    // 設置表單提交事件
    setupFormSubmission();
    
    // 顯示已存在的圖片
    showExistingImages();
    
    // 初始化拖曳功能
    initDragAndDrop();
});

/**
 * 顯示已存在的圖片
 */
function showExistingImages() {
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
        
        if (img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '') {
            preview.classList.remove('hidden');
            const placeholder = document.getElementById(`placeholder${i}`);
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
            console.log(`位置 ${i} 已顯示現有圖片`);
        }
    }
}

/**
 * 設置表單提交處理
 */
function setupFormSubmission() {
    const form = document.getElementById('productForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // 處理表單提交前的圖片數據
        processImagesBeforeSubmit(form);
        
        // 檢查是否有有效圖片
        if (!hasAtLeastOneValidImage()) {
            alert('請至少上傳一張商品圖片');
            return;
        }
        
        // 提交表單
        console.log('提交表單...');
        form.submit();
    });
}

/**
 * 處理表單提交前的圖片數據
 */
function processImagesBeforeSubmit(form) {
    // 清除舊的隱藏輸入
    form.querySelectorAll('input[name="encrypted_image_path[]"]').forEach(input => input.remove());
    form.querySelectorAll('input[name="image_positions[]"]').forEach(input => input.remove());
    
    // 圖片順序數據
    const imageOrderData = [];
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
    
    console.log('處理圖片位置，當前圖片路徑:', processedImagePaths);
    
    // 處理每個位置
    for (let i = 0; i < 5; i++) {
        const uploadBox = document.querySelector(`[x-data="imageUploader${i}"]`);
        if (!uploadBox) continue;
        
        // 處理新上傳的圖片
        if (processedImagePaths[i]) {
            console.log(`處理位置 ${i} 的新上傳圖片`);
            
            // 添加圖片路徑輸入
            const pathInput = document.createElement('input');
            pathInput.type = 'hidden';
            pathInput.name = 'encrypted_image_path[]';
            pathInput.value = processedImagePaths[i];
            form.appendChild(pathInput);
            
            // 添加位置輸入 - 確保位置保持不變
            const posInput = document.createElement('input');
            posInput.type = 'hidden';
            posInput.name = 'image_positions[]';
            posInput.value = i; // 固定使用原始位置索引
            form.appendChild(posInput);
            
            // 添加到順序數據
            imageOrderData.push({
                id: `new_${i}`,
                position: i,
                isNew: true
            });
            
            console.log(`新上傳圖片已添加到位置 ${i}`);
        }
        
        // 處理現有圖片
        const imageIdInput = uploadBox.querySelector('input[name="image_ids[]"]');
        if (imageIdInput && imageIdInput.value && imageIdInput.value !== '' && !deletedIds.includes(parseInt(imageIdInput.value))) {
            // 添加到順序數據，保持位置不變
            imageOrderData.push({
                id: imageIdInput.value,
                position: i, // 固定使用原始位置索引
                isNew: false
            });
            
            console.log(`現有圖片 ID:${imageIdInput.value} 保持在位置 ${i}`);
        }
    }
    
    // 更新圖片順序數據
    document.getElementById('imageOrder').value = JSON.stringify(imageOrderData);
    console.log('最終圖片順序數據:', JSON.stringify(imageOrderData));
}

/**
 * 檢查是否至少有一張有效圖片
 */
function hasAtLeastOneValidImage() {
    // 檢查所有圖片位置是否有有效圖片
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
    
    // 檢查現有圖片和新上傳圖片
    for (let i = 0; i < 5; i++) {
        // 檢查是否有新上傳的圖片
        if (processedImagePaths[i]) {
            console.log(`位置 ${i} 有新上傳圖片: ${processedImagePaths[i]}`);
            return true;
        }
        
        // 檢查是否有現有圖片ID且未被標記為刪除
        const imageIdInput = document.querySelectorAll('input[name="image_ids[]"]')[i];
        if (imageIdInput && imageIdInput.value && imageIdInput.value !== '' && 
            !deletedIds.includes(parseInt(imageIdInput.value))) {
            console.log(`位置 ${i} 有有效圖片 ID: ${imageIdInput.value}`);
            return true;
        }
        
        // 檢查DOM中的預覽圖片是否有效
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
        if (preview && !preview.classList.contains('hidden') && 
            img && img.src && img.src !== location.href + '#') {
            console.log(`位置 ${i} 有有效預覽圖片: ${img.src}`);
            return true;
        }
    }
    
    console.log('未找到有效圖片');
    return false;
}

/**
 * 為每個上傳框創建 Alpine.js 組件
 */
for (let i = 0; i < 5; i++) {
    window[`imageUploader${i}`] = function() {
        // 檢查是否有現有圖片
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
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
            
            /**
             * 開始上傳
             */
            startUpload(event) {
                const file = event.target.files[0];
                if (!file) return;
                
                console.log(`開始上傳新圖片到位置 ${this.imageIndex}`);
                
                this.reset();
                this.uploading = true;
                this.processing = true;
                
                // 驗證文件
                if (!this.validateFile(file)) return;
                
                // 如果替換現有圖片，標記為刪除
                this.markExistingForDeletion();
                
                // 執行上傳
                this.uploadFile(file);
            },
            
            /**
             * 驗證文件
             */
            validateFile(file) {
                // 檢查文件類型
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    this.handleError('不支援的檔案格式');
                    return false;
                }
                
                // 檢查文件大小 (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    this.handleError('檔案大小不能超過2MB');
                    return false;
                }
                
                return true;
            },
            
            /**
             * 標記現有圖片為刪除
             */
            markExistingForDeletion() {
                const imageIdInput = document.querySelectorAll('input[name="image_ids[]"]')[this.imageIndex];
                if (!imageIdInput || !imageIdInput.value) return;
                
                const existingId = parseInt(imageIdInput.value);
                if (isNaN(existingId)) return;
                
                // 添加到刪除列表
                const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
                if (!deletedIds.includes(existingId)) {
                    deletedIds.push(existingId);
                    document.getElementById('deletedImageIds').value = JSON.stringify(deletedIds);
                    console.log(`標記圖片 ID:${existingId} 為刪除`);
                }
            },
            
            /**
             * 上傳文件
             */
            uploadFile(file) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                // 添加位置參數
                formData.append('position', this.imageIndex);
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/products/process-image');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                
                // 監聽上傳進度
                xhr.upload.addEventListener('progress', (event) => {
                    if (event.lengthComputable) {
                        this.progress = Math.round((event.loaded / event.total) * 100);
                    }
                });
                
                // 處理完成
                xhr.onload = () => {
                    if (xhr.status === 200) {
                        try {
                            const result = JSON.parse(xhr.responseText);
                            
                            if (result.success) {
                                this.success = true;
                                this.processing = false;
                                
                                // 使用返回的位置信息（如果有）或默認使用當前索引
                                const position = (result.position !== undefined && result.position >= 0) 
                                    ? result.position 
                                    : this.imageIndex;
                                    
                                // 存儲圖片路徑到對應位置
                                processedImagePaths[position] = result.path;
                                console.log(`保存新圖片路徑到位置 ${position}: ${result.path}`);
                                
                                // 顯示預覽
                                this.showPreview(file);
                                
                                // 延遲隱藏進度條
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
                
                // 錯誤處理
                xhr.onerror = () => this.handleError('網路錯誤');
                xhr.onabort = () => this.handleError('上傳已取消');
                xhr.ontimeout = () => this.handleError('上傳超時');
                
                // 發送請求
                xhr.send(formData);
                
                // 模擬進度
                this.simulateProgressIfNeeded();
            },
            
            /**
             * 模擬進度更新
             */
            simulateProgressIfNeeded() {
                let lastProgress = 0;
                const interval = setInterval(() => {
                    if (!this.uploading || this.error || this.progress >= 100) {
                        clearInterval(interval);
                        return;
                    }
                    
                    // 如果進度停滯，緩慢增加
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
            
            /**
             * 顯示圖片預覽
             */
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
            
            /**
             * 處理錯誤
             */
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
            
            /**
             * 移除圖片
             */
            removeImage(productId, imageId) {
                // 記錄當前位置
                const currentPosition = this.imageIndex;
                console.log(`開始移除位置 ${currentPosition} 的圖片 (ID: ${imageId})`);
                
                // 如果是已存在的圖片，添加到刪除列表
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
                
                // 清空對應的 image_ids[] input 的值
                const imageIdInput = document.querySelectorAll('input[name="image_ids[]"]')[currentPosition];
                if (imageIdInput) {
                    imageIdInput.value = ''; // 清空 ID 值
                    console.log(`位置 ${currentPosition} 的 image_ids[] 已清空`);
                }

                // 重置UI
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
                
                // 清除已處理圖片路徑，但仍保持位置索引
                processedImagePaths[currentPosition] = null;
                console.log(`位置 ${currentPosition} 的圖片路徑已清除`);
                
                // 重置狀態
                this.reset();
                this.hasExistingImage = false;
                
                // 更新 Alpine store 狀態
                safeUpdateAlpineState(currentPosition, false);
                
                console.log(`位置 ${currentPosition} 的圖片已成功移除`);
            },
            
            /**
             * 重置所有狀態
             */
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

/**
 * 拖曳功能初始化
 */
function initDragAndDrop() {
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        if (!preview) continue;

        // 設置為可拖曳
        preview.setAttribute('draggable', 'true');
        preview.dataset.position = i;

        // 拖曳開始
        preview.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', i);
            console.log(`開始拖曳位置 ${i} 的圖片`);
        });

        // 允許放置
        preview.addEventListener('dragover', function(e) {
            e.preventDefault();
        });

        // 放置時執行交換
        preview.addEventListener('drop', function(e) {
            e.preventDefault();
            const sourcePosition = parseInt(e.dataTransfer.getData('text/plain'));
            const targetPosition = parseInt(this.dataset.position);

            if (sourcePosition === targetPosition) return;
            console.log(`交換位置 ${sourcePosition} 和 ${targetPosition} 的圖片`);

            swapImages(sourcePosition, targetPosition);
        });
    }
}

/**
 * 交換兩個位置的圖片
 */
function swapImages(sourcePos, targetPos) {
    console.log(`嘗試交換位置 ${sourcePos} 和 ${targetPos}`);

    const sourcePreview = document.getElementById(`preview${sourcePos}`);
    const targetPreview = document.getElementById(`preview${targetPos}`);
    const sourcePlaceholder = document.getElementById(`placeholder${sourcePos}`);
    const targetPlaceholder = document.getElementById(`placeholder${targetPos}`);

    if (!sourcePreview || !targetPreview || !sourcePlaceholder || !targetPlaceholder) {
        console.error("交換中止：缺少預覽或佔位符元素。");
        return;
    }

    const sourceImg = sourcePreview.querySelector('img');
    const targetImg = targetPreview.querySelector('img');
    const sourceIdInput = document.querySelectorAll('input[name="image_ids[]"]')[sourcePos];
    const targetIdInput = document.querySelectorAll('input[name="image_ids[]"]')[targetPos];
    const sourceUploaderElement = document.querySelector(`[x-data="imageUploader${sourcePos}"]`);
    const targetUploaderElement = document.querySelector(`[x-data="imageUploader${targetPos}"]`);

    if (!sourceImg || !targetImg || !sourceIdInput || !targetIdInput) {
        console.error("交換中止：缺少圖片或 ID 輸入元素。");
        return;
    }

    // 檢查圖片是否有效 (src 不是 '#' 或空)
    const sourceHasValidImage = sourceImg.src && sourceImg.src !== location.href + '#'; // location.href + '#' 是 src='#' 的實際值
    const targetHasValidImage = targetImg.src && targetImg.src !== location.href + '#';

    console.log(`位置 ${sourcePos} 有效圖片: ${sourceHasValidImage}`);
    console.log(`位置 ${targetPos} 有效圖片: ${targetHasValidImage}`);

    // 只有在至少一個位置有有效圖片時才進行交換
    if (sourceHasValidImage || targetHasValidImage) {
        // 1. 交換圖片 src
        const tempSrc = sourceImg.src;
        sourceImg.src = targetImg.src;
        targetImg.src = tempSrc;

        // 2. 交換 image_ids[] 的值
        const tempId = sourceIdInput.value;
        sourceIdInput.value = targetIdInput.value;
        targetIdInput.value = tempId;

        // 3. 交換 processedImagePaths 中的路徑
        const tempPath = processedImagePaths[sourcePos];
        processedImagePaths[sourcePos] = processedImagePaths[targetPos];
        processedImagePaths[targetPos] = tempPath;
        console.log('已交換 processedImagePaths:', processedImagePaths);

        // 4. 更新預覽和佔位符的顯示狀態
        updateVisibility(sourcePos, targetHasValidImage); // 來源位置更新為目標是否有圖
        updateVisibility(targetPos, sourceHasValidImage); // 目標位置更新為來源是否有圖

        // 5. 更新 Alpine 組件的 hasExistingImage 狀態
        const sourceUpdated = safeUpdateAlpineState(sourcePos, targetHasValidImage);
        const targetUpdated = safeUpdateAlpineState(targetPos, sourceHasValidImage);
        
        // 如果安全更新失敗，嘗試直接更新
        if (!sourceUpdated && sourceUploaderElement && sourceUploaderElement.__x) {
            try {
                sourceUploaderElement.__x.getUnobservedData().hasExistingImage = targetHasValidImage;
                console.log(`直接更新位置 ${sourcePos} 的狀態為 ${targetHasValidImage}`);
            } catch (e) {
                console.warn(`無法直接更新位置 ${sourcePos} 的狀態: ${e.message}`);
            }
        }
        
        if (!targetUpdated && targetUploaderElement && targetUploaderElement.__x) {
            try {
                targetUploaderElement.__x.getUnobservedData().hasExistingImage = sourceHasValidImage;
                console.log(`直接更新位置 ${targetPos} 的狀態為 ${sourceHasValidImage}`);
            } catch (e) {
                console.warn(`無法直接更新位置 ${targetPos} 的狀態: ${e.message}`);
            }
        }

        console.log(`位置 ${sourcePos} 和 ${targetPos} 的圖片已交換`);
    } else {
        console.log("交換中止：兩個位置都沒有有效圖片。");
    }
}

/**
 * 更新指定位置的預覽和佔位符可見性
 */
function updateVisibility(position, hasImage) {
    const preview = document.getElementById(`preview${position}`);
    const placeholder = document.getElementById(`placeholder${position}`);
    if (preview && placeholder) {
        if (hasImage) {
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            // 確保 img src 不是 '#'
            const img = preview.querySelector('img');
            if (img && img.src === location.href + '#') {
                 console.warn(`位置 ${position} 的 preview 可見，但 img src 無效。`);
                 // 這裡可能需要根據 processedImagePaths 或其他數據恢復 src，
                 // 但在 swapImages 上下文中，src 應該已經被正確交換了。
            }
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            // 清空 img src
            const img = preview.querySelector('img');
            if (img) img.src = '#';
        }
    }
}

// 需要在 Alpine 初始化之前或之後定義一個 store 來管理狀態（如果選擇使用 store）
document.addEventListener('alpine:init', () => {
    console.log('初始化 Alpine store...');

    Alpine.store('imageUploaders', {
        states: Array(5).fill(false), // 初始狀態都為 false
        setState(index, hasImage) {
            if (index < 0 || index >= this.states.length) {
                console.warn(`嘗試設置無效索引 ${index} 的狀態`);
                return;
            }
            
            console.log(`設置位置 ${index} 的狀態為 ${hasImage}`);
            this.states[index] = hasImage;
            
            // 手動更新對應組件的狀態，因為 store 不會自動觸發組件更新
            const el = document.querySelector(`[x-data="imageUploader${index}"]`);
            if (el && el.__x) {
                try {
                    el.__x.getUnobservedData().hasExistingImage = hasImage;
                } catch (e) {
                    console.warn(`無法更新 imageUploader${index} 的 hasExistingImage 狀態: ${e.message}`);
                }
            }
        },
        getState(index) {
            if (index < 0 || index >= this.states.length) {
                console.warn(`嘗試獲取無效索引 ${index} 的狀態`);
                return false;
            }
            return this.states[index];
        }
    });

    // 初始化 store 狀態
    console.log('初始化圖片上傳器狀態...');
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
        const hasExistingImage = img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '';
        Alpine.store('imageUploaders').setState(i, hasExistingImage);
        console.log(`位置 ${i} 的初始狀態: ${hasExistingImage}`);
    }
});

// 全局輔助函數 - 安全地更新 Alpine store
function safeUpdateAlpineState(index, hasImage) {
    try {
        if (typeof Alpine !== 'undefined' && Alpine.store && Alpine.store('imageUploaders')) {
            Alpine.store('imageUploaders').setState(index, hasImage);
            return true;
        }
    } catch (e) {
        console.warn(`安全更新 Alpine 狀態時出錯: ${e.message}`);
    }
    return false;
}