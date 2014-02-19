<?php
/**
 * This file is part of the Imbo package
 *
 * (c) Christer Edvartsen <cogo@starzinger.net>
 *
 * For the full copyright and license information, please view the LICENSE file that was
 * distributed with this source code.
 */

namespace ImboUnitTest\Resource;

use Imbo\Resource\ShortUrl;

/**
 * @covers Imbo\Resource\ShortUrl
 * @group unit
 * @group resources
 */
class ShortUrlTest extends ResourceTests {
    /**
     * @var ShortUrl
     */
    private $resource;

    /**
     * {@inheritdoc}
     */
    protected function getNewResource() {
        return new ShortUrl();
    }

    /**
     * Set up the resource
     */
    public function setUp() {
        $this->resource = $this->getNewResource();
    }

    /**
     * Tear down the resource
     */
    public function tearDown() {
        $this->resource = null;
    }
}
