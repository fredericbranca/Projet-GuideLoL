<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AllowedVoies extends Constraint
{
    public $message = 'Le rôle "{{ string }}" n\' est pas reconnu.';
    public $allowedVoies = [];
}
