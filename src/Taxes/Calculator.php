<?php

namespace App\Taxes;


use Psr\Log\LoggerInterface;

class Calculator {
    protected $logger;
    public function __construct(LoggerInterface $logger, float $tva)
    {
        $this->logger = $logger;
    }

    public function calcul(float $prix): float {
        $this->logger->info("Un calcul à lieu : $prix");
        return $prix * 0.2;
    }
}