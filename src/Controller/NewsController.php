<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\QueryBuilder;
use MSBios\Application\Controller\IndexController as DefaultIndexController;
use MSBios\Doctrine\DBAL\Types\PublishingStateType;
use MSBios\Doctrine\ObjectManagerAwareTrait;
use MSBios\Form\FormElementManagerAwareTrait;
use MSBios\Media\Doctrine\Form\NewsForm;
use MSBios\Media\Resource\Doctrine\Entity\News;
use MSBios\Paginator\Doctrine\Adapter\QueryBuilderPaginator;
use MSBios\Resource\Doctrine\EntityInterface;
use Zend\Form\FormInterface;
use Zend\Paginator\Paginator;
use Zend\ServiceManager\PluginManagerInterface;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

/**
 * Class NewsController
 * @package MSBios\Media\Doctrine\Controller
 */
class NewsController extends DefaultIndexController
{
    use FormElementManagerAwareTrait;
    use ObjectManagerAwareTrait;

    /** @const DEFAULT_ITEM_COUNT_PER_PAGE */
    const DEFAULT_ITEM_COUNT_PER_PAGE = 3;

    /**
     * NewsController constructor.
     * @param PluginManagerInterface $formElementManager
     * @param ObjectManager $objectManager
     */
    public function __construct(PluginManagerInterface $formElementManager, ObjectManager $objectManager)
    {
        $this->setFormElementManager($formElementManager);
        $this->setObjectManager($objectManager);
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->getObjectManager()
            ->getRepository(News::class);
    }

    /**
     * @return ModelInterface
     */
    public function indexAction()
    {
        /** @var FormInterface $form */
        $form = $this->getFormElementManager()
            ->get(NewsForm::class);

        /** @var Paginator $paginator */
        $paginator = $this->getRepository()->fetchAll(function (QueryBuilder $queryBuilder) use ($form) {

            $queryBuilder->where('n.rowStatus = :rowStatus')
                ->andWhere('n.state = :state')
                ->setParameter('state', PublishingStateType::PUBLISHING_STATE_PUBLISHED)
                ->setParameter('rowStatus', true);

            if ($form->setData($this->params()->fromQuery())->isValid()) {

                /** @var array $values */
                $values = $form->getData();

                if (!empty($values['title'])) {
                    $queryBuilder->andWhere('n.title LIKE :title')
                        ->setParameter('title', "{$values['title']}%");
                }

                if (!empty($values['postdate']['from']) && !empty($values['postdate']['to'])) {
                    $queryBuilder->andWhere('n.postdate BETWEEN :from AND :to')
                        ->setParameter('from', new \DateTime($values['postdate']['from']), Type::DATETIME)
                        ->setParameter('to', new \DateTime($values['postdate']['to']), Type::DATETIME);
                } elseif (!empty($values['postdate']['from'])) {
                    $queryBuilder->andWhere('n.postdate > :from')
                        ->setParameter('from', new \DateTime($values['postdate']['from']), Type::DATETIME);
                } elseif (!empty($values['postdate']['to'])) {
                    $queryBuilder->andWhere('n.postdate < :to')
                        ->setParameter('to', new \DateTime($values['postdate']['to']), Type::DATETIME);
                } else {
                    $queryBuilder->andWhere('n.postdate < :now')
                        ->setParameter('now', new \DateTime('now'), Type::DATETIME);
                }
            }

            return new QueryBuilderPaginator($queryBuilder, [], ['n.postdate' => 'DESC']);
        });

        $paginator
            ->setCurrentPageNumber($this->params()->fromQuery('page', 1))
            ->setItemCountPerPage(self::DEFAULT_ITEM_COUNT_PER_PAGE);

        /** @var ModelInterface $viewModel */
        $viewModel = parent::indexAction();
        $viewModel->setVariables([
            'search' => $form,
            'paginator' => $paginator
        ]);

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function viewAction()
    {
        /** @var EntityInterface $entity */
        $entity = $this->getRepository()->findOneBy([
            'id' => (int)$this->params()->fromRoute('id'),
            'state' => $this->params()->fromQuery(
                'state',
                PublishingStateType::PUBLISHING_STATE_PUBLISHED
            ),
            'rowStatus' => true
        ]);

        if (!$entity) {
            return $this->notFoundAction();
        }

        return new ViewModel([
            'item' => $entity
        ]);
    }
}
