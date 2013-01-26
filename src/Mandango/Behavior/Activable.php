<?php

/*
 * This file is part of Mandango.
 *
 * (c) Ignacio Colomina <ignacio.colomina@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Behavior;

use Mandango\Mondator\ClassExtension;
use Mandango\Mondator\Definition\Method;

class Activable extends ClassExtension {
    
    /**
     * preInsert method name
     * @var string 
     */
    protected $preInsertMethodName;
    
    /**
     * setter method name
     * @var string 
     */
    protected $fieldSetter;
    
    /**
     * {@inheritdoc}
     * Also loads preInsertMethodName and fieldSetter name
     */
    protected function setUp()
    {
        $this->preInsertMethodName = 'updateActivable';
        
        $this->fieldSetter = 'setIsActive';
        
        $this->addOptions(array(
            'active_field' => 'isActive',
            'default_active' => false
        ));
    }
    
     /**
     * {@inheritdoc}
     */
     protected function doConfigClassProcess() {
        
        $this->configClass['fields'][$this->getOption('active_field')] = 'boolean';
        $this->configClass['events']['preInsert'][] = $this->preInsertMethodName;
     }
     
     /**
     * {@inheritdoc}
     */
    protected function doClassProcess(){
        
        $isActive = $this->getOption('is_active');
        
        $method = new Method('protected', $this->preInsertMethodName, '', <<<EOF
        
            \$this->$this->fieldSetter($isActive);
EOF
            );
        
        $this->definitions['document_base']->addMethod($method);
    }
}

?>
