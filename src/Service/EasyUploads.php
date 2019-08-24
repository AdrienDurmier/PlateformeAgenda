<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EasyUploads
{

    private $params;

    /**
     * Constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * Facilitateur pour l'uploads de fichier
     *
     * @param string $file : fichier envoyé par l'utilisateur
     * @param string $path_name : nom du parameter dans /config/services.yaml
     * @return $newFilename : nom final du fichier uploadé
     * @throws ServerException
     */
    public function file($file, $path_name)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move(
                $this->params->get($path_name),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $newFilename;
    }


}