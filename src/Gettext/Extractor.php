<?php

/**
 * This file is part of the YAML Gettext utility.
 *
 *  (c) Alexander Rakushin <lexander.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yml\Gettext;

use Symfony\Component\Translation\Dumper\PoFileDumper;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Translation\MessageCatalogue;


/**
 * Extracts translations from twig templates.
 *
 * @author Alexander Rakushin <lexander.r@gmail.com>
 */
class Extractor
{
    /**
     * @var string Path to output po file
     */
    private $outFile;
    /**
     * @var MessageCatalogue
     */
    private $catalogue;
    /**
     * @var array Keys
     */
    private $whiteList = [];
    /**
     * @var string translate domain
     */
    private $domain;
    /**
     * @var array List of a input yml files
     */
    private $resources;

    public function __construct(  )
    {
         $this->reset();
    }

    /**
     * @param string $outFile
     */
    public function setOut($outFile)
    {
        $this->outFile = $outFile;
    }

    protected function reset()
    {
        $this->domain = uniqid('messages_');

        $this->resources = [];
        $this->parameters = [];
        $this->whiteList = [];
        $this->catalogue = new MessageCatalogue('en');
        $this->catalogue->setMetadata('', time());
    }

    public function addResource($path)
    {
        if(file_exists($path))
            $this->resources[] = $path;
    }
    public function addKey($fn)
    {
        $this->whiteList[] = $fn;
    }


    public function extract()
    {
        $domain = $this->domain;
        foreach($this->resources as $resource) {

            $cfg = Yaml::parse(file_get_contents($resource));

            $whiteList = $this->whiteList;

            $messages = $this->catalogue;

            //Пробегаемся рекурсивно по конфигу, и находим нужные записи
            array_walk_recursive($cfg, function ($item, $key) use ($whiteList, &$messages,$domain) {
                if (in_array($key, $whiteList) and is_scalar($item)) {
                    $messages->add([$item => null],$domain);
                }

            });

        }


        //Сбразываем результат
        $dir = sys_get_temp_dir();
        $po = new PoFileDumper();
        $po->dump($this->catalogue, ['path'=>$dir]);
        $tmpFile = $dir.DIRECTORY_SEPARATOR.$domain.'.en.po';

        if(!count($this->catalogue->all())){
            // Когда нет переводов, пустой файл не создается,
            //Из за этого падает poedit. Формируем пустой файл
            file_put_contents($tmpFile, $po->formatCatalogue($this->catalogue, $this->domain));
        }
        if(file_exists($tmpFile) and isset($this->outFile))
        {
            if(file_exists($this->outFile))
                unlink($this->outFile);
            rename($tmpFile, $this->outFile);

        }

        if(file_exists($tmpFile))
        unlink($tmpFile);
        $this->reset();
    }


}
