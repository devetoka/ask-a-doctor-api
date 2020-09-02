<?php


namespace Tests\Feature\Authentication\Verification;


use App\Models\User;
use App\Utilities\Enum\Encryption\Encryption;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Tests\BaseTestCase;

class VerificationTest extends BaseTestCase
{


    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'email_verified_at' => null
        ]);
        Event::fake();
    }

    /**
     * @test
     */
    public function user_email_can_be_verified()
    {
        $response = $this->get('/api/v1/auth/verify/'.$this->getData());
        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message'
        ]);
    }

    /**
     * @test
     */
    public function user_email_can_not_be_verified_twice()
    {
        $this->get('/api/v1/auth/verify/'.$this->getData());
        $response = $this->get('/api/v1/auth/verify/'.$this->getData());
        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertSee(trans('verification.existing'));
        $response->assertJsonStructure([
            'status',
            'message'
        ]);
    }

    /**
     * @test
     */
    public function validation_is_unsuccessful_with_invalid_token()
    {
        $this->user->email = 'wrong@email.com';

        $this->get('/api/v1/auth/verify/'.$this->getData());
        $response = $this->get('/api/v1/auth/verify/'.$this->getData());
        $response->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
        $response->assertSee(trans('verification.error'));
        $response->assertJsonStructure([
            'status',
            'message'
        ]);

    }

    private function getData()
    {
        $string = $this->user->email . '-' .
            bin2hex(random_bytes(mt_rand(1,10))) . '-' . strtotime(now());
        return Encryption::encryptString($string);

    }
}