<?php
/**
 * Imbo
 *
 * Copyright (c) 2011 Christer Edvartsen <cogo@starzinger.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * * The above copyright notice and this permission notice shall be included in
 *   all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package Imbo
 * @subpackage Resources
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/imbo
 */

namespace Imbo\Resource;

use Imbo\Http\Request\RequestInterface;
use Imbo\Http\Response\ResponseInterface;
use Imbo\Database\DatabaseInterface;
use Imbo\Storage\StorageInterface;
use Imbo\Database\Exception as DatabaseException;

/**
 * Metadata resource
 *
 * @package Imbo
 * @subpackage Resources
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/imbo
 */
class Metadata extends Resource implements ResourceInterface {
    /**
     * @see Imbo\Resource\ResourceInterface::getAllowedMethods()
     */
    public function getAllowedMethods() {
        return array(
            RequestInterface::METHOD_GET,
            RequestInterface::METHOD_POST,
            RequestInterface::METHOD_DELETE,
        );
    }

    /**
     * @see Imbo\Resource\ResourceInterface::delete()
     */
    public function delete(RequestInterface $request, ResponseInterface $response, DatabaseInterface $database, StorageInterface $storage) {
        try {
            $database->deleteMetadata($request->getPublicKey(), $request->getImageIdentifier());
        } catch (DatabaseException $e) {
            throw new Exception('Database error: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @see Imbo\Resource\ResourceInterface::post()
     */
    public function post(RequestInterface $request, ResponseInterface $response, DatabaseInterface $database, StorageInterface $storage) {
        $imageIdentifier = $request->getImageIdentifier();

        // Fetch metadata from the request
        $metadata = $request->getRequest()->get('metadata');

        if (!$metadata) {
            $metadata = array();
        } else {
            $metadata = json_decode($metadata, true);
        }

        try {
            $database->updateMetadata($request->getPublicKey(), $imageIdentifier, $metadata);
        } catch (DatabaseException $e) {
            throw new Exception('Database error: ' . $e->getMessage(), $e->getCode(), $e);
        }

        $response->setBody(array('imageIdentifier' => $imageIdentifier));
    }

    /**
     * @see Imbo\Resource\ResourceInterface::get()
     */
    public function get(RequestInterface $request, ResponseInterface $response, DatabaseInterface $database, StorageInterface $storage) {
        try {
            $data = $database->getMetadata($request->getPublicKey(), $request->getImageIdentifier());
        } catch (DatabaseException $e) {
            throw new Exception('Database error: ' . $e->getMessage(), $e->getCode(), $e);
        }

        $response->setBody($data);
    }
}