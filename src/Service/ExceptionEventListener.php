<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class ExceptionEventListener
{

    protected $router;
    protected $twig;
    protected $request;

    public function __construct(RouterInterface $router, Environment $twig, RequestStack $request)
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->request = $request;
    }

    public function test(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $session = new Session();



        if ($response->getStatusCode() === 404){

            $route = 'home';
            $url = $this->router->generate($route);

            $session->getFlashBag()->add('danger','ERREUR 404');
            $response = new RedirectResponse($url);

        }


        if ($response->getStatusCode() === 500){
            $route = 'ads_index';
            $url = $this->router->generate($route);

            $session->getFlashBag()->add('danger','ERREUR 500');

            $response = new RedirectResponse($url);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);

    }
}