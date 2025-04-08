<?php

namespace KoKoP\Controller;

use \KoKoP\Helper\Redirect as Redirect;
use \KoKoP\Interfaces\AbstractServices as AbstractServices;

abstract class BaseController
{
  public $data = [];
  protected $action = null;
  protected AbstractServices $services;

  /**
   * Constructor for the class.
   *
   * Initializes the object by setting up action listeners, triggering specific actions
   * based on the provided child class, handling global actions, retrieving manifest data,
   * and checking if the user is authenticated.
   *
   * @param mixed $child The child class instance.
   *
   * @return void
   */
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

  /**
   * Perform user logout action.
   *
   * This function initiates the user logout process by calling the static `logout` method
   * of the User class. It then creates a new Redirect instance to redirect the user to
   * the home page with an additional query parameter ('action' => 'logoutmsg') to indicate
   * a successful logout.
   *
   * @return void
   */
  public function actionLogout()
  {
    $this->services->getSessionService()->endSession();
    new Redirect('/', ['action' => 'logoutmsg']);
  }

  /**
   * Get the data as an array.
   *
   * This function returns the data property as an associative array.
   *
   * @return array The data as an associative array.
   */
  public function getData(): array
  {
    return (array) $this->data;
  }

  /**
   * Initialize action listener based on the 'action' parameter in the query string.
   *
   * This function checks if the 'action' parameter is set in the $_GET array. If set,
   * it cleans up the action parameter by removing spaces and converting it to a camel-case
   * format. The cleaned action is then assigned to the class property $this->action.
   * If the 'action' parameter is not set, $this->action is set to false.
   *
   * @return string|false Returns the cleaned and formatted action if 'action' is set,
   *                     otherwise returns false.
   */
  public function initActionListener()
  {
    if (isset($_GET['action'])) {
      return $this->action = str_replace(' ', '', ucwords(str_replace('-', " ", $_GET['action'])));
    }
    return $this->action = false;
  }


  /**
   * Retrieve assets from the manifest file.
   *
   * This function reads the manifest file located at BASEPATH/assets/dist/manifest.json
   * and returns the decoded contents as an associative array. If the file doesn't exist
   * or cannot be decoded, it returns false.
   *
   * @return array|false Returns an associative array containing the assets from the manifest
   *                   file, or false if the file doesn't exist or cannot be decoded.
   */
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
