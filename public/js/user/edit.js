function previewImage(input, number) {
    const preview = document.getElementById('preview' + number);
    const placeholder = document.getElementById('placeholder' + number);
    const deleteButton = document.getElementById('deleteButton' + number);
    const imageIdInput = input.parentNode.querySelector('input[name="image_ids[]"]');
    const file = input.files[0];
    const reader = new FileReader();

    if (file && imageIdInput.value) {
        const deletedImageIds = JSON.parse(document.getElementById('deletedImageIds').value);
        if (!deletedImageIds.includes(imageIdInput.value)) {
            deletedImageIds.push(imageIdInput.value);
            document.getElementById('deletedImageIds').value = JSON.stringify(deletedImageIds);
        }
        imageIdInput.value = '';
    }

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

// 頁面加載時初始化預覽
document.addEventListener('DOMContentLoaded', function() {
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById(`preview${i}`);
        const placeholder = document.getElementById(`placeholder${i}`);
        const deleteButton = document.getElementById(`deleteButton${i}`);
        if (preview.querySelector('img')?.src && preview.querySelector('img').src !== window.location.href + '#') {
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            deleteButton.classList.remove('hidden');
        }
    }

    initializeDragAndDrop();
    updatePositions();
});

function deleteImage(productId, imageId, index) {
    const deletedImageIds = JSON.parse(document.getElementById('deletedImageIds').value);
    if (imageId && !deletedImageIds.includes(imageId)) {
        deletedImageIds.push(imageId);
        document.getElementById('deletedImageIds').value = JSON.stringify(deletedImageIds);
    }

    removeImage(index);
    updatePositions();
}

function removeImage(index) {
    const preview = document.getElementById(`preview${index}`);
    const placeholder = document.getElementById(`placeholder${index}`);
    const imageInput = document.getElementById(`image${index}`);
    const deleteButton = document.getElementById(`deleteButton${index}`);

    preview.querySelector('img').src = '#';
    preview.classList.add('hidden');
    placeholder.classList.remove('hidden');
    imageInput.value = '';
    deleteButton.classList.add('hidden');
}

// 表單提交處理
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        const requiredFields = ['name', 'description', 'grade', 'semester', 'category'];
        let allFieldsFilled = true;

        for (const fieldId of requiredFields) {
            const field = document.getElementById(fieldId);
            if (!field.value) {
                allFieldsFilled = false;
                break;
            }
        }

        let hasValidImage = false;
        const imageContainers = document.querySelectorAll('#imageContainer .relative');

        for (const container of imageContainers) {
            const preview = container.querySelector('[id^="preview"]');
            const imageInput = container.querySelector('input[type="file"]');
            const imageIdInput = container.querySelector('input[name="image_ids[]"]');

            if ((imageInput.files.length > 0) ||
                (imageIdInput.value &&
                 !preview.classList.contains('hidden') &&
                 preview.querySelector('img')?.src &&
                 preview.querySelector('img').src !== '#')) {
                hasValidImage = true;
                break;
            }
        }

        if (!allFieldsFilled || !hasValidImage) {
            alert('請確保所有必填欄位都已填寫，且至少上傳一張商品圖片');
            return;
        }

        updatePositions();
        this.submit();
    });
});

// 拖曳功能
function initializeDragAndDrop() {
    const imageContainer = document.getElementById('imageContainer');
    if (!imageContainer) return;

    const items = imageContainer.getElementsByClassName('relative');
    let draggedItem = null;

    Array.from(items).forEach(item => {
        item.setAttribute('draggable', 'true');

        item.addEventListener('dragstart', function(e) {
            draggedItem = this;
            e.dataTransfer.effectAllowed = 'move';
            this.classList.add('opacity-50');
        });

        item.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });

        item.addEventListener('drop', function(e) {
            e.preventDefault();
            if (this !== draggedItem) {
                const parent = this.parentNode;
                const allItems = [...parent.children];
                const draggedIndex = allItems.indexOf(draggedItem);
                const droppedIndex = allItems.indexOf(this);

                if (draggedIndex < droppedIndex) {
                    this.parentNode.insertBefore(draggedItem, this.nextSibling);
                } else {
                    this.parentNode.insertBefore(draggedItem, this);
                }

                updatePositions();
            }
        });

        item.addEventListener('dragend', function() {
            this.classList.remove('opacity-50');
            draggedItem = null;
        });
    });
}

function updatePositions() {
    const imageContainer = document.getElementById('imageContainer');
    const items = imageContainer.getElementsByClassName('relative');

    const orderData = Array.from(items).map((item, index) => {
        const imageIdInput = item.querySelector('input[name="image_ids[]"]');
        const imageInput = item.querySelector('input[type="file"]');

        return {
            id: imageIdInput.value || (imageInput.files.length > 0 ? `new_${index}` : ''),
            position: index,
            isNew: imageInput.files.length > 0
        };
    }).filter(item => item.id !== '');

    document.getElementById('imageOrder').value = JSON.stringify(orderData);
}
