document.addEventListener('DOMContentLoaded', function () {
    let currentIndex = 0;
    const images = document.querySelectorAll('img[data-index]');
    const thumbnails = document.querySelectorAll('.thumbnail');
    const totalImages = images.length;

    function updateDisplay(newIndex) {
        images[currentIndex].classList.add('hidden');
        thumbnails[currentIndex].classList.remove('border-blue-500');
        thumbnails[currentIndex].classList.add('border-gray-300');

        currentIndex = (newIndex + totalImages) % totalImages;

        images[currentIndex].classList.remove('hidden');
        thumbnails[currentIndex].classList.remove('border-gray-300');
        thumbnails[currentIndex].classList.add('border-blue-500');
    }

    function changeImage(direction) {
        let newIndex = (currentIndex + direction + totalImages) % totalImages;
        updateDisplay(newIndex);
    }

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', () => updateDisplay(index));
    });

    document.getElementById('leftArrow')?.addEventListener('click', () => changeImage(-1));
    document.getElementById('rightArrow')?.addEventListener('click', () => changeImage(1));

    updateDisplay(0);

    // 圖片模態視窗功能
    const imageContainer = document.querySelector('.relative.mb-4');
    if (imageContainer) {
        imageContainer.addEventListener('click', function (event) {
            // 找到目前可見的圖片
            const visibleImage = imageContainer.querySelector('img:not(.hidden)');

            if (visibleImage && event.target.tagName === 'IMG') {
                // 建立覆蓋層
                const overlay = document.createElement('div');
                overlay.classList.add('image-modal-overlay');

                // 建立模態視窗的圖片元素
                const modalImage = document.createElement('img');
                modalImage.src = visibleImage.src;
                modalImage.alt = '放大圖片';
                modalImage.classList.add('image-modal-content');

                // 建立關閉按鈕
                const closeButton = document.createElement('span');
                closeButton.innerHTML = '&times;';
                closeButton.classList.add('image-modal-close');

                // 附加元素
                overlay.appendChild(modalImage);
                overlay.appendChild(closeButton);
                document.body.appendChild(overlay);

                // 防止背景滾動
                document.body.style.overflow = 'hidden';

                // 點擊覆蓋層或關閉按鈕時關閉模態視窗
                overlay.addEventListener('click', function (e) {
                    if (e.target === overlay || e.target === closeButton) {
                        document.body.removeChild(overlay);
                        document.body.style.overflow = ''; // 恢復背景滾動
                    }
                });
            }
        });
    }
});

window.addEventListener('load', function () {
    console.log('頁面已完全加載');
    var reportButton = document.getElementById('reportButton');
    if (reportButton) {
        console.log('找到檢舉按鈕');
        reportButton.addEventListener('click', function (e) {
            handleReport(e, '商品', this.dataset.productId);
        });
    } else {
        console.error('未找到檢舉按鈕');
    }
});

function handleReport(event, entityType, entityId) {
    event.preventDefault();
    console.log(`處理檢舉: ${entityType}, ID: ${entityId}`);

    const reportLink = event.target.closest('[data-reports]');
    if (!reportLink) {
        console.error('未找到 data-reports 的元素');
        return;
    }

    const reports = JSON.parse(reportLink.dataset.reports || '{}');
    const storeUrl = reportLink.dataset.storeUrl;
    console.log('檢舉資料:', { reports, storeUrl });

    Swal.fire({
        title: `檢舉${entityType}`,
        html: `
            <select id="reportReason" class="swal2-input">
                <option value="" disabled selected>選擇檢舉原因</option>
                ${Object.entries(reports).map(([key, value]) =>
            `<option value="${key}">${value.zh_TW || value}</option>`
        ).join('')}
            </select>
            <textarea id="customReason" class="swal2-textarea" placeholder="輸入自定義原因"
                      style="width:80%;height:80px;margin-top:1rem;"></textarea>
        `,
        preConfirm: () => {
            const reportId = document.getElementById('reportReason').value;
            const customReason = document.getElementById('customReason').value;
            if (!reportId && !customReason) {
                Swal.showValidationMessage('請選擇原因或輸入自定義內容');
            }
            return { reportId, customReason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { reportId, customReason } = result.value;
            console.log('送出檢舉:', { reportId, customReason, storeUrl });

            fetch(storeUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${localStorage.getItem("token")}`,
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    report_type_id: reportId,
                    description: customReason || '無補充說明'
                })
            })
                .then(res => {
                    console.log('Response Status:', res.status);
                    if (res.status === 401) {
                        throw new Error('未登入');
                    }
                    return res.json();
                })
                .then(data => {
                    // 無論是第一次還是重複檢舉，都顯示相同的成功訊息
                    Swal.fire({
                        title: '檢舉已送出',
                        text: '感謝您的回報，我們會盡快處理',
                        icon: 'success',
                        confirmButtonText: '確定'
                    });
                })
                .catch(err => {
                    // error，顯示相同的成功訊息
                    Swal.fire({
                        title: '檢舉已送出',
                        text: '感謝您的回報，我們會盡快處理',
                        icon: 'success',
                        confirmButtonText: '確定'
                    });
                    console.log("檢舉處理:", err);
                });
        }
    });
}

document.getElementById('reportButton')?.addEventListener('click', function (e) {
    handleReport(e, '商品', this.dataset.productId);
});

document.body.addEventListener('click', function (e) {
    console.log('點擊元素:', e.target);
    const trigger = e.target.closest('[data-report-type="message"]');
    if (trigger) {
        console.log('偵測到留言檢舉點擊:', trigger);
        e.preventDefault();
        const messageId = trigger.dataset.messageId;
        handleReport(e, '留言', messageId);
    }
});

// 留言回覆表單切換功能
function toggleReplyForm(messageId) {
    const form = document.getElementById(`replyForm${messageId}`);
    form.classList.toggle('hidden');
    const textarea = form.querySelector('textarea');
    if (!form.classList.contains('hidden')) {
        textarea.focus();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // 檢查 URL 是否包含錨點
    if (window.location.hash) {
        const messageId = window.location.hash;
        const messageElement = document.querySelector(messageId);

        if (messageElement) {
            // 檢查是否要滾動到中間
            const urlParams = new URLSearchParams(window.location.search);
            const scrollCenter = urlParams.get('scrollCenter');
            const highlightReplyId = urlParams.get('highlight');

            // 處理需要高亮的回覆
            if (highlightReplyId) {
                const replyElement = document.getElementById(`reply-${highlightReplyId}`);

                if (replyElement) {
                    setTimeout(() => {
                        // 先滾動到主留言
                        if (scrollCenter === 'true') {
                            const rect = messageElement.getBoundingClientRect();
                            const windowHeight = window.innerHeight;
                            const elementHeight = rect.height;
                            const offsetY = rect.top + window.pageYOffset - (windowHeight / 4); // 顯示在上方1/4處

                            window.scrollTo({
                                top: offsetY,
                                behavior: 'smooth'
                            });
                        } else {
                            messageElement.scrollIntoView({ behavior: 'smooth' });
                        }

                        // 然後高亮回覆
                        replyElement.style.transition = 'background-color 0.5s';
                        replyElement.style.backgroundColor = 'rgba(255, 255, 0, 0.3)';

                        // 300ms後再滾動到回覆位置
                        setTimeout(() => {
                            const replyRect = replyElement.getBoundingClientRect();
                            if (replyRect.top < 0 || replyRect.bottom > window.innerHeight) {
                                replyElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }

                            // 2秒後移除高亮
                            setTimeout(() => {
                                replyElement.style.backgroundColor = '';
                            }, 2000);
                        }, 300);
                    }, 300);
                }
            } else if (scrollCenter === 'true') {
                // 原有的中間滾動處理
                setTimeout(() => {
                    const rect = messageElement.getBoundingClientRect();
                    const windowHeight = window.innerHeight;
                    const elementHeight = rect.height;
                    const offsetY = rect.top + window.pageYOffset - (windowHeight / 2) + (elementHeight / 2);

                    // 滾動到計算出的位置
                    window.scrollTo({
                        top: offsetY,
                        behavior: 'smooth'
                    });

                    // 突顯該留言
                    messageElement.style.transition = 'background-color 0.5s';
                    messageElement.style.backgroundColor = 'rgba(255, 255, 0, 0.3)';
                    setTimeout(() => {
                        messageElement.style.backgroundColor = '';
                    }, 2000); // 2秒後移除突顯效果
                }, 300); // 短暫延遲確保DOM已經渲染
            } else {
                // 原始的滾動行為
                messageElement.scrollIntoView({ behavior: 'smooth' });

                // 突顯該留言（可選）
                messageElement.classList.add('highlight-message');
                setTimeout(() => {
                    messageElement.classList.remove('highlight-message');
                }, 2000); // 3秒後移除突顯效果
            }
        }
    }
});
