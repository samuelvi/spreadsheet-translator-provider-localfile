<?php

/*
 * This file is part of the Atico/SpreadsheetTranslator package.
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atico\SpreadsheetTranslator\Provider\LocalFile;

use Atico\SpreadsheetTranslator\Core\Configuration\Configuration;
use Atico\SpreadsheetTranslator\Core\Provider\ProviderInterface;
use Atico\SpreadsheetTranslator\Core\Resource\Resource;

class LocalFileProvider implements ProviderInterface
{
    protected LocalFileConfigurationManager $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = new LocalFileConfigurationManager($configuration);
    }

    public function getProvider(): string
    {
        return 'local_file';
    }

    public function handleSourceResource(): Resource
    {
        $tempLocalSourceFile = $this->configuration->getTempLocalSourceFile();
        copy($this->configuration->getSourceResource(), $tempLocalSourceFile);
        return new Resource($tempLocalSourceFile, $this->configuration->getFormat());
    }
}