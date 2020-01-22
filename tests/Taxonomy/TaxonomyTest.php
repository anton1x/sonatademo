<?php

namespace App\Tests;

use App\Taxonomy\TaxonomyConfiguration;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class TaxonomyTest extends TestCase
{

    public function testTaxonomyConfigurationCorrectlyProcessed()
    {
        $config = $this->getConfig();
        $taxonomyConfig = new TaxonomyConfiguration();
        $processor = new Processor();

        $processedConfiguration = $processor->processConfiguration($taxonomyConfig, [$config]);

        //print_r($processedConfiguration);

        $this->assertArrayHasKey('antonix_taxonomy', $processedConfiguration);

        $this->assertTrue(is_array($processedConfiguration['antonix_taxonomy']['items']['item1']['children']));
        $this->assertCount(1, $processedConfiguration['antonix_taxonomy']['items']['item2']['children']);
    }

    private function getConfig()
    {
        $config = Yaml::parse(
            file_get_contents(__DIR__.'/config/taxonomy.yaml')
        );

        return $config;


    }
}
