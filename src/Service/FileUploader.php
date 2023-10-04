<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file, string $uploadDirectory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory().'/'.$uploadDirectory, $fileName);
        } catch (FileException $e) {
            echo "Une erreur s'est produite lors du téléchargement du fichier.";
           // Redirection vers une page d'erreur ou une autre action appropriée
            // header("Location: erreur.php");
            // exit;
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}