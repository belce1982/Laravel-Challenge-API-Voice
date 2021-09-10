<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VoiceRequest;
use App\Models\{
    Question,
    Voice,
};

class VoiceController extends Controller
{
    public function index(){
        $questions = Question::all();
        return view('voice.voting', [
            'questions' => $questions
        ]);
    }
    public function voice(Request $request){
        $request->validate([
            'question_id'=>'required|int|exists:questions,id',
            'value'=>'required|boolean',
        ]);
    
        $question=Question::find($request->post('question_id'));
        if (!$question)
            return response()->json([
                'status'=>404,
                'message'=>'not found question ..'
            ]);
        if ($question->user_id==auth()->id())
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote to your question'
            ]);
    
        //check if user voted 
        $voice=Voice::where([
            ['user_id','=',auth()->id()],
            ['question_id','=',$request->post('question_id')]
        ])->first();

        if (!is_null($voice)&&boolval($voice->value)===$request->boolean('value')) {
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote more than once'
            ]);
        }else if (!is_null($voice)&&$voice->value!==$request->post('value')){
            $voice->update([
                'value'=>$request->post('value')
            ]);
            return response()->json([
                'status'=>201,
                'message'=>'update your voice'
            ]);
        }
        $question->voice()->create([
            'user_id'=>auth()->id(),
            'value'=>$request->post('value')
        ]);
    
        return response()->json([
            'status'=>200,
            'message'=>'Voting completed successfully'
        ]);
    }
    
    /* 
    public function voice(VoiceRequest $request){
        
        $question=Question::findOrFail($request->question_id);
        if (!$question)
            return response()->json([
                'status'=>404,
                'message'=>'not found question ..'
            ]);
        if ($question->user_id==auth()->id())
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote to your question'
            ]);
    
        //check if user voted 
        $voice=Voice::where([
            ['user_id','=',auth()->id()],
            ['question_id','=',$request->post('question_id')]
        ])->first();
        if (!is_null($voice)&&$voice->value===$request->post('value')) {
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote more than once'
            ]);
        }else if (!is_null($voice)&&$voice->value!==$request->post('value')){
            $voice->update([
                'value'=>$request->post('value')
            ]);
            return response()->json([
                'status'=>201,
                'message'=>'update your voice'
            ]);
        }
    
        $question->voice()->create([
            'user_id'=>auth()->id(),
            'value'=>$request->post('value')
        ]);
    
        return response()->json([
            'status'=>200,
            'message'=>'Voting completed successfully'
        ]);
    }
    */
}
