<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use MSBios\Application\Controller\IndexController as DefaultIndexController;
use MSBios\Doctrine\ObjectManagerAwareTrait;
use MSBios\Media\Resource\Doctrine\Entity\News;
use MSBios\Resource\Doctrine\EntityInterface;
use Zend\Paginator\Paginator;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package MSBios\Media\Doctrine\Controller
 */
class IndexController extends DefaultIndexController
{
    use ObjectManagerAwareTrait;

    /**
     * IndexController constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
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
        /** @var Paginator $paginator */
        $paginator = $this->getRepository()->getPaginator(
            $this->params()->fromQuery('page'), 3
        );

        /** @var ModelInterface $viewModel */
        $viewModel = parent::indexAction();
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