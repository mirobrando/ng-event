<?php

namespace mirolabs\phalcon\modules\ngEvent\services;

use JMS\Serializer\SerializerBuilder;

/**
 * @Service(name='jms')
 */
class Jms {

    public function buildSerializer() {
        return SerializerBuilder::create()->build();
    }

    public function serializeJson($data) {
        return $this->buildSerializer()->serialize($data, 'json');
    }

    public function deserializeJson($jsonData, $type) {
        return $this->buildSerializer()->deserialize($jsonData, $type, 'json');
    }
}
