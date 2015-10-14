<?php

namespace mirolabs\phalcon\modules\ngEvent\plugin\model;
use Phalcon\Annotations\Annotation;
use mirolabs\phalcon\Framework\Compile\AnnotationParser as AnnotationParser;
use mirolabs\phalcon\modules\ngEvent\plugin\Annotation as PluginAnnotation;
use mirolabs\phalcon\Framework\Logger;
use mirolabs\collection\ArrayList;

class NgEvent extends \mirolabs\phalcon\Framework\Compile\Plugin\Model\Service {
    
    
    public function getEvents() {
        $result = [];
        foreach ($this->annotationParser->getMethods(PluginAnnotation::METHOD_ANNOTATION) as $method => $list) {
            $param = $list
                ->find(function (Annotation $annotation) { return $annotation->getName() == PluginAnnotation::METHOD_ANNOTATION;})
                ->getArgument(PluginAnnotation::METHOD_ANNOTATION_PARAM);
            
            $result[] = sprintf("\t\t\t\t['method'=>'%s','param' => '%s']", $method, $param);
        }
        if (count($result)) {
            $cache = sprintf("\t\t\t'%s' => [\n", $this->getServiceName());
            $cache .= implode(",\n", $result);
            $cache .= "\n\t\t\t]";
            return $cache;
        }
        return "";
    }
    
}
