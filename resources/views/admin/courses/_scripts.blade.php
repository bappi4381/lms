{{-- JavaScript for course form: repeater Alpine components + slug auto-gen + thumbnail upload --}}
<script>
/**
 * Auto-generate slug from title_en (only if slug is empty)
 */
function autoSlug(value) {
    const slugField = document.getElementById('slug');
    if (slugField && !slugField.dataset.manual) {
        slugField.value = value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
}

// Mark slug as manually edited once user touches it
document.addEventListener('DOMContentLoaded', function () {
    const slugField = document.getElementById('slug');
    if (slugField) {
        slugField.addEventListener('input', function () {
            this.dataset.manual = 'true';
        });
    }
});

/**
 * Handle thumbnail file upload via fetch (ajax to upload endpoint).
 * For now we just preview the image locally.
 */
function handleThumbnailUpload(input) {
    const file = input.files[0];
    if (!file) return;
    // Show local preview
    const reader = new FileReader();
    reader.onload = function(e) {
        // Find or create preview img
        let preview = document.getElementById('thumbnail_preview');
        if (!preview) {
            preview = document.createElement('img');
            preview.id = 'thumbnail_preview';
            preview.className = 'w-32 h-20 object-cover rounded mt-2';
            preview.style.borderRadius = '3px';
            preview.style.border = '1px solid var(--a-line)';
            input.parentElement.appendChild(preview);
        }
        preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
    // Note: actual upload is handled via the form's multipart submission
    // The controller will handle the file upload and set the thumbnail path
}

/**
 * Alpine component factory for simple string repeaters.
 * @param {string} fieldId - The hidden input element ID
 * @param {string} itemKey - The key for each item (e.g. 'feature', 'tool', 'item')
 */
function repeater(fieldId, itemKey) {
    return {
        items: [],
        fieldId: fieldId,
        itemKey: itemKey,

        init() {
            const hiddenEl = document.getElementById(fieldId + '_json');
            if (hiddenEl) {
                try {
                    const raw = JSON.parse(hiddenEl.value || '[]');
                    // Normalize: if array of strings, wrap into objects
                    this.items = raw.map(item => {
                        if (typeof item === 'string') {
                            return { [itemKey]: item };
                        }
                        return item;
                    });
                } catch (e) {
                    this.items = [];
                }
            }
        },

        add() {
            this.items.push({ [this.itemKey]: '' });
            this.save();
        },

        remove(index) {
            this.items.splice(index, 1);
            this.save();
        },

        save() {
            const hiddenEl = document.getElementById(this.fieldId + '_json');
            if (hiddenEl) {
                hiddenEl.value = JSON.stringify(
                    this.items.filter(item => (item[this.itemKey] || '').trim() !== '')
                );
            }
        }
    };
}

/**
 * Alpine component factory for FAQ repeaters (question + answer).
 * @param {string} fieldId - The hidden input element ID
 */
function faqRepeater(fieldId) {
    return {
        items: [],
        fieldId: fieldId,

        init() {
            const hiddenEl = document.getElementById(fieldId + '_json');
            if (hiddenEl) {
                try {
                    const raw = JSON.parse(hiddenEl.value || '[]');
                    this.items = raw.map(item => ({
                        question: item.question || '',
                        answer: item.answer || ''
                    }));
                } catch (e) {
                    this.items = [];
                }
            }
        },

        add() {
            this.items.push({ question: '', answer: '' });
            this.save();
        },

        remove(index) {
            this.items.splice(index, 1);
            this.save();
        },

        save() {
            const hiddenEl = document.getElementById(this.fieldId + '_json');
            if (hiddenEl) {
                hiddenEl.value = JSON.stringify(
                    this.items.filter(item => item.question.trim() !== '' || item.answer.trim() !== '')
                );
            }
        }
    };
}

/**
 * Alpine component factory for project repeaters (title + preview image upload).
 * @param {string} fieldId - The hidden input element ID
 */
function projectRepeater(fieldId) {
    let uidCounter = 0;
    const nextUid = () => `${fieldId}-${Date.now()}-${uidCounter++}`;

    return {
        items: [],
        fieldId: fieldId,

        init() {
            const hiddenEl = document.getElementById(fieldId + '_json');
            if (hiddenEl) {
                try {
                    const raw = JSON.parse(hiddenEl.value || '[]');
                    this.items = raw.map(item => ({
                        title: item.title || (typeof item === 'string' ? item : ''),
                        image: item.image || '',
                        _previewUrl: '',
                        _uid: nextUid(),
                    }));
                } catch (e) {
                    this.items = [];
                }
            }
        },

        add() {
            this.items.push({ title: '', image: '', _previewUrl: '', _uid: nextUid() });
            this.save();
        },

        remove(index) {
            this.items.splice(index, 1);
            this.save();
        },

        previewImage(event, index) {
            const file = event.target.files[0];
            if (!file) return;
            this.items[index]._previewUrl = URL.createObjectURL(file);
            this.save();
        },

        save() {
            const hiddenEl = document.getElementById(this.fieldId + '_json');
            if (hiddenEl) {
                hiddenEl.value = JSON.stringify(
                    this.items
                        .filter(item => item.title.trim() !== '')
                        .map(item => ({ title: item.title, image: item.image }))
                );
            }
        }
    };
}

// Save all repeaters before form submission
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[method="POST"]');
    if (form) {
        form.addEventListener('submit', function () {
            // Alpine's reactivity should have already synced, but trigger a save just in case
            // by dispatching an input event on all textareas in repeaters
            document.querySelectorAll('[x-data]').forEach(el => {
                el.querySelectorAll('input, textarea').forEach(input => {
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                });
            });
        });
    }
});
</script>
