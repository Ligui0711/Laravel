<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function Todoの新規作成()
    {
        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertOk();
        $todos = Todo::all();

        $this->assertCount(1, $todos);

        $todo = $todos->first();

        $this->assertEquals($params['title'], $todo->title);
        $this->assertEquals($params['content'], $todo->content);

    }
    /**
     * @test
     */
    public function Todoの新規作成の失敗()
    {
        $params = [
            'title' => null,
            'content' => null
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertStatus(422);

    }

    /**
     * @test
     */
    public function Todoの更新処理()
    {
        $todo = Todo::factory()->create();

        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->putJson(route('api.todo.update', $todo->id), $params);

        $res->assertOk();

        $data = $res->json();
        $this->assertEquals($params['title'], $data['title']);
        $this->assertEquals($params['content'], $data['content']);
    }
    /**
     * @test
     */
    public function Todoの更新処理の失敗()
    {
        $todo = Todo::factory()->create();

        $params = [
            'title' => null,
            'content' => null
        ];

        $res = $this->putJson(route('api.todo.update', $todo->id), $params);
        $res->assertStatus(422);

    }
    
    /**
     * @test
     */
    public function Todoの詳細取得()
    {
        $todo = Todo::factory()->create();

        $res = $this->getJson(route('api.todo.show', $todo->id));

        $res->assertOk();

        $data = $res->json();

        $this->assertEquals($todo->title, $data['title']);
        $this->assertEquals($todo->content, $data['content']);
    }
    /**
     * @test
     */
    public function Todoの詳細取得の失敗()
    {
        $todo = Todo::factory()->create();

        $res = $this->getJson(route('api.todo.show', $todo->id * 0));

        $res->assertStatus(404);
    }

    /**
     * @test
     */
    public function Todoの削除処理()
    {
        $todo = Todo::factory()->create();

        $res = $this->deleteJson(route('api.todo.destroy', $todo->id));

        $res->assertOk();

        $this->assertNull(Todo::find($todo->id));
    }
    /**
     * @test
     */
    public function Todoの削除処理の失敗()
    {
        $todo = Todo::factory()->create();

        $res = $this->deleteJson(route('api.todo.destroy', $todo->id * 0));

        $res->assertStatus(404);
    }
}
