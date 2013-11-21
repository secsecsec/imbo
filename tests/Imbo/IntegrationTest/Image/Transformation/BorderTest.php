<?php
/**
 * This file is part of the Imbo package
 *
 * (c) Christer Edvartsen <cogo@starzinger.net>
 *
 * For the full copyright and license information, please view the LICENSE file that was
 * distributed with this source code.
 */

namespace Imbo\IntegrationTest\Image\Transformation;

use Imbo\Image\Transformation\Border,
    Imagick;

/**
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @package Test suite\Integration tests
 * @covers Imbo\Image\Transformation\Border
 */
class BorderTest extends TransformationTests {
    /**
     * {@inheritdoc}
     */
    protected function getTransformation() {
        return new Border();
    }

    /**
     * Data provider
     *
     * @return array[]
     */
    public function getBorderParams() {
        return array(
            'inline border' => array(665, 463, 3, 4, 'inset'),
            'outbound border' => array(671, 471, 3, 4, 'outbound'),
        );
    }

    /**
     * @dataProvider getBorderParams
     */
    public function testTransformationSupportsDifferentModes($expectedWidth, $expectedHeight, $borderWidth, $borderHeight, $borderMode) {
        $image = $this->getMock('Imbo\Model\Image');
        $image->expects($this->once())->method('setWidth')->with($expectedWidth)->will($this->returnValue($image));
        $image->expects($this->once())->method('setHeight')->with($expectedHeight)->will($this->returnValue($image));
        $image->expects($this->once())->method('hasBeenTransformed')->with(true);

        $event = $this->getMock('Imbo\EventManager\Event');
        $event->expects($this->at(0))
              ->method('getArgument')
              ->with('image')
              ->will($this->returnValue($image));
        $event->expects($this->at(1))
              ->method('getArgument')
              ->with('params')
              ->will($this->returnValue(array(
                  'color' => 'white',
                  'width' => $borderWidth,
                  'height' => $borderHeight,
                  'mode' => $borderMode,
              )));

        $blob = file_get_contents(FIXTURES_DIR . '/image.png');

        $imagick = new Imagick();
        $imagick->readImageBlob($blob);

        $this->getTransformation()->setImagick($imagick)->transform($event);
    }
}
