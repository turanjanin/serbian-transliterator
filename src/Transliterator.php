<?php

declare(strict_types=1);

namespace Turanjanin\SerbianTransliterator;

class Transliterator
{
    /**
     * Convert text from Serbian Cyrillic to Latin script.
     *
     * @param string $text
     * @return string
     */
    public static function toLatin(string $text): string
    {
        return strtr($text, Data::$cyrillicToLatin);
    }

    /**
     * Convert text from cyrillic or latin script to ASCII equivalent,
     * without diacritic characters (the so called "bald latin").
     *
     * @param string $text
     * @return string
     */
    public static function toAsciiLatin(string $text): string
    {
        $text = static::toLatin($text);
        $text = static::normalizeLatin($text);

        return strtr($text, Data::$latinToAscii);
    }

    /**
     * Convert text from Serbian Latin to Cyrillic script.
     * Most of the digraph exceptions will be correctly recognized.
     * Foreign words will be left in their original form.
     *
     * @param string $text
     * @return string
     */
    public static function toCyrillic(string $text): string
    {
        $text = static::normalizeLatin($text);

        $transliterated = [];

        $words = explode(' ', $text);
        foreach ($words as $word) {
            if (static::looksLikeForeignWord($word)) {
                $transliterated[] = $word;
                continue;
            }

            $transliterated[] = static::wordToCyrillic($word);
        }

        return implode(' ', $transliterated);
    }

    /**
     * Transform various encodings of latin characters to Serbian latin letters.
     *
     * @param string $word
     * @return string
     */
    protected static function normalizeLatin(string $word): string
    {
        return strtr($word, Data::$latinToNormalizedLatin);
    }

    protected static function looksLikeForeignWord(string $word): bool
    {
        $lowercaseWord = mb_strtolower($word);

        if (in_array($lowercaseWord, Data::$commonForeignWords)) {
            return true;
        }

        foreach (Data::$commonForeignWordsPrefixes as $prefix) {
            if (static::wordStartsWith($lowercaseWord, $prefix)) {
                return true;
            }
        }

        foreach (Data::$foreignCharacterCombinations as $characterCombination) {
            if (!static::wordContainsString($lowercaseWord, $characterCombination)) {
                continue;
            }

            // We definitely have a foreign combination of characters in this word.
            // Let's just check for exceptions, just to be sure.

            foreach (Data::$serbianWordsWithForeignCharacterCombinations as $exception) {
                if (static::wordStartsWith($lowercaseWord, $exception)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    protected static function wordToCyrillic(string $word): string
    {
        $word = static::splitLatinDigraphs($word);

        return strtr($word, Data::$latinToCyrillic);
    }

    protected static function splitLatinDigraphs(string $word): string
    {
        $lowercaseWord = mb_strtolower($word);

        foreach (Data::$digraphExceptions as $digraph => $exceptions) {
            if (!static::wordContainsString($lowercaseWord, $digraph)) {
                continue;
            }

            foreach ($exceptions as $exception) {
                if (static::wordStartsWith($lowercaseWord, $exception)) {
                    return strtr($word, Data::$digraphReplacements[$digraph]);
                }
            }
        }

        return $word;
    }

    private static function wordStartsWith(string $word, string $prefix): bool
    {
        return strncmp($word, $prefix, strlen($prefix)) === 0;
    }

    private static function wordContainsString(string $word, string $string): bool
    {
        return strpos($word, $string) !== false;
    }
}
