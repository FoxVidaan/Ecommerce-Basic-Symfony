<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController
{
    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route("/hello{name?World}", name="Hello")
     */
    public function hello($name, LoggerInterface $logger, Calculator $calculator, Slugify $slugify, Environment $twig) {
        dump($twig);
        $logger->info("Mon message de log !");
        dump($slugify->slugify("Hello World"));
        $tva = $calculator->calcul(100);
        dd($tva);
        return new Response("Hello $name");
    }
}