// 設置 AJAX 的默認 headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function showSuspendDialog(userId, userName) {
    // 定義所有時間選項
    const durationOptions = [
        { value: '60', label: '60秒' },
        { value: '300', label: '5分' },
        { value: '600', label: '10分' },
        { value: '3600', label: '1小時' },
        { value: '86400', label: '1天' },
        { value: '604800', label: '1週' }
    ];

    // 生成選項的 HTML
    const optionsHtml = durationOptions.map(option => `
        <label class="duration-option">
            <input type="radio" name="duration" value="${option.value}" class="hidden">
            <span class="text-sm">${option.label}</span>
        </label>
    `).join('');

    Swal.fire({
        html: `
            <div class="mb-4">${userName}</div>
            <div class="mb-2"><strong>停用時間</strong></div>
            <div class="flex flex-wrap justify-center gap-2">
                ${optionsHtml}
            </div>
            <div class="mt-4">
                <input type="text" id="suspend-reason" class="w-full px-3 py-2 border rounded" placeholder="請輸入停用原因" required style="max-width: 400px">
            </div>
            <style>
                .duration-option {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0.5rem;
                    border: 1px solid #e2e8f0;
                    border-radius: 0.25rem;
                    cursor: pointer;
                    width: 70px;
                    transition: all 0.2s;
                }
                .duration-option:hover {
                    background-color: #f3f4f6;
                }
                .duration-option.selected {
                    background-color: #e2e8f0;
                    border-color: #4a5568;
                }
                .swal2-popup {
                    padding: 1.5rem;
                }
                .swal2-html-container {
                    margin: 0;
                }
            </style>
        `,
        width: '500px',
        padding: '1rem',
        showCancelButton: true,
        confirmButtonText: '停用',
        cancelButtonText: '取消',
        showCloseButton: true,
        footer: '<a href="#">瞭解更多看要不要用規則之類的</a>',
        customClass: {
            container: 'swal-compact',
            popup: 'swal-compact-popup',
        },
        didOpen: () => {
            document.querySelectorAll('.duration-option').forEach(label => {
                label.addEventListener('click', function () {
                    document.querySelectorAll('.duration-option').forEach(l => l.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });
        },
        preConfirm: () => {
            const selectedDuration = document.querySelector('input[name="duration"]:checked')?.value;
            const reason = document.getElementById('suspend-reason').value.trim();

            if (!selectedDuration) {
                Swal.showValidationMessage('請選擇停用時間');
                return false;
            }

            if (!reason) {
                Swal.showValidationMessage('請輸入停用原因');
                return false;
            }

            return { duration: selectedDuration, reason: reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/users/${userId}/suspend`,
                method: 'POST',
                data: {
                    user_id: userId,
                    duration: result.value.duration,
                    reason: result.value.reason
                },
                success: function (response) {
                    Swal.fire({
                        title: '用戶已被停用',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        // 成功後重新載入頁面
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire('錯誤', '無法暫停用戶', 'error');
                }
            });
        }
    });
}
