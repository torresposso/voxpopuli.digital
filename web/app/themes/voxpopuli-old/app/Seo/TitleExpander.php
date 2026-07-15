<?php

namespace App\Seo;

/**
 * Expands Yoast-style %%variable%% placeholders in title strings.
 *
 * Supported variables:
 * - %%title%%    → Post title
 * - %%sep%%      → Separator (default: -)
 * - %%sitename%% → Site name
 * - %%page%%     → Page number
 * - %%excerpt%%  → Post excerpt
 */
class TitleExpander
{
    /**
     * Expand %%variable%% placeholders in the given value.
     *
     * @param  string  $value  The string containing %%variables%%
     * @param  array<string, mixed>  $context  Variable values: title, sep, sitename, page, excerpt
     * @return string The expanded string
     */
    public static function expand(string $value, array $context): string
    {
        $replacements = [
            '%%title%%' => $context['title'] ?? '',
            '%%sep%%' => $context['sep'] ?? '-',
            '%%sitename%%' => $context['sitename'] ?? '',
            '%%page%%' => (string) ($context['page'] ?? ''),
            '%%excerpt%%' => $context['excerpt'] ?? '',
        ];

        $expanded = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $value,
        );

        // Strip HTML tags then escape entities for safe output
        $expanded = strip_tags($expanded);
        $expanded = htmlspecialchars($expanded, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);

        return $expanded;
    }
}
