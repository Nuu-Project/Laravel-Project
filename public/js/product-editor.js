const processedImagePaths = new Array(5).fill(null);


document.addEventListener('DOMContentLoaded', function () {
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
        }
    }
}


function setupFormSubmission() {
    const form = document.getElementById('productForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        processImagesBeforeSubmit(form);

        if (!hasAtLeastOneValidImage()) {
            alert('請至少上傳一張商品圖片');
            return;
        }

        form.submit();
    });
}


function processImagesBeforeSubmit(form) {
    form.querySelectorAll('input[name="encrypted_image_path[]"]').forEach(input => input.remove());
    form.querySelectorAll('input[name="image_positions[]"]').forEach(input => input.remove());

    const imageOrderData = [];
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');

    for (let i = 0; i < 5; i++) {
        const uploadBox = document.querySelector(`[x-data="imageUploader${i}"]`);
        if (!uploadBox) continue;

        if (processedImagePaths[i]) {
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
        }

        const imageIdInput = uploadBox.querySelector('input[name="image_ids[]"]');
        if (imageIdInput && imageIdInput.value && imageIdInput.value !== '' && !deletedIds.includes(parseInt(imageIdInput.value))) {
            imageOrderData.push({
                id: imageIdInput.value,
                position: i,
                isNew: false
            });
        }
    }


    document.getElementById('imageOrder').value = JSON.stringify(imageOrderData);
}


function hasAtLeastOneValidImage() {
    const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');

    for (let i = 0; i < 5; i++) {
        if (processedImagePaths[i]) {
            return true;
        }

        const imageIdInput = document.querySelectorAll('input[name="image_ids[]"]')[i];
        if (imageIdInput && imageIdInput.value && imageIdInput.value !== '' &&
            !deletedIds.includes(parseInt(imageIdInput.value))) {
            return true;
        }

        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
        if (preview && !preview.classList.contains('hidden') &&
            img && img.src && img.src !== location.href + '#') {
            return true;
        }
    }

    return false;
}


for (let i = 0; i < 5; i++) {
    window[`imageUploader${i}`] = function () {
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
                };
                reader.readAsDataURL(file);
            },


            handleError(message) {
                this.error = true;
                this.errorMessage = message;
                this.success = false;
                this.processing = false;

                setTimeout(() => {
                    this.uploading = false;
                }, 1500);
            },


            removeImage(productId, imageId) {
                const currentPosition = this.imageIndex;

                // 從 input 框讀取最新的 imageId（處理交換後的情況）
                const imageIdInput = document.querySelectorAll('input[name="image_ids[]"]')[currentPosition];
                const actualImageId = imageIdInput ? imageIdInput.value : imageId;

                if (actualImageId && actualImageId !== 'null' && actualImageId !== '') {
                    const numId = parseInt(actualImageId);
                    if (!isNaN(numId)) {
                        const deletedIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
                        if (!deletedIds.includes(numId)) {
                            deletedIds.push(numId);
                            document.getElementById('deletedImageIds').value = JSON.stringify(deletedIds);
                        }
                    }
                }

                if (imageIdInput) {
                    imageIdInput.value = '';
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

                this.reset();
                this.hasExistingImage = false;

                safeUpdateAlpineState(currentPosition, false);
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

        preview.addEventListener('dragstart', function (e) {
            e.dataTransfer.setData('text/plain', i);
        });

        preview.addEventListener('dragover', function (e) {
            e.preventDefault();
        });

        preview.addEventListener('drop', function (e) {
            e.preventDefault();
            const sourcePosition = parseInt(e.dataTransfer.getData('text/plain'));
            const targetPosition = parseInt(this.dataset.position);

            if (sourcePosition === targetPosition) return;

            swapImages(sourcePosition, targetPosition);
        });
    }
}


function swapImages(sourcePos, targetPos) {
    const sourcePreview = document.getElementById(`preview${sourcePos}`);
    const targetPreview = document.getElementById(`preview${targetPos}`);
    const sourcePlaceholder = document.getElementById(`placeholder${sourcePos}`);
    const targetPlaceholder = document.getElementById(`placeholder${targetPos}`);

    if (!sourcePreview || !targetPreview || !sourcePlaceholder || !targetPlaceholder) {
        return;
    }

    const sourceImg = sourcePreview.querySelector('img');
    const targetImg = targetPreview.querySelector('img');
    const sourceIdInput = document.querySelectorAll('input[name="image_ids[]"]')[sourcePos];
    const targetIdInput = document.querySelectorAll('input[name="image_ids[]"]')[targetPos];
    const sourceUploaderElement = document.querySelector(`[x-data="imageUploader${sourcePos}"]`);
    const targetUploaderElement = document.querySelector(`[x-data="imageUploader${targetPos}"]`);

    if (!sourceImg || !targetImg || !sourceIdInput || !targetIdInput) {
        return;
    }

    const sourceHasValidImage = sourceImg.src && sourceImg.src !== location.href + '#';
    const targetHasValidImage = targetImg.src && targetImg.src !== location.href + '#';

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

        updateVisibility(sourcePos, targetHasValidImage);
        updateVisibility(targetPos, sourceHasValidImage);

        const sourceUpdated = safeUpdateAlpineState(sourcePos, targetHasValidImage);
        const targetUpdated = safeUpdateAlpineState(targetPos, sourceHasValidImage);

        if (!sourceUpdated && sourceUploaderElement && sourceUploaderElement.__x) {
            try {
                sourceUploaderElement.__x.getUnobservedData().hasExistingImage = targetHasValidImage;
            } catch (e) {
            }
        }

        if (!targetUpdated && targetUploaderElement && targetUploaderElement.__x) {
            try {
                targetUploaderElement.__x.getUnobservedData().hasExistingImage = sourceHasValidImage;
            } catch (e) {
            }
        }
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
            if (img && img.src === location.href + '#') { }
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');

            const img = preview.querySelector('img');
            if (img) img.src = '#';
        }
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.store('imageUploaders', {
        states: Array(5).fill(false),
        setState(index, hasImage) {
            if (index < 0 || index >= this.states.length) {
                return;
            }

            this.states[index] = hasImage;

            const el = document.querySelector(`[x-data="imageUploader${index}"]`);
            if (el && el.__x) {
                try {
                    el.__x.getUnobservedData().hasExistingImage = hasImage;
                } catch (e) {

                }
            }
        },
        getState(index) {
            if (index < 0 || index >= this.states.length) {
                return false;
            }
            return this.states[index];
        }
    });

    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const img = preview?.querySelector('img');
        const hasExistingImage = img && img.getAttribute('src') !== '#' && img.getAttribute('src') !== '';
        Alpine.store('imageUploaders').setState(i, hasExistingImage);
    }
});

function safeUpdateAlpineState(index, hasImage) {
    try {
        if (typeof Alpine !== 'undefined' && Alpine.store && Alpine.store('imageUploaders')) {
            Alpine.store('imageUploaders').setState(index, hasImage);
            return true;
        }
    } catch (e) {
    }
    return false;
}
