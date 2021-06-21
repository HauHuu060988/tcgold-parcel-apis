<?php

namespace Tests\Unit;

use Laravel\Lumen\Testing\DatabaseTransactions;
use TestCase;
use App\Repositories\v1\ParcelRepository;
use App\Models\v1\Parcel;
use Faker;
use Exception;

class ParcelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function caseCreateParcel()
    {
        $faker = Faker\Factory::create();

        $data = [
            'name' => $faker->sentence(2),
            'weight' => $faker->randomFloat(2, 0, 10),
            'volume' => $faker->randomFloat(5, 0, 0.001),
            'value' => $faker->randomFloat(0, 1, 1000),
            'model' => $faker->randomElement([MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]),
        ];

        $parcelRepository = new ParcelRepository(new Parcel);
        try {
            $parcel = $parcelRepository->createParcel($data);
        } catch (Exception $e) {
        }

        $this->assertIsInt($parcel['id']);
    }

    /** @test */
    public function caseGetParcel()
    {
        $parcel = factory(Parcel::class)->create();
        $parcelRepository = new ParcelRepository(new Parcel);
        $found = $parcelRepository->getParcel($parcel->id);

        $this->assertIsInt($found->id);
        $this->assertIsString($found->name);
        $this->assertIsNumeric($found->weight);
        $this->assertIsNumeric($found->volume);
        $this->assertIsNumeric($found->value);
        $this->assertContains($found->model, [MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]);
        $this->assertIsNumeric($found->quote);
    }

    /** @test
     * @throws Exception
     */
    public function caseUpdateParcel()
    {
        $faker = Faker\Factory::create();
        $parcel = factory(Parcel::class)->create();

        $data = [
            'name' => $faker->sentence(2),
            'weight' => $faker->randomFloat(2, 0, 10),
            'volume' => $faker->randomFloat(5, 0, 0.001),
            'value' => $faker->randomFloat(0, 1, 1000),
            'model' => $faker->randomElement([MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]),
        ];

        $parcelRepository = new ParcelRepository(new Parcel);
        $parcelRepository->updateParcel($data, $parcel->id);

        $parcelDetail = $parcelRepository->getParcel($parcel->id);

        $this->assertEquals($parcelDetail->id, $parcel->id);
        $this->assertEquals($parcelDetail->name, $data['name']);
        $this->assertEquals($parcelDetail->weight, $data['weight']);
        $this->assertEquals($parcelDetail->volume, $data['volume']);
        $this->assertEquals($parcelDetail->value, $data['value']);
        $this->assertEquals($parcelDetail->model, $data['model']);

        $this->assertIsInt($parcelDetail->id);
        $this->assertIsString($parcelDetail->name);
        $this->assertIsNumeric($parcelDetail->weight);
        $this->assertIsNumeric($parcelDetail->volume);
        $this->assertIsNumeric($parcelDetail->value);
        $this->assertContains($parcelDetail->model, [MODEL_BY_WEIGHT, MODEL_BY_VOLUME, MODEL_BY_VALUE]);
        $this->assertIsNumeric($parcelDetail->quote);
    }

    /** @test
     * @throws Exception
     */
    public function caseDeleteParcel()
    {
        $parcel = factory(Parcel::class)->create();

        $parcelRepository = new ParcelRepository(new Parcel);
        $rsDeleteParcel = $parcelRepository->deleteParcel($parcel->id);

        $this->assertEquals($rsDeleteParcel, 1);
    }

    /** @test
     * @throws Exception
     */
    public function caseCalculateParcel()
    {
        $parcel = factory(Parcel::class)->create();

        $parcelRepository = new ParcelRepository(new Parcel);
        $rsCalculateParcel = $parcelRepository->calculateParcel($parcel->id);

        $this->assertIsNumeric($rsCalculateParcel);
    }
}
