<?php

namespace App\Tests\Parser;

use App\DTO\Provider;
use App\Formatter\DecimalFormatter;
use App\Parser\BienIciParser;
use App\Parser\LeBonCoinParser;
use App\Parser\LogicImmoParser;
use App\Parser\LogicImmoPartnerParser;
use App\Parser\OuestFranceImmo2Parser;
use App\Parser\OuestFranceImmoNeufParser;
use App\Repository\ProviderRepository;
use DateTime;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OuestFranceImmoNeufParserTest extends KernelTestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|ProviderRepository */
    private $providerRepository;

    /** @var ObjectProphecy|LoggerInterface */
    private $logger;

    private string $projectDir;
    private OuestFranceImmoNeufParser $parser;

    public function setUp(): void
    {
        self::bootKernel();
        $this->projectDir = self::$kernel->getProjectDir();

        $this->providerRepository = $this->prophesize(ProviderRepository::class);
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->parser = new OuestFranceImmoNeufParser(
            $this->providerRepository->reveal(),
            new DecimalFormatter(),
            $this->logger->reveal()
        );
    }

    public function testParseCreatesAPropertyAdFromAnEmail(): void
    {
        $provider = $this->prophesize(Provider::class);

        // Given
        $provider->isNewBuildOnly()->willReturn(true);
        $this->providerRepository->find(Argument::any())->willReturn($provider->reveal());
        $html = file_get_contents($this->projectDir . '/tests/data/ouestfrance_immo_neuf.html','r');

        // When
        $propertyAds = $this->parser->parse($html, [], ['date' => new DateTime('2020-01-01 12:00:00')]);

        // Then
        self::assertCount(1, $propertyAds);
        $p = $propertyAds[0];
        self::assertEquals('LE JEAN INGRES', $p->getName());
        self::assertEquals('Nantes Zola (44)', $p->getLocation());
        self::assertEquals(2, $p->getRoomsCount());
        self::assertEquals(149494, $p->getPrice());
        self::assertEquals('ouestfrance_immo_neuf', $p->getProvider());
        self::assertTrue($p->isNewBuild());
        self::assertNotNull($p->getUrl());
        self::assertNotNull($p->getPhoto());
        self::assertNull($p->getArea());
        self::assertNull($p->getTitle());
        self::assertNull($p->getDescription());
    }
}