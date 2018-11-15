<?php
namespace App\Services\Session;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class SessionService
{
    private $prefix = self::class;

    public function set(String $key, $value)
    {
        Cache::put($this->get_key($key), $value, 1);
        return true;
    }
    
    public function get($key) {
        if(Cache::has($this->get_key($key))) {
        $data = Cache::get($this->get_key($key));
        return $data;
        }
        return false;
    }

    private function get_key($key)
    {
        return $this->prefix.'$'.$key;
    }
}