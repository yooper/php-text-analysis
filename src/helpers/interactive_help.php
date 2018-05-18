<?php

/**
 * List out a help menu in the interactive console
 */
function help()
{
    $menu = [
        'text(string $text) -> Return a TextCorpus object',
        'normalize(string $text) -> Normalize text to lower case',
        'todo ....'
    ];
    print_array($menu);
}

