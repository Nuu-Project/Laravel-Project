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

    const imageContainer = document.querySelector('.relative.mb-4');
    if (imageContainer) {
        imageContainer.addEventListener('click', function (event) {
            const visibleImage = imageContainer.querySelector('img:not(.hidden)');

            if (visibleImage && event.target.tagName === 'IMG') {
                const overlay = document.createElement('div');
                overlay.classList.add('image-modal-overlay');

                const modalImage = document.createElement('img');
                modalImage.src = visibleImage.src;
                modalImage.alt = '放大圖片';
                modalImage.classList.add('image-modal-content');

                const closeButton = document.createElement('span');
                closeButton.innerHTML = '&times;';
                closeButton.classList.add('image-modal-close');

                overlay.appendChild(modalImage);
                overlay.appendChild(closeButton);
                document.body.appendChild(overlay);

                document.body.style.overflow = 'hidden';

                overlay.addEventListener('click', function (e) {
                    if (e.target === overlay || e.target === closeButton) {
                        document.body.removeChild(overlay);
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    }

    // 處理展開/收縮留言功能
    const toggleButtons = document.querySelectorAll('.toggle-replies');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.getAttribute('data-message-id');
            const isExpanded = this.getAttribute('data-is-expanded') === 'true';
            const totalHidden = this.getAttribute('data-total-hidden');
            
            // 獲取該留言下所有回覆
            const replies = document.querySelectorAll(`.reply-item[data-message-id="${messageId}"]`);
            
            if (isExpanded) {
                // 收起留言，只顯示前4則
                replies.forEach((reply, index) => {
                    if (index >= 4) {
                        reply.classList.add('hidden');
                    }
                });
                
                // 更新按鈕文字和狀態
                this.textContent = `查看更多留言 (${totalHidden})`;
                this.setAttribute('data-is-expanded', 'false');
            } else {
                // 展開所有留言
                replies.forEach(reply => {
                    reply.classList.remove('hidden');
                });
                
                // 更新按鈕文字和狀態
                this.textContent = '縮小留言區';
                this.setAttribute('data-is-expanded', 'true');
            }
        });
    });
    
    // 初始化隱藏回覆表單
    const replyForms = document.querySelectorAll('[id^="replyForm"]');
    replyForms.forEach(form => {
        form.style.display = 'none';
    });

    if (window.location.hash) {
        const messageId = window.location.hash;
        const messageElement = document.querySelector(messageId);

        if (messageElement) {
            const urlParams = new URLSearchParams(window.location.search);
            const scrollCenter = urlParams.get('scrollCenter');
            const highlightReplyId = urlParams.get('highlight');

            if (highlightReplyId) {
                const replyElement = document.getElementById(`reply-${highlightReplyId}`);

                if (replyElement) {
                    setTimeout(() => {
                        if (scrollCenter === 'true') {
                            const rect = messageElement.getBoundingClientRect();
                            const windowHeight = window.innerHeight;
                            const elementHeight = rect.height;
                            const offsetY = rect.top + window.pageYOffset - (windowHeight / 4);

                            window.scrollTo({
                                top: offsetY,
                                behavior: 'smooth'
                            });
                        } else {
                            messageElement.scrollIntoView({ behavior: 'smooth' });
                        }

                        replyElement.style.transition = 'background-color 0.5s';
                        replyElement.style.backgroundColor = 'rgba(255, 255, 0, 0.3)';

                        setTimeout(() => {
                            const replyRect = replyElement.getBoundingClientRect();
                            if (replyRect.top < 0 || replyRect.bottom > window.innerHeight) {
                                replyElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }

                            setTimeout(() => {
                                replyElement.style.backgroundColor = '';
                            }, 2000);
                        }, 300);
                    }, 300);
                }
            } else if (scrollCenter === 'true') {
                setTimeout(() => {
                    const rect = messageElement.getBoundingClientRect();
                    const windowHeight = window.innerHeight;
                    const elementHeight = rect.height;
                    const offsetY = rect.top + window.pageYOffset - (windowHeight / 2) + (elementHeight / 2);

                    window.scrollTo({
                        top: offsetY,
                        behavior: 'smooth'
                    });

                    messageElement.style.transition = 'background-color 0.5s';
                    messageElement.style.backgroundColor = 'rgba(255, 255, 0, 0.3)';
                    setTimeout(() => {
                        messageElement.style.backgroundColor = '';
                    }, 2000);
                }, 300);
            } else {
                messageElement.scrollIntoView({ behavior: 'smooth' });

                messageElement.classList.add('highlight-message');
                setTimeout(() => {
                    messageElement.classList.remove('highlight-message');
                }, 2000);
            }
        }
    }
});

window.addEventListener('load', function () {
    var reportButton = document.getElementById('reportButton');
    if (reportButton) {
        reportButton.addEventListener('click', function (e) {
            handleReport(e, '商品', this.dataset.productId);
        });
    }
});

function handleReport(event, entityType, entityId) {
    event.preventDefault();

    const reportLink = event.target.closest('[data-reports]');
    if (!reportLink) {
        return;
    }

    const reports = JSON.parse(reportLink.dataset.reports || '{}');
    const storeUrl = reportLink.dataset.storeUrl;

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
            if (!reportId) {
                Swal.showValidationMessage('請選擇檢舉原因');
                return false;
            }
            return { reportId, customReason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { reportId, customReason } = result.value;

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
                    if (res.status === 401) {
                        throw new Error('未登入');
                    }
                    return res.json();
                })
                .then(data => {
                    Swal.fire({
                        title: '檢舉已送出',
                        text: '感謝您的回報，我們會盡快處理',
                        icon: 'success',
                        confirmButtonText: '確定'
                    });
                })
                .catch(err => {
                    Swal.fire({
                        title: '檢舉已送出',
                        text: '感謝您的回報，我們會盡快處理',
                        icon: 'success',
                        confirmButtonText: '確定'
                    });
                });
        }
    });
}

document.getElementById('reportButton')?.addEventListener('click', function (e) {
    handleReport(e, '商品', this.dataset.productId);
});

document.body.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-report-type="message"]');
    if (trigger) {
        e.preventDefault();
        const messageId = trigger.dataset.messageId;
        handleReport(e, '留言', messageId);
    }
});

// 更新 toggleReplyForm 函數，以便可以全局訪問
function toggleReplyForm(messageId) {
    const form = document.getElementById(`replyForm${messageId}`);
    form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
}

