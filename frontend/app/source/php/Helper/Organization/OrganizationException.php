<?php

declare(strict_types=1);

namespace KoKoP\Helper\Organization;

use \KoKoP\Enums\OrganizationErrorReason;

class OrganizationException extends \Exception
{
    function __construct(OrganizationErrorReason $reason)
    {
        [$message, $code] = match ($reason) {
            OrganizationErrorReason::InvalidLenght =>
            [
                OrganizationErrorReason::InvalidLenght->message(),
                OrganizationErrorReason::InvalidLenght->value,
            ],
            OrganizationErrorReason::InvalidFormat =>
            [
                OrganizationErrorReason::InvalidFormat->message(),
                OrganizationErrorReason::InvalidFormat->value
            ],
        };

        parent::__construct($message, $code);
    }

    public function getReason(): OrganizationErrorReason
    {
        return OrganizationErrorReason::from($this->code);
    }

    public function getDetails(): array
    {
        return [
            'httpErrorCode' => OrganizationErrorReason::from($this->code)->httpErrorCode(),
            'code' => $this->code,
            'field' => OrganizationErrorReason::from($this->code)->getField(),
            'allowedValues' => OrganizationErrorReason::from($this->code)->allowedValues(),
        ];
    }
}
