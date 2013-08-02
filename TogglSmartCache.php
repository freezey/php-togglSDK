<?php
class TogglSmartCache{
    private $ttl = 300;
    private $name = '';
    private $dir = '';


    public function __construct($name, $ttl = 300, $dir = ''){
        $this->ttl = $ttl;
        if($dir == ''){
            $dir = dirname(realpath(dirname(WEBROOT_DIR))) . '/tmp/cache';
        }
        $this->dir = $dir;
        $this->name = $name;
    }

    public function getData(){
        $data = $this->getDataFromCache();
        if($data === false){
            $data = $this->getDataFromSource();
            if($data != null){
                $this->cacheData($data);
            }
        } else {
            unset($data['expiration']);
        }
        return $data;
    }

    public function getCacheFileName(){
        return $this->dir . '/' . $this->name . '.cache';
    }

    public function expireCache(){
        unlink($this->getCacheFileName());
    }

    public function getDataFromSource(){
        throw new Exception("Must Override This Method");
    }

    public function getDataFromCache(){
        $data = false;
        if(file_exists($this->getCacheFileName())){
            $serialized_data = file_get_contents($this->getCacheFileName());
            $data = unserialize($serialized_data);
            //If this is expired, return false
            if($data['expiration'] < time()){
                $data = false;
            }
        }
        return $data;
    }

    public function cacheData($data){
        $fh = fopen($this->dir . "/" . $this->name . '.cache', 'w+');

	    $expiration = time() + $this->ttl;
	    $data['expiration'] = $expiration;

	    fwrite($fh, serialize($data));
	    fclose($fh);
    }
}