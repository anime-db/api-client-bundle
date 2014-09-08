<?php
/**
 * AnimeDb package
 *
 * @package   AnimeDb
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ApiClientBundle\Tests\Service;

use AnimeDb\Bundle\ApiClientBundle\Service\Client;

/**
 * Test client
 *
 * @package AnimeDb\Bundle\ApiClientBundle\Tests\Service
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Version
     *
     * @var integer
     */
    protected $version = 100500;

    /**
     * Site host
     *
     * @var string
     */
    protected $host = 'http://example.com';

    /**
     * API path prefix
     *
     * @var string
     */
    protected $prefix = '/bar';

    /**
     * Locale
     *
     * @var string
     */
    protected $locale = 'en';

    /**
     * Client
     *
     * @var \AnimeDb\Bundle\ApiClientBundle\Service\Client
     */
    protected $client;

    /**
     * Guzzle
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $guzzle;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        parent::setUp();
        $this->guzzle = $this->getMock('\Guzzle\Http\Client');
        $this->client = new Client($this->guzzle, $this->locale, $this->host, $this->prefix, $this->version);
    }

    /**
     * Test get host
     */
    public function testGetHost()
    {
        $this->assertEquals($this->host, $this->client->getHost());
    }

    /**
     * Get methods
     *
     * @return array
     */
    public function getMethods()
    {
        return [
            ['/plugin/', 'getPlugins'],
            ['/plugin/foo/bar/', 'getPlugin', ['foo', 'bar']],
        ];
    }

    /**
     * Test methods
     *
     * @dataProvider getMethods
     *
     * @param string $path
     * @param string $method
     * @param array $params
     */
    public function testMethods($path, $method, array $params = [])
    {
        $expected = ['foo', 'bar'];
        $this->createDialog($path, $expected);
        $this->assertEquals($expected, call_user_func_array([$this->client, $method], $params));
    }

    /**
     * Test methods fail
     *
     * @expectedException \RuntimeException
     * @dataProvider getMethods
     *
     * @param string $path
     * @param string $method
     * @param array $params
     */
    public function testMethodsFail($path, $method, array $params = [])
    {
        $this->createDialog($path, null);
        call_user_func_array([$this->client, $method], $params);
    }

    /**
     * Create dialog
     *
     * @param string $path
     * @param mixed $result
     */
    protected function createDialog($path, $result)
    {
        $request = $this->getMock('\Guzzle\Http\Message\RequestInterface');
        $response = $this->getMockBuilder('\Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $this->guzzle
            ->expects($this->once())
            ->method('get')
            ->willReturn($request)
            ->with($this->prefix.'/v'.$this->version.'/'.$this->locale.$path);
        $request
            ->expects($this->once())
            ->method('send')
            ->willReturn($response);
        $response
            ->expects($this->once())
            ->method('isError')
            ->willReturn(!$result);

        if ($result) {
            $response
                ->expects($this->once())
                ->method('getBody')
                ->willReturn(json_encode($result))
                ->with(true);
        } else {
            $response
                ->expects($this->never())
                ->method('getBody');
        }
    }
}
