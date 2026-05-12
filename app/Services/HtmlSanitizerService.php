<?php

namespace App\Services;

class HtmlSanitizerService
{
    /**
     * Clean post HTML content for safe mobile rendering.
     * Strips fixed widths, nowrap, overflow:hidden etc from inline styles
     * WITHOUT removing the element or other safe styles.
     */
    public function cleanContent(string $html): string
    {
        if (empty($html)) {
            return $html;
        }

        // 1. Remove/rewrite dangerous inline style properties
        $html = preg_replace_callback(
            '/style\s*=\s*["\']([^"\']*)["\']/',
            function ($matches) {
                $style = $matches[1];
                $style = $this->sanitizeStyleAttribute($style);
                if (empty(trim($style))) {
                    return ''; // remove the style attr entirely if nothing left
                }
                return 'style="' . $style . '"';
            },
            $html
        );

        // 2. Remove fixed width/height attributes on table/td/img/div
        $html = preg_replace('/(<(?:table|td|th|tr|div|span|p|img|figure)\b[^>]*?)\s+width\s*=\s*["\']?\d+[%px]*["\']?/i', '$1', $html);
        $html = preg_replace('/(<(?:table|td|th|tr|div|span|p|img|figure)\b[^>]*?)\s+height\s*=\s*["\']?\d+[%px]*["\']?/i', '$1', $html);

        // 3. Make sure all <table> have overflow wrapper class (for scroll on mobile)
        // Add class="mobile-scroll-table" if not present
        $html = preg_replace_callback(
            '/<table(\b[^>]*)>/i',
            function ($m) {
                $attrs = $m[1];
                if (str_contains($attrs, 'mobile-scroll-table')) {
                    return $m[0]; // Already done
                }
                // Add or extend class attribute
                if (preg_match('/class\s*=\s*["\']([^"\']*)["\']/', $attrs, $cm)) {
                    $newClass = $cm[1] . ' mobile-scroll-table';
                    $attrs = str_replace($cm[0], 'class="' . $newClass . '"', $attrs);
                } else {
                    $attrs .= ' class="mobile-scroll-table"';
                }
                return '<table' . $attrs . '>';
            },
            $html
        );

        // 4. Wrap bare <table> occurrences (those not already inside a .post-table-wrap) in a scrollable div
        // This is done via the class added above — CSS handles the rest

        // 5. Force images to be responsive: remove fixed width/height on <img>
        //    (already done above, but we also ensure lazy loading and basic styles)
        $html = preg_replace_callback(
            '/<img(\b[^>]*)>/i',
            function ($m) {
                $attrs = $m[1];
                
                // Ensure loading="lazy"
                if (!str_contains($attrs, 'loading=')) {
                    $attrs .= ' loading="lazy"';
                }
                
                // If it doesn't have a style attribute, add the default responsive one
                if (!str_contains($attrs, 'style=')) {
                    $attrs .= ' style="max-width:100%;height:auto;display:block;border-radius:8px"';
                }
                
                return '<img' . $attrs . '>';
            },
            $html
        );

        return $html;
    }

    /**
     * Strip overflow-causing CSS properties from a style attribute value.
     */
    private function sanitizeStyleAttribute(string $style): string
    {
        // Properties to completely remove (they cause overflow)
        $removeProps = [
            'width',
            'min-width',
            'max-width',       // we'll add back safe max-width via CSS
            'height',
            'min-height',
            'white-space',     // removes nowrap
            'overflow',
            'overflow-x',
            'overflow-y',
            'position',        // removes fixed/absolute that can break layout
            'left',
            'right',
            'top',
            'bottom',
            'z-index',
            'float',
        ];

        // Split into individual declarations
        $declarations = explode(';', $style);
        $safe = [];

        foreach ($declarations as $decl) {
            $decl = trim($decl);
            if (empty($decl)) continue;

            // Get property name
            $parts = explode(':', $decl, 2);
            if (count($parts) < 2) continue;

            $prop = strtolower(trim($parts[0]));
            $val  = trim($parts[1]);

            // Skip if it's in the removal list
            $remove = false;
            foreach ($removeProps as $rp) {
                if ($prop === $rp || str_starts_with($prop, $rp . '-')) {
                    $remove = true;
                    break;
                }
            }

            if (!$remove) {
                $safe[] = $prop . ':' . $val;
            }
        }

        return implode(';', $safe);
    }
}
