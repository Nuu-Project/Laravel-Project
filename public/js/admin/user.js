// 設置 AJAX 的默認 headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function showSuspendDialog(userId, userName) {
    Swal.fire({
        html: `
                ${userName}<br><br>
                <strong>停用時間</strong><br>
                <input type="text" id="suspend-reason" class="swal2-input" placeholder="請輸入停用原因">
            `,
        input: 'radio',
        inputOptions: {
            '60': '60秒',
            '300': '5分',
            '600': '10分',
            '3600': '1小時',
            '86400': '1天',
            '604800': '1週'
        },
        inputValidator: (value) => {
            if (!value) {
                return '請選擇一個選項！'
            }
        },
        showCancelButton: true,
        confirmButtonText: '停用',
        cancelButtonText: '取消',
        showCloseButton: true,
        footer: '<a href="#">瞭解更多看要不要用規則之類的</a>'
    }).then((result) => {
        if (result.isConfirmed) {
            var suspendReason = document.getElementById('suspend-reason').value;
            var duration = parseInt(result.value);
            $.ajax({
                url: `/api/users/${userId}/suspend`,
                method: 'POST',
                data: {
                    user_id: userId,
                    duration: duration,
                    reason: suspendReason
                },
                success: function (response) {
                    Swal.fire('用戶已被停用', response.message, 'success');
                },
                error: function (xhr) {
                    Swal.fire('錯誤', '無法暫停用戶', 'error');
                }
            });
        }
    });
}
