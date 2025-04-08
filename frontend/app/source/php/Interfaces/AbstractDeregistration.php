<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractDeregistration
{
    public function getDeregistrationReasonCode(): string;
    public function getDeregistrationReasonDescription(): string;
    public function getDeregistrationDate(): string;
}
