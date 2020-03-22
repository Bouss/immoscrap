<?php

namespace App\Parser\EmailParser;

use App\Enum\Provider;
use App\Parser\AbstractParser;
use Symfony\Component\DomCrawler\Crawler;

class OuestFranceImmoNeuf2Parser extends AbstractParser
{
    protected const SITE = Provider::OUESTFRANCE_IMMO;
    protected const SELECTOR_AD_WRAPPER = 'td[style*="padding:5px 0;"]';
    protected const SELECTOR_EXTERNAL_ID = '';
    protected const SELECTOR_TITLE = '';
    protected const SELECTOR_DESCRIPTION = '';
    protected const SELECTOR_LOCATION = 'div[style*="color:#65707a"]:nth-child(2)';
    protected const SELECTOR_PUBLISHED_AT = '';
    protected const SELECTOR_URL = 'a:first-child';
    protected const SELECTOR_PRICE = 'span[style*="font-size:20px"]';
    protected const SELECTOR_AREA = '';
    protected const SELECTOR_ROOMS_COUNT = '';
    protected const SELECTOR_PHOTO = 'img';
    protected const SELECTOR_REAL_AGENT_ESTATE = '';
    protected const SELECTOR_NEW_BUILD = '';
    protected const PUBLISHED_AT_FORMAT = '';

    /**
     * {@inheritDoc}
     */
    protected function isNewBuild(Crawler $crawler, $nodeExistenceOnly = true): bool
    {
        return true;
    }
}