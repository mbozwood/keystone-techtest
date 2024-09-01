<?php

namespace App\Spiders;

use App\Spiders\Processors\LinkValidator;
use App\Spiders\Processors\SaveLink;
use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use Symfony\Component\DomCrawler\Crawler;

class Pinboard extends BasicSpider
{
    public array $startUrls = [
        'https://pinboard.in/u:alasdairw?per_page=120'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        LinkValidator::class,
        SaveLink::class,
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {
        // filter through all divs with the bookmark class
        // define a new item object, extracting the necessary properties
        $bookmarks = $response
            ->filter('div.bookmark')
            ->each(fn(Crawler $node) => [
                'title' => $node->filter('a.bookmark_title')->text(),
                'url' => $node->filter('a.bookmark_title')->link()->getUri(),
                'comment' => $node->filter('.description')->text(),
                'tags' => $node->filter('.tag')->each(fn(Crawler $node) => $node->text()),
            ]);

        foreach ($bookmarks as $bookmark) {
            yield $this->item($bookmark);
        }
    }
}
