<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


class AuthentificationController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

     public function index(Request $request, LoggerInterface $logger, EntityManagerInterface $entityManager)
     {
        $userRequest = $request->getContent();
        $postData = $request->request->all();
        $logger->log(LogLevel::INFO, 'Received a POST request with data:', $postData);
        
        $data = json_decode($userRequest, true);

        $user = new User();
        $user->setUsername($data["username"]);
        $entityManager->persist($user);
        $entityManager->flush();

        dump($data["password"]);
        $process1 = new Process(['sh', './useradd.sh', $data["username"], $data["password"]]);
        $process1->run();

        // mysql -u root -p -e "SET @username='myuser'; SET @password='mypassword';" < script.sql

        $process = new Process(['mysql', '-u', 'root', '-p', '-e', '"SET', '@username='.$data["username"].';', 'SET', '@password='.$data["password"].';"',' < script.sql']);
        $process->run();

        return new JsonResponse($userRequest);
    }
}