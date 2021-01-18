<?php

namespace App\Parser;

use App\Enum\Provider;
use Symfony\Component\DomCrawler\Crawler;
use function preg_match;

class PapNeufParser extends AbstractParser
{
    protected const PROVIDER = Provider::PAP_NEUF;

    protected const SELECTOR_AD_WRAPPER  = '#email-body tr:nth-child(n+2):not(:nth-last-child(-n+2))';
    protected const SELECTOR_LOCATION    = '.box-text-content';
    protected const SELECTOR_NAME        = '.box-text-content';

    /**
     * {@inheritDoc}
     */
    protected function parsePrice(Crawler $crawler): ?float
    {
        return $this->formatter->parsePrice(str_replace('.', '', $crawler->html()));
    }

    /**
     * {@inheritDoc}
     */
    protected function parseLocation(Crawler $crawler): ?string
    {
        if (1 === preg_match('/Adresse : (.+) A partir/', parent::parseBuildingName($crawler), $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    protected function parseBuildingName(Crawler $crawler): ?string
    {
        if (1 === preg_match('/\) (.+) Adresse/', parent::parseBuildingName($crawler), $matches)) {
            return $matches[1];
        }

        return null;
    }
}
