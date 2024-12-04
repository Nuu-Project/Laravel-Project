document.addEventListener('DOMContentLoaded', function () {
    // 搜索功能
    function initializeSearch() {
        const searchInput = document.getElementById('search-reviews');
        const reviewsTable = document.getElementById('reviews-table');
        const noResults = document.getElementById('no-results');
        const reviewsList = document.querySelector('.overflow-x-auto tbody');
        const rows = reviewsList.querySelectorAll('tr');

        reviewsTable.style.display = 'block';
        noResults.classList.add('hidden');

        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();
            let hasResults = false;

            rows.forEach(row => {
                const userName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                row.style.display = userName.includes(searchTerm) ? '' : 'none';
                if (userName.includes(searchTerm)) hasResults = true;
            });

            reviewsTable.style.display = 'block';
            noResults.classList[hasResults ? 'add' : 'remove']('hidden');
        });
    }

    // 文字截斷功能
    function truncateText(text, length) {
        const characters = Array.from(text);
        if (characters.length <= length) return text;

        let count = 0;
        let result = '';

        for (let char of characters) {
            const charWidth = /[a-zA-Z0-9]/.test(char) ? 1 : 2;
            if (count + charWidth > length) break;
            count += charWidth;
            result += char;
        }

        return result;
    }

    // 展開/收合功能
    function initializeMessageExpansion() {
        document.querySelectorAll('.message-container').forEach(container => {
            const content = container.querySelector('.message-content');
            const expandBtn = container.querySelector('.expand-btn');
            const fullText = content.textContent;

            if (expandBtn) {
                content.textContent = truncateText(fullText, 15);

                expandBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const isExpanded = this.classList.contains('expanded');

                    content.textContent = isExpanded ? truncateText(fullText, 15) : fullText;
                    content.classList.toggle('expanded');
                    this.querySelector('svg').classList.toggle('rotate-180');
                    this.classList.toggle('expanded');
                });
            }
        });
    }

    // 響應式處理
    function handleResize() {
        document.querySelectorAll('.message-content').forEach(content => {
            if (!content.classList.contains('expanded')) {
                const fullText = content.getAttribute('data-full-text') || content.textContent;
                content.textContent = truncateText(fullText, 15);
            }
        });
    }

    // 初始化所有功能
    initializeSearch();
    initializeMessageExpansion();
    window.addEventListener('resize', handleResize);
});
