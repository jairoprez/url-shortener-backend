<?php

namespace App\Transformers;

class LinkTransformer extends Transformer
{
    /**
     * Transform a link
     * 
     * @param  $link
     * @return array
     */
    public function transform($link)
    {
        return [
            'id' => $link['id'],
            'url' => $link['url'],
            'code' => $link['code']
        ];
    } 
}
