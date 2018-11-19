<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessagesTemplates extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function insert_values($values = [])
    {
        foreach ($values as $oldVal => $value) {
            if (strpos($this->body, $this->getValue($oldVal)) !== false) {
                $this->body = str_replace($this->getValue($oldVal), $value, $this->body);
            } else if (strpos($this->body, $this->getValue(camel_case($oldVal))) !== false) {
                $this->body = str_replace($this->getValue(camel_case($oldVal)), $value, $this->body);
            } else {
                throw new \Exception("$value not found in the template with name $this->name");
            }
        }
    }

    protected function getValue($val)
    {
        return "%$val%";
    }
}
