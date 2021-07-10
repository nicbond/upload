<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;
use App\Service\FileJson;
use App\Service\Validator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadFileController extends AbstractController
{
    /**
     * @param Request $request
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param FileJson $fileJson
     * @param Validator $validator
     * @param SerializerInterface $serializer
     * @return Response
     * @throws \Exception
     */
    #[Route('/doUpload', name: 'do-upload')]
    public function index(Request $request, string $uploadDir, FileUploader $uploader, FileJson $fileJson,Validator $validator, SerializerInterface $serializer): Response
    {
        $data = array();
        //On récupère toutes les keys de request.
        $config = $request->request->all();

        //On récupére le UUID passé dans le header, on set le convert à false (qui nous servira pour la conversion du PDF en image) et on le range dans $config
        $config['uuid'] = $request->headers->get('uuid');
        $config['convert'] = 'false';

        /** @var UploadedFile $uploadedFile */
        $file = $request->files->get('attachment');

        //Vérification des clés et retour des erreurs éventuelles
        $key = $validator->validator($file, $request);

        //Récupération du nom du fichier original
        $config['originalFilename'] = $file->getClientOriginalName();
        $config['dpi'] = $key['dpi'];

        //Création d'un uuid
        $templateUuid = Uuid::v4();

        //On détermine le nom du PDF avec en préfix l'UUID précédemment créé
        $extensionFile = $key['extensionFile'];
        $newFilename = $templateUuid . '.' . $extensionFile;

        //Appel du service FileUploader puis FileJson
        $uploader->upload($uploadDir, $file, $newFilename);
        $fileJson->create($uploadDir, $templateUuid, $config);

        $data[] = ['template_uuid' => $templateUuid, 'message' => 'PDF uploaded', 'additional_information' => 'Send to MQueue to conversion to image'];
        return new Response($serializer->serialize($data, 'json', []));
    }
}