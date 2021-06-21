<?php

namespace App\Models\v1;

class User
{
    protected $table;
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'created_date',
        'modified_date',
        'created_date_unix',
        'modified_date_unix',
        'created_by',
        'modified_by',
        'is_published',
        'is_deleted',
        'data',
    ];
    protected $hidden = [];
    public $timestamps = false;
    protected $dateFormat = 'U';
    public static $rules = [];

    public function __construct()
    {
        $this->table = $this->getTableName('user');
    }

    //Return properties to class parent
    protected function getTableInfo()
    {
        $result = [
            'alias' => ' as u',
            'primaryKey' => $this->primaryKey,
        ];
        return (object)$result;
    }
}
