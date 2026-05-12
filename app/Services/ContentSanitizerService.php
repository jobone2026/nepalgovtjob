<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Str;

class ContentSanitizerService
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', storage_path('app/purifier'));
        $config->set('HTML.DefinitionID', 'content-sanitizer-html');
        $config->set('HTML.DefinitionRev', 1);
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.Trusted', true);
        $config->set('Attr.EnableID', true);
        $config->set('Attr.AllowedClasses', null);
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        
        $config->set('CSS.Trusted', true);
        $config->set('CSS.AllowTricky', true);
        $config->set('CSS.Proprietary', true);
        $config->set('CSS.MaxImgLength', null);
        $config->set('HTML.MaxImgLength', null);
        $config->set('HTML.SafeObject', true);
        $config->set('HTML.SafeEmbed', true);
        $config->set('Attr.AllowedRel', ['nofollow', 'noopener', 'noreferrer']);
        $config->set('HTML.FlashAllowFullScreen', true);
        
        $config->set('HTML.Allowed', 
            'h1[class|id|style],h2[class|id|style],h3[class|id|style],h4[class|id|style],h5[class|id|style],h6[class|id|style],' .
            'p[class|id|style|align],br,hr[class|id|style],strong,em,b,i[class|id|style],u,s,blockquote[class|id|style],code,pre[class|id|style],' .
            'ul[class|id|style],ol[class|id|style|start|type],li[class|id|style|value],' .
            'table[class|id|style|width|height|border|cellpadding|cellspacing|summary|bgcolor|align],thead[class|id|style|align|valign],tbody[class|id|style|align|valign],tfoot[class|id|style|align|valign],' .
            'tr[class|id|style|align|valign|bgcolor],th[class|id|style|width|height|colspan|rowspan|align|valign|bgcolor|scope],td[class|id|style|width|height|colspan|rowspan|align|valign|bgcolor|scope],colgroup[class|id|style|span|width],col[class|id|style|span|width],' .
            'div[class|id|style|align],span[class|id|style|align],a[href|title|target|rel|class|id|style|name|download],' .
            'img[src|alt|title|width|height|class|id|style|loading|decoding|srcset|sizes],' .
            'iframe[src|title|width|height|frameborder|class|id|style|allow|allowfullscreen|loading],' .
            'section[class|id|style],article[class|id|style],header[class|id|style],footer[class|id|style],nav[class|id|style],' .
            'main[class|id|style],aside[class|id|style],button[class|id|style|type|name|value|disabled],' .
            'svg[class|id|style|width|height|viewBox|xmlns|fill|stroke|stroke-width|stroke-linecap|stroke-linejoin],' .
            'path[class|id|style|d|fill|stroke|stroke-width|stroke-linecap|stroke-linejoin],' .
            'circle[class|id|style|cx|cy|r|fill|stroke|stroke-width],' .
            'rect[class|id|style|x|y|width|height|rx|ry|fill|stroke|stroke-width],' .
            'g[class|id|style|fill|stroke|transform],' .
            'details[class|id|style|open],summary[class|id|style]'
        );
        
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^https://(www\.youtube\.com/embed/|player\.vimeo\.com/video/)%');
        
        $config->set('AutoFormat.RemoveEmpty', false);
        $config->set('AutoFormat.AutoParagraph', false);
        $config->set('AutoFormat.Linkify', false);

        // Get raw definition for custom modification
        $def = $config->getHTMLDefinition(true);
        if ($def) {
            $def->addElement('section', 'Block', 'Flow', 'Common');
            $def->addElement('article', 'Block', 'Flow', 'Common');
            $def->addElement('aside', 'Block', 'Flow', 'Common');
            $def->addElement('header', 'Block', 'Flow', 'Common');
            $def->addElement('footer', 'Block', 'Flow', 'Common');
            $def->addElement('nav', 'Block', 'Flow', 'Common');
            $def->addElement('main', 'Block', 'Flow', 'Common');
            $def->addElement('button', 'Inline', 'Flow', 'Common', [
                'type' => 'Enum#button,submit,reset',
                'name' => 'Text',
                'value' => 'Text',
                'disabled' => 'Bool'
            ]);
            
            $def->addElement('svg', 'Inline', 'Flow', 'Common', [
                'width' => 'Text', 'height' => 'Text', 'viewBox' => 'Text', 'xmlns' => 'Text',
                'fill' => 'Text', 'stroke' => 'Text', 'stroke-width' => 'Text',
                'stroke-linecap' => 'Text', 'stroke-linejoin' => 'Text'
            ]);
            $def->addElement('path', 'Inline', 'Flow', 'Common', [
                'd' => 'Text', 'fill' => 'Text', 'stroke' => 'Text', 'stroke-width' => 'Text',
                'stroke-linecap' => 'Text', 'stroke-linejoin' => 'Text'
            ]);
            $def->addElement('circle', 'Inline', 'Flow', 'Common', [
                'cx' => 'Text', 'cy' => 'Text', 'r' => 'Text', 'fill' => 'Text', 'stroke' => 'Text', 'stroke-width' => 'Text'
            ]);
            $def->addElement('rect', 'Inline', 'Flow', 'Common', [
                'x' => 'Text', 'y' => 'Text', 'width' => 'Text', 'height' => 'Text',
                'rx' => 'Text', 'ry' => 'Text', 'fill' => 'Text', 'stroke' => 'Text', 'stroke-width' => 'Text'
            ]);
            $def->addElement('g', 'Inline', 'Flow', 'Common', [
                'fill' => 'Text', 'stroke' => 'Text', 'transform' => 'Text'
            ]);

            $def->addElement('details', 'Block', 'Flow', 'Common', ['open' => 'Bool']);
            $def->addElement('summary', 'Inline', 'Flow', 'Common');
            
            $def->addAttribute('table', 'height', 'Text');
            $def->addAttribute('span', 'align', 'Enum#left,center,right,justify');
            $def->addAttribute('a', 'download', 'Text');
            $def->addAttribute('img', 'loading', 'Enum#lazy,eager');
            $def->addAttribute('img', 'decoding', 'Enum#async,sync,auto');
            $def->addAttribute('img', 'srcset', 'Text');
            $def->addAttribute('img', 'sizes', 'Text');
            $def->addAttribute('iframe', 'allow', 'Text');
            $def->addAttribute('iframe', 'allowfullscreen', 'Bool');
            $def->addAttribute('iframe', 'loading', 'Enum#lazy,eager');
            $def->addAttribute('svg', 'viewBox', 'Text');
        }

        // Get CSS definition. Using getDefinition('CSS') is safer if CSS.DefinitionID is not supported
        $css = $config->getDefinition('CSS', true);
        if ($css) {
            $css->info['display'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['flex'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['flex-direction'] = new \HTMLPurifier_AttrDef_Enum(['row', 'row-reverse', 'column', 'column-reverse']);
            $css->info['flex-wrap'] = new \HTMLPurifier_AttrDef_Enum(['nowrap', 'wrap', 'wrap-reverse']);
            $css->info['flex-flow'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['flex-grow'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['flex-shrink'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['flex-basis'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['justify-content'] = new \HTMLPurifier_AttrDef_Enum(['flex-start', 'flex-end', 'center', 'space-between', 'space-around', 'space-evenly', 'start', 'end', 'left', 'right']);
            $css->info['align-items'] = new \HTMLPurifier_AttrDef_Enum(['stretch', 'flex-start', 'flex-end', 'center', 'baseline', 'start', 'end', 'self-start', 'self-end']);
            $css->info['align-content'] = new \HTMLPurifier_AttrDef_Enum(['stretch', 'flex-start', 'flex-end', 'center', 'space-between', 'space-around', 'space-evenly', 'start', 'end']);
            $css->info['align-self'] = new \HTMLPurifier_AttrDef_Enum(['auto', 'stretch', 'flex-start', 'flex-end', 'center', 'baseline', 'start', 'end', 'self-start', 'self-end']);
            $css->info['justify-items'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['justify-self'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['order'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['gap'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['row-gap'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['column-gap'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-template-columns'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-template-rows'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-template-areas'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-column'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-row'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-area'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-auto-columns'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-auto-rows'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['grid-auto-flow'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['place-items'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['place-content'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['place-self'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['aspect-ratio'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['object-fit'] = new \HTMLPurifier_AttrDef_Enum(['fill', 'contain', 'cover', 'none', 'scale-down']);
            $css->info['object-position'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['position'] = new \HTMLPurifier_AttrDef_Enum(['static', 'relative', 'absolute', 'fixed', 'sticky']);
            $css->info['top'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['left'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['right'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['bottom'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['z-index'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['inset'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['border-radius'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['border-top-left-radius'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['border-top-right-radius'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['border-bottom-left-radius'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['border-bottom-right-radius'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['box-shadow'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['transition'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['transition-property'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['transition-duration'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['transition-timing-function'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['transition-delay'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['transform'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['transform-origin'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background-image'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background-size'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background-position'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background-repeat'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background-attachment'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background-clip'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background-origin'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['background-color'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['backdrop-filter'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['filter'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['mix-blend-mode'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['cursor'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['pointer-events'] = new \HTMLPurifier_AttrDef_Enum(['auto', 'none', 'inherit', 'initial', 'unset']);
            $css->info['opacity'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['user-select'] = new \HTMLPurifier_AttrDef_Enum(['none', 'auto', 'text', 'all', 'contain']);
            $css->info['overflow'] = new \HTMLPurifier_AttrDef_Enum(['visible', 'hidden', 'scroll', 'auto']);
            $css->info['overflow-x'] = new \HTMLPurifier_AttrDef_Enum(['visible', 'hidden', 'scroll', 'auto']);
            $css->info['overflow-y'] = new \HTMLPurifier_AttrDef_Enum(['visible', 'hidden', 'scroll', 'auto']);
            $css->info['white-space'] = new \HTMLPurifier_AttrDef_Enum(['normal', 'nowrap', 'pre', 'pre-line', 'pre-wrap']);
            $css->info['word-break'] = new \HTMLPurifier_AttrDef_Enum(['normal', 'break-all', 'keep-all', 'break-word']);
            $css->info['text-overflow'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['list-style'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['list-style-type'] = new \HTMLPurifier_AttrDef_Text();
            $css->info['list-style-position'] = new \HTMLPurifier_AttrDef_Enum(['inside', 'outside']);
        }

        $this->purifier = new HTMLPurifier($config);
    }

    public function sanitize(string $content): string
    {
        // Force manual conversion of height/align attributes to style for better compatibility
        $content = preg_replace_callback('/<(table|span)\b([^>]*)\b(height|align)\s*=\s*["\']([^"\']+)["\']([^>]*)>/i', function($m) {
            $tag = $m[1]; $before = $m[2]; $attr = $m[3]; $val = $m[4]; $after = $m[5];
            $style = ($attr === 'height') ? "height:" . (is_numeric($val) ? $val . "px" : $val) . ";" : "text-align:" . $val . ";";
            if (preg_match('/style\s*=\s*["\']([^"\']+)["\']/', $before . $after, $sm)) {
                return "<$tag" . preg_replace('/style\s*=\s*["\']([^"\']+)["\']/', 'style="' . rtrim($sm[1], ';') . ';' . $style . '"', $before . $after) . ">";
            }
            return "<$tag$before$after style=\"$style\">";
        }, $content) ?? $content;

        $styles = [];
        $content = preg_replace_callback('/<style\b[^>]*>(.*?)<\/style>/is', function ($matches) use (&$styles) {
            $styles[] = $matches[0];
            return 'STYLE_BLOCK_PLACEHOLDER_' . (count($styles) - 1) . '_END';
        }, $content) ?? $content;

        $sanitized = $this->purifier->purify($content);

        foreach ($styles as $index => $style) {
            $sanitized = str_replace('STYLE_BLOCK_PLACEHOLDER_' . $index . '_END', $style, $sanitized);
        }

        $sanitized = preg_replace('/<a\b(?![^>]*\brel=)([^>]*)target="_blank"([^>]*)>/i', '<a$1target="_blank" rel="noopener noreferrer"$2>', $sanitized) ?? $sanitized;

        return $this->applyResponsiveEnhancements($sanitized);
    }

    private function applyResponsiveEnhancements(string $content): string
    {
        $processed = preg_replace_callback('/<img\b[^>]*>/i', function ($matches) {
            $tag = $matches[0];
            if (!Str::contains(Str::lower($tag), 'loading=')) {
                $tag = preg_replace('/\s*\/?>$/', ' loading="lazy"$0', $tag, 1) ?? $tag;
            }
            if (!Str::contains(Str::lower($tag), 'decoding=')) {
                $tag = preg_replace('/\s*\/?>$/', ' decoding="async"$0', $tag, 1) ?? $tag;
            }
            return $tag;
        }, $content) ?? $content;

        $processed = preg_replace_callback('/<table\b[^>]*>.*?<\/table>/is', function ($matches) {
            return '<div class="post-table-scroll">' . $matches[0] . '</div>';
        }, $processed) ?? $processed;

        return preg_replace_callback('/<iframe\b[^>]*>.*?<\/iframe>/is', function ($matches) {
            return '<div class="post-embed-responsive">' . $matches[0] . '</div>';
        }, $processed) ?? $processed;
    }
}
