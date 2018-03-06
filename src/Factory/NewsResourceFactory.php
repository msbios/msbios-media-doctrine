<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use MSBios\Media\Doctrine\V1\Rest\News\NewsResource;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class NewsResourceFactory
 * @package MSBios\Media\Doctrine\Factory
 */
class NewsResourceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NewsResource
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NewsResource($container->get(EntityManager::class));
    }
}