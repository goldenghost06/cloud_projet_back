<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\FileBag;

class FilesController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function index(Request $request): Response
    {
        $file = $request->files->get('file');
        $filesystem = new Filesystem();

        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
        dd($file->move($destination));
        // dump($file->originalName);
        // $destination = '/test/' . $file->originalName;

        // $filesystem->copy($file->pathname, $destination);

        // dump($request->files);

        return new JsonResponse('ok');
    }
}
