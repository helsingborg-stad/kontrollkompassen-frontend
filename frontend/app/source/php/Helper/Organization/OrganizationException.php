<?php

declare(strict_types=1);

namespace KoKoP\Helper\Organization;

use Exception;
use \KoKoP\Enums\OrganizationErrorReason;

class OrganizationException extends Exception
{
    /**
     * @throws Exception
     */
    function __construct(OrganizationErrorReason $reason, ?Exception $previous = null)
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
            OrganizationErrorReason::Empty =>
            [
                OrganizationErrorReason::Empty->message(),
                OrganizationErrorReason::Empty->value
            ],
            OrganizationErrorReason::ServiceError =>
            [
                OrganizationErrorReason::ServiceError->message(),
                OrganizationErrorReason::ServiceError->value
            ],
        };

        parent::__construct($message, $code, $previous);
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
