<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use MSBios\Application\Controller\IndexController as DefaultIndexController;
use MSBios\Doctrine\DBAL\Types\PublishingStateType;
use MSBios\Doctrine\ObjectManagerAwareTrait;
use MSBios\Form\FormElementManagerAwareTrait;
use MSBios\Media\Doctrine\Form\NewsForm;
use MSBios\Media\Resource\Doctrine\Entity\News;
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
        $paginator = $this->getRepository()->getPaginatorFromQuery(
            $this->params()->fromQuery(),
            $this->params()->fromQuery('page', 1),
            self::DEFAULT_ITEM_COUNT_PER_PAGE
        );

        /** @var ModelInterface $viewModel */
        $viewModel = parent::indexAction();
        $viewModel->setVariable('search', $form);
        $viewModel->setVariable('paginator', $paginator);
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
