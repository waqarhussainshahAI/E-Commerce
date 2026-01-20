@extends('layouts.app')

@section('content')
    <h1>Query Products Database</h1>


    <div class="m-10 p-10 border-gray-300 bg-white h-100 w-100  shadow">
        <div class="h-2/3 mb-auto">
            @if (session('answer'))
                <h3>Answer:</h3>
                <p>{{ session('answer') }}</p>
            @else
                <h3 class="text-gray-300"> What is in your mind</h3>
            @endif
        </div>
        <div class="mt-auto border">
            <form action="{{ route('query') }}" method="POST">
                @csrf


                <label for="question">Ask a question:</label>
                <input class="px-2 py-1 border rounded" type="text" name="question" id="question" value=""
                    placeholder="Query here...">
                <button class="px-2 py-1 border bg-blue-600 text-white  rounded-md" type="submit">Submit</button>
            </form>
        </div>
    </div>
    </body>

    </html>
@endsection
