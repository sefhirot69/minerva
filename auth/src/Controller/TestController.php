<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class TestController extends AbstractController
{

    #[Route('/api/test')]
    public function __invoke()
    {
        return $this->json('Hola');
    }
}