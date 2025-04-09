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

    //Prevent uninlogged users
    if (!$services->getSessionService()->isValidSession()) {
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
          'action' => 'search-orgno-malformed',
          'orgno' => $cleanOrgNo
        ]
      );
    }

    $this->data = $this->services->getChechOrgNoService()->getDetails($cleanOrgNo);


    if (!$this->data['searchResult']) {
      new Redirect(
        '/uppslag/',
        [
          'action' => 'search-no-hit',
          'orgno' => $cleanOrgNo,
          'code' => 200,
        ]
      );
    }
  }
}
