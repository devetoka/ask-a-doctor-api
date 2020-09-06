<?php


namespace Tests\Feature\Question;


use App\Http\Response\ApiResponse;
use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\BaseTestCase;

class QuestionControllerTest extends BaseTestCase
{
    /**
     * @var Collection|Model
     */
    private $question;

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
        $this->question = factory(Question::class)->create();
    }
    /**
     * @test
     */
    // a user can create  question
    public function a_user_can_create_a_question()
    {
        $response = $this->post(route('question.store'), $this->getData());
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertSee($this->getData()['title']);
    }
    /**
     *
     * @test
     */
    //a user cant create a question if validation fails
    public function a_user_cannot_create_a_question_if_validation_fails()
    {
        $response = $this->post(route('question.store'), []);
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
    //a user cant create a question if validation fails
    public function a_user_cannot_create_a_question_if_slug_or_title_is_not_unique()
    {
        $response = $this->post(route('question.store'), $this->question->toArray());
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

    //a question can be fetched by id
    /**
     * @test
     */
    public function a_question__can_be_fetched_by_id()
    {
        $response = $this->get(route('question.show', $this->question->id));
        $response->assertOk();
        $data = json_decode($response->getContent())->data;
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);

        $this->assertSame($data->title, $this->question->title);
    }

    //a question can be fetched by slug
    /**
     * @test
     */
    public function a_question__can_be_fetched_by_slug()
    {
        $response = $this->get(route('question.show', $this->question->slug));
        $response->assertOk();
        $data = json_decode($response->getContent())->data;
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);

        $this->assertSame($data->title, $this->question->title);
    }

//    /**
//     * @test
//     * TODO:
//     */
//    public function a_question__can_be_fetched_by_slug_without_user_authentication()
//    {
//        $response = $this->get(route('question.show', $this->question->slug));
//        $response->assertOk();
//        $data = json_decode($response->getContent())->data;
//        $response->assertJsonStructure([
//            'status',
//            'message',
//            'data'
//        ]);
//
//        $this->assertSame($data->title, $this->question->title);
//    }


    //a user can delete his question
    /**
     * @test
     */
    public function a_question_can_be_deleted()
    {
        $response = $this->delete(route('question.destroy', $this->question->id));
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $question = Question::find($this->question->id);

        $this->assertEmpty($question);
    }

    //a user can not edit his question if it has replies

    //a user can edit his question his question if it doesn't have replies
    /**
     * @test
     */
    public function a_question_can_be_updated()
    {
        $data = $this->getData();
        $data['title'] = 'edited time';
        $response = $this->put(route('question.update', $this->question->id), $data);
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $question = Question::find($this->question->id);

        $this->assertSame($question->title, $data['title']);
    }

    /**
     * @test
     */
    public function returns_404_if_question_not_found_by_id()
    {
        $question = Question::find($this->question->id)->delete();
        $response = $this->get(route('question.show', $this->question->id));
        $response->assertNotFound();

    }

    /**
     * @test
     */
    public function returns_404_if_question_not_found_by_slug()
    {
        $question = Question::find($this->question->id)->delete();
        $response = $this->get(route('question.show', $this->question->slug));
        $response->assertNotFound();

    }


    /**
     * @test
     */
    public function slug_is_updated_if_title_is_changed()
    {
        $data = $this->getData();
        $data['title'] = 'edited time';
        $slug = Str::slug($data['title']);
        $response = $this->put(route('question.update', $this->question->id), $data);
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $question = Question::find($this->question->id);

        $this->assertSame($question->slug, $slug);
    }

    private function getData()
    {

        return [
            'title' => 'How are people getting along',
            'description' => 'They are really trying',
            'category_id' => (factory(Category::class)->create())->id
        ];
    }

}