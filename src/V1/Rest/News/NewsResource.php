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
use MSBios\Media\Doctrine\Controller\NewsController;
use MSBios\Media\Resource\Doctrine\Entity\News;
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

    /** @var HydratorInterface */
    protected $hydrator;

    /** @const ITEM_COUNT_PER_PAGE */
    const ITEM_COUNT_PER_PAGE = NewsController::DEFAULT_ITEM_COUNT_PER_PAGE;

    /**
     * NewsResource constructor.
     * @param ObjectManager $dem
     */
    public function __construct(ObjectManager $dem)
    {
        $this->setObjectManager($dem);

        /** @var HydratorInterface $hydrator */
        $this->hydrator = new DoctrineObject($dem);
    }

    /**
     * @param mixed $id
     * @return JsonModel
     */
    public function get($id)
    {
        /** @var EntityInterface $item */
        $item = $this->getObjectManager()
            ->find(News::class, $id);

        return new JsonModel([
            'success' => true,
            'item' => $this->hydrator->extract($item)
        ]);
    }

    /**
     * @return JsonModel
     */
    public function getList()
    {
        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var ObjectRepository $repository */
        $repository = $dem->getRepository(News::class);

        /** @var Paginator $paginator */
        $paginator = $repository->getPaginatorFromQuery(
            $this->params()->fromQuery(),
            $this->params()->fromQuery('page', 1),
            $this->params()->fromQuery('limit', self::ITEM_COUNT_PER_PAGE)
        );

        /** @var array $items */
        $items = [];

        /** @var EntityInterface $item */
        foreach ($paginator as $item) {
            $items[] = $this->hydrator->extract($item);
        }

        return new JsonModel([
            'success' => true,
            'items' => $items,
            'total' => $paginator->getTotalItemCount()
        ]);
    }
}
