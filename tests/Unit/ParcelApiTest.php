<?php

namespace Tests\Unit;

use Illuminate\Http\Response as Response;
use Laravel\Lumen\Testing\DatabaseTransactions;
use TestCase;
use App\Models\v1\Parcel;
use Faker;
use Exception;

class ParcelApiTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function register()
    {
        $faker = Faker\Factory::create();
        $res = $this->post(route('register'), ['username' => $faker->userName]);
        $content = json_decode($res->response->getContent());
        $data = $content->data;

        $res->seeStatusCode(Response::HTTP_OK);
        $res->seeJsonStructure(
            [
                'status',
                'code',
                'message',
                'data' => ['jwt']
            ]
        );
        $this->assertIsString($data->jwt);
    }

    /** @test */
    public function apiCreateParcel()
    {
        $faker = Faker\Factory::create();
        $res = $this->post(route('register'), ['username' => $faker->userName]);
        $jwt = json_decode($res->response->getContent())->data->jwt;

        $res = $this->post(
            route('createParcel'),
            [
                'name' => $faker->sentence(2),
                'weight' => $faker->randomFloat(2, 0, 10),
                'volume' => $faker->randomFloat(5, 0, 0.001),
                'value' => $faker->randomFloat(0, 1, 1000),
                'model' => $faker->randomElement([MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]),
            ],
            [
                'Authorization' => $jwt
            ]
        );
        $content = json_decode($res->response->getContent());
        $data = $content->data;

        $res->seeStatusCode(Response::HTTP_CREATED);
        $res->seeJsonStructure(
            [
                'status',
                'code',
                'message',
                'data' => ['id', 'name', 'weight', 'volume', 'value', 'model', 'quote']
            ]
        );
        $this->assertIsInt($data->id);
        $this->assertIsString($data->name);
        $this->assertIsNumeric($data->weight);
        $this->assertIsNumeric($data->volume);
        $this->assertIsNumeric($data->value);
        $this->assertContains($data->model, [MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]);
        $this->assertIsNumeric($data->quote);
    }

    /** @test */
    public function apiGetParcel()
    {
        $faker = Faker\Factory::create();
        $res = $this->post(route('register'), ['username' => $faker->userName]);
        $jwt = json_decode($res->response->getContent())->data->jwt;

        $parcel = factory(Parcel::class)->create();
        $res = $this->get(
            route('getParcel', ['id' => $parcel->id]),
            [
                'Authorization' => $jwt
            ]
        );
        $content = json_decode($res->response->getContent());
        $data = $content->data;

        $res->seeStatusCode(Response::HTTP_OK);
        $res->seeJsonStructure(
            [
                'status',
                'code',
                'message',
                'data' => ['id', 'name', 'weight', 'volume', 'value', 'model', 'quote']
            ]
        );
        $this->assertIsInt($data->id);
        $this->assertIsString($data->name);
        $this->assertIsNumeric($data->weight);
        $this->assertIsNumeric($data->volume);
        $this->assertIsNumeric($data->value);
        $this->assertContains($data->model, [MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]);
        $this->assertIsNumeric($data->quote);
    }


    /** @test
     * @throws Exception
     */
    public function apiUpdateParcel()
    {
        $faker = Faker\Factory::create();
        $parcel = factory(Parcel::class)->create();

        $res = $this->post(route('register'), ['username' => $faker->userName]);
        $jwt = json_decode($res->response->getContent())->data->jwt;

        $res = $this->put(
            route('updateParcel', ['id' => $parcel->id]),
            [
                'name' => $faker->sentence(2),
                'weight' => $faker->randomFloat(2, 0, 10),
                'volume' => $faker->randomFloat(5, 0, 0.001),
                'value' => $faker->randomFloat(0, 1, 1000),
                'model' => $faker->randomElement([MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]),
            ],
            [
                'Authorization' => $jwt
            ]
        );
        $content = json_decode($res->response->getContent());
        $data = $content->data;

        $res->seeStatusCode(Response::HTTP_OK);
        $res->seeJsonStructure(
            [
                'status',
                'code',
                'message',
                'data' => ['id', 'name', 'weight', 'volume', 'value', 'model', 'quote']
            ]
        );
        $this->assertIsInt($data->id);
        $this->assertIsString($data->name);
        $this->assertIsNumeric($data->weight);
        $this->assertIsNumeric($data->volume);
        $this->assertIsNumeric($data->value);
        $this->assertContains($data->model, [MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]);
        $this->assertIsNumeric($data->quote);
    }

    /** @test
     * @throws Exception
     */
    public function apiDeleteParcel()
    {
        $faker = Faker\Factory::create();
        $res = $this->post(route('register'), ['username' => $faker->userName]);
        $jwt = json_decode($res->response->getContent())->data->jwt;

        $parcel = factory(Parcel::class)->create();

        $res = $this->delete(
            route('deleteParcel', ['id' => $parcel->id]),
            [],
            [
                'Authorization' => $jwt
            ]
        );

        $content = json_decode($res->response->getContent());
        $data = $content->data;

        $res->seeStatusCode(Response::HTTP_OK);
        $res->seeJsonStructure(
            [
                'status',
                'code',
                'message',
                'data'
            ]
        );
        $this->assertIsInt($data);
    }

    /** @test
     * @throws Exception
     */
    public function apiCalculateParcels()
    {
        $faker = Faker\Factory::create();
        $res = $this->post(route('register'), ['username' => $faker->userName]);
        $jwt = json_decode($res->response->getContent())->data->jwt;

        $parcel = factory(Parcel::class)->create();

        $res = $this->get(
            route('calculateParcels', ['parcelIds' => $parcel->id]),
            [
                'Authorization' => $jwt
            ]
        );

        $content = json_decode($res->response->getContent());
        $data = $content->data;

        $res->seeStatusCode(Response::HTTP_OK);
        $res->seeJsonStructure(
            [
                'status',
                'code',
                'message',
                'data' => ['quote']
            ]
        );
        $this->assertIsNumeric($data->quote);
    }
}
