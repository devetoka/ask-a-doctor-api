<?php


namespace Tests\Feature\Question;


use App\Http\Response\ApiResponse;
use App\Models\Category;
use App\Models\Question;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\BaseTestCase;

class ReplyControllerTest extends BaseTestCase
{

    /**
     * @var Collection|Model
     */
    private $reply;
    /**
     * @var Collection|Model
     */
    private $question;

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
        $this->reply = factory(Reply::class)->create();
        $this->question = factory(Question::class)->create();
    }
    /**
     * @test
     */
    // a user can create  reply
    public function a_user_can_submit_a_reply_to_a_question()
    {
        $response = $this->post(route('reply.question.store', $this->question->id), $this->getData());
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertSee($this->getData()['content']);
    }

    /**
     * @test
     */
    // a user can create  reply to a reply
    public function a_user_can_create_a_reply_to_a_reply()
    {
        $response = $this->post(route('reply.store', $this->reply->id), $this->getData());
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertSee($this->getData()['content']);
    }
    /**
     *
     * @test
     */
    //a user cant create a reply if validation fails
    public function a_user_cannot_create_a_reply_if_validation_fails()
    {
        $response = $this->post(route('reply.question.store', $this->question->id), []);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                '*' => []
            ]
        ]);
        $response->assertSee(false);
    }

    /**
     *
     * @test
     */
    //a user cant create a reply if validation fails
    public function a_user_cannot_create_a_reply_if_content_of_reply_is_less_than_50()
    {
        $data = $this->getData();
        $data['content'] = 'less than 50';
        $response = $this->post(route('reply.question.store', $this->question->id), $data);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                '*' => []
            ]
        ]);
        $response->assertSee(false);
    }



    //a reply can be fetched by id
    /**
     * @test
     */
    public function a_reply__can_be_fetched_by_id()
    {
        $response = $this->get(route('reply.show', $this->reply->id));
        $response->assertOk();
        $data = json_decode($response->getContent())->data;
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $this->assertSame($data->content, $this->reply->content);
    }




    //a user can delete his reply
    /**
     * @test
     */
    public function a_reply_can_be_deleted()
    {
        $response = $this->delete(route('reply.destroy', $this->reply->id));
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $reply = Reply::find($this->reply->id);

        $this->assertEmpty($reply);
    }


    //a user can edit his reply
    /**
     * @test
     */
    public function a_reply_can_be_updated()
    {
        $data = $this->getData();
        $data['content'] = $data['content'].'edited time';
        $response = $this->put(route('reply.update', $this->reply->id), $data);
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $reply = reply::find($this->reply->id);

        $this->assertSame($reply->content, $data['content']);
    }

    /**
     *
     * @test
     */
    //a user cant update a reply if validation fails
    public function a_user_cannot_update_a_reply_if_content_of_reply_is_less_than_50()
    {
        $data = $this->getData();
        $data['content'] = 'less than 50';
        $response = $this->put(route('reply.update', $this->question->id), $data);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                '*' => []
            ]
        ]);
        $response->assertSee(false);
    }

    /**
     *
     * @test
     */
    //a user cant update a reply if validation fails
    public function a_user_cannot_update_a_reply_if_validation_fails()
    {

        $response = $this->put(route('reply.update', $this->question->id), []);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                '*' => []
            ]
        ]);
        $response->assertSee(false);
    }



    /**
     * @test
     */
    public function returns_404_if_reply_not_found_by_id()
    {
        $reply = reply::find($this->reply->id)->delete();
        $response = $this->get(route('reply.show', $this->reply->id));
        $response->assertNotFound();

    }




    private function getData()
    {

        return [
            'content' => 'this is my comment. It is a very lovely comment and it is more than 50 characters'
        ];
    }

}