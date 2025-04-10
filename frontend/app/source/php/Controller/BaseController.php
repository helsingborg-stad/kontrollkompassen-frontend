<?php

namespace KoKoP\Controller;

use \KoKoP\Helper\Redirect as Redirect;
use \KoKoP\Interfaces\AbstractServices as AbstractServices;

abstract class BaseController
{
  public $data = [];
  protected $action = null;
  protected AbstractServices $services;

  public function __construct(string $child, AbstractServices $services)
  {
    $this->services = $services;

    //Listen for actions 
    $this->action = $this->initActionListener();

    //Trigger action 
    if (method_exists($child, "action" . ucfirst($this->action))) {
      $this->{"action" . ucfirst($this->action)}($_REQUEST);
    }

    $session = $this->services->getSessionService();

    //Trigger global action
    if (method_exists(__CLASS__, "action" . ucfirst($this->action))) {
      $this->{"action" . ucfirst($this->action)}($_REQUEST);
    }

    $user = $session->getUser();

    //Manifest data
    $this->data['assets'] = $this->getAssets();

    //Is authenticated user
    $this->data['isAuthenticated'] = $session->isValidSession();

    if ($user) {
      //Formatted user
      $this->data['formattedUser']   = $user->format();

      //Get current user
      $this->data['user'] = $user;
    }
    //Debugging
    if ($this->services->getConfigService()->getValue('DEBUG') == true) {
      $this->data['debugResponse'] = true;
    } else {
      $this->data['debugResponse'] = false;
    }
  }

  public function actionLogout()
  {
    $this->services->getSessionService()->endSession();
    new Redirect('/', ['action' => 'logoutmsg']);
  }

  public function getData(): array
  {
    return (array) $this->data;
  }

  public function initActionListener()
  {
    if (isset($_GET['action'])) {
      return $this->action = str_replace(' ', '', ucwords(str_replace('-', " ", $_GET['action'])));
    }
    return $this->action = false;
  }

  public function getAssets()
  {
    $revManifest = rtrim(BASEPATH, "/") . "/assets/dist/manifest.json";

    if (file_exists($revManifest)) {
      $revManifestContents = file_get_contents($revManifest);
      if ($revManifestContentsDecoded = json_decode($revManifestContents)) {
        $assets = [];
        foreach ($revManifestContentsDecoded as $id => $file) {
          $fileType = pathinfo($file, PATHINFO_EXTENSION);
          $assets[$id] = [
            'file' => $file,
            'type' => $fileType,
            'id' => md5($file)
          ];
        }
        return $assets;
      }
    }
    return false;
  }
}
