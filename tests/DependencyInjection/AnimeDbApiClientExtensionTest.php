<?php
/**
 * AnimeDb package
 *
 * @package   AnimeDb
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ApiClientBundle\Tests\DependencyInjection;

use AnimeDb\Bundle\ApiClientBundle\DependencyInjection\AnimeDbApiClientExtension;

/**
 * Test DependencyInjection
 *
 * @package AnimeDb\Bundle\ApiClientBundle\Tests\DependencyInjection
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class AnimeDbApiClientExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test load
     */
    public function testLoad()
    {
        $di = new AnimeDbApiClientExtension();
        $di->load([], $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder'));
    }
}
