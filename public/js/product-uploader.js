let processedImagePaths = new Array(5).fill(null);

for (let i = 0; i < 5; i++) {
    window[`imageUploader${i}`] = function () {
        return {
            uploading: false,
            processing: false,
            progress: 0,
            error: false,
            errorMessage: '',
            success: false,
            imageIndex: i,

            startUpload(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.reset();
                this.uploading = true;
                this.processing = true;

                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    this.error = true;
                    this.errorMessage = '不支援的檔案格式';
                    this.uploading = false;
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    this.error = true;
                    this.errorMessage = '處理失敗! 檔案大小不能超過2MB';
                    this.uploading = false;
                    return;
                }

                this.uploadFile(file);
            },

            uploadFile(file) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

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
                                processedImagePaths[this.imageIndex] = result.path;

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
                reader.onloadend = function () {
                    preview.querySelector('img').src = reader.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
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

            removeImage() {
                const preview = document.getElementById(`preview${this.imageIndex}`);
                const placeholder = document.getElementById(`placeholder${this.imageIndex}`);
                const imageInput = document.getElementById(`image${this.imageIndex}`);

                preview.querySelector('img').src = '#';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                imageInput.value = '';

                processedImagePaths[this.imageIndex] = null;

                this.reset();

                updatePositions();
            },

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

document.addEventListener('DOMContentLoaded', function () {
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const oldErrorContainer = document.querySelector('.frontend-error-container');
            if (oldErrorContainer) {
                oldErrorContainer.remove();
            }

            const errors = [];

            const requiredFields = {
                'name': '書名不能留空',
                'price': '價格不能留空',
                'description': '描述不能留空',
                'grade': '年級不能留空',
                'semester': '學期不能留空',
                'subject': '科目不能留空',
                'category': '課程類別不能留空'
            };

            Object.entries(requiredFields).forEach(([fieldId, errorMessage]) => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    errors.push(errorMessage);
                }
            });

            const validPaths = processedImagePaths.filter(path => path !== null);
            if (validPaths.length === 0) {
                errors.push('請至少上傳一張商品圖片');
            }

            if (errors.length > 0) {
                const errorContainer = document.createElement('div');
                errorContainer.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 frontend-error-container';
                errorContainer.setAttribute('role', 'alert');

                const errorTitle = document.createElement('strong');
                errorTitle.className = 'font-bold';
                errorTitle.textContent = '驗證錯誤！';
                errorContainer.appendChild(errorTitle);

                const errorList = document.createElement('ul');
                errors.forEach(error => {
                    const errorItem = document.createElement('li');
                    errorItem.textContent = error;
                    errorList.appendChild(errorItem);
                });
                errorContainer.appendChild(errorList);

                const form = document.getElementById('productForm');
                form.parentNode.insertBefore(errorContainer, form);

                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            const oldInputs = this.querySelectorAll('input[name^="encrypted_image_path"]');
            oldInputs.forEach(input => input.remove());

            validPaths.forEach((path, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `encrypted_image_path[]`;
                input.value = path;
                this.appendChild(input);
            });

            this.submit();
        });
    }

    initializeDragAndDrop();
    updatePositions();
});

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

            dragPlaceholder = document.createElement('div');
            dragPlaceholder.className = 'relative h-[192px] border-2 border-dashed border-blue-300 rounded-lg bg-blue-50';

            e.dataTransfer.effectAllowed = 'move';
            this.classList.add('opacity-50');

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
