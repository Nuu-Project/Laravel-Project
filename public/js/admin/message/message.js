document.addEventListener('DOMContentLoaded', function () {
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

    function handleResize() {
        document.querySelectorAll('.message-content').forEach(content => {
            if (!content.classList.contains('expanded')) {
                const fullText = content.getAttribute('data-full-text') || content.textContent;
                content.textContent = truncateText(fullText, 15);
            }
        });
    }

    // 
    initializeMessageExpansion();
    window.addEventListener('resize', handleResize);
});
