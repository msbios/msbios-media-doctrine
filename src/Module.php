<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Media\Doctrine;

/**
 * Class Module
 * @package MSBios\Media\Doctrine
 */
class Module extends \MSBios\Module
{
    /** @const VERSION */
    const VERSION = '1.0.21';

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getDir()
    {
        return __DIR__;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }
}
