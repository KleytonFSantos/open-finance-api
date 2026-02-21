<?php

namespace App\Controller\Finance;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FinanceViewAction extends AbstractController
{
    #[Route('/finance/view', name: 'finance_view', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('finance/index.html.twig');
    }
}