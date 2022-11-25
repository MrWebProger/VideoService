<?php

namespace App\Normalizer;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsNormalizer
{
    public function __construct(
        private ConstraintViolationListInterface $violations
    ) {
    }

    public function normalize(): array
    {
        $violationMessages = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($this->violations as $violation) {
            $fieldName = str_replace(['[', ']'], '', $violation->getPropertyPath());
            $violationMessages[$fieldName] = $violation->getMessage();
        }

        return $violationMessages;
    }
}
