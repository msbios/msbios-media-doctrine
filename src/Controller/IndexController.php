<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\Controller;

use MSBios\Application\Controller\IndexController as DefaultIndexController;

/**
 * Class IndexController
 * @package MSBios\Media\Doctrine\Controller
 */
class IndexController extends DefaultIndexController
{
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
