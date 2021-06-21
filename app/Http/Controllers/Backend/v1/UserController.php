<?php /** @noinspection PhpUnused */

namespace App\Http\Controllers\Backend\v1;

use App\Repositories\v1\UserRepository;

class UserController
{
    protected $userBusinessLogic;

    public function __construct(UserRepository $userBusinessLogic)
    {
        $this->userBusinessLogic = $userBusinessLogic;
    }

    public function testApi()
    {
        $dataTest = $this->userBusinessLogic->testApi();
        return getResponse(INT_VALUE_FALSE, 'Success', $dataTest);
    }

}
