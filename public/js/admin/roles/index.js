document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.role-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const role = this.dataset.role;
            const checkedBoxes = document.querySelectorAll(
                `.role-checkbox[data-role="${role}"]:checked`);

            const modifyBtn = document.querySelector(
                `#modify${role.charAt(0).toUpperCase() + role.slice(1)}Btn`);

            // 顯示或隱藏按鈕
            modifyBtn.classList.toggle('hidden', checkedBoxes.length === 0);
        });
    });

    // 當修改按鈕被點擊時提交表單
    const modifyAdminBtn = document.getElementById('modifyAdminBtn');
    const modifyUserBtn = document.getElementById('modifyUserBtn');

    modifyAdminBtn?.addEventListener('click', function () {
        document.getElementById('adminForm').submit();
    });

    modifyUserBtn?.addEventListener('click', function () {
        document.getElementById('userForm').submit();
    });
});
