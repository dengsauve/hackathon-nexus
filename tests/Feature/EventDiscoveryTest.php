<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_upcoming_public_events(): void
    {
        Event::factory()->create([
            'name' => 'Civic Tech Sprint',
            'slug' => 'civic-tech-sprint',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Civic Tech Sprint')
            ->assertSee('/events/civic-tech-sprint');
    }

    public function test_visitors_can_browse_and_search_public_events(): void
    {
        Event::factory()->create([
            'name' => 'Climate Data Challenge',
            'summary' => 'Climate risk visualizations',
            'format' => 'hybrid',
        ]);
        Event::factory()->create([
            'name' => 'AI Builder Weekend',
            'summary' => 'Workflow automation',
            'format' => 'online',
        ]);

        $this->get('/events?search=Climate&format=hybrid')
            ->assertOk()
            ->assertSee('Climate Data Challenge')
            ->assertDontSee('AI Builder Weekend');
    }

    public function test_public_event_detail_pages_render_metadata(): void
    {
        $event = Event::factory()->create([
            'name' => 'Civic Tech Sprint',
            'slug' => 'civic-tech-sprint',
            'location' => 'San Francisco, CA',
        ]);

        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertSee('Civic Tech Sprint')
            ->assertSee('San Francisco, CA')
            ->assertSee('Event details');
    }

    public function test_private_or_draft_events_are_not_publicly_viewable(): void
    {
        $draft = Event::factory()->draft()->create();
        $private = Event::factory()->create(['visibility' => 'private']);

        $this->get('/events')
            ->assertOk()
            ->assertDontSee($draft->name)
            ->assertDontSee($private->name);

        $this->get(route('events.show', $draft))->assertNotFound();
        $this->get(route('events.show', $private))->assertNotFound();
    }
}
