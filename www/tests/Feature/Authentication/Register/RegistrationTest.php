<?php


namespace Tests\Feature\Authentication\Register;


use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Tests\Feature\BaseTestCase;
use Illuminate\Support\Facades\Event;

class RegistrationTest extends BaseTestCase
{

    /**
     * @test
     */
    public function missing_required_data_returns_error()
    {
         $response = $this->post('/api/v1/auth/register');
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                '*' => []
            ]
        ]);
    }

    /**
     * @test
     */
    public function user_can_be_registered_with_valid_data()
    {
        $response = $this->post('/api/v1/auth/register', $this->getData());
        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => []
            ]
        ]);
        $response->assertSee($this->getData()['first_name']);
    }

    /**
     * @test
     */
    public function registered_event_was_triggered_when_user_was_created()
    {
        Event::fake();
        $response = $this->post('/api/v1/auth/register', $this->getData());
        Event::assertDispatched(Registered::class);
    }

    private function getData()
    {
        return [
            'first_name' => 'Francis',
            'last_name' => 'Etoka',
            'email' =>'francis.dretoka@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'username' => 'etoks'
        ];
    }

}