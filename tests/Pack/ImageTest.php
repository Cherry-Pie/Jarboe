<?php

namespace Yaro\Jarboe\Tests\Pack;

use Yaro\Jarboe\Pack\Image;
use Yaro\Jarboe\Tests\AbstractBaseTest;

class ImageTest extends AbstractBaseTest
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('filesystems.disks.public.driver', 'local');
        $app['config']->set('filesystems.disks.public.root', storage_path('app/public'));
        $app['config']->set('filesystems.disks.public.url', 'http://localhost/storage');
        $app['config']->set('filesystems.disks.public.visibility', 'public');
    }

    protected function pack($data): Image
    {
        return new Image($data);
    }

    /**
     * @test
     */
    public function test_invalid_data()
    {
        $wrongData = [
            'oh hai',
            null,
            true,
            42,
        ];
        foreach ($wrongData as $data) {
            $pack = $this->pack($data);

            $this->assertFalse($pack->exist());
            $this->assertFalse($pack->hasCropped());
            $this->assertNull($pack->croppedOrOriginalSourceUrl());
            $this->assertNull($pack->originalSourceUrl());
            $this->assertNull($pack->originalSource());
            $this->assertNull($pack->croppedSourceUrl());
            $this->assertNull($pack->croppedSource());
            $this->assertFalse($pack->isEncoded());
            $this->assertNull($pack->getDisk());
            $this->assertNull($pack->cropWidth());
            $this->assertNull($pack->cropHeight());
            $this->assertNull($pack->cropX());
            $this->assertNull($pack->cropY());
            $this->assertNull($pack->cropRotate());
            $this->assertNull($pack->cropRotateBackground());
        }
    }

    /**
     * @test
     */
    public function test_valid_data()
    {
        $data = [
            'storage' => [
                'disk' => 'public',
                'is_encoded' => false,
            ],
            'crop' => [
                'width' => '1269.4214876033056',
                'height' => '714.0495867768594',
                'x' => '723.2937883978874',
                'y' => '457.69561048965454',
                'rotate' => '31',
                'rotate_background' => 'rgba(217,53,53,1)',
            ],
            'sources' => [
                'original' => 'efTEwyoxM5ZzOWl2VC7GhZHzP3ZknkYaQgxStnrN.png',
                'cropped' => 'cSqf8KuSZTJ36wYfK4TnI12I5vOtSEnQFnXqqAAk.png',
            ],
        ];
        $pack = $this->pack($data);

        $this->assertTrue($pack->exist());
        $this->assertTrue($pack->hasCropped());
        $this->assertSame('http://localhost/storage/cSqf8KuSZTJ36wYfK4TnI12I5vOtSEnQFnXqqAAk.png', $pack->croppedOrOriginalSourceUrl());
        $this->assertSame('http://localhost/storage/efTEwyoxM5ZzOWl2VC7GhZHzP3ZknkYaQgxStnrN.png', $pack->originalSourceUrl());
        $this->assertSame('http://localhost/storage/cSqf8KuSZTJ36wYfK4TnI12I5vOtSEnQFnXqqAAk.png', $pack->croppedSourceUrl());
        $this->assertSame('efTEwyoxM5ZzOWl2VC7GhZHzP3ZknkYaQgxStnrN.png', $pack->originalSource());
        $this->assertSame('cSqf8KuSZTJ36wYfK4TnI12I5vOtSEnQFnXqqAAk.png', $pack->croppedSource());
        $this->assertFalse($pack->isEncoded());
        $this->assertSame($data['storage']['disk'], $pack->getDisk());
        $this->assertSame($data['crop']['width'], $pack->cropWidth());
        $this->assertSame($data['crop']['height'], $pack->cropHeight());
        $this->assertSame($data['crop']['x'], $pack->cropX());
        $this->assertSame($data['crop']['y'], $pack->cropY());
        $this->assertSame($data['crop']['rotate'], $pack->cropRotate());
        $this->assertSame($data['crop']['rotate_background'], $pack->cropRotateBackground());



        $data = [
            'storage' => [
                'disk' => 'public',
                'is_encoded' => true,
            ],
            'crop' => [
                'width' => '1269.4214876033056',
                'height' => '714.0495867768594',
                'x' => '723.2937883978874',
                'y' => '457.69561048965454',
                'rotate' => '31',
                'rotate_background' => 'rgba(217,53,53,1)',
            ],
            'sources' => [
                'original' => 'data:image/png;base64, original==',
                'cropped' => 'data:image/png;base64, cropped==',
            ],
        ];
        $pack = $this->pack($data);

        $this->assertTrue($pack->isEncoded());
        $this->assertTrue($pack->hasCropped());
        $this->assertSame($data['sources']['cropped'], $pack->croppedOrOriginalSourceUrl());
        $this->assertSame($data['sources']['original'], $pack->originalSourceUrl());
        $this->assertSame($data['sources']['cropped'], $pack->croppedSourceUrl());
        $this->assertSame($data['sources']['original'], $pack->originalSource());
        $this->assertSame($data['sources']['cropped'], $pack->croppedSource());



        $data = [
            'storage' => [
                'disk' => 'public',
                'is_encoded' => true,
            ],
            'crop' => [
                'width' => '1269.4214876033056',
                'height' => '714.0495867768594',
                'x' => '723.2937883978874',
                'y' => '457.69561048965454',
                'rotate' => '31',
                'rotate_background' => 'rgba(217,53,53,1)',
            ],
            'sources' => [
                'original' => 'data:image/png;base64, original==',
                'cropped' => '',
            ],
        ];
        $pack = $this->pack($data);

        $this->assertTrue($pack->isEncoded());
        $this->assertFalse($pack->hasCropped());
        $this->assertSame($data['sources']['original'], $pack->croppedOrOriginalSourceUrl());
        $this->assertSame($data['sources']['original'], $pack->originalSourceUrl());
        $this->assertNull($pack->croppedSourceUrl());
        $this->assertSame('sasa', $pack->croppedSourceUrl('sasa'));
        $this->assertSame($data['sources']['original'], $pack->originalSource());
        $this->assertNull($pack->croppedSource());
        $this->assertSame('lele', $pack->croppedSource('lele'));
    }
}
