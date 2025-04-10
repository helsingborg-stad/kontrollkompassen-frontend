<?php

namespace KoKoP\Controller;

use \KoKoP\Enums\AuthErrorReason;
use \KoKoP\Helper\AuthException;
use \KoKoP\Helper\Redirect as Redirect;
use \KoKoP\Helper\Validate as Validate;
use \KoKoP\Interfaces\AbstractServices as AbstractServices;

class Home extends BaseController
{
  public function __construct(AbstractServices $services)
  {
    parent::__construct(__CLASS__, $services);

    if ($services->getSessionService()->isValidSession()) {
      new Redirect('/uppslag');
    }
  }

  public function actionLogin(array $req)
  {
    // Always set vars that should be used
    $req = (object) array_merge([
      'username' => false,
      'password' => false
    ], $req);

    // Basic validation of credentials
    if (!Validate::username($req->username)) {
      new Redirect('/', ['action' => 'login-error-username']);
    }

    if (!Validate::password($req->password)) {
      new Redirect('/', ['action' => 'login-error-password']);
    }

    // Fetch user
    try {
      $user = $this->services->getAuthService()->authenticate(
        $req->username,
        $req->password
      );
      $this->services->getSessionService()->setSession($user);
    } catch (AuthException $e) {
      match (AuthErrorReason::from($e->getCode())) {
        AuthErrorReason::Unauthorized => new Redirect('/', ['action' => 'login-error-no-access']),

        default => new Redirect('/', [
          'action' => 'login-error',
          'username' => $req->username
        ])
      };
    };
    new Redirect('/uppslag');
  }
}
