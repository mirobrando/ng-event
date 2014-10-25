<?php

namespace mirolabs\phalcon\modules\ngEvent\controllers;

use ngEvent\exceptions\ValidateException;
use Phalcon\Mvc\Controller;

class EventController extends Controller
{

    public function beforeExecuteRoute($dispatcher)
    {
        $config = $this->getDI()->get('config');
        $result = true;
        if ($dispatcher->getActionName() == 'fire') {
            $events = $config->get('ngEvent.available.events');
            $body = $this->request->getJsonRawBody();
            $result = in_array($body->eventName, $events);
        } else {
            $service = $dispatcher->getParam('service');
            $method = $dispatcher->getParam('method');
            $services = $config->get('ngEvent.available.services');
            $result = property_exists($services, $service) && in_array($method, $services->$service);
        }


        if (!$result) {
            $this->response->setStatusCode(400, 'Bad Request');
            $this->response->send();
            return false;
        }
    }


    public function fireAction()
    {
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
    }


    public function getAction($language, $service, $method)
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

            $this->response->setJsonContent(['message' => $data]);
            $this->response->send();
        } catch (\Exception $e) {
            $this->response->setStatusCode(400, 'Bad Request');
            $this->response->send();
        }
    }

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

            $this->response->setJsonContent($data);
            $this->response->send();

        } catch (\Exception $e) {
            $this->response->setStatusCode(400, 'Bad Request');
            $this->response->send();
        }
    }


    public function postAction($language, $service, $method)
    {
        try {
            $body = $this->request->getJsonRawBody();
            $result = $this->getDI()->get($service)->$method($body->param);

            if (!is_object($result)) {
                throw new \Exception('Invalid response');
            }

            $this->response
                ->setStatusCode(201, 'Created')
                ->setJsonContent($result)
                ->send();

        } catch(ValidateException $e) {
            $this->response
                ->setStatusCode(409, 'Conflict')
                ->setJsonContent($e->getErrorValidete())
                ->send();

        } catch (\Exception $e) {
            $this->response
                ->setStatusCode(400, 'Bad Request')
                ->send();
        }
    }


    public function putAction($language, $service, $method)
    {
        try {
            $body = $this->request->getJsonRawBody();
            $result = $this->getDI()->get($service)->$method($body);

            if (!is_object($result)) {
                throw new \Exception('Invalid response');
            }

            $this->response
                ->setStatusCode(201, 'Created')
                ->setJsonContent($result)
                ->send();

        } catch(ValidateException $e) {
            $this->response
                ->setStatusCode(409, 'Conflict')
                ->setJsonContent($e->getErrorValidete())
                ->send();

        } catch (\Exception $e) {
            $this->response
                ->setStatusCode(400, 'Bad Request')
                ->send();
        }
    }

    public function deleteAction($language, $service, $method)
    {
        try {
            $body = $this->request->getJsonRawBody();
            $this->getDI()->get($service)->$method($body->param);

            $this->response
                ->setStatusCode(204, 'No Content')
                ->send();

        } catch (\Exception $e) {
            $this->response
                ->setStatusCode(400, 'Bad Request')
                ->send();
        }
    }
}