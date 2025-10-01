<?php

namespace App\Serializer;

use App\Entity\Assessment;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AssessmentRequestDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return new Assessment($data);
    }

    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = []
    ): bool {
        return $type === Assessment::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Assessment::class => true,
        ];
    }
}

