<?php

namespace mirolabs\phalcon\modules\ngEvent\plugin;

use Phalcon\Annotations\Adapter as Annotations;
use mirolabs\phalcon\Framework\Compile\AnnotationParser as AnnotationParser;
use mirolabs\phalcon\Framework\Compile\Plugin\Service;
use mirolabs\collection\ArrayList;
use mirolabs\phalcon\modules\ngEvent\plugin\model\NgEvent;

class Annotation implements \mirolabs\phalcon\Framework\Compile\Plugin {
    
    const METHOD_ANNOTATION = 'ngEvent';
    const METHOD_ANNOTATION_PARAM = 'param';
    const CACHE_FILE = '/ngEvents.php';
    
    
    /**
     * @var Config 
     */
    private $config;
    
    /**
     * @var ArrayList
     */
    private $services;

    use \mirolabs\phalcon\Framework\Compile\Plugin\Config;
    
    public function __construct() {
        $this->services = new ArrayList('\mirolabs\phalcon\modules\ngEvent\plugin\model\NgEvent');
    }

    public function getConfig() {
        return $this->config;
    }

    public function setConfig(\Phalcon\Config $config) {
        $this->config = $config;
    }    
    
    public function parseFile(Annotations $adapter, $className, $module) {
        $parser = new AnnotationParser($adapter->get($className));
        if ($parser->isExistsAnnotationClass(Service::CLASS_ANNOTATION)) {
            $this->services->add(new NgEvent($parser, $className, function() {}, function() {}));
        }
    }
    
    public function createCache($cacheDir) {
        $file = "<?php\n\n";
        $file .= "\tfunction _availableEvents() {\n";
        $file .= "\t\treturn [\n";
        $file .= implode(",\n", $this->services
                ->map(function(NgEvent $model){return $model->getEvents();})
                ->filter(function($data) {return strlen($data) > 0;})
                ->toArray());
        $file .= "\n\t\t];\n";
        $file .= "\n\t}\n";
        file_put_contents($cacheDir . self::CACHE_FILE, $file);
    }
}
