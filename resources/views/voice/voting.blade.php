<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-black-800 leading-tight">
            Vote
        </h1>
    </x-slot>    
    <form 
        action="{{ route('voice.voice') }}"
        method="POST" 
        enctype="multipart/form-data"
        >
        @csrf 
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-1"></div>
                            <div class="alert alert-danger col-span-6 text-red-800">
                                <p class="text-left font-bold">
                                    {{ $error }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif                
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-1 text-right">
                        <label class="font-bold" for="question_id">
                            Question:
                        </label>
                    </div>
                    <div class="col-span-6">
                        <select name="question_id">
                            @forelse ($questions as $question )
                                <option value="{{ $question->id }}"
                                    >{{ $question->value }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>                    
                    <div class="col-span-1 text-right">
                        <label class="font-bold" for="value">
                            Value:
                        </label>                            
                    </div>
                    <div class="col-span-4">
                        <select name="value">
                            <option value="0">False</option>
                            <option value="1">True</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-3 text-center" >
                    <div class="col-span-4"></div>
                    <div class="col-span-4 text-center">
                        <button type="submit"
                        class="bg-green-500 block shadow-5xl mb-10 
                        p-2 w-80 uyppercase font-bold">
                            Vote
                        </button>
                    </div>
                </div>                
            </div>
        </div>                    
    </form>
</x-app-layout>
