<?php /** @noinspection PhpUnused */

namespace App\Http\Controllers\Backend\v1;

use App\Http\Requests\v1\UserRequest;
use App\Repositories\v1\UserRepository;
use Illuminate\Http\Response as Response;

class UserController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRequest $request)
    {
        $jwt = $this->userRepository->register($request->get('username'));
        return getResponse(['jwt' => $jwt], null, true, null, Response::HTTP_OK);
    }

}
