<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Media\Doctrine\V1\Rpc\News;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use MSBios\Doctrine\DBAL\Types\PublishingStateType;
use MSBios\Media\Doctrine\Form\NewsForm;
use MSBios\Media\Resource\Doctrine\Entity\News;
use MSBios\Paginator\Doctrine\Adapter\QueryBuilderPaginator;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;

/**
 * Class NewsController
 * @package MSBios\Media\Doctrine\V1\Rpc\News
 */
class NewsController extends AbstractRestfulController implements ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /** @const ITEM_COUNT_PER_PAGE */
    const ITEM_COUNT_PER_PAGE = 3;

    /**
     * NewsController constructor.
     *
     * @param ObjectManager $objectManager
     * @param NewsForm $form
     */
    public function __construct(ObjectManager $objectManager, NewsForm $form)
    {
        $this->setObjectManager($objectManager);
        $this->form = $form;
    }

    /**
     * @return JsonModel
     */
    public function newsAction()
    {
        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var ObjectRepository $repository */
        $repository = $dem->getRepository(News::class);

        $this->form
            ->setData($this->params()->fromQuery());

        /**
         * @param QueryBuilder $qb
         * @return QueryBuilderPaginator
         */
        $fetchBy = self::factorySearch($this->form);

        /** @var Paginator $paginator */
        $paginator = $repository->fetchAll($fetchBy)
            ->setPageRange(4)
            ->setItemCountPerPage($this->params()->fromQuery('limit', self::ITEM_COUNT_PER_PAGE))
            ->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        /** @var News[] $news */
        $news = [];

        /** @var DoctrineObject $hydrator */
        $hydrator = new DoctrineObject($dem);

        foreach ($paginator as $new) {
            $news[] = $hydrator->extract($new);
        }

        return new JsonModel([
            'news' => $news,
            'item_count' => $paginator->getTotalItemCount(),
            'total' => $repository->count([
                'state' => $this->params()->fromQuery(
                    'state',
                    PublishingStateType::PUBLISHING_STATE_PUBLISHED
                ),
                'rowStatus' => true
            ])
        ]);
    }

    /**
     * @param NewsForm $search
     * @return \Closure
     */
    public static function factorySearch(NewsForm $search)
    {
        return function (QueryBuilder $qb) use ($search) {

            $qb->where('n.rowStatus = :rowStatus')
                ->andWhere('n.state = :state')
                ->setParameter('state', PublishingStateType::PUBLISHING_STATE_PUBLISHED)
                ->setParameter('rowStatus', true);

            if ($search->isValid()) {

                /** @var array $values */
                $values = $search->getData();

                if (! empty($values['title'])) {
                    $qb->andWhere($qb->expr()->like('n.title', ':title'))
                        ->setParameter('title', "{$values['title']}%");
                }

                if (! empty($values['postdate']['from']) && ! empty($values['postdate']['to'])) {
                    $qb->andWhere('n.postdate BETWEEN :from AND :to')
                        ->setParameter('from', new \DateTime($values['postdate']['from']), Type::DATETIME)
                        ->setParameter('to', new \DateTime($values['postdate']['to']), Type::DATETIME);
                } elseif (! empty($values['postdate']['from'])) {
                    $qb->andWhere('n.postdate > :from')
                        ->setParameter('from', new \DateTime($values['postdate']['from']), Type::DATETIME);
                } elseif (! empty($values['postdate']['to'])) {
                    $qb->andWhere('n.postdate < :to')
                        ->setParameter('to', new \DateTime($values['postdate']['to']), Type::DATETIME);
                } else {
                    $qb->andWhere('n.postdate < :now')
                        ->setParameter('now', new \DateTime('now'), Type::DATETIME);
                }

                if (! empty($params['sort'])) {
                    $order = explode(' ', $params['sort']);
                    $qb->orderBy("n.$order[0]", $order[1]);
                } else {
                    $qb->orderBy("n.postdate", 'DESC');
                }
            }

            return new QueryBuilderPaginator($qb, [], ['n.postdate' => 'DESC']);
        };
    }
}
