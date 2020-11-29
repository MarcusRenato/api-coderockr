<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * Test Get All Events
     *
     * @return void
     */
    public function testGetAllEvents()
    {
        Event::factory()->count(5)->create();

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /**
     * Test Get Error Single Event
     *
     * @return void
     */
    public function testGetEventByIdentify()
    {
        $event = Event::factory()->create();

        $response = $this->getJson("/api/event/{$event->id}");

        $response->assertStatus(200);
    }
}
