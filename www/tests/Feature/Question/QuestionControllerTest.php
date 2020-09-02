<?php


namespace Tests\Feature\Question;


use App\Http\Response\ApiResponse;
use App\Models\Category;
use Tests\BaseTestCase;

class QuestionControllerTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
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

    //a user can fetch his questions

    //a user can fetch one of his questions

    //a user can delete his question

    //a user can not edit his question if it has replies

    //a user can edit his question his question if it doesn't have replies
    private function getData()
    {

        return [
            'title' => 'How are people getting along',
            'description' => 'They are really trying',
            'slug' => 'how-are-people-getting-along',
            'category_id' => (factory(Category::class)->create())->id
        ];
    }

}