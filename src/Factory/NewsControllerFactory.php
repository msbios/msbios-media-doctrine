<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Media\Doctrine\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use MSBios\Media\Doctrine\Controller\NewsController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class NewsControllerFactory
 * @package MSBios\Media\Doctrine\Factory
 */
class NewsControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NewsController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NewsController(
            $container->get('FormElementManager'),
            $container->get(EntityManager::class)
        );
    }
}
