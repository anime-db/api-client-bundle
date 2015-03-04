<?php
/**
 * AnimeDb package
 *
 * @package   AnimeDb
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ApiClientBundle\Service;

use Guzzle\Http\Client as Guzzle;

/**
 * Browser
 *
 * @link http://anime-db.org/
 * @package AnimeDb\Bundle\ApiClientBundle\Service
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Client
{
    /**
     * API default locale
     *
     * @var string
     */
    const DEFAULT_LOCALE = 'en';

    /**
     * Host
     *
     * @var string
     */
    private $host;

    /**
     * API path prefix
     *
     * @var string
     */
    private $prefix;

    /**
     * HTTP client
     *
     * @var \Guzzle\Http\Client
     */
    private $client;

    /**
     * Locale
     *
     * @var string
     */
    protected $locale = self::DEFAULT_LOCALE;

    /**
     * List of available locales
     *
     * @var array
     */
    protected $locales = [self::DEFAULT_LOCALE];

    /**
     * Construct
     *
     * @param \Guzzle\Http\Client $client
     * @param string $locale
     * @param string $host
     * @param string $prefix
     * @param string $version
     */
    public function __construct(Guzzle $client, $host, $prefix, $version, array $locales, $locale) {
        $this->client = $client;
        $this->host = $host;
        $this->locales = $locales;
        $this->prefix = $prefix.'/v'.$version.'/';
        $this->setLocale($locale);
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return boolean
     */
    public function setLocale($locale)
    {
        $locale = substr($locale, 0, 2);
        if (in_array($locale, $this->locales)) {
            $this->locale = $locale;
            return true;
        }
        return false;
    }

    /**
     * Get plugins
     *
     * @return array
     */
    public function getPlugins()
    {
        return $this->get('/plugin/');
    }

    /**
     * Get plugin
     *
     * @param string $vendor
     * @param string $package
     *
     * @return array
     */
    public function getPlugin($vendor, $package)
    {
        return $this->get('/plugin/'.$vendor.'/'.$package.'/');
    }

    /**
     * Get data
     *
     * @param string $request
     *
     * @return array
     */
    protected function get($request)
    {
        $response = $this->client->get($this->prefix.$this->getLocale().$request)->send();
        if ($response->isError()) {
            throw new \RuntimeException(
                'Failed execute request "'.$request.'" to the server "'.$this->client->getBaseUrl().'"'
            );
        }
        return json_decode($response->getBody(true), true);
    }

    /**
     * Get site URL
     *
     * @param string $path
     *
     * @return string
     */
    public function getSiteUrl($path)
    {
        return $this->host.'/'.$this->locale.$path;
    }
}
