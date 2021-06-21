<?php

namespace App\Repositories\v1;

use App\Models\v1\Parcel;
use Exception;

class ParcelRepository
{

    protected $parcelModel;

    /**
     * ParcelRepository constructor.
     * @param Parcel $parcel
     */
    public function __construct(Parcel $parcel)
    {
        $this->parcelModel = $parcel;
    }

    /**
     * @param $parcelData
     * @return float|int
     */
    private function __calculateQuote($parcelData)
    {
        if (empty($parcelData)) {
            return 0;
        }
        $pricingModels = config('common.pricing_models');

        if ($parcelData['model'] == MODEL_BY_WEIGHT) {
            $quote = $pricingModels[MODEL_BY_WEIGHT] * $parcelData['weight'];
        } elseif ($parcelData['model'] == MODEL_BY_VOLUME) {
            $quote = $pricingModels[MODEL_BY_VOLUME] * $parcelData['volume'];
        } else {
            $quote = $pricingModels[MODEL_BY_VALUE] * $parcelData['value'];
        }

        return $quote;
    }

    /**
     * @param $id
     * @return array|object
     */
    public function getParcel($id)
    {
        $whereRawQuery = [['strRaw' => 'id= ?', 'params' => [$id]]];
        return $this->parcelModel->getDetail([], $whereRawQuery);
    }

    /**
     * Save product's detail (create or update)
     *
     * @param array $parcelData
     * @return array
     * @throws Exception
     * @throws Exception
     */
    public function createParcel(array $parcelData = [])
    {
        $parcelData['quote'] = $this->__calculateQuote($parcelData);
        $id = $this->parcelModel->saveData($parcelData);
        return $id ? array_merge(['id' => $id], $parcelData) : [];
    }

    /**
     * @param array $parcelData
     * @param $id
     * @return array
     * @throws Exception
     */
    public function updateParcel(array $parcelData = [], int $id)
    {
        $parcelData['quote'] = $this->__calculateQuote($parcelData);
        $id = $this->parcelModel->saveData($parcelData, $id);
        $id && $parcelData['id'] = $id;

        return $id ? $parcelData : [];
    }

    /**
     * @param $id
     * @return int
     * @throws Exception
     */
    public function deleteParcel($id)
    {
        $whereRawQuery = [['strRaw' => 'id = ?', 'params' => [$id]]];
        return $this->parcelModel->removeData($whereRawQuery);
    }

    /**
     * @param string $parcelIds
     * @return mixed
     */
    public function calculateParcel($parcelIds = '')
    {
        $whereRawQuery = [
            [
                'strRaw' => sprintf('id IN (' . $parcelIds . ')'),
                'params' => [],
            ]
        ];
        $rsGetList = $this->parcelModel->getList([], $whereRawQuery);
        return $rsGetList->sum('quote');
    }
}
