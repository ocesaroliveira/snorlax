<?php

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;

/**
 * Tests for the Snorlax\Resource class. Since it is an abstract class, we need
 * to test using the example class PokemonResource.
 */
class ResourceTest extends TestCase
{
    public function testAllMethod()
    {
        $json = json_encode([
            'pokemons' => []
        ]);
        $mock = new Response(200, [], $json);

        $guzzle = $this->prophesize(ClientInterface::class);
        $guzzle->request('GET', 'pokemons/', [])
            ->willReturn($mock);

        $client = $this->getRestClient([
            'custom' => $guzzle->reveal()
        ]);

        $response = $client->pokemons->all();

        $this->assertEquals([], $response->pokemons);
        $this->assertEquals($mock, $client->pokemons->getLastResponse());
    }

    public function testGetMethod()
    {
        $json = json_encode([
            'pokemon' => [
                'id' => 143,
                'name' => 'Snorlax'
            ]
        ]);
        $mock = new Response(200, [], $json);

        $guzzle = $this->prophesize(ClientInterface::class);
        $guzzle->request('GET', 'pokemons/143', [])
            ->willReturn($mock);

        $client = $this->getRestClient([
            'custom' => $guzzle->reveal()
        ]);

        $response = $client->pokemons->get(143);

        $this->assertEquals((object) [
            'id' => 143,
            'name' => 'Snorlax'
        ], $response->pokemon);
        $this->assertEquals($mock, $client->pokemons->getLastResponse());
    }

    public function testPostMethod()
    {
        $mock = new Response(201);

        $guzzle = $this->prophesize(ClientInterface::class);
        $guzzle->request('POST', 'pokemons/', ['body' => ['pokemon_id' => 143]])
            ->willReturn($mock);

        $client = $this->getRestClient([
            'custom' => $guzzle->reveal()
        ]);

        $response = $client->pokemons->capture([
            'body' => ['pokemon_id' => 143]
        ]);

        $this->assertEquals($mock, $client->pokemons->getLastResponse());
    }

    public function testPatchMethod()
    {
        $mock = new Response(204);

        $guzzle = $this->prophesize(ClientInterface::class);
        $guzzle->request('PATCH', 'pokemons/143/144/rest', [])
            ->willReturn($mock);

        $client = $this->getRestClient([
            'custom' => $guzzle->reveal()
        ]);

        $response = $client->pokemons->attack(143, 144, 'rest');

        $this->assertEquals($mock, $client->pokemons->getLastResponse());
    }
}
