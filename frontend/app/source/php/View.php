<?php

namespace NavetSearch;

class View
{

    /**
     * Loads additional data from a controller associated with the specified view.
     *
     * This method takes a view name as a parameter, converts it to a controller class name,
     * and attempts to instantiate the corresponding controller. If the controller file exists,
     * it creates an instance of the controller and retrieves the 'data' property from it.
     * If the controller or file does not exist, an empty array is returned.
     *
     * @param string $view The name of the view for which additional controller data is needed.
     *
     * @return array An associative array of additional data from the controller, or an empty array
     *               if the controller or file does not exist.
     */
    public function loadControllerData($view) {
        $view = ucfirst(trim(str_replace(' ', '', ucwords(str_replace(array("-","/"), ' ', $view))), "/"));
        if(file_exists(__DIR__ . "/Controller/" . $view . ".php")) {
            $class = "NavetSearch\\Controller\\".$view; 
            $obj = new $class;
            return $obj->data; 
        }
        return []; 
    }

    /**
     * Renders and displays a view using Blade templating engine.
     *
     * This method takes a view name, data array, and a Blade instance as parameters.
     * It renders the specified view, merges the data with additional controller data,
     * and applies HTML tidying if the 'tidy' extension is available. The tidying process
     * helps to improve the HTML structure and compliance. In case of an exception during
     * rendering, it falls back to displaying an error page with information about the error.
     *
     * @param string $view The name of the view to be rendered.
     * @param array $data An associative array of data to be passed to the view.
     * @param mixed $blade The Blade templating engine instance.
     *
     * @throws \Throwable If an error occurs during the rendering process, it catches the
     *                    exception and displays an error page with relevant information.
     */
    public function show($view, $data = array(), $blade)
    {
        //Run view
        try {
            $result = $blade->make(
                'pages.' . $view,
                array_merge(
                    $data, 
                    $this->loadControllerData($view)
                )
            )->render();

            $result = preg_replace('/(id|href)=""/', "", $result);

            if(class_exists("tidy")) {
                $tidy = new \tidy;
                $tidy->parseString($result, array(
                    'indent'         => true,
                    'output-xhtml'   => true,
                    'wrap'           => 5000,
                    'show-body-only' => false
                ), 'utf8');
                
                $tidy->cleanRepair();

                if(isset($tidy->value)) {
                    echo $tidy->value;
                }

            } else {
                echo $result; 
            }

        } catch (\Throwable $e) {
            echo $blade->make(
                'pages.E404',
                array_merge(
                    $data,
                    $this->loadControllerData("E404"),
                    array('errorMessage' => print_r(['line' => $e->getLine(), 'file' => $e->getFile()], true))
                )
            )->render();
        }
    }

    /**
     * Proxy for accessing provate props
     *
     * @return string Array of values
     */
    public static function accessProtected($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }
}
