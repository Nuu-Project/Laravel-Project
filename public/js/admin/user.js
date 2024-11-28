
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
                url: `/admin/users/${userId}/suspend`,
                method: 'POST',
                data: {
                    user_id: userId,
                    duration: duration,
                    reason: suspendReason,
                    _token: '{{ csrf_token() }}'
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

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-users');
    const searchResults = document.getElementById('search-results');
    const allUsersList = document.getElementById('all-users-list');
    const users = document.querySelectorAll('#all-users-list tbody tr');
    const searchResultsBody = searchResults.querySelector('tbody');

    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();

        searchResultsBody.innerHTML = '';

        if (searchTerm.length > 0) {
            searchResults.style.display = 'block';
            allUsersList.style.display = 'none';

            users.forEach(user => {
                const userName = user.querySelector('td:nth-child(1)').textContent
                    .toLowerCase();
                const userPosition = user.querySelector('td:nth-child(2)').textContent
                    .toLowerCase();

                if (userName.includes(searchTerm) || userPosition.includes(searchTerm)) {
                    const clonedRow = user.cloneNode(true);
                    searchResultsBody.appendChild(clonedRow);
                }
            });
        } else {
            searchResults.style.display = 'none';
            allUsersList.style.display = 'block';
        }
    });
});

