<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\Controller;

use MSBios\Application\Controller\IndexController as DefaultIndexController;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package MSBios\Media\Doctrine\Controller
 */
class IndexController extends DefaultIndexController
{
    /**
     * @return ModelInterface
     */
    public function indexAction()
    {
        /** @var ModelInterface $viewModel */
        $viewModel = parent::indexAction();
        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function viewAction()
    {
        return new ViewModel;
    }

}
