<?php

declare(strict_types=1);

namespace App\Service\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Update
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        public int $id,

        #[Assert\Email]
        #[Assert\Length(['max' => 100])]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(['max' => 255])]
        public string $name,

        #[Assert\Choice(['Man', 'Woman'])]
        #[Assert\NotBlank]
        public string $sex,

        #[Assert\NotBlank]
        #[Assert\Regex(['pattern' => '/[+]\\d+/'])]
        public string $phone,
    ) {}
}
