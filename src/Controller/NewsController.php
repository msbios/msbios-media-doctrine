<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use MSBios\Application\Controller\IndexController as DefaultIndexController;
use MSBios\Doctrine\DBAL\Types\PublishingStateType;
use MSBios\Form\FormElementManagerAwareTrait;
use MSBios\Media\Doctrine\Form\NewsForm;
use MSBios\Media\Doctrine\V1\Rpc\News\NewsController as RpcNewsController;
use MSBios\Media\Resource\Doctrine\Entity\News;
use MSBios\Resource\Doctrine\EntityInterface;
use Zend\Paginator\Paginator;
use Zend\ServiceManager\PluginManagerInterface;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

/**
 * Class NewsController
 * @package MSBios\Media\Doctrine\Controller
 */
class NewsController extends DefaultIndexController implements ObjectManagerAwareInterface
{
    use ProvidesObjectManager;
    use FormElementManagerAwareTrait;

    /**
     * NewsController constructor.
     *
     * @param ObjectManager $objectManager
     * @param PluginManagerInterface $formElementManager
     */
    public function __construct(ObjectManager $objectManager, PluginManagerInterface $formElementManager)
    {
        $this->setObjectManager($objectManager);
        $this->setFormElementManager($formElementManager);
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
     * @return ModelInterface|ViewModel
     */
    public function indexAction()
    {
        /** @var NewsForm $form */
        $search = $this->getFormElementManager()
            ->get(NewsForm::class);
        $search->setData($this->params()->fromQuery());

        /** @var \Closure $fetchBy */
        $fetchBy = RpcNewsController::factorySearch($search);

        /** @var Paginator $paginator */
        $paginator = $this->getRepository()
            ->fetchAll($fetchBy);

        $paginator
            ->setCurrentPageNumber($this->params()->fromQuery('page', 1))
            ->setItemCountPerPage(RpcNewsController::ITEM_COUNT_PER_PAGE);

        /** @var ModelInterface $viewModel */
        $viewModel = parent::indexAction();
        $viewModel->setVariables([
            'search' => $search,
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

        if (! $entity) {
            return $this->notFoundAction();
        }

        return new ViewModel([
            'item' => $entity
        ]);
    }
}
