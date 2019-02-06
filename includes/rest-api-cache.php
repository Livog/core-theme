<?php
declare(strict_types=1);

class RESTWPCache {
    private $cachePath;
    public function __construct()
    {
      $this->cachePath = get_template_directory() . '/rest-cache';
      if (false === file_exists($this->cachePath)) {
        mkdir($this->cachePath);
      }
    }

    public function createUpdateCache($route, $data)
    {
      $route = ltrim(str_replace('/', '-', $route), '-');
      file_put_contents("$this->cachePath/$route", json_encode($data));
    }

    public function bustCache($route)
    {
      $route = ltrim(str_replace('/', '-', $route), '-');
      unlink("$this->cachePath/$route");
    }

    public function getLastModified($route)
    {
      $route = ltrim(str_replace('/', '-', $route), '-');
      if(file_exists("$this->cachePath/$route")){
        $lastModTime = filemtime("$this->cachePath/$route");
      }
      return $lastModTime;
    }

    public function getCacheFolder()
    {
      $folder = array_diff(scandir($this->cachePath), array('..', '.'));
      return $folder;
    }

    public function bustCacheFolder()
    {
      $files = $this->getCacheFolder();
      foreach($files as $file){
        unlink($this->cachePath . '/' . $file);
      }
      return $folder;
    }

    /**
     * @return boolean
     */
    public function cacheExists($route)
    {
      $route = ltrim(str_replace('/', '-', $route), '-');
      return file_exists("$this->cachePath/$route");
    }

    public function getCache($route)
    {
      $route = ltrim(str_replace('/', '-', $route), '-');
      if ($this->cacheExists($route)) {
        return json_decode(file_get_contents("$this->cachePath/$route"), true);
      }
    }
}

$service_restApiCache = (new RESTWPCache);