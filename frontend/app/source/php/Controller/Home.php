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

    //You shall pass
    if ($services->getSessionService()->isValidSession()) {
      new Redirect('/uppslag');
    }
  }

  /**
   * Action method for user login.
   *
   * This method handles user login based on the provided username and password. It sets
   * default values for the required parameters and performs basic validation for the 
   * username and password. If the validation fails, it redirects to the home page with 
   * appropriate error actions. It then fetches the user using the provided credentials 
   * and validates the login response. If the login is unsuccessful, it redirects to the 
   * home page with a 'login-error' action and the username. It checks whether the user 
   * is authorized to access the application and, if not, redirects with a 'login-error-no-access'
   * action. If the user is authorized, it sets a cookie, logs in the user, and redirects 
   * to the search page. If setting the cookie fails, it redirects to the home page with 
   * a 'login-error' action.
   *
   * @param array $req An associative array of request parameters including username and password.
   *
   * @throws RedirectException If validation fails, login is unsuccessful, user is not authorized, 
   *                           or setting the cookie fails, a RedirectException is thrown to redirect
   *                           the user to the appropriate page with relevant error actions.
   */
  public function actionLogin(array $req)
  {
    //Alwasy set vars that should be used
    $req = (object) array_merge([
      'username' => false,
      'password' => false
    ], $req);

    //Basic validation of credentials
    if (!Validate::username($req->username)) {
      new Redirect('/', ['action' => 'login-error-username']);
    }

    if (!Validate::password($req->password)) {
      new Redirect('/', ['action' => 'login-error-password']);
    }

    //Fetch user
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
