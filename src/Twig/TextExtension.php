<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

// `truncate` filter from the abandoned twig/extensions, same output as before
class TextExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [new TwigFilter('truncate', [$this, 'truncate'])];
    }

    public function truncate(?string $value, int $length = 30, bool $preserve = false, string $separator = '...'): ?string
    {
        if (null === $value || mb_strlen($value) <= $length) {
            return $value;
        }
        if ($preserve) {
            // cut at the first space after $length; no space left means keep the whole value
            if (false === ($breakpoint = mb_strpos($value, ' ', $length))) {
                return $value;
            }
            $length = $breakpoint;
        }

        return rtrim(mb_substr($value, 0, $length)) . $separator;
    }
}
