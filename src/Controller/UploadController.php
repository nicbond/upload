<?php

namespace App\Controller;

use App\Service\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    /**
     * @Route("/upload", name="upload")
     * @param string $imageLocal
     * @param string $imageDestination
     * @return Response
     */
    public function index(string $imageLocal, string $imageDestination)
    {
        $this->uploaderHelper->uploads($imageLocal, $imageDestination);
        return new Response('Upload effectué');
    }
}

