<?php

namespace App\Actions;

use cijic\phpMorphy\Morphy;
use Illuminate\Support\Facades\Http;

class ParseDomainAction
{
    public function run(string $domain): array
    {
        libxml_use_internal_errors(true);

        $response = Http::get($domain);
        $content = $response->body();

        $content = preg_replace('/\n/', '', $content);
        $content = preg_replace('/\t/', '', $content);
        $content = preg_replace('/\r/', '', $content);
        $searchData = [];

        foreach (config('catalog') as $catalog => $categories){
            foreach ($categories as $category => $words){
                foreach ($words as $word){
                    $wordLower =  addcslashes(mb_strtolower($word), '/\\');
                    preg_match_all("/{$wordLower}/", $content, $match);
                    if(count($match) && count($match[0])){
                        foreach ($match[0] as $item){
                            if(!isset($searchData[$catalog][$category])){
                                $searchData[$catalog][$category] = 0;
                            }
                            $searchData[$catalog][$category]++;
                        }
                    }
                }
            }
        }

        uasort($searchData, function ($a, $b){
            $counterA = 0;
            $counterB = 0;
            foreach (array_keys($a) as $key){
                $counterA += $a[$key];
            }
            foreach (array_keys($b) as $key){
                $counterB += $b[$key];
            }
            return $counterB > $counterA;
        });


        if(count($searchData)){
            $keys = array_keys($searchData);
            $keysCategory = array_keys($searchData[$keys[0]]);
            return [
                'category' => $keysCategory[0],
                'theme' => $keys[0]
            ];
        }
        return [
            'category' => 'unknown',
            'theme' => 'unknown',
        ];
    }
}
