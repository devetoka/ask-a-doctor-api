<?php


namespace Tests\Feature\Authentication\ResetPassword;


use App\Http\Response\ApiResponse;
use App\Models\User;
//use App\Notifications\Auth\MailResetPasswordNotification;
//use Illuminate\Support\Facades\Mail;
//use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\BaseTestCase;

class ResetPasswordTest extends BaseTestCase
{

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'email' => 'francis.dretoka@gmail.com'
        ]);
        $this->token = Password::broker()->createToken($this->user);

    }

    /**
     * @test
     */

    public function password_reset_is_successful_if_valid_data_is_Sent()
    {
        $response = $this->post('/api/v1/auth/reset/', $this->getData());
        $response->assertStatus(ApiResponse::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertSee('true');

    }

    /**
     * @test
     */

    public function password_reset_is_unsuccessful_if_invalid_data_is_Sent()
    {
        $data = $this->getData();
        $data['email'] = 'example@gmail.com';
        $response = $this->post('/api/v1/auth/reset/', $data);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                '*' => []
            ]
        ]);
        $response->assertSee('false');

    }


    /**
     * @test
     */

    public function validation_error_is_returned_if_invalid_data_is_Sent()
    {

        $response = $this->post('/api/v1/auth/reset/', []);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                '*' => []
            ]
        ]);

    }



    private function getData()
    {
        return [
            'email' => 'francis.dretoka@gmail.com',
            'token' => $this->token,
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
    }

}