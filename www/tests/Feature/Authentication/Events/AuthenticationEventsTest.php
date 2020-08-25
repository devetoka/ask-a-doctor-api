<?php
//
//namespace Tests\Feature\Authentication\Events;
//
//
//use App\Mail\Authentication\VerificationMail;
//use App\Models\User;
//use Illuminate\Auth\Events\Registered;
//use Illuminate\Support\Facades\Event;
//use Illuminate\Support\Facades\Mail;
//use Tests\BaseTestCase;
//
//class AuthenticationEventsTest extends BaseTestCase
//{
//
//    private $user;
//
//    protected function setUp(): void
//    {
//        parent::setUp();
////        Event::fake();
//        $this->user = factory(User::class)->create();
//        //dd($this->user);
////        parent::setUp();
//    }
//
////    /**
////     *
////     * @test
////     **/
////    public function verification_mail_sent_when_registered_event_is_fired()
////    {
////        Mail::fake();
////        event(new Registered($this->user));
////        $user = $this->user;
////        Mail::assertQueued(VerificationMail::class, function($mail) use ($user){
////            return $mail->user->id == $user->id;
////        });
////
////    }
//
//}
