<?php

namespace App\Spiders\Processors;

use App\Models\Link;
use App\Models\Tag;
use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;
use RoachPHP\Support\Configurable;

class SaveLink implements ItemProcessorInterface
{
    use Configurable;

    public function processItem(ItemInterface $item): ItemInterface
    {

        /*
         * Check if the item was dropped in a previous processor
         * This would, in this case, be validating the tags
         */
        // this
        if(!$item->wasDropped()) {

            // check if the item has been saved previously and if it has, drop it so we don't process any longer
            $link = Link::query()->where('url', $item->get('url'))->where('title', $item->get('title'))->first();
            if($link) {
                $item->drop('Link has already been saved');
                return $item;
            }

            // save the link data to the database
            $link = Link::query()->create([
                'title' => $item->get('title'),
                'comment' => $item->get('comment'),
                'url' => $item->get('url'),
                'url_status' => $this->checkUrlStatus($item->get('url')),
            ]);

            // save all tags associated with the link
            foreach($item->get('tags') as $tag) {
                $tag = new Tag(['tag' => $tag]);
                $link->tags()->save($tag);
            }
        }

        return $item;
    }

    /**
     * @param string $url
     * @return mixed|null
     *
     * Get the HTTP Status Code for saving in the DB
     * We need this to render on the frontend
     */
    protected function checkUrlStatus(string $url): mixed
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, true); // we need the headers exclusively
            curl_setopt($ch, CURLOPT_NOBODY, true); // ignore the body, this is just a status code
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT,10);
            $output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpcode;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
