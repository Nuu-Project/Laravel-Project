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
        });

        updateSelectedTagPills();
        updateTagsSummary();

        tagSearchInput.value = '';

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
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.submit();
        }
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
            tagsDisplay.innerHTML = '';

            let selectedCount = 0;

            Object.keys(selectedTags).forEach(type => {
                const tag = selectedTags[type];
                if (tag.selected && tag.name) {
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

            updateTagsSummary();

            tagsDisplay.querySelectorAll('.delete-tag-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const tagType = btn.dataset.tagType;

                    const selectedOption = document.querySelector(`.milestone-option[data-tag-type="${tagType}"].selected`);
                    if (selectedOption) {
                        selectedOption.classList.remove('selected');
                    }

                    selectedTags[tagType] = { id: null, name: '', selected: false };
                    const input = document.getElementById(`${tagType}-input`);
                    if (input) input.value = '';

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
                        const deleteBtn = pill.querySelector('.delete-tag-btn');
                        if (deleteBtn) {
                            deleteBtn.addEventListener('click', (e) => {
                                e.stopPropagation();
                                const tagType = deleteBtn.dataset.tagType;
                                const selectedOption = document.querySelector(`.milestone-option[data-tag-type="${tagType}"].selected`);
                                if (selectedOption) {
                                    selectedOption.classList.remove('selected');
                                }
                                selectedTags[tagType] = { id: null, name: '', selected: false };
                                const input = document.getElementById(`${tagType}-input`);
                                if (input) input.value = '';
                                pill.classList.add('hidden');
                                pill.innerHTML = '';
                                updateSelectedTagPills();
                                updateTagsSummary();
                            });
                        }
                    } else {
                        pill.classList.add('hidden');
                        pill.innerHTML = '';
                    }
                }
            });
        }
    }

    function updateTagsSummary() {
        const count = Object.values(selectedTags)
            .filter(t => t.selected && t.name)
            .length;

        selectedTagsSummary.textContent = count === 0 ? '選擇標籤...' : `已選擇 ${count} 個標籤`;

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
                const percentage = Math.round((count / 4) * 100);
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

    tagSearchInput.addEventListener('input', () => {
        const searchTerm = tagSearchInput.value.toLowerCase().trim();

        tagOptions.forEach(option => {
            const tagName = option.dataset.tagName.toLowerCase();
            const tagType = option.dataset.tagType;

            if (tagName.includes(searchTerm)) {
                option.style.display = 'flex';
                option.style.visibility = 'visible';

                option.querySelectorAll('span').forEach(span => {
                    span.style.display = 'inline-block';
                    span.style.visibility = 'visible';
                });
            } else {
                option.style.display = 'none';
                option.style.visibility = 'hidden';
            }
        });

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
