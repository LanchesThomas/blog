<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\Request;
use App\Service\Validator;
use App\Service\ContactFormValidator;

final class Contact
{
    private Request $request;
    private Validator $validator;

    public function __construct(Request $request, Validator $validator)
    {
    }

    public function execute()
    {
    }
}
