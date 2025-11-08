<?php

declare(strict_types=1);

namespace Atico\SpreadsheetTranslator\Provider\LocalFile\Tests;

use Atico\SpreadsheetTranslator\Core\Configuration\Configuration;
use Atico\SpreadsheetTranslator\Core\Resource\Resource;
use Atico\SpreadsheetTranslator\Provider\LocalFile\LocalFileProvider;
use PHPUnit\Framework\TestCase;

final class LocalFileProviderTest extends TestCase
{
    private string $sourceFile;
    private string $tempFile;

    protected function setUp(): void
    {
        $this->sourceFile = tempnam(sys_get_temp_dir(), 'st_source_') ?: '';
        $this->tempFile = tempnam(sys_get_temp_dir(), 'st_temp_') ?: '';

        if ('' === $this->sourceFile || '' === $this->tempFile) {
            $this->fail('Unable to create temporary files for the test suite.');
        }

        file_put_contents($this->sourceFile, 'spreadsheet-content');

        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    protected function tearDown(): void
    {
        if ('' !== $this->sourceFile && file_exists($this->sourceFile)) {
            unlink($this->sourceFile);
        }

        if ('' !== $this->tempFile && file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testHandleSourceResourceCopiesFileAndCreatesResource(): void
    {
        $configuration = $this->createConfiguration(['format' => 'csv']);
        $provider = new LocalFileProvider($configuration);

        $resource = $provider->handleSourceResource();

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertSame($this->tempFile, $resource->getValue());
        $this->assertSame('csv', $resource->getFormat());
        $this->assertFileExists($this->tempFile);
        $this->assertSame('spreadsheet-content', file_get_contents($this->tempFile));
        $this->assertSame('local_file', $provider->getProvider());
    }

    public function testHandleSourceResourceRequiresSourcePath(): void
    {
        $configurationData = [
            'providers' => [
                'local_file' => [
                    'temp_local_source_file' => $this->tempFile,
                    'format' => 'xlsx',
                ],
            ],
        ];

        $provider = new LocalFileProvider(new Configuration($configurationData, 'local_file'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('source_resource');

        $provider->handleSourceResource();
    }

    private function createConfiguration(array $overrides = []): Configuration
    {
        $defaults = [
            'source_resource' => $this->sourceFile,
            'temp_local_source_file' => $this->tempFile,
            'format' => 'xlsx',
        ];

        return new Configuration(
            [
                'providers' => [
                    'local_file' => array_merge($defaults, $overrides),
                ],
            ],
            'local_file'
        );
    }
}
