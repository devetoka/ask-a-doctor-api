<?php


namespace Tests\Feature\Authentication\Login;


use App\Http\Response\ApiResponse;
use App\Models\User;
//use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTestCase;

class UserTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        factory(User::class)->create([
            'email' => $this->getData()['username'],
            'username' => 'etoks',
            'password' => Hash::make($this->getData()['password'])
        ]);
    }

    /**
     * @test
     */
    public function missing_required_data_returns_error()
    {
        $response = $this->post('/api/v1/auth/login');
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
    public function user_can_login_with_valid_data()
    {
        $response = $this->post('/api/v1/auth/login', $this->getData());
        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => []
            ]
        ]);
        $response->assertSee('access_token');
        $response->assertSee('expires_at');
    }

    /**
     * @test
     */
    public function user_can_not_login_with_invalid_data()
    {
        $data = $this->getData();
        $data['username'] = 'wrong_username';
        $response = $this->post('/api/v1/auth/login', $data);
        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors'
        ]);
    }

    /**
     * @test
     */
    public function user_can_login_with_username()
    {
        $data = $this->getData();
        $data['username'] = 'etoks';
        $response = $this->post('/api/v1/auth/login', $data);
        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => []
            ]
        ]);
        $response->assertSee('access_token');
        $response->assertSee('expires_at');
    }

    private function getData()
    {
        return [
            'username' =>'francis.dretoka@gmail.com',
            'password' => '12345678',
        ];
    }

    /**
     * @test
     */
    public function user_id_locked_after_too_many_failed_login_attempts()
    {
        $this->withMiddleware(
            ThrottleRequests::class
        );
        $data = $this->getData();
        $data['password'] = 'pasl';
        for($i = 0; $i < 30 ; $i++){
            $this->post('/api/v1/auth/login', $data);
        }
        $response = $this->post('/api/v1/auth/login', $data);
        $response->assertStatus(ApiResponse::HTTP_TOO_MANY_REQUESTS);
        $response->assertSee(false);

    }

}