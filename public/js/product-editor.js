const processedImagePaths = new Array(5).fill(null);


document.addEventListener('DOMContentLoaded', function() {
    console.log('圖片編輯器初始化...');

    setupFormSubmission();

    showExistingImages();

    initDragAndDrop();
});


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


function setupFormSubmission() {
    const form = document.getElementById('productForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        processImagesBeforeSubmit(form);

        if (!hasAtLeastOneValidImage()) {
            alert('請至少上傳一張商品圖片');
            return;
        }

        console.log('提交表單...');
        form.submit();
    });
}


function processImagesBeforeSubmit(form) {
    form.querySelectorAll('input[name="encrypted_image_path[]"]').forEach(input => input.remove());
    form.querySelectorAll('input[name="image_positions[]"]').forEach(input => input.remove());

    const imageOrderData = [];
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');

    console.log('處理圖片位置，當前圖片路徑:', processedImagePaths);

    for (let i = 0; i < 5; i++) {
        const uploadBox = document.querySelector(`[x-data="imageUploader${i}"]`);
        if (!uploadBox) continue;

        if (processedImagePaths[i]) {
            console.log(`處理位置 ${i} 的新上傳圖片`);

            const pathInput = document.createElement('input');
            pathInput.type = 'hidden';
            pathInput.name = 'encrypted_image_path[]';
            pathInput.value = processedImagePaths[i];
            form.appendChild(pathInput);

            const posInput = document.createElement('input');
            posInput.type = 'hidden';
            posInput.name = 'image_positions[]';
            posInput.value = i;
            form.appendChild(posInput);

            imageOrderData.push({
                id: `new_${i}`,
                position: i,
                isNew: true
            });

            console.log(`新上傳圖片已添加到位置 ${i}`);
        }

        const imageIdInput = uploadBox.querySelector('input[name="image_ids[]"]');
        if (imageIdInput && imageIdInput.value && imageIdInput.value !== '' && !deletedIds.includes(parseInt(imageIdInput.value))) {
            imageOrderData.push({
                id: imageIdInput.value,
                position: i,
                isNew: false
            });

            console.log(`現有圖片 ID:${imageIdInput.value} 保持在位置 ${i}`);
        }
    }


    document.getElementById('imageOrder').value = JSON.stringify(imageOrderData);
    console.log('最終圖片順序數據:', JSON.stringify(imageOrderData));
}


function hasAtLeastOneValidImage() {
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');

    for (let i = 0; i < 5; i++) {
        if (processedImagePaths[i]) {
            console.log(`位置 ${i} 有新上傳圖片: ${processedImagePaths[i]}`);
            return true;
        }

        const imageIdInput = document.querySelectorAll('input[name="image_ids[]"]')[i];
        if (imageIdInput && imageIdInput.value && imageIdInput.value !== '' &&
            !deletedIds.includes(parseInt(imageIdInput.value))) {
            console.log(`位置 ${i} 有有效圖片 ID: ${imageIdInput.value}`);
            return true;
        }

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


for (let i = 0; i < 5; i++) {
    window[`imageUploader${i}`] = function() {
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


function initDragAndDrop() {
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        if (!preview) continue;

        preview.setAttribute('draggable', 'true');
        preview.dataset.position = i;

        preview.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', i);
            console.log(`開始拖曳位置 ${i} 的圖片`);
        });

        preview.addEventListener('dragover', function(e) {
            e.preventDefault();
        });

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

    const sourceHasValidImage = sourceImg.src && sourceImg.src !== location.href + '#';
    const targetHasValidImage = targetImg.src && targetImg.src !== location.href + '#';

    console.log(`位置 ${sourcePos} 有效圖片: ${sourceHasValidImage}`);
    console.log(`位置 ${targetPos} 有效圖片: ${targetHasValidImage}`);

    if (sourceHasValidImage || targetHasValidImage) {
        const tempSrc = sourceImg.src;
        sourceImg.src = targetImg.src;
        targetImg.src = tempSrc;

        const tempId = sourceIdInput.value;
        sourceIdInput.value = targetIdInput.value;
        targetIdInput.value = tempId;

        const tempPath = processedImagePaths[sourcePos];
        processedImagePaths[sourcePos] = processedImagePaths[targetPos];
        processedImagePaths[targetPos] = tempPath;
        console.log('已交換 processedImagePaths:', processedImagePaths);

        updateVisibility(sourcePos, targetHasValidImage);
        updateVisibility(targetPos, sourceHasValidImage);

        const sourceUpdated = safeUpdateAlpineState(sourcePos, targetHasValidImage);
        const targetUpdated = safeUpdateAlpineState(targetPos, sourceHasValidImage);

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


function updateVisibility(position, hasImage) {
    const preview = document.getElementById(`preview${position}`);
    const placeholder = document.getElementById(`placeholder${position}`);
    if (preview && placeholder) {
        if (hasImage) {
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');

            const img = preview.querySelector('img');
            if (img && img.src === location.href + '#') {
                 console.warn(`位置 ${position} 的 preview 可見，但 img src 無效。`);

            }
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');

            const img = preview.querySelector('img');
            if (img) img.src = '#';
        }
    }
}

document.addEventListener('alpine:init', () => {
    console.log('初始化 Alpine store...');

    Alpine.store('imageUploaders', {
        states: Array(5).fill(false),
        setState(index, hasImage) {
            if (index < 0 || index >= this.states.length) {
                console.warn(`嘗試設置無效索引 ${index} 的狀態`);
                return;
            }

            console.log(`設置位置 ${index} 的狀態為 ${hasImage}`);
            this.states[index] = hasImage;

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

    console.log('初始化圖片上傳器狀態...');
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
        const hasExistingImage = img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '';
        Alpine.store('imageUploaders').setState(i, hasExistingImage);
        console.log(`位置 ${i} 的初始狀態: ${hasExistingImage}`);
    }
});

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
