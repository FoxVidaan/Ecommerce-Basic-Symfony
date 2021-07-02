<?php

namespace App\Controller;

use App\Taxes\Calculator;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route(path="/", name="index")
     */
    public function index() {
        dd("accueil");
    }

    /**
     * @Route(path="/test/{age<\d+>?0}", name="test", methods={"GET","POST"}, host="127.0.0.1", schemes={"http","https"})
     */
    public function test(Request $request, $age) {
        return new Response("Vous avez $age ans");
    }
}
