<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use App\Models\Voice;


class VoiceControllerTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testGuestCannotPostVoice()
    {
        $response = $this->postJson('api/voices',
            ['question_id' => 1, 'value' => 1]);
        $response->assertStatus(401);
    }

    /*public function testPostVoiceValidation()
    {
        $response = $this->post('/voice');

        //'question_id'=>'required',
        //'value'=>'required',
        $response->assertSessionHasErrors([
            'question_id'   => 'The question id field is required.',
            'value'         => 'The value field is required.'
        ]);

        $response = $this->actingAs($this->user, 'api')->postJson('api/voices',
            ['question_id'   => 'qwerty',
            'value'         => 'b',
        ]);

        //'question_id'=>'int',
        //'value'=>'boolean',
        $response->assertSessionHasErrors([
            'question_id'   => 'The question id must be an integer.',
            'value'         => 'The value field must be true or false.'
        ]);        
        
        $response = $this->actingAs($this->user, 'api')->postJson('api/voices',
            [
            'question_id'   => 2342,
            'value'         => $this->faker->boolean(),
        ]);
        
        //'question_id'=>'exists:questions,id',
        $response->assertSessionHasErrors([
            'question_id'   => 'The selected question id is invalid.',
        ]);                
    }*/
    public function testPostVoiceUserNotAllowedToYourQuestion()
    {
        $question = Question::factory()
            ->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user, 'api')->postJson('api/voices',
            [
            'question_id'   => $question->id,
            'value'         => 1,
        ]);        
//        $response->assertStatus(500);
        $response->assertJson([
            'message' => 'The user is not allowed to vote to your question',
        ]);
    }

    public function testPostVoiceUserNotAllowedToVoteMoreThanOnce()
    {
        
        $question_user = User::factory()->create();
        $question = Question::factory()
            ->create(['user_id' => $question_user->id]);
        Voice::factory()->create([
                'user_id' => $this->user->id,
                'question_id' => $question->id,
                'value' => 1,
            ]);
        $response = $this->actingAs($this->user, 'api')->postJson('api/voices',
            [
            'question_id'   => $question->id,
            'value'         => 1
        ]);
        //$response->assertStatus(500);
        $response->assertJson([
            'message' => 'The user is not allowed to vote more than once',
        ]);        
    }
    public function testPostVoiceUpdateYourVoice()
    {
        
        $question_user = User::factory()->create();
        $question = Question::factory()
            ->create(['user_id' => $question_user->id]);

        Voice::factory()->create([
                'user_id' => $this->user->id,
                'question_id' => $question->id,
                'value' => 1,
        ]);

        $response = $this->actingAs($this->user, 'api')->postJson('api/voices',
            [
            'question_id'   => $question->id,
            'value'         => 0,
        ]);

        //$response->assertStatus(201);
        $response->assertJson([
            'message' => 'update your voice',
        ]);
    }
    public function testPostVoiceVotingComplete()
    {
        
        $question_user = User::factory()->create();
        $question = Question::factory()
            ->create(['user_id' => $question_user->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('api/voices',
            [
            'question_id'   => $question->id,
            'value'         => 1,
        ]);

        //$response->assertStatus(200);
        $response->assertJson([
            'message' => 'Voting completed successfully',
        ]);
        $this->assertDatabaseHas(
            Voice::class,
            [
                'user_id'     => $this->user->id,
                'question_id' => $question->id,
                'value'       => 1,
            ]
        );
        //$response->dumpSession();
    } 
}
