<?php


namespace Tests\Feature\Authentication\ForgotPassword;


use App\Http\Response\ApiResponse;
use App\Models\User;
use App\Notifications\Auth\MailResetPasswordNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\BaseTestCase;

class ForgotPasswordTest extends BaseTestCase
{

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'email' => 'francis.dretoka@gmail.com'
        ]);
    }

    /**
     * @test
     */

    public function password_reset_link_is_not_sent_if_email_is_invalid()
    {
        $response = $this->post('/api/v1/auth/password-reset/', ['email' => 'a@example.com']);
        $response->assertNotFound();

    }


    /**
     * @test
     */

    public function fail_validation_if_email_key_is_absent()
    {
        $response = $this->post('/api/v1/auth/password-reset/', ['user' => 'a@example.com']);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);

    }

    /**
     * @test
     */

    public function password_reset_link_is_sent()
    {

        Notification::fake();
        $response = $this->post('/api/v1/auth/password-reset/', $this->getData());
        $response->assertOk();
        Notification::assertSentTo($this->user, MailResetPasswordNotification::class);
    }

    private function getData()
    {
        return ['email' => 'francis.dretoka@gmail.com'];
    }

}