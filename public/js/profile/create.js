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

function removeImage(number) {
    const input = document.getElementById('image' + number);
    const preview = document.getElementById('preview' + number);
    const placeholder = document.getElementById('placeholder' + number);
    const deleteButton = document.getElementById('deleteButton' + number);

    input.value = '';
    preview.src = "";
    preview.classList.add('hidden');
    placeholder.classList.remove('hidden');
    deleteButton.classList.add('hidden');
}

document.getElementById('productForm').addEventListener('submit', function (event) {
    event.preventDefault();

    // 檢查所有必填欄位
    var requiredFields = ['name', 'price', 'description'];
    var requiredSelects = ['grade', 'semester', 'category'];
    var allFieldsFilled = true;

    // 檢查一般輸入欄位
    for (var i = 0; i < requiredFields.length; i++) {
        var field = document.getElementById(requiredFields[i]);
        if (!field.value) {
            allFieldsFilled = false;
            break;
        }
    }

    // 檢查下拉選單
    for (var i = 0; i < requiredSelects.length; i++) {
        var select = document.getElementById(requiredSelects[i]);
        if (!select.value || select.value === "") {
            allFieldsFilled = false;
            break;
        }
    }

    // 檢查第一張圖片是否已上傳
    var firstImage = document.getElementById('image0').files.length > 0;

    if (!allFieldsFilled) {
        alert('請填寫所有必填欄位！');
    } else if (!firstImage) {
        alert('請上傳第一張圖片！');
    } else {
        // 所有欄位都已填寫，提交表單
        alert('商品已成功刊登！');
        this.submit();
    }
});
