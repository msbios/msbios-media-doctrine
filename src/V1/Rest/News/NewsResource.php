<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Media\Doctrine\V1\Rest\News;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use MSBios\Doctrine\ObjectManagerAwareTrait;
use MSBios\Media\Resource\Doctrine\Entity\News;
use MSBios\Media\Resource\Doctrine\Repository\NewsRepository;
use MSBios\Resource\Doctrine\EntityInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;

/**
 * Class NewsResource
 * @package MSBios\Media\Doctrine\V1\Rest\News
 */
class NewsResource extends AbstractRestfulController
{
    use ObjectManagerAwareTrait;

    /**
     * NewsResource constructor.
     * @param NewsRepository $repository
     */
    public function __construct(ObjectManager $dem)
    {
        $this->setObjectManager($dem);
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function get($id)
    {
        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();
        /** @var EntityInterface $item */
        $item = $dem->find(News::class, $id);

        return new JsonModel([
            'success' => true,
            'item' => (new DoctrineObject($dem))->extract($item)
        ]);
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var ObjectRepository $repository */
        $repository = $dem->getRepository(News::class);

        /** @var Paginator $paginator */
        $paginator = $repository->getPaginatorFromQuery(
            $this->params()->fromQuery(), $this->params()->fromQuery('page', 1), 3
        );

        /** @var HydratorInterface $hydrator */
        $hydrator = new DoctrineObject($dem);

        /** @var array $items */
        $items = [];

        /** @var News $item */
        foreach ($paginator as $item) {
            $items[] = $hydrator->extract($item);
        }

        return new JsonModel([
            'success' => true,
            'items' => $items,
            'total' => $paginator->count()
        ]);
    }

}