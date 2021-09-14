<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use App\Models\Voice;


class VoiceControllerTest extends TestCase
{
    use RefreshDatabase;
    private $user;
    private $faker;


    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
        $this->user = User::factory()->create();
        Question::factory()
            ->count(1)
            ->create([
                'user_id' => $this->user->id,
            ]
        );
        $question = Question::inRandomOrder()->first();
        Voice::factory()
            ->count(1)
            ->create([
                'user_id' => $this->user->id,
                'question_id' => $question->id,
                'value' => false,
            ]);
        User::factory()
            ->count(20)
            ->create();            
        Question::factory()
            ->count(100)
            ->create();
        Voice::factory()
            ->count(20)
            ->create();
    }

    public function testGuestCannotPostVoice()
    {
        $response = $this->post('/voice');
        $response->assertRedirect('login');
    }

    public function testPostVoiceValidation()
    {
        $this->actingAs($this->user);

        $response = $this->post('/voice');

        //'question_id'=>'required',
        //'value'=>'required',
        $response->assertSessionHasErrors([
            'question_id'   => 'The question id field is required.',
            'value'         => 'The value field is required.'
        ]);

        $response = $this->post('/voice', [
            'question_id'   => 'qwerty',
            'value'         => 'b',
        ]);

        //'question_id'=>'int',
        //'value'=>'boolean',
        $response->assertSessionHasErrors([
            'question_id'   => 'The question id must be an integer.',
            'value'         => 'The value field must be true or false.'
        ]);        
        
        $response = $this->post('/voice', [
            'question_id'   => 2342,
            'value'         => $this->faker->boolean(),
        ]);
        
        //'question_id'=>'exists:questions,id',
        $response->assertSessionHasErrors([
            'question_id'   => 'The selected question id is invalid.',
        ]);                
    }
    public function testPostVoiceUserNotAllowed()
    {
        $this->actingAs($this->user);

        $question = Question::where('user_id', $this->user->id)
            ->first();
        $response = $this->post('/voice', [
            'question_id'   => $question->id,
            'value'         => $this->faker->boolean(),
        ]);        
        
        $response->assertJson([
            'status'  => 500,
            'message' => 'The user is not allowed to vote to your question',
        ]);

        $question = Question::where('user_id', '!=', $this->user->id)
            ->first();
        $voice = Voice::factory()
            ->count(1)
            ->create([
                'user_id' => $this->user->id,
                'question_id' => $question->id,
                'value' => true,
            ])->first();
        $response = $this->post('/voice', [
            'question_id'   => $voice->question_id,
            'value'         => $voice->value,
        ]);
        $response->assertJson([
            'status'  => 500,
            'message' => 'The user is not allowed to vote more than once',
        ]);
    }
    public function testPostVoiceSuccess()
    {
        $this->actingAs($this->user);

        $question = Question::where('user_id', '!=', $this->user->id)
            ->first();

        $voice = Voice::factory()
            ->count(1)
            ->create([
                'user_id' => $this->user->id,
                'question_id' => $question->id,
                'value' => true,
            ])->first();

        $response = $this->post('/voice', [
            'question_id'   => $voice->question_id,
            'value'         => false,
        ]);

        $response->assertJson([
            'status'  => 201,
            'message' => 'update your voice',
        ]);    

        $user = User::factory()->count(1)->create()->first();
        $question = Question::factory()
            ->count(1)
            ->create([
                'user_id' => $user->id,
                'value' => $this->faker->words(3, true),
            ])->first();

        $response = $this->post('/voice', [
            'question_id'   => $question->id,
            'value'         => false,
        ]);

        $response->assertJson([
            'status'  => 200,
            'message' => 'Voting completed successfully',
        ]);        
        //$response->dumpSession();
    }
}
