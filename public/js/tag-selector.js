document.addEventListener('DOMContentLoaded', function () {
    const tagSelectorButton = document.getElementById('tag-selector-button');
    const tagSelectionPopup = document.getElementById('tag-selection-popup');
    const closeTagSelector = document.getElementById('close-tag-selector');
    const applyTagFilters = document.getElementById('apply-tag-filters');
    const clearTagSelection = document.getElementById('clear-tag-selection');
    const selectedTagsSummary = document.getElementById('selected-tags-summary');
    const tagSearchInput = document.getElementById('tagSearchInput');
    const tagOptions = document.querySelectorAll('.milestone-option');

    let selectedTags = {};
    ['grade', 'semester', 'subject', 'category'].forEach(type => {
        const id = window.initialSelectedTags?.[type] || null;
        selectedTags[type] = {
            id: id,
            name: '',
            selected: !!id
        };
    });

    initializeSelectedTags();
    updateTagsSummary();

    tagSelectorButton.addEventListener('click', () => {
        tagSelectionPopup.classList.remove('hidden');
        positionPopup();
    });

    closeTagSelector.addEventListener('click', () => {
        tagSelectionPopup.classList.add('hidden');
    });

    clearTagSelection.addEventListener('click', () => {
        tagOptions.forEach(el => el.classList.remove('selected'));
        Object.keys(selectedTags).forEach(type => {
            selectedTags[type] = { id: null, name: '', selected: false };
            const input = document.getElementById(`${type}-input`);
            if (input) input.value = '';
            const pill = document.getElementById(`selected-${type}-pill`);
            if (pill) pill.classList.add('hidden');
        });
        updateTagsSummary();
    });

    applyTagFilters.addEventListener('click', () => {
        tagSelectionPopup.classList.add('hidden');
        updateSelectedTagPills();
    });

    document.addEventListener('click', event => {
        if (!tagSelectionPopup.contains(event.target) &&
            !tagSelectorButton.contains(event.target) &&
            !tagSelectionPopup.classList.contains('hidden')) {
            tagSelectionPopup.classList.add('hidden');
        }
    });

    tagOptions.forEach(option => {
        option.addEventListener('click', function () {
            const tagType = this.dataset.tagType;
            const tagId = this.dataset.tagId;
            const tagName = this.dataset.tagName;

            document.querySelectorAll(`.milestone-option[data-tag-type="${tagType}"]`)
                .forEach(el => el.classList.remove('selected'));

            this.classList.add('selected');

            const input = document.getElementById(`${tagType}-input`);
            if (input) input.value = tagId;

            selectedTags[tagType] = { id: tagId, name: tagName, selected: true };

            updateSelectedTagPills();
            updateTagsSummary();
        });
    });

    tagSearchInput.addEventListener('input', () => {
        const searchTerm = tagSearchInput.value.toLowerCase();
        tagOptions.forEach(option => {
            const tagName = option.dataset.tagName.toLowerCase();
            option.style.display = tagName.includes(searchTerm) ? '' : 'none';
        });
    });

    function initializeSelectedTags() {
        Object.keys(selectedTags).forEach(type => {
            if (selectedTags[type].id) {
                const option = document.querySelector(`.milestone-option[data-tag-type="${type}"][data-tag-id="${selectedTags[type].id}"]`);
                if (option) {
                    option.classList.add('selected');
                    selectedTags[type].name = option.dataset.tagName;
                    selectedTags[type].selected = true;
                }
            }
        });
        updateSelectedTagPills();
    }

    function updateSelectedTagPills() {
        const tagsDisplay = document.getElementById('selected-tags-display');
        if (tagsDisplay) {
            tagsDisplay.innerHTML = ''; // 清空現有標籤
            Object.keys(selectedTags).forEach(type => {
                const tag = selectedTags[type];
                if (tag.selected) {
                    const pill = document.createElement('div');
                    pill.className = 'tag-pill flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm';
                    pill.innerHTML = `
                        <span class="tag-icon mr-1">${getTagIcon(type)}</span>
                        <span class="mr-2">${tag.name}</span>
                    `;
                    tagsDisplay.appendChild(pill);
                }
            });
        } else {
            Object.keys(selectedTags).forEach(type => {
                const tag = selectedTags[type];
                const pill = document.getElementById(`selected-${type}-pill`);
                if (pill) {
                    if (tag.selected) {
                        pill.innerHTML = `
                            <span class="tag-icon">${getTagIcon(type)}</span>
                            <span>${tag.name}</span>
                        `;
                        pill.classList.remove('hidden');
                    } else {
                        pill.classList.add('hidden');
                    }
                }
            });
        }
    }

    function updateTagsSummary() {
        const count = Object.values(selectedTags).filter(t => t.selected).length;
        selectedTagsSummary.textContent = count === 0 ? '選擇標籤...' : `已選擇 ${count} 個標籤`;

        // 計算可見的標籤類型數量
        const visibleSections = Array.from(document.querySelectorAll('.milestone-section')).filter(section => {
            const hasVisibleOptions = Array.from(section.querySelectorAll('.milestone-option'))
                .some(option => option.style.display !== 'none');
            return hasVisibleOptions;
        }).length;

        // 更新進度條和百分比
        const progressBar = document.getElementById('tag-progress');
        const progressBarFill = document.getElementById('tag-progress-bar');
        const progressPercentage = document.getElementById('tag-progress-percentage');

        if (progressBar && progressBarFill) {
            if (count === 0 || visibleSections === 0) {
                progressBar.classList.add('hidden');
            } else {
                progressBar.classList.remove('hidden');
                const percentage = Math.round((count / visibleSections) * 100);
                progressBarFill.style.width = `${Math.min(percentage, 100)}%`;
                if (progressPercentage) {
                    progressPercentage.textContent = `${Math.min(percentage, 100)}%`;
                }
            }
        }
    }

    function positionPopup() {
        // popup 使用 fixed 置中
        tagSelectionPopup.style.position = 'fixed';
        tagSelectionPopup.style.top = '50%';
        tagSelectionPopup.style.left = '50%';
        tagSelectionPopup.style.transform = 'translate(-50%, -50%)';
    }

    function getTagIcon(type) {
        switch (type) {
            case 'grade': return '📚';
            case 'semester': return '🗓️';
            case 'subject': return '📝';
            case 'category': return '📋';
            default: return '🏷️';
        }
    }

    window.addEventListener('resize', () => {
        if (!tagSelectionPopup.classList.contains('hidden')) {
            positionPopup();
        }
    });
});
