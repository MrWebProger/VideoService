<?php

namespace App\Service;

use App\Entity\File;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUpload
{
    public function __construct(
        private string $uploadDirectory,
        private ManagerRegistry $doctrine
    ) {
        $this->createUploadDirIfNotExists();
    }

    private function createUploadDirIfNotExists(): void
    {
        if (file_exists($this->uploadDirectory)) {
            return;
        }

        if (!mkdir($this->uploadDirectory)) {
            throw new \RuntimeException('Не удалось создать директорию для загрузки файлов');
        }
    }

    public function upload(UploadedFile $uploadedFile): File
    {
        $file = $this->getPreparedFile($uploadedFile);
        $uploadPath = $this->getUploadPath();
        $uploadedFile->move($uploadPath, $file->getName());

        $entityManager = $this->doctrine->getManager();

        $entityManager->persist($file);
        $entityManager->flush();

        return $file;
    }

    private function getPreparedFile(UploadedFile $uploadedFile): File
    {
        $randomName = $this->getRandomNameForNewFile($uploadedFile);
        $file = new File();

        $file->setName($randomName);
        $file->setOriginalName($uploadedFile->getClientOriginalName());
        $file->setExtension($uploadedFile->getClientOriginalExtension());
        $file->setMime($uploadedFile->getMimeType());
        $file->setSize($uploadedFile->getSize());
        $file->setUploadedAt(new \DateTimeImmutable());

        return $file;
    }

    private function getRandomNameForNewFile(UploadedFile $uploadedFile): string
    {
        return uniqid() . RandomString::get(10) . '.' . $uploadedFile->getClientOriginalExtension();
    }

    private function getUploadPath(): string
    {
        $currentDate = (new \DateTimeImmutable())->format('Y-m-d');
        $uploadPath = "{$this->uploadDirectory}/{$currentDate}";

        $this->createUploadSubDirIfNotExists($uploadPath);

        return $uploadPath;
    }

    private function createUploadSubDirIfNotExists(string $uploadPath): void
    {
        if (file_exists($uploadPath)) {
            return;
        }

        if (!mkdir($uploadPath)) {
            throw new \RuntimeException('Не удалось создать поддиректорию для загрузки файлов');
        }
    }
}
