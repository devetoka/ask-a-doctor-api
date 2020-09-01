<?php


namespace Tests\Feature\Authentication\Login;


use App\Http\Response\ApiResponse;
use App\Models\User;
//use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTestCase;

class CategoryControllerTest extends BaseTestCase
{
    /**
     * @var Collection|Model
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user =factory(User::class)->create([
           'role' => 'admin'
        ]);
        $this->actingAs($this->user, 'api');
    }

    /**
     * @test
     */
    public function an_admin_can_create_a_category()
    {
        $response = $this->post(route('category.store', $this->getData()));
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertSee($this->getData()['name']);
    }


    /**
     * @test
     */
    public function an_admin_cannot_create_a_category_if_validation_fails()
    {
        $response = $this->post(route('category.store', []));
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
    public function only_an_admin_can_create_a_category()
    {
        $this->user =factory(User::class)->create();
        $this->actingAs($this->user, 'api');
        $response = $this->post(route('category.store', $this->getData()));
        $response->assertStatus(ApiResponse::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            'status',
            'message'
        ]);

        $response->assertSee(false);

    }

    private function getData()
    {
        return [
            'name' => 'Health',
            'description' => 'this is a short description'
        ];
    }


}