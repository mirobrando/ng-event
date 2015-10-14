<?php

namespace mirolabs\phalcon\modules\ngEvent\services;

use mirolabs\phalcon\Framework\Module;
use mirolabs\phalcon\Framework\Container\Load;
use mirolabs\phalcon\modules\ngEvent\plugin\Annotation;

/**
 * @Service('ngEvent.security')
 */
class Security {

    
    /**
     * @Inject(service="config")
     * @var Phalcon\Config\Adapter\Yaml;
     */
    private $config;
    
    private $securityTable;
    
    public function getSecurityTable() {
        if (is_null($this->securityTable)) {
            $cacheDir = $this->config->projectPath .'/' . Module::COMMON_CACHE;
            $load = new Load($cacheDir);
            $load->execute(Annotation::CACHE_FILE, function() {
                 $this->securityTable =  _availableEvents();
            });
        }
        return $this->securityTable;
    }

    

    public function checkAccess($service, $method) {
        return !is_null($this->getEventDefifition($service, $method));
    }
    
    public function getParamType($service, $method) {
        $def = $this->getEventDefifition($service, $method);
        if (!is_null($def)) {
            return $def['param'];
        }
        return '';
    }
    
    public function getEventDefifition($service, $method) {
        if (array_key_exists($service, $this->getSecurityTable())) {
            foreach ($this->securityTable[$service] as $data) {
                if ($data['method'] == $method) {
                    return $data;
                }
            }
        }
        
        return null;
    }

    
    
}
