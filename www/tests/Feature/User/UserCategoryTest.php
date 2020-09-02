<?php


namespace Tests\Feature\Authentication\Login;


use App\Http\Response\ApiResponse;
use App\Models\Category;
use App\Models\User;
//use Illuminate\Http\Exceptions\ThrottleRequestsException;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTestCase;

class UserCategoryTest extends BaseTestCase
{

    private $categories;
    private $userRepository;


    protected function setUp(): void
    {
        parent::setUp();
        //create a user
        //sign in as the user
        $this->user =factory(User::class)->create();
        $this->login();
        //create categories
//        dd($this->user->categories);
        $this->categories = factory(Category::class, 4)->create()
            ->map(
                function($category) {return $category->id;
                })->toArray();
    }

    /**
     * @test
     */
    public function user_cannot_add_categories_if_validation_fails()
    {
        $response = $this->post(route('user.categories.create'),
            []);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);
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
    public function user_can_be_attached_to_categories()
    {
        $response = $this->post(route('user.categories.create'),
            ['categories' => $this->categories]);
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
//        dd(gettype($this->user->categories->pluck('id')->toArray()));
        $this->assertEquals($this->user->categories->pluck('id')->toArray(), $this->categories);
    }

    /**
     * @test
     */
    public function user_can_delete_his_categories()
    {
        $response = $this->delete(route('user.categories.destroy'),
            ['categories' => $this->categories]);
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $this->assertNotEquals($this->user->categories, $this->categories);
    }

    /**
     * @test
     */
    public function user_cannot_delete_his_categories_if_validation_fails()
    {
        $response = $this->delete(route('user.categories.destroy'),
            []);
        $response->assertStatus(ApiResponse::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                '*' => []
            ]
        ]);
        $this->assertNotEquals($this->user->categories, $this->categories);
    }


}