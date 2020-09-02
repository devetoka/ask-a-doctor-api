<?php


namespace Tests\Feature\Authentication\Login;


use App\Http\Response\ApiResponse;
use App\Models\Category;
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


    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user =factory(User::class)->create([
           'role' => 'admin'
        ]);
        $this->category = factory(Category::class)->create();
        $this->login();
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
    public function an_admin_can_update_a_category()
    {
        $data = $this->getData();
        $data['name'] = 'edited name';
        $response = $this->put(route('category.update', $this->category->id), $data);
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $category = Category::find($this->category->id);

        $this->assertSame($category->name, $data['name']);
    }

    /**
     * @test
     */
    public function an_admin_can_delete_a_category()
    {
        $response = $this->delete(route('category.destroy', $this->category->id));
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $category = Category::find($this->category->id);

        $this->assertEmpty($category);
    }

    /**
     * @test
     */
    public function an_admin_can_fetch_a_category()
    {
        $response = $this->get(route('category.show', $this->category->id));
        $response->assertOk();
        $data = json_decode($response->getContent())->data;
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);

        $this->assertSame($data->name, $this->category->name);
    }

    /**
     * @test
     */
    public function returns_404_if_category_not_found()
    {
        $category = Category::find($this->category->id)->delete();
        $response = $this->get(route('category.show', $this->category->id));
        $response->assertNotFound();

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

    /**
     * @test
     */
    public function only_an_admin_can_update_a_category()
    {
        $this->user =factory(User::class)->create();
        $this->actingAs($this->user, 'api');
        $response = $this->put(route('category.update', $this->category->id), $this->getData());
        $response->assertStatus(ApiResponse::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            'status',
            'message'
        ]);

        $response->assertSee(false);

    }

    /**
     * @test
     */
    public function only_an_admin_can_delete_a_category()
    {
        $this->user =factory(User::class)->create();
        $this->actingAs($this->user, 'api');
        $response = $this->delete(route('category.destroy', $this->category->id));
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