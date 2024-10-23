<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactValidation
{
    public function __construct(private ValidatorInterface $validator){}

    public function getErrors($data) {

        $name = $data['name'];
        $email = $data['email'];
        $subject = $data['subject'];
        $msg = $data['message'];

        $nameConstraint = [
            new Assert\NotBlank(['message' => 'Name cannot be empty.'])
        ];

        $nameErrors = $this->validator->validate(
            $name,
            $nameConstraint,
        );
        
        $emailConstraint = [
            new Assert\NotBlank(['message' => 'Email cannot be empty.']),
            new Assert\Email(['message' => 'Invalid email address.'])
        ];

        $emailErrors = $this->validator->validate(
            $email,
            $emailConstraint,
        );

        $subjectConstraint = [
            new Assert\NotBlank(['message' => 'Subject cannot be empty.'])
        ];

        $subjectErrors = $this->validator->validate(
            $subject,
            $subjectConstraint,
        );

        $msgConstraint = [
            new Assert\NotBlank(['message' => 'Message cannot be empty.'])
        ];

        $msgErrors = $this->validator->validate(
            $msg,
            $msgConstraint,
        );

        $contactFormErrors = [
            'name_error' => $nameErrors->count() === 1 ? $nameErrors[0]->getMessage() : '',
            'subject_error' => $subjectErrors->count() === 1 ? $subjectErrors[0]->getMessage() : '',
            'email_error' => $emailErrors->count() === 1 ? $emailErrors[0]->getMessage() : '',
            'msg_error' => $msgErrors->count() === 1 ? $msgErrors[0]->getMessage() : ''
        ];

        return $contactFormErrors;
    }
}