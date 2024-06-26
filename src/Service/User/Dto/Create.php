<?php

declare(strict_types=1);

namespace App\Service\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Create
{
    public function __construct(
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

        #[Assert\NotBlank]
        #[Assert\DateTime]
        public string $birthday
    ) {
        $this->birthday = (new \DateTimeImmutable($this->birthday))->format('Y-m-d H:i:s');
    }
}
