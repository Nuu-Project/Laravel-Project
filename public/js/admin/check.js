window.addEventListener('load', function () {
    var reportButton = document.getElementById('reportbutton');
    if (reportButton) {
        reportButton.addEventListener('click', function () {
            // 假設這是從資料庫獲取的檢舉資料
            var reportData = [{
                reason: '不當內容',
                customreason: '名稱極度不雅',
                date: '2023-05-20'
            },
            {
                reason: '侵犯版權',
                customreason: '名稱觸犯到版權了',
                date: '2023-05-21'
            },
            {
                reason: '虛假資訊',
                customreason: '商品描述與實體不符',
                date: '2023-05-22'
            }
            ];

            var reportContent = reportData.map(function (report) {
                return `<div class="mb-2 p-2 bg-gray-100 rounded">
                                <p class="font-bold">${report.reason}</p>
                                <p class="font-bold">${report.customreason}</p>
                                <p class="text-sm text-gray-600">檢舉日期：${report.date}</p>
                            </div>`;
            }).join('');

            Swal.fire({
                title: '檢舉詳情',
                html: `<div class="max-h-60 overflow-y-auto">
                              ${reportContent}
                           </div>`,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: '確定',
                customClass: {
                    container: 'swal-wide',
                    popup: 'swal-tall'
                }
            });
        });
    }
});
