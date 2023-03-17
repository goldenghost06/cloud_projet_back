<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\JsonResponse;

class DiskSpaceController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getUsedSpaceDisk(Request $request, LoggerInterface $logger)
    {
        $user = $request->getContent();
        $data = json_decode($user, true);

        $postData = $request->request->all();
        $logger->log(LogLevel::INFO, 'Received a POST request with data:', $postData);
        
        $usedSpaceDisk = new Process(['du', '-sh', '/home/'.$data["username"]]);
        $usedSpaceDisk->run();


        return new JsonResponse($usedSpaceDisk->getOutput());
    }
}