<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine\Form;

use Zend\Form\Element\Date;
use Zend\Form\Element\Search;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class NewsForm
 * @package MSBios\Media\Doctrine\Form
 */
class NewsForm extends Form
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('method', Request::METHOD_GET);
        $this->add([
            'type' => Search::class,
            'name' => 'title'
        ])->add([
            'type' => Fieldset::class,
            'name' => 'postdate',
            'elements' => [
                [
                    'spec' => [
                        'type' => Date::class,
                        'name' => 'from',
                        'options' => [
                            'format' => 'Y-m-d'
                        ],
                    ]
                ], [
                    'spec' => [
                        'type' => Date::class,
                        'name' => 'to',
                        'options' => [
                            'format' => 'Y-m-d'
                        ],
                    ]
                ]
            ]
        ]);

        /** @var InputFilterInterface $factory */
        $inputFilter = (new Factory)->createInputFilter([
            [
                'name' => 'title',
                'required' => false,
            ], [
                'type' => InputFilter::class,
                'name' => 'postdate',
                [
                    'name' => 'from',
                    'required' => false,
                ], [
                    'name' => 'to',
                    'required' => false,
                ]
            ]
        ]);

        $this->setInputFilter($inputFilter);

    }
}
