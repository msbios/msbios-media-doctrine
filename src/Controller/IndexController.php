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
//    use ObjectManagerAwareTrait;
//
//    /**
//     * IndexController constructor.
//     * @param ObjectManager $objectManager
//     */
//    public function __construct(ObjectManager $objectManager)
//    {
//        $this->setObjectManager($objectManager);
//    }
//
//    /**
//     * @return ObjectRepository
//     */
//    protected function getRepository()
//    {
//        return $this->getObjectManager()
//            ->getRepository(News::class);
//    }

    /**
     * @return mixed
     */
    public function indexAction()
    {
        return $this->forward()->dispatch(NewsController::class, [
            'action' => 'index'
        ]);
    }
}