<?php

namespace Tests\Feature;

use Tests\TestCase;

class CronjobTest extends TestCase
{
    /**
     * Test at /api/cron/run-jobs endpoint virker og returnerer 200.
     */
    public function testRunJobsEndpoint()
    {
        // Send en GET request til endpointet
        $response = $this->getJson(route('api.cron.run-jobs'));

        // Tjek at statuskoden er 200 OK
        $response->assertStatus(200);

        // Hvis du forventer et bestemt JSON-respons, kan du teste det her fx:
        // $response->assertJsonStructure([
        //     'message',
        //     'data'
        // ]);
    }

    /**
     * Test at /api/cron/run-jobs også virker med POST.
     */
    public function testRunJobsEndpointPost()
    {
        $response = $this->postJson(route('api.cron.run-jobs'));

        $response->assertStatus(200);
    }
}
