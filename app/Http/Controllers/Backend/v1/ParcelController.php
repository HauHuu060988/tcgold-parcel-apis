<?php /** @noinspection PhpUnused */

namespace App\Http\Controllers\Backend\v1;

use App\Repositories\v1\ParcelRepository;
use App\Http\Requests\v1\ParcelRequest;
use Exception;
use Illuminate\Http\Response as Response;

class ParcelController
{
    protected $parcelRepository;

    public function __construct(ParcelRepository $parcelRepository)
    {
        $this->parcelRepository = $parcelRepository;
    }

    /**
     * @param ParcelRequest $request
     * @param $id
     * @return Response
     */
    public function getParcel(ParcelRequest $request, $id)
    {
        $rsGetParcel = $this->parcelRepository->getParcel($id);
        $httpCode = $rsGetParcel ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
        return getResponse($rsGetParcel, null, true, null, $httpCode);
    }

    /**
     * @param ParcelRequest $request
     * @return Response
     * @throws Exception
     */

    public function createParcel(ParcelRequest $request)
    {
        $parcelData = $request->all();
        $rsCreateParcel = $this->parcelRepository->createParcel($parcelData);

        return getResponse($rsCreateParcel, null, true, null, Response::HTTP_CREATED);
    }

    /**
     * @param ParcelRequest $request
     * @param $id
     * @return Response
     * @throws Exception
     */
    public function updateParcel(ParcelRequest $request, $id)
    {
        $rsGetParcel = $this->parcelRepository->getParcel($id);
        if (!$rsGetParcel) {
            return getResponse($rsGetParcel, null, false, null, Response::HTTP_BAD_REQUEST);
        } else {
            $parcelData = $request->all();
            $rsUpdateParcel = $this->parcelRepository->updateParcel($parcelData, $id);

            return getResponse($rsUpdateParcel, null, true, null, Response::HTTP_OK);
        }
    }

    /**
     * @param ParcelRequest $request
     * @param $id
     * @return Response
     * @throws Exception
     */
    public function deleteParcel(ParcelRequest $request, $id)
    {
        $rsGetParcel = $this->parcelRepository->getParcel($id);
        if (!$rsGetParcel) {
            return getResponse($rsGetParcel, null, false, null, Response::HTTP_BAD_REQUEST);
        } else {
            $rsDeleteParcel = $this->parcelRepository->deleteParcel($id);
            return getResponse($rsDeleteParcel, null, true, null, Response::HTTP_OK);
        }
    }

    /**
     * @param ParcelRequest $request
     * @return Response
     */
    public function calculateParcels(ParcelRequest $request)
    {
        $parcelIds = $request->query('parcelIds');
        $rsCalculateParcel = $this->parcelRepository->calculateParcel($parcelIds);
        return getResponse(['quote' => $rsCalculateParcel], null, true, null, Response::HTTP_OK);
    }
}
