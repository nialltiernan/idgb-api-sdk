<?php
declare(strict_types=1);

namespace Igdb\Tests\Unit\Resources;

use Igdb\ApiClient;
use Igdb\Tests\Base;
use Lukasoppermann\Httpstatus\Httpstatuscodes as Status;

class CharacterTest extends Base
{
    private const RESOURCE = 'Character';

    /** @test */
    public function fetch()
    {
        $client = new ApiClient($this->config, $this->getMockedHttpClient(self::RESOURCE, __FUNCTION__));

        $response = $client->characters()->fetch();
        $this->assertEquals(Status::HTTP_OK, $response->getResponse()->getStatusCode());

        $data = $response->getData();
        $this->assertIsArray($data);
    }

    /** @test */
    public function fields()
    {
        $client = new ApiClient($this->config, $this->getMockedHttpClient(self::RESOURCE, __FUNCTION__));

        $data = $client->characters()->fetch('fields checksum, name;')->getData();

        foreach ($data as $datum) {
            $this->assertArrayHasKey('checksum', $datum);
            $this->assertArrayHasKey('name', $datum);
        }
    }

    /** @test */
    public function where()
    {
        $client = new ApiClient($this->config, $this->getMockedHttpClient(self::RESOURCE, __FUNCTION__));

        $data = $client->characters()->fetch('where id = (100, 200);')->getData();

        $this->assertEquals([['id' => 100], ['id' => 200]], $data);
    }

    /** @test */
    public function limit()
    {
        $client = new ApiClient($this->config, $this->getMockedHttpClient(self::RESOURCE, __FUNCTION__));

        $this->assertCount(2, $client->characterMugShots()->fetch('limit 2;')->getData());
    }
}