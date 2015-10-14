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

}
