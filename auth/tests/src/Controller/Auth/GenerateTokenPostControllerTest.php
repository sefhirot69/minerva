<?php

namespace Tests\src\Controller\Auth;

use App\Factory\ClientFactory;
use Auth\Domain\Client\Grant;

class GenerateTokenPostControllerTest extends AbstractWebTestCase
{

    /** @test */
    public function itShouldGenerateTokenWithClientCredentials(): void
    {
        // GIVEN
        $client = ClientFactory::new()->withGrantClientCredentials()->create();
        $router = $this->client->getContainer()->get('router');
        $server = ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];
        $parameters = [
            'client_id' => (string)$client->getCredentials()->getIdentifier(),
            'client_secret' => (string)$client->getCredentials()->getSecret(),
            'grant_type' => Grant::CLIENT_CREDENTIALS->value,
        ];

        // WHEN
        $this->client->request(
            'POST',
            $router->generate('auth_token'),
            $parameters,
            [],
            $server,
        );

        // THEN
        self::assertResponseIsSuccessful();
    }
}
