<?php

namespace mirolabs\phalcon\modules\ngEvent\controllers;

use mirolabs\phalcon\Framework\Logger;
use mirolabs\phalcon\Framework\Module\Controller;
use mirolabs\phalcon\modules\ngEvent\api\Model;
use mirolabs\phalcon\modules\ngEvent\exceptions\ValidateException;

/**
 * @Controller
 */
class EventController extends Controller {

 
    /**
     * @Inject(service="ngEvent.security")
     * @var \mirolabs\phalcon\modules\ngEvent\services\Security;
     */
    protected $security;

    /**
     * @Inject(service="jms")
     * @var \mirolabs\phalcon\modules\ngEvent\services\Jms;
     */
    protected $jms;
    
    
    public function beforeExecuteRoute($dispatcher) {
        $service = $dispatcher->getParam('service');
        $method = $dispatcher->getParam('method');
        if (!$this->security->checkAccess($service, $method)) {
            Logger::getInstance()->warning('ngEvent: service %s and method %s not available', $service, $method);
            $this->response->setStatusCode(400, 'Bad Request');
            $this->response->send();
            return false;
        }
    }


    public function fireAction() {
        /**
        
        try {
            $body = $this->request->getJsonRawBody();
            $param = null;
            if (property_exists($body, 'param')) {
                $param = $body->param;
            }
            $this->eventsManager->fire($body->eventName, $param);
            $this->response->setStatusCode(204, 'No Content');
            $this->response->send();
        } catch (\Exception $e) {
            $this->response->setStatusCode(406, 'Not Acceptable');
            $this->response->send();
        }
        */
    }

    /**
     * 
     * @param type $language
     * @param type $service
     * @param type $method
     * @Route(path=/{language:[a-z]{2}}/event/get/{service}/{method}, method=GET)
     */
    public function getAction($language, $service, $method) {
          try {
            $params = [];
            foreach (array_keys($_GET) as $key) {
                if ($key == '_url') {
                    continue;
                }

                $params[] = $this->request->getQuery($key);
            }
            $serviceObj = $this->getDI()->get($service);
            $data = call_user_func_array([$serviceObj, $method], $params);
            $this->response->setContent($this->jms->serializeJson($data));
            $this->response->send();
        } catch (\Exception $e) {
            Logger::getInstance()->warning('ngEvent (GET) error: %s', $e->getMessage());
            $this->response->setStatusCode(400, 'Bad Request');
            $this->response->send();
        }
    }

    
    /**
     * 
     * @param type $language
     * @param type $service
     * @param type $method
     * @Route(path=/{language:[a-z]{2}}/event/query/{service}/{method}, method=GET)
     */
    public function queryAction($language, $service, $method)
    {
        try {
            $params = [];
            foreach (array_keys($_GET) as $key) {
                if ($key == '_url') {
                    continue;
                }

                $params[] = $this->request->getQuery($key);
            }
            $serviceObj = $this->getDI()->get($service);
            $data = call_user_func_array([$serviceObj, $method], $params);
            if (!is_array($data)) {
                throw new \Exception('Invalid response');
            }
            $this->response->setContent($this->jms->serializeJson($data));
            $this->response->send();

        } catch (\Exception $e) {
            Logger::getInstance()->warning('ngEvent (QUERY) error: %s', $e->getMessage());
            $this->response->setStatusCode(400, 'Bad Request');
            $this->response->send();
        }
    }


    /**
     * 
     * @param type $language
     * @param type $service
     * @param type $method
     * @Route(path=/{language:[a-z]{2}}/event/{service}/{method}, method=POST)
     */
    public function postAction($language, $service, $method) {
        try {
            $body = $this->request->getJsonRawBody();

            if (is_array($body->param)) {
                $result = call_user_func_array([$this->getDI()->get($service), $method], $body->param);
            } else {
                $result = $this->getDI()->get($service)->$method($body->param);
            }
            if (!is_object($result)) {
                throw new \Exception('Invalid response');
            }

            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            $jsonContent = $serializer->serialize($result, 'json');

            $this->response
                ->setStatusCode(201, 'Created')
                ->setContent($this->jms->serializeJson($result))
                ->send();

        } catch(ValidateException $e) {
            $this->response
                ->setStatusCode(409, 'Conflict')
                ->setJsonContent($e->getErrorValidate())
                ->send();

        } catch (\Exception $e) {
            Logger::getInstance()->warning('ngEvent (POST) error: %s', $e->getMessage());
            $this->response
                ->setStatusCode(400, 'Bad Request')
                ->send();
        }
    }


    /**
     * 
     * @param type $language
     * @param type $service
     * @param type $method
     * @Route(path=/{language:[a-z]{2}}/event/{service}/{method}, method=PUT)
     */
    public function putAction($language, $service, $method)
    {
        try {
            $body = $this->request->getJsonRawBody();
            if (is_array($body->param)) {
                $result = call_user_func_array([$this->getDI()->get($service), $method], $body->param);
            } else {
                $result = $this->getDI()->get($service)->$method($body->param);
            }

            if (!is_object($result)) {
                throw new \Exception('Invalid response');
            }

            $this->response
                ->setStatusCode(200, 'OK')
                ->setContent($this->jms->serializeJson($result))
                ->send();

        } catch(ValidateException $e) {
            $this->response
                ->setStatusCode(409, 'Conflict')
                ->setJsonContent($e->getErrorValidate())
                ->send();

        } catch (\Exception $e) {
            Logger::getInstance()->warning('ngEvent (PUT) error: %s', $e->getMessage());
            $this->response
                ->setStatusCode(400, 'Bad Request')
                ->send();
        }
    }

    /**
     * 
     * @param type $language
     * @param type $service
     * @param type $method
     * @Route(path=/{language:[a-z]{2}}/event/{service}/{method}, method=DELETE)
     */
    public function deleteAction($language, $service, $method)
    {
        try {
            $body = $this->request->getJsonRawBody();
            if (is_array($body->param)) {
                call_user_func_array([$this->getDI()->get($service), $method], $body->param);
            } else {
                $this->getDI()->get($service)->$method($body->param);
            }

            $this->response
                ->setStatusCode(204, 'No Content')
                ->send();

        } catch (\Exception $e) {
            Logger::getInstance()->warning('ngEvent (DELETE) error: %s', $e->getMessage());
            $this->response
                ->setStatusCode(400, 'Bad Request')
                ->send();
        }
    }

    private function getData($data)
    {
        if ($data instanceof Model) {
            return $data->toArray();
        }

        if (is_string($data)) {
            return ['message' => $data];
        }

        return $data;
    }
    
    private function getParam($service, $method, $body) {
        $paramType = $this->security->getParamType($service, $method);
        if ($paramType != '') {
            return $this->jms->deserializeJson($body, $paramType);
        }
        return $body;
    }
}