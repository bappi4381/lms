@extends('layouts.admin')

@section('title', 'Header & Footer Settings')
@section('eyebrow', 'Site Settings')
@section('page_heading', 'Header & Footer Settings')

@section('content')
<div class="mx-auto max-w-5xl"
     x-data="{
        activeTab: 'header',
        headerLinks: {{ Illuminate\Support\Js::from(old('header_links', $settings->header_links ?: [])) }},
        footerColumns: {{ Illuminate\Support\Js::from(old('footer_columns', $settings->footer_columns ?: [])) }},
        socialLinks: {{ Illuminate\Support\Js::from(old('social_links', $settings->social_links ?: [])) }},
        addHeaderLink() { this.headerLinks.push({ label_bn: '', label_en: '', url: '', is_active: true, open_in_new_tab: false }); },
        removeHeaderLink(i) { this.headerLinks.splice(i, 1); },
        addFooterColumn() { this.footerColumns.push({ column_title_bn: '', column_title_en: '', links: [] }); },
        removeFooterColumn(i) { this.footerColumns.splice(i, 1); },
        addFooterLink(ci) { this.footerColumns[ci].links.push({ label_bn: '', label_en: '', url: '', is_active: true }); },
        removeFooterLink(ci, li) { this.footerColumns[ci].links.splice(li, 1); },
        addSocialLink() { this.socialLinks.push({ platform: 'facebook', url: '', is_active: true }); },
        removeSocialLink(i) { this.socialLinks.splice(i, 1); },
     }">
    @include('admin.settings._nav')

    <form method="POST" action="{{ route('admin.settings.header-footer.update') }}" class="space-y-5">
        @csrf @method('PUT')

        <!-- Tabs -->
        <div class="flex flex-wrap gap-1.5 rounded-ledger p-1" style="background:var(--a-panel);border:1px solid var(--a-line-soft)">
            <button type="button" @click="activeTab = 'header'" class="rounded-ledger px-3 py-1.5 text-[12.5px] font-semibold" :style="activeTab === 'header' ? 'background:var(--a-accent);color:#fff' : 'color:var(--a-ink-soft)'">Header Navigation</button>
            <button type="button" @click="activeTab = 'brand'" class="rounded-ledger px-3 py-1.5 text-[12.5px] font-semibold" :style="activeTab === 'brand' ? 'background:var(--a-accent);color:#fff' : 'color:var(--a-ink-soft)'">Footer Brand</button>
            <button type="button" @click="activeTab = 'footer'" class="rounded-ledger px-3 py-1.5 text-[12.5px] font-semibold" :style="activeTab === 'footer' ? 'background:var(--a-accent);color:#fff' : 'color:var(--a-ink-soft)'">Footer Navigation</button>
            <button type="button" @click="activeTab = 'contact'" class="rounded-ledger px-3 py-1.5 text-[12.5px] font-semibold" :style="activeTab === 'contact' ? 'background:var(--a-accent);color:#fff' : 'color:var(--a-ink-soft)'">Contact &amp; Social</button>
            <button type="button" @click="activeTab = 'copyright'" class="rounded-ledger px-3 py-1.5 text-[12.5px] font-semibold" :style="activeTab === 'copyright' ? 'background:var(--a-accent);color:#fff' : 'color:var(--a-ink-soft)'">Copyright</button>
        </div>

        <!-- Tab 1: Header Navigation -->
        <div x-show="activeTab === 'header'" x-cloak class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Header Navbar Links</h3>
            </div>
            <div class="space-y-3">
                <template x-for="(link, index) in headerLinks" :key="index">
                    <div class="rounded-ledger border p-4" style="border-color:var(--a-line-soft);background:var(--a-page)">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-[12px] font-semibold uppercase tracking-wide" style="color:var(--a-ink-faint)">Link <span x-text="index + 1"></span></span>
                            <button type="button" @click="removeHeaderLink(index)" class="text-[12px] font-semibold" style="color:var(--a-brick)">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Link Label (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                                <input type="text" :name="'header_links[' + index + '][label_bn]'" x-model="link.label_bn" required class="admin-input">
                            </div>
                            <div>
                                <label class="admin-label">Link Label (English) <span style="color:var(--a-brick)">*</span></label>
                                <input type="text" :name="'header_links[' + index + '][label_en]'" x-model="link.label_en" required class="admin-input">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="admin-label">Target URL / Route <span style="color:var(--a-brick)">*</span></label>
                                <input type="text" :name="'header_links[' + index + '][url]'" x-model="link.url" required placeholder="e.g. / or #courses" class="admin-input">
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" :name="'header_links[' + index + '][is_active]'" x-model="link.is_active" value="1" class="h-4 w-4 rounded" style="accent-color:var(--a-accent)">
                                <label class="text-[13px] font-medium" style="color:var(--a-ink-soft)">Active</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" :name="'header_links[' + index + '][open_in_new_tab]'" x-model="link.open_in_new_tab" value="1" class="h-4 w-4 rounded" style="accent-color:var(--a-accent)">
                                <label class="text-[13px] font-medium" style="color:var(--a-ink-soft)">Open in New Tab</label>
                            </div>
                        </div>
                    </div>
                </template>
                <p x-show="headerLinks.length === 0" class="admin-empty">No header links yet.</p>
            </div>
            <button type="button" @click="addHeaderLink()" class="admin-btn admin-btn-secondary mt-4">+ Add Header Link</button>
        </div>

        <!-- Tab 2: Footer Brand -->
        <div x-show="activeTab === 'brand'" x-cloak class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Footer Description</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Footer Description (বাংলা)</label>
                    <textarea name="brand_description_bn" rows="3" class="admin-textarea">{{ old('brand_description_bn', $settings->brand_description_bn) }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Footer Description (English)</label>
                    <textarea name="brand_description_en" rows="3" class="admin-textarea">{{ old('brand_description_en', $settings->brand_description_en) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Tab 3: Footer Navigation Columns -->
        <div x-show="activeTab === 'footer'" x-cloak class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Footer Link Columns</h3>
            </div>
            <div class="space-y-4">
                <template x-for="(column, ci) in footerColumns" :key="ci">
                    <div class="rounded-ledger border p-4" style="border-color:var(--a-line-soft);background:var(--a-page)">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-[12px] font-semibold uppercase tracking-wide" style="color:var(--a-ink-faint)">Column <span x-text="ci + 1"></span></span>
                            <button type="button" @click="removeFooterColumn(ci)" class="text-[12px] font-semibold" style="color:var(--a-brick)">Remove Column</button>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Column Header (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                                <input type="text" :name="'footer_columns[' + ci + '][column_title_bn]'" x-model="column.column_title_bn" required class="admin-input">
                            </div>
                            <div>
                                <label class="admin-label">Column Header (English) <span style="color:var(--a-brick)">*</span></label>
                                <input type="text" :name="'footer_columns[' + ci + '][column_title_en]'" x-model="column.column_title_en" required class="admin-input">
                            </div>
                        </div>

                        <div class="mt-3 space-y-2 border-t pt-3" style="border-color:var(--a-line-soft)">
                            <template x-for="(link, li) in column.links" :key="li">
                                <div class="grid grid-cols-1 gap-2 rounded-ledger p-3 sm:grid-cols-[1fr_1fr_1fr_auto_auto]" style="background:var(--a-panel)">
                                    <input type="text" :name="'footer_columns[' + ci + '][links][' + li + '][label_bn]'" x-model="link.label_bn" placeholder="Label (বাংলা)" required class="admin-input">
                                    <input type="text" :name="'footer_columns[' + ci + '][links][' + li + '][label_en]'" x-model="link.label_en" placeholder="Label (English)" required class="admin-input">
                                    <input type="text" :name="'footer_columns[' + ci + '][links][' + li + '][url]'" x-model="link.url" placeholder="URL" required class="admin-input">
                                    <label class="flex items-center gap-1.5 whitespace-nowrap px-1 text-[12px] font-medium" style="color:var(--a-ink-soft)">
                                        <input type="checkbox" :name="'footer_columns[' + ci + '][links][' + li + '][is_active]'" x-model="link.is_active" value="1" class="h-4 w-4 rounded" style="accent-color:var(--a-accent)">
                                        Active
                                    </label>
                                    <button type="button" @click="removeFooterLink(ci, li)" class="text-[12px] font-semibold" style="color:var(--a-brick)">Remove</button>
                                </div>
                            </template>
                            <button type="button" @click="addFooterLink(ci)" class="text-[12.5px] font-semibold" style="color:var(--a-accent)">+ Add Column Link</button>
                        </div>
                    </div>
                </template>
                <p x-show="footerColumns.length === 0" class="admin-empty">No footer columns yet.</p>
            </div>
            <button type="button" @click="addFooterColumn()" class="admin-btn admin-btn-secondary mt-4">+ Add Footer Column</button>
        </div>

        <!-- Tab 4: Contact & Social -->
        <div x-show="activeTab === 'contact'" x-cloak class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Contact Details</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="admin-label">Support Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}" placeholder="support@secondshiftbd.com" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Phone Number (বাংলা)</label>
                    <input type="text" name="contact_phone_bn" value="{{ old('contact_phone_bn', $settings->contact_phone_bn) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Phone Number (English)</label>
                    <input type="text" name="contact_phone_en" value="{{ old('contact_phone_en', $settings->contact_phone_en) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Address (বাংলা)</label>
                    <input type="text" name="contact_address_bn" value="{{ old('contact_address_bn', $settings->contact_address_bn) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Address (English)</label>
                    <input type="text" name="contact_address_en" value="{{ old('contact_address_en', $settings->contact_address_en) }}" class="admin-input">
                </div>
            </div>

            <div class="mt-5 border-t pt-4" style="border-color:var(--a-line-soft)">
                <h4 class="mb-3 text-[13px] font-semibold" style="color:var(--a-ink)">Social Media Links</h4>
                <div class="space-y-2">
                    <template x-for="(link, index) in socialLinks" :key="index">
                        <div class="grid grid-cols-1 gap-2 rounded-ledger p-3 sm:grid-cols-[1fr_2fr_auto_auto]" style="background:var(--a-page);border:1px solid var(--a-line-soft)">
                            <select :name="'social_links[' + index + '][platform]'" x-model="link.platform" class="admin-select">
                                <option value="facebook">Facebook</option>
                                <option value="youtube">YouTube</option>
                                <option value="instagram">Instagram</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="twitter">Twitter / X</option>
                                <option value="tiktok">TikTok</option>
                            </select>
                            <input type="url" :name="'social_links[' + index + '][url]'" x-model="link.url" placeholder="https://..." required class="admin-input">
                            <label class="flex items-center gap-1.5 whitespace-nowrap px-1 text-[12px] font-medium" style="color:var(--a-ink-soft)">
                                <input type="checkbox" :name="'social_links[' + index + '][is_active]'" x-model="link.is_active" value="1" class="h-4 w-4 rounded" style="accent-color:var(--a-accent)">
                                Active
                            </label>
                            <button type="button" @click="removeSocialLink(index)" class="text-[12px] font-semibold" style="color:var(--a-brick)">Remove</button>
                        </div>
                    </template>
                    <p x-show="socialLinks.length === 0" class="admin-empty">No social links yet.</p>
                </div>
                <button type="button" @click="addSocialLink()" class="admin-btn admin-btn-secondary mt-3">+ Add Social Link</button>
            </div>
        </div>

        <!-- Tab 5: Copyright -->
        <div x-show="activeTab === 'copyright'" x-cloak class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Copyright Notice</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Copyright Notice (বাংলা)</label>
                    <input type="text" name="copyright_text_bn" value="{{ old('copyright_text_bn', $settings->copyright_text_bn) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Copyright Notice (English)</label>
                    <input type="text" name="copyright_text_en" value="{{ old('copyright_text_en', $settings->copyright_text_en) }}" class="admin-input">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="admin-btn admin-btn-primary">Save Header &amp; Footer Settings</button>
        </div>
    </form>
</div>
@endsection
