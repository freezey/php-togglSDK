<?php
/* Usage */

class Toggl_Classloader{
    protected $paths = array();

    public function __construct(){
        $path = dirname(__FILE__) . "/";
        $this->paths[] = $path;
    }

    public function loadClass($className){
        $relativePath = $className . ".php";
        foreach($this->paths as $path){
            if(file_exists($path . $relativePath)){
                include($path . $relativePath);
                return true;
            }
        }
        return false;
    }

    protected function addPath($path){
        $paths[] = $path;
    }

}