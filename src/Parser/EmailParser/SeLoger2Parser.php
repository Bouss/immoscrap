<?php

namespace App\Parser\EmailParser;

use App\Definition\SiteEnum;
use App\Exception\ParseException;
use App\Parser\AbstractParser;
use App\Util\NumericUtil;
use Exception;
use Symfony\Component\DomCrawler\Crawler;

class SeLoger2Parser extends AbstractParser
{
    protected const SITE = SiteEnum::SELOGER;
    protected const SELECTOR_AD_WRAPPER = 'table[class="outer"] > tr:nth-child(5) > td';
    protected const SELECTOR_EXTERNAL_ID = '';
    protected const SELECTOR_TITLE = '';
    protected const SELECTOR_DESCRIPTION = '';
    protected const SELECTOR_LOCATION = '';
    protected const SELECTOR_PUBLISHED_AT = '';
    protected const SELECTOR_URL = 'a';
    protected const SELECTOR_PRICE = 'table[class*="contents"] tr:nth-child(1) a';
    protected const SELECTOR_AREA = 'table[class*="contents"] tr:nth-child(3) a';
    protected const SELECTOR_ROOMS_COUNT = 'table[class*="contents"] tr:nth-child(3) a';
    protected const SELECTOR_PHOTO = 'img';
    protected const SELECTOR_REAL_AGENT_ESTATE = '';
    protected const SELECTOR_NEW_BUILD = '';
    protected const PUBLISHED_AT_FORMAT = '';

    /**
     * {@inheritDoc}
     */
    protected function getArea(Crawler $crawler): float
    {
        try {
            $description = trim($crawler->filter(static::SELECTOR_AREA)->text());
        } catch (Exception $e) {
            throw new ParseException('Error while parsing the area: ' . $e->getMessage());
        }

        $areaStr = explode('•', $description)[1];

        return NumericUtil::extractFloat($areaStr);
    }

    /**
     * {@inheritDoc}
     */
    protected function getRoomsCount(Crawler $crawler): int
    {
        try {
            $description = trim($crawler->filter(static::SELECTOR_ROOMS_COUNT)->text());
        } catch (Exception $e) {
            throw new ParseException('Error while parsing the number of rooms: ' . $e->getMessage());
        }

        $roomsCountStr = explode('•', $description)[0];

        return NumericUtil::extractInt($roomsCountStr);
    }
}