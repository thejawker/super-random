<?php

namespace TheJawker\SuperRandom;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class SuperRandomCodeGenerator
{
    public $table;
    public $column;
    public $length = 8;
    public $chars = 'ABCDEFGHJKLMNPQRSTUVW23456789';

    public function generate($config = [])
    {
        $this->setConfig($config);

        return $this->isDbAware() ?
            $this->unique() : $this->composeCode();
    }

    private function unique()
    {
        do {
            $code = $this->composeCode();
        } while ($this->codeExists($code));

        return $code;
    }

    public function composeCode()
    {
        return substr(str_shuffle(str_repeat($this->chars, $this->length)), 0, $this->length);
    }

    public function length($length)
    {
        $this->length = $length;

        return $this;
    }

    public function chars($chars)
    {
        $this->chars = $chars;

        return $this;
    }

    public function for($tableColumn)
    {
        if (class_exists($tableColumn) && is_subclass_of($tableColumn, Model::class)) {
            $table = (new $tableColumn)->getTable();
            $column = 'code';
        } else {
            list($table, $column) = explode('.', $tableColumn);
        }

        $this->setConfig(['table' => $table, 'column' => $column]);

        return $this;
    }

    public function setConfig($config)
    {
        $this->length = Arr::get($config, 'length', $this->length);
        $this->chars = Arr::get($config, 'chars', $this->chars);
        $this->table = Arr::get($config, 'table', $this->table);
        $this->column = Arr::get($config, 'column', $this->column);

        return $this;
    }

    public function getChars()
    {
        return $this->chars;
    }

    public function isDbAware()
    {
        return Schema::hasColumn($this->table, $this->column);
    }

    private function codeExists($code)
    {
        return DB::table($this->table)->where($this->column, 'like', $code)->count() > 0;
    }
}