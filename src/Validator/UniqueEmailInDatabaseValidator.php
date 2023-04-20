<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailInDatabaseValidator extends ConstraintValidator
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function validate(
        $value,
        Constraint $constraint,
    ) {
        /* @var string $value */
        /* @var App\Validator\UniqueEmailInDatabase $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $user = $this->userRepository->findOneBy(['email' => $value]);

        if (null === $user) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter(
                '{{ email }}',
                $value,
            )
            ->addViolation()
        ;
    }
}
