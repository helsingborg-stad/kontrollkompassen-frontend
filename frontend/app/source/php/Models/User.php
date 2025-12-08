<?php

declare(strict_types=1);

namespace KoKoP\Models;

use JsonSerializable;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractUser;
use stdClass;

class User implements AbstractUser, JsonSerializable
{
    private string $account;
    private string $memberof;
    private string $company;
    private string $displayname;
    private string $sn;
    private string $mail;

    public function __construct(
        private ?AbstractConfig $config,
        object $user = new stdClass
    ) {
        if (is_object($user)) {
            $this->account = $user->samaccountname ?? '';
            $this->memberof = $user->memberof ?? '';
            $this->company = $user->company ?? '';
            $this->displayname = $user->displayname ?? '';
            $this->sn = $user->sn ?? '';
            $this->mail = $user->mail ?? '';
        }
    }

    public function getAccountName(): string
    {
        return $this->account;
    }

    public function getCompanyName(): string
    {
        return $this->company;
    }

    public function getDisplayName(): string
    {
        return $this->displayname;
    }

    public function getLastName(): string
    {
        return $this->sn;
    }

    public function getMailAddress(): string
    {
        return $this->mail;
    }

    public function getGroups(): array
    {
        if (empty($this->memberof)) {
            return [];
        }

        $groups = [];

        $parts = explode(',', $this->memberof);

        foreach ($parts as $part) {
            $group = explode('=', $part);
            $key = trim($group[0] ?? '');
            $value = trim($group[1] ?? '');

            if (in_array($value, $groups)) {
                continue;
            }

            if (
                !is_null($this->config) &&
                !in_array($value, $this->config->getValue('ADVANCED_USER_AD_GROUPS', []))
            ) {
                continue;
            }

            $groups[] = $value;
        }

        return $groups;
    }

    public function getGroupsString(): string
    {
        $carry = '';

        foreach ($this->getGroups() as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $v) {
                    $carry .= $key . '=' . $v . ',';
                }
            } else {
                $carry .= $key . '=' . $value . ',';
            }
        }

        return rtrim($carry, ',');
    }

    public function format(): object
    {
        $u = [
            'firstname' => '',
            'lastname' => '',
            'administration' => $this->company
        ];

        if (isset($this->sn) && !empty($this->sn)) {
            $names = explode(' - ', $this->displayname, 2);
            $u['firstname'] = trim(str_replace($this->sn, '', $names[0]));
            $u['lastname'] = $this->sn;
        } elseif (isset($this->mail) && strpos($this->mail, '.')) {
            list($u['firstname'], $u['lastname']) = explode('.', strtok($this->mail, '@'), 2);
        } else {
            $tempData = trim(explode(' - ', $this->displayname, 2)[0]);

            if (!empty($tempData)) {
                $tempData = explode(' ', $tempData);
                $u['lastname'] = $tempData[0];
                unset($tempData[0]);
                $u['firstname'] = implode(' ', $tempData);
            }
        }

        $u['firstname'] = ucfirst($u['firstname']);
        $u['lastname'] = ucfirst($u['lastname']);

        return (object) $u;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'samaccountname' => $this->account,
            'memberof' => $this->getGroupsString(),
            'company' => $this->company,
            'displayname' => $this->displayname,
            'sn' => $this->sn,
            'mail' => $this->mail,
        ];
    }
}
