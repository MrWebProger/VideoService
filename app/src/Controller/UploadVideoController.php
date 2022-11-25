<?php

namespace App\Controller;

use App\Exception\RestException;
use App\Normalizer\ViolationsNormalizer;
use App\Service\FileUpload;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UploadVideoController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private FileUpload $fileUpload,
        private ManagerRegistry $doctrine
    ) {
    }

    #[Route('/api/video', 'video_upload_route', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $video = $request->files->all();

        $this->validateInputData($video);

        $file = $this->fileUpload->upload($video['video']);

        $entityManager = $this->doctrine->getManager();

        $entityManager->persist($file);
        $entityManager->flush();

        return $this->json('Видео успешно загружено');
    }

    private function validateInputData(array $inputData): void
    {
        $constraints = new Collection($this->getConstraints());
        $violations = $this->validator->validate($inputData, $constraints);

        if ($violations->count() < 1) {
            return;
        }

        $violationMessages = (new ViolationsNormalizer($violations))->normalize();
        throw new RestException($violationMessages);
    }

    private function getConstraints(): array
    {
        return [
            'video' => [
                new NotBlank(message: 'Не заполнено поле'),
                new File(
                    maxSize: '1024M',
                    mimeTypes: 'video/*',
                    maxSizeMessage: 'Размер файла не должен превышать {{ limit }}мб',
                    mimeTypesMessage: 'Неверный формат видео'
                )
            ],
        ];
    }
}
