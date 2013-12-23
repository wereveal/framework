<?php
/**
 *  @brief Interface for page controllers
 *  @file PageControllerInterface.php
 *  @ingroup example_app controllers
 *  @namespace Ritc/ExampleApp/Controllers
 *  @class PageControllerInterface
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @date 2013-12-12 12:34:12
 *  @note A file in ExampleApp v1 app
 *  @note <pre><b>Change Log</b>
 *      v0.1 - Initial version 12/12/2013
 *  </pre>
**/
namespace Ritc\ExampleApp\Controllers;

interface PageControllerInterface
{
    /**
     *  Main Router and Puker outer (more descriptive method name).
     *  Turns over the hard work to the specific controllers through the router.
     *  @param none
     *  @return str $html
    **/
    public function renderPage();
    /**
     *  Routes the code to the appropriate sub-controllers or methods and returns a string.
     *  As much as I have been looking at putting the actual route pairs somewhere else
     *  it feels like the routes are so specific to the controller, they might as well
     *  be in the controller.
     *  @param array $a_actions optional, the actions derived from the URL/Form
     *  @param array $a_values optional, the values from a form
     *  @return str normally html to be displayed.
    **/
    public function router(array $a_actions = array(), array $a_values = array());
}
