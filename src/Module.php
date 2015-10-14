<?php

namespace mirolabs\phalcon\modules\ngEvent;

class Module extends \mirolabs\phalcon\Framework\Module
{
	public function __construct()
	{
		$this->moduleNamespace =  __NAMESPACE__;
		$this->modulePath = __DIR__;
	}
        public function getAnnotationPlugins() {
            return [new plugin\Annotation()];
        }
        
        public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = NULL) {
            parent::registerAutoloaders($dependencyInjector);
            
            \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
                'JMS\Serializer\Annotation', 
                $dependencyInjector->get('config')->projectPath . "/vendor/jms/serializer/src");
        }


}
