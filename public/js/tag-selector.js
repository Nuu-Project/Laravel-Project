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
        // 移除所有選項的選中狀態
        tagOptions.forEach(el => el.classList.remove('selected'));

        // 清除所有標籤選擇
        Object.keys(selectedTags).forEach(type => {
            selectedTags[type] = { id: null, name: '', selected: false };
            const input = document.getElementById(`${type}-input`);
            if (input) input.value = '';
        });

        // 更新 UI
        updateSelectedTagPills();
        updateTagsSummary();

        // 清空搜尋輸入框
        tagSearchInput.value = '';

        // 重置所有標籤的可見性
        tagOptions.forEach(option => {
            option.style.display = 'flex';
            option.style.visibility = 'visible';
        });

        document.querySelectorAll('.milestone-section').forEach(section => {
            section.style.display = 'block';
            section.style.visibility = 'visible';
        });
    });

    applyTagFilters.addEventListener('click', () => {
        tagSelectionPopup.classList.add('hidden');
        updateSelectedTagPills();
        updateTagsSummary();
        document.getElementById('filterForm').submit();
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
            // 清空現有的標籤顯示
            tagsDisplay.innerHTML = '';

            // 重置標籤計數
            let selectedCount = 0;

            Object.keys(selectedTags).forEach(type => {
                const tag = selectedTags[type];
                if (tag.selected && tag.name) {  // 確保只有當標籤被選中且有名稱時才顯示
                    selectedCount++;
                    const pill = document.createElement('div');
                    pill.className = 'tag-pill flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm mr-2';
                    pill.innerHTML = `
                        <div class="flex items-center">
                            <span class="tag-icon mr-1">${getTagIcon(type)}</span>
                            <span class="tag-name mr-2">${tag.name}</span>
                            <button class="delete-tag-btn text-blue-600 hover:text-blue-800" data-tag-type="${type}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    tagsDisplay.appendChild(pill);
                }
            });

            // 更新進度條
            updateTagsSummary();

            // 添加刪除按鈕的事件監聽器
            tagsDisplay.querySelectorAll('.delete-tag-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const tagType = btn.dataset.tagType;

                    // 移除選中的選項
                    const selectedOption = document.querySelector(`.milestone-option[data-tag-type="${tagType}"].selected`);
                    if (selectedOption) {
                        selectedOption.classList.remove('selected');
                    }

                    // 清除標籤選擇
                    selectedTags[tagType] = { id: null, name: '', selected: false };
                    const input = document.getElementById(`${tagType}-input`);
                    if (input) input.value = '';

                    // 更新 UI
                    updateSelectedTagPills();
                });
            });
        } else {
            Object.keys(selectedTags).forEach(type => {
                const tag = selectedTags[type];
                const pill = document.getElementById(`selected-${type}-pill`);
                if (pill) {
                    if (tag.selected) {
                        pill.innerHTML = `
                            <div class="flex items-center">
                                <span class="tag-icon mr-1">${getTagIcon(type)}</span>
                                <span class="tag-name mr-2">${tag.name}</span>
                                <button class="delete-tag-btn ml-2 text-blue-600 hover:text-blue-800" data-tag-type="${type}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        `;
                        pill.classList.remove('hidden');

                        // Add event listener for delete button
                        const deleteBtn = pill.querySelector('.delete-tag-btn');
                        if (deleteBtn) {
                            deleteBtn.addEventListener('click', (e) => {
                                e.stopPropagation();
                                const tagType = deleteBtn.dataset.tagType;

                                // Remove selection from the option
                                const selectedOption = document.querySelector(`.milestone-option[data-tag-type="${tagType}"].selected`);
                                if (selectedOption) {
                                    selectedOption.classList.remove('selected');
                                }

                                // Clear the tag selection
                                selectedTags[tagType] = { id: null, name: '', selected: false };
                                const input = document.getElementById(`${tagType}-input`);
                                if (input) input.value = '';

                                // Update UI
                                updateSelectedTagPills();
                                updateTagsSummary();
                            });
                        }
                    } else {
                        pill.classList.add('hidden');
                    }
                }
            });
        }
    }

    function updateTagsSummary() {
        // 計算實際選中的標籤數量
        const count = Object.values(selectedTags)
            .filter(t => t.selected && t.name)  // 確保只計算有效的選中標籤
            .length;

        selectedTagsSummary.textContent = count === 0 ? '選擇標籤...' : `已選擇 ${count} 個標籤`;

        // 計算可見區段數量
        const visibleSections = Array.from(document.querySelectorAll('.milestone-section'))
            .filter(section => {
                const hasVisibleOptions = Array.from(section.querySelectorAll('.milestone-option'))
                    .some(option => option.style.display !== 'none');
                return hasVisibleOptions;
            }).length;

        const progressBar = document.getElementById('tag-progress');
        const progressBarFill = document.getElementById('tag-progress-bar');
        const progressPercentage = document.getElementById('tag-progress-percentage');

        if (progressBar && progressBarFill) {
            if (count === 0 || visibleSections === 0) {
                progressBar.classList.add('hidden');
            } else {
                progressBar.classList.remove('hidden');
                const percentage = Math.round((count / 4) * 100);  // 固定使用 4 作為分母，因為總共有 4 種類型的標籤
                progressBarFill.style.width = `${Math.min(percentage, 100)}%`;
                if (progressPercentage) {
                    progressPercentage.textContent = `${Math.min(percentage, 100)}%`;
                }
            }
        }
    }

    function positionPopup() {
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

    // 修改搜尋功能
    tagSearchInput.addEventListener('input', () => {
        const searchTerm = tagSearchInput.value.toLowerCase().trim();

        // 遍歷所有標籤選項
        tagOptions.forEach(option => {
            const tagName = option.dataset.tagName.toLowerCase();
            const tagType = option.dataset.tagType;

            if (tagName.includes(searchTerm)) {
                option.style.display = 'flex';
                option.style.visibility = 'visible';

                // 確保所有子元素都是可見的
                option.querySelectorAll('span').forEach(span => {
                    span.style.display = 'inline-block';
                    span.style.visibility = 'visible';
                });
            } else {
                option.style.display = 'none';
                option.style.visibility = 'hidden';
            }
        });

        // 更新各區段的可見性
        document.querySelectorAll('.milestone-section').forEach(section => {
            const visibleOptions = Array.from(section.querySelectorAll('.milestone-option'))
                .filter(option => option.style.display !== 'none');

            if (visibleOptions.length > 0) {
                section.style.display = 'block';
                section.style.visibility = 'visible';
            } else {
                section.style.display = 'none';
                section.style.visibility = 'hidden';
            }
        });
    });
});
