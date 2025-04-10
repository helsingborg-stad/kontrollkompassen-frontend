<?php

namespace KoKoP\Controller;

use \KoKoP\Helper\Redirect as Redirect;
use \KoKoP\Helper\Sanitize as Sanitize;
use \KoKoP\Helper\Validate as Validate;
use \KoKoP\Interfaces\AbstractServices;

class Uppslag extends BaseController
{
  public function __construct(AbstractServices $services)
  {
    parent::__construct(__CLASS__, $services);

    if (!$this->data['isAuthenticated']) {
      new Redirect('/', ['action' => 'not-authenticated']);
    }
  }

  public function actionUppslag(array $req)
  {
    $req = (object) array_merge(['orgno' => false], $req);

    $cleanOrgNo = Sanitize::number($req->orgno);

    if (!Validate::orgno($cleanOrgNo)) {
      new Redirect(
        '/uppslag/',
        [
          'action' => 'check-orgno-malformed',
          'orgno' => $cleanOrgNo,
        ]
      );
    }

    $this->data = $this->services->getChechOrgNoService()->getDetails($cleanOrgNo);

    if (!$this->data['checkOrgNoResult']) {
      new Redirect(
        '/uppslag/',
        [
          'action' => 'check-orgno-not-found',
          'orgno' => $cleanOrgNo,
          'code' => 200,
        ]
      );
    }
  }
}
