<?php

/**
 * This file is part of the YAML Gettext utility.
 *
 *  (c) Alexander Rakushin <lexander.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yml\Gettext\Test;

use Yml\Gettext\Extractor;
use Symfony\Component\Translation\Loader\PoFileLoader;

/**
 * @author Alexander Rakushin <lexander.r@gmail.com>
 */
class ExtractorTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var PoFileLoader
     */
    protected $loader;

    protected function setUp()
    {
        $this->loader = new PoFileLoader();
    }

    /**
     * @dataProvider extractDataProvider
     */
    public function testExtract(array $templates, array $keys, array $messages)
    {
        $extractor = new Extractor();

        foreach ($templates as $resource) {
            $extractor->addResource($resource);
        }

        foreach ($keys as $key) {
            $extractor->addKey($key);
        }

        $extractor->setOut($this->getPotFile());


        $extractor->extract();

        $catalog = $this->loader->load($this->getPotFile(), null);

        foreach ($messages as $message) {
            $this->assertTrue(
                $catalog->has($message),
                sprintf('Message "%s" not found in catalog.', $message)
            );
        }
    }

    public function extractDataProvider()
    {
        return [
            [
                [
                    __DIR__ . '/Fixtures/GoldenTable.yml'
                ],
                ['text', 'tooltip'],
                [
                    'Hello %name%!',
                    'Hello World!',
                    'Hey %name%, I have one apple.',
                    'Hey %name%, I have %count% apples.',
                ],
            ],
        ];
    }

    public function testExtractNoTranslations()
    {
        $extractor = new Extractor( );

        $extractor->addResource(__DIR__ . '/Fixtures/empty.yml');
        $extractor->setOut($this->getPotFile());

        $extractor->addKey('non-existent');


        $extractor->extract();
        $catalog = $this->loader->load($this->getPotFile(), null );

         $this->assertEmpty($catalog->all('messages'));
    }

    private function getPotFile()
    {
        return __DIR__ . '/Fixtures/messages.pot';
    }


    protected function tearDown()
    {
        if (file_exists($this->getPotFile())) {
            unlink($this->getPotFile());
        }
    }

}
