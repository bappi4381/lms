@extends('layouts.admin')

@section('title', 'Why Choose Us Section Settings')
@section('eyebrow', 'Site Settings')
@section('page_heading', 'Why Choose Us Section')

@section('content')
<div class="mx-auto max-w-4xl"
     x-data="{
        cards: {{ Illuminate\Support\Js::from(old('whyus_cards', $settings->whyus_cards ?: [])) }},
        addCard() { this.cards.push({ title_bn: '', title_en: '', desc_bn: '', desc_en: '' }); },
        removeCard(i) { this.cards.splice(i, 1); },
     }">
    @include('admin.settings._nav')

    <form method="POST" action="{{ route('admin.settings.why-us.update') }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Section Headings</h3>
                <p class="text-[12.5px]" style="color:var(--a-ink-faint)">Set eyebrow label and title for the Why Choose Us section.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Eyebrow Text (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="whyus_eyebrow_bn" value="{{ old('whyus_eyebrow_bn', $settings->whyus_eyebrow_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Eyebrow Text (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="whyus_eyebrow_en" value="{{ old('whyus_eyebrow_en', $settings->whyus_eyebrow_en) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Title (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="whyus_title_bn" value="{{ old('whyus_title_bn', $settings->whyus_title_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Title (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="whyus_title_en" value="{{ old('whyus_title_en', $settings->whyus_title_en) }}" required class="admin-input">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Feature Cards</h3>
                <p class="text-[12.5px]" style="color:var(--a-ink-faint)">Add, edit, or reorder value-proposition feature cards shown on the homepage.</p>
            </div>

            <div class="space-y-3">
                <template x-for="(card, index) in cards" :key="index">
                    <div class="rounded-ledger border p-4" style="border-color:var(--a-line-soft);background:var(--a-page)">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-[12px] font-semibold uppercase tracking-wide" style="color:var(--a-ink-faint)">Card <span x-text="index + 1"></span></span>
                            <button type="button" @click="removeCard(index)" class="text-[12px] font-semibold" style="color:var(--a-brick)">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Card Title (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                                <input type="text" :name="'whyus_cards[' + index + '][title_bn]'" x-model="card.title_bn" required class="admin-input">
                            </div>
                            <div>
                                <label class="admin-label">Card Title (English) <span style="color:var(--a-brick)">*</span></label>
                                <input type="text" :name="'whyus_cards[' + index + '][title_en]'" x-model="card.title_en" required class="admin-input">
                            </div>
                            <div>
                                <label class="admin-label">Description (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                                <textarea :name="'whyus_cards[' + index + '][desc_bn]'" x-model="card.desc_bn" rows="2" required class="admin-textarea"></textarea>
                            </div>
                            <div>
                                <label class="admin-label">Description (English) <span style="color:var(--a-brick)">*</span></label>
                                <textarea :name="'whyus_cards[' + index + '][desc_en]'" x-model="card.desc_en" rows="2" required class="admin-textarea"></textarea>
                            </div>
                        </div>
                    </div>
                </template>

                <p x-show="cards.length === 0" class="admin-empty">No feature cards yet.</p>
            </div>

            <button type="button" @click="addCard()" class="admin-btn admin-btn-secondary mt-4">+ Add Feature Card</button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="admin-btn admin-btn-primary">Save Why Choose Us Section</button>
        </div>
    </form>
</div>
@endsection
