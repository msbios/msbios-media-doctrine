<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\V1\Rpc\News;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use MSBios\Media\Doctrine\Form\NewsForm;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class NewsControllerFactory
 * @package MSBios\Media\Doctrine\V1\Rpc\News
 */
class NewsControllerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NewsController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NewsController(
            $container->get(EntityManager::class),
            $container->get('FormElementManager')->get(NewsForm::class)
        );
    }
}
