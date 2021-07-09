<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class Validator
{
    public function validator($file, $request): array
    {
        $data = array();

        //Par défaut on set le dpi à 72
        $data['dpi'] = 72;
        $fileExtension = '';

        //S'il n'y a pas de fichier uploadé
        if (empty($file)) {
            throw new \Exception('File upload is required', Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $fileExtension = $file->guessExtension();

            //Si le fichier uploadé est différent du format PDF
            if ($fileExtension != 'pdf') {
                throw new \Exception('Pdf file are only allowed', Response::HTTP_BAD_REQUEST);
            } else {
                $data['extensionFile'] = $file->guessExtension();
            }
        }

        //S'il n'y a pas de label
        if (empty($request->get('label'))) {
            throw new \Exception('Label is required', Response::HTTP_BAD_REQUEST);
        }

        //Vérification si le dpi est renseigné par le vendor et si c'est le cas on le récupére
        if ($request->get('dpi')) {
            $data['dpi'] = $request->get('dpi');
        }
        return $data;
    }
}