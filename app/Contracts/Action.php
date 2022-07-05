<?php
namespace App\Contracts;

abstract class Action
{
    protected $input = [];

    protected function getInput($field, $default = null)
    {
        return $this->input[$field] ?? $default;
    }
}
