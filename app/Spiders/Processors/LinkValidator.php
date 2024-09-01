<?php

namespace App\Spiders\Processors;

use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;
use RoachPHP\Support\Configurable;

class LinkValidator implements ItemProcessorInterface
{
    use Configurable;

    public function processItem(ItemInterface $item): ItemInterface
    {
        // process each item and drop it if it doesn't contain one of the applicable tags
        foreach ($item->get('tags', []) as $tag) {
            if(in_array($tag, $this->option('tags'))) {
                // the item has at least one valid tag, so we can return it to save processing the remaining tags
                return $item;
            }
        }

        return $item->drop('Item has no applicable tags');
    }

    private function defaultOptions(): array
    {
        // this
        return [
            'tags' => [
                'laravel', 'vue', 'vue.js', 'php', 'api'
            ]
        ];
    }
}
