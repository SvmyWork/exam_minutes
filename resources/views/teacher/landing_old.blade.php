
@extends('teacher.layout')
@section('content')
<div class="flex-1 p-4 md:p-10">
<div class="flex items-center mb-6">
    <button id="hamburgerBtn" class="text-xl md:hidden focus:outline-none mr-4">
        <i class="fas fa-bars"></i>
    </button>
    <h1 class="text-xl font-semibold">Test Series</h1>
</div>

<!-- Input and Create Button -->
<div class="flex flex-col sm:flex-row items-stretch sm:items-center mb-8 gap-4">
    <input type="text" placeholder="Enter Test Series Name"
        class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />
    <button class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Create</button>
</div>

<div class="border-t border-gray-300 mb-6"></div>

<!-- Test Series Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Repeat this block for each test series -->
     @foreach ($testSeries as $series)
    <div class="flex items-center bg-white p-4 rounded shadow-sm cursor-pointer hover:bg-gray-100" onclick="window.location='{{ route('teacher.edit_question') }}'">
        <div class="bg-blue-100 p-2 rounded-full mr-4">
            <img src="{{ asset('assets/images/open-folder.png') }}" class="w-6 h-6" />
        </div>
        <div class="flex-1">
            <div class="font-semibold">{{ $series->name }}</div>
            <div class="text-gray-500 text-sm">{{ $series->tests_count }} Tests</div>
        </div>
        <div
            class="relative rounded-full border border-gray-300 flex justify-center items-center mr-4 p-4 w-6 h-6 cursor-pointer hover:bg-gray-100">
            <div class="flex space-x-1">
                <div class="w-1 h-1 rounded-full bg-gray-700"></div>
                <div class="w-1 h-1 rounded-full bg-gray-700"></div>
                <div class="w-1 h-1 rounded-full bg-gray-700"></div>
            </div>
        </div>
        <button class="text-gray-600"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
    @endforeach
    
    
</div>
</div>
@endsection