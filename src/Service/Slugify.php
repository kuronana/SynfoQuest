<?php

namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $input = str_replace('€', '', $input);
        $input = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $input );
        $input = preg_replace('/\s{2,}/', ' ', $input);
        $input = str_replace(' ', '-', $input);
        $input = preg_replace('/[^a-zA-Z0-9-]/', '', $input);
        $input = trim($input, '-');
        $input = strtolower($input);
        while (strpos($input, '--') !== false)
        {
            $input = str_replace('--', '-',$input);
        }
            return $input;
    }
}