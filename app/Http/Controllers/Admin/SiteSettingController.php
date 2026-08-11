<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function heroEdit(): View
    {
        return view('admin.settings.hero', ['settings' => SiteSetting::getSettings()]);
    }

    public function heroUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_eyebrow_bn' => 'required|string|max:255',
            'hero_eyebrow_en' => 'required|string|max:255',
            'hero_title_bn' => 'required|string|max:255',
            'hero_title_en' => 'required|string|max:255',
            'hero_highlight_bn' => 'required|string|max:255',
            'hero_highlight_en' => 'required|string|max:255',
            'hero_description_bn' => 'required|string',
            'hero_description_en' => 'required|string',
            'hero_btn_primary_bn' => 'required|string|max:255',
            'hero_btn_primary_en' => 'required|string|max:255',
            'hero_btn_secondary_bn' => 'required|string|max:255',
            'hero_btn_secondary_en' => 'required|string|max:255',
        ]);

        SiteSetting::getSettings()->update($data);
        SiteSetting::clearCache();

        return redirect()->route('admin.settings.hero.edit')->with('success', 'Hero Section updated successfully!');
    }

    public function aboutEdit(): View
    {
        return view('admin.settings.about', ['settings' => SiteSetting::getSettings()]);
    }

    public function aboutUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'about_eyebrow_bn' => 'required|string|max:255',
            'about_eyebrow_en' => 'required|string|max:255',
            'about_title_bn' => 'required|string|max:255',
            'about_title_en' => 'required|string|max:255',
            'about_description_bn' => 'required|string',
            'about_description_en' => 'required|string',
            'about_btn_bn' => 'required|string|max:255',
            'about_btn_en' => 'required|string|max:255',
        ]);

        SiteSetting::getSettings()->update($data);
        SiteSetting::clearCache();

        return redirect()->route('admin.settings.about.edit')->with('success', 'About Section updated successfully!');
    }

    public function whyUsEdit(): View
    {
        return view('admin.settings.why-us', ['settings' => SiteSetting::getSettings()]);
    }

    public function whyUsUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'whyus_eyebrow_bn' => 'required|string|max:255',
            'whyus_eyebrow_en' => 'required|string|max:255',
            'whyus_title_bn' => 'required|string|max:255',
            'whyus_title_en' => 'required|string|max:255',
            'whyus_cards' => 'nullable|array',
            'whyus_cards.*.title_bn' => 'required|string|max:255',
            'whyus_cards.*.title_en' => 'required|string|max:255',
            'whyus_cards.*.desc_bn' => 'required|string',
            'whyus_cards.*.desc_en' => 'required|string',
        ]);

        $data['whyus_cards'] = array_values($data['whyus_cards'] ?? []);

        SiteSetting::getSettings()->update($data);
        SiteSetting::clearCache();

        return redirect()->route('admin.settings.why-us.edit')->with('success', 'Why Choose Us Section updated successfully!');
    }

    public function pricingEdit(): View
    {
        return view('admin.settings.pricing', ['settings' => SiteSetting::getSettings()]);
    }

    public function pricingUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pricing_eyebrow_bn' => 'required|string|max:255',
            'pricing_eyebrow_en' => 'required|string|max:255',
            'pricing_title_bn' => 'required|string|max:255',
            'pricing_title_en' => 'required|string|max:255',
        ]);

        SiteSetting::getSettings()->update($data);
        SiteSetting::clearCache();

        return redirect()->route('admin.settings.pricing.edit')->with('success', 'Pricing Section updated successfully!');
    }

    public function testimonialsEdit(): View
    {
        return view('admin.settings.testimonials', ['settings' => SiteSetting::getSettings()]);
    }

    public function testimonialsUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'testi_eyebrow_bn' => 'required|string|max:255',
            'testi_eyebrow_en' => 'required|string|max:255',
            'testi_title_bn' => 'required|string|max:255',
            'testi_title_en' => 'required|string|max:255',
        ]);

        SiteSetting::getSettings()->update($data);
        SiteSetting::clearCache();

        return redirect()->route('admin.settings.testimonials.edit')->with('success', 'Testimonials Section updated successfully!');
    }

    public function headerFooterEdit(): View
    {
        return view('admin.settings.header-footer', ['settings' => SiteSetting::getSettings()]);
    }

    public function headerFooterUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'header_links' => 'nullable|array',
            'header_links.*.label_bn' => 'required|string|max:255',
            'header_links.*.label_en' => 'required|string|max:255',
            'header_links.*.url' => 'required|string|max:255',
            'header_links.*.is_active' => 'nullable|boolean',
            'header_links.*.open_in_new_tab' => 'nullable|boolean',

            'brand_description_bn' => 'nullable|string',
            'brand_description_en' => 'nullable|string',

            'footer_columns' => 'nullable|array',
            'footer_columns.*.column_title_bn' => 'required|string|max:255',
            'footer_columns.*.column_title_en' => 'required|string|max:255',
            'footer_columns.*.links' => 'nullable|array',
            'footer_columns.*.links.*.label_bn' => 'required|string|max:255',
            'footer_columns.*.links.*.label_en' => 'required|string|max:255',
            'footer_columns.*.links.*.url' => 'required|string|max:255',
            'footer_columns.*.links.*.is_active' => 'nullable|boolean',

            'contact_email' => 'nullable|email|max:255',
            'contact_phone_bn' => 'nullable|string|max:255',
            'contact_phone_en' => 'nullable|string|max:255',
            'contact_address_bn' => 'nullable|string|max:255',
            'contact_address_en' => 'nullable|string|max:255',

            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required|in:facebook,youtube,instagram,linkedin,twitter,tiktok',
            'social_links.*.url' => 'required|url|max:255',
            'social_links.*.is_active' => 'nullable|boolean',

            'copyright_text_bn' => 'nullable|string|max:255',
            'copyright_text_en' => 'nullable|string|max:255',
        ]);

        // Repeater checkboxes are omitted from the request when unchecked —
        // normalize booleans explicitly so "off" is actually persisted as false.
        foreach (($data['header_links'] ?? []) as $i => $link) {
            $data['header_links'][$i]['is_active'] = (bool) ($link['is_active'] ?? false);
            $data['header_links'][$i]['open_in_new_tab'] = (bool) ($link['open_in_new_tab'] ?? false);
        }
        foreach (($data['footer_columns'] ?? []) as $ci => $column) {
            foreach (($column['links'] ?? []) as $li => $link) {
                $data['footer_columns'][$ci]['links'][$li]['is_active'] = (bool) ($link['is_active'] ?? false);
            }
        }
        foreach (($data['social_links'] ?? []) as $i => $link) {
            $data['social_links'][$i]['is_active'] = (bool) ($link['is_active'] ?? false);
        }

        $data['header_links'] = array_values($data['header_links'] ?? []);
        $data['footer_columns'] = array_values($data['footer_columns'] ?? []);
        $data['social_links'] = array_values($data['social_links'] ?? []);

        SiteSetting::getSettings()->update($data);
        SiteSetting::clearCache();

        return redirect()->route('admin.settings.header-footer.edit')->with('success', 'Header & Footer Settings updated successfully!');
    }
}
