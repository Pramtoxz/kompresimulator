<?php

namespace App\Practice;

class ViewPreview
{
    public static function render(?string $source): string
    {
        $html = self::stripDirectives((string) $source);

        if (trim($html) === '') {
            return '';
        }

        return $html;
    }

    private static function stripDirectives(string $source): string
    {
        $patterns = [
            '/@csrf\b/',
            '/@method\s*\([^)]*\)/',
            '/@(extends|section|endsection|yield|push|endpush|include)\s*(\([^)]*\))?/',
            '/@(if|elseif|foreach|for|while|isset|empty)\s*\([^)]*\)/',
            '/@(endif|endforeach|endfor|endwhile|endisset|endempty|else)\b/',
            '/\{\{--.*?--\}\}/s',
            '/\{\{.*?\}\}/s',
            '/\{!!.*?!!\}/s',
            '/<\?php.*?\?>/s',
            '/<\?=.*?\?>/s',
            '/<\?php.*$/s',
        ];

        return trim((string) preg_replace($patterns, '', $source));
    }
}
