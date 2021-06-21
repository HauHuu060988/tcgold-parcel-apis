<?php

namespace App\Models\v1;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    protected $table = 'parcels';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'weight',
        'volume',
        'value',
        'model',
        'quote'
    ];
    protected $hidden = [];
    public $timestamps = false;
    public static $rules = [];
    protected $query;

    /**
     * [__fillData description]
     * @param array $dataInfo [description]
     * @return array [description]
     */
    protected function __fillData($dataInfo = [])
    {
        if (!empty($this->fillable)) {
            return array_intersect_key($dataInfo, array_flip($this->fillable));
        }
        return [];
    }

    /**
     * @param array $selectRawQuery
     * @param array $whereRawQuery
     * @return mixed
     */
    public function getList($selectRawQuery = [], $whereRawQuery = [])
    {
        $query = DB::table($this->table);
        foreach ($selectRawQuery as $itm) {
            $query->selectRaw($itm['strRaw'], $itm['params']);
        }
        foreach ($whereRawQuery as $itm) {
            $query->whereRaw($itm['strRaw'], $itm['params']);
        }
        return $query->get();
    }

    /**
     * @param array $selectRawQuery
     * @param array $whereRawQuery
     * @param array $orderByRawQuery
     * @return array|object
     */
    public function getDetail($selectRawQuery = [], $whereRawQuery = [], $orderByRawQuery = [])
    {
        $query = DB::table($this->table);
        foreach ($selectRawQuery as $itm) {
            $query->selectRaw($itm['strRaw'], $itm['params']);
        }
        foreach ($whereRawQuery as $itm) {
            $query->whereRaw($itm['strRaw'], $itm['params']);
        }
        foreach ($orderByRawQuery as $itm) {
            $query->orderByRaw($itm['strRaw'], $itm['params']);
        }
        return $query->first();
    }

    /**
     * @param array $dataInfo
     * @param int $id
     * @return array|bool
     * @throws Exception
     */
    public function saveData($dataInfo = [], int $id = null)
    {
        $query = DB::table($this->table);
        $dataInfo = $this->__fillData($dataInfo);
        if (empty($id)) {
            $id = $query->insertGetId($dataInfo);
        } else {
            $rsUpdateData = $query
                ->where($this->primaryKey, $id)
                ->update($dataInfo);
            empty($rsUpdateData) && $id = null;
        }
        return $id;
    }

    /**
     * @param array $whereRawQuery
     * @return int
     * @throws Exception
     */
    public function removeData($whereRawQuery = [])
    {
        $removeQuery = DB::table($this->table);
        foreach ($whereRawQuery as $itm) {
            $removeQuery->whereRaw($itm['strRaw'], $itm['params']);
        }
        return $removeQuery->delete();
    }
}
