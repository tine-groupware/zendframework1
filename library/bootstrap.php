<?php declare(strict_types=1);

function save_chr(int $codepoint): string
{
    while ($codepoint < 0) $codepoint += 256;
    return chr($codepoint % 256);
}
