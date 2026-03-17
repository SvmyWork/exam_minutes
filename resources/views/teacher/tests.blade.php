@extends('teacher.layouts.layout')
@push('head')
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/f5ab145519.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endpush
@section('content')
<div class="flex-1 p-4 md:p-10">

    <!-- Header Section -->
    <div class="relative flex flex-col items-center justify-center mb-6">
        <!-- Mobile Hamburger (absolute left) -->
        <button id="hamburgerBtn" class="absolute left-0 text-xl md:hidden focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Top line: Test Series Name + Edit Icon -->
        <div class="flex items-center space-x-2">
            <h1 class="text-2xl font-semibold text-center">
                Test Series: {{$testSeriesName}}
            </h1>

            <!-- Edit Icon with Tooltip -->
            <div class="relative group inline-flex">
                <i class="fas fa-edit text-xl text-blue-500 hover:text-blue-600 cursor-pointer"></i>

                <!-- Tooltip -->
                <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-3 py-1 bg-white text-gray-700 text-xs rounded shadow border border-gray-300 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none">
                    Rename Test Series

                    <!-- Arrow (triangle) -->
                    <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-white border-l border-t border-gray-300 rotate-45"></div>
                </div>
            </div>
        </div>

        <!-- Below line: Created On -->
        <h3 class="text-base font-semibold text-center mt-1" id="Nooftest">
            Number of Tests: 0
        </h3>
        <!-- Below line: Created On -->
        <div class="text-sm text-gray-500 mt-1 italic">
            Created on: {{$creationDate}}
        </div>
        <div class="text-sm text-gray-500 mt-1 italic">
            Last modified on: {{$modifyDate}}
        </div>
    </div>

    <div class="border-t border-gray-300 mb-4"></div>

    <!-- Section Title -->
    <h1 class="text-xl font-semibold mb-4">
        Create New Test
    </h1>

    <!-- Input and Create Button -->
    <div 
        x-data="createTestComponent"
        class="flex flex-col sm:flex-row items-stretch sm:items-center mb-8 gap-4"
    >
        <input 
            type="text" 
            x-model="testName"
            placeholder="Enter Test Name"
            class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" 
        />

        <button 
            @click="createTest"
            class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600"
        >
            Create
        </button>

        <x-notification />
    </div>

    <div class="border-t border-gray-300 mb-2"></div>

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Your Tests</h1>

        <div class="relative">
            
            <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                Sort by
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Created Date (Default)</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Modified Date</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Name (A to Z)</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Name (Z to A)</a>
                </li>
                </ul>
            </div>
            
        </div>
    </div>




    <!-- Test Series Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">

        <!-- Repeat this block for each test series -->
        <div 
            x-data="testListComponent()" 
            x-init="loadTests()" 
            class="space-y-3"
        >
            <!-- Loading skeleton -->
            <template x-if="loading">
                <div class="space-y-2 animate-pulse">
                    <template x-for="i in 5" :key="i">
                        <div class="flex items-center bg-white px-4 py-2 rounded-xl border border-l-4 border-[#007bff] shadow-sm">
                            <div class="bg-blue-100 p-2 rounded-full mr-4">
                                <div class="w-6 h-6 bg-blue-200 rounded-full"></div>
                            </div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                                <div class="h-2 bg-gray-200 rounded w-1/4 mt-1"></div>
                            </div>
                            <div class="w-4 h-4 bg-gray-200 rounded-full"></div>
                        </div>
                    </template>
                </div>
            </template>


            <!-- No tests -->
            <template x-if="!loading && tests.length === 0">
                <div class="text-gray-500 text-center py-4">No tests found.</div>
            </template>

            <!-- Tests list -->
            <template x-for="(test, index) in tests" :key="index">
                <div 
                    class="flex items-center bg-white px-4 py-2 rounded-xl border border-l-4 border-[#007bff] shadow-sm cursor-pointer hover:bg-gray-100"
                    @click="openTestDetails(test.test_id)"
                >
                    <div class="bg-blue-100 p-2 rounded-full mr-4">
                        <img src="{{ asset('assets/images/open-folder.png') }}" class="w-6 h-6" />
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold" x-text="test.test_name"></div>
                        <div class="text-gray-500 text-sm" x-text="`${test.num_questions} Questions`"></div>
                        <div class="text-gray-500 text-xs mt-1 italic" x-text="`Last modified: ${test.updated_at}`"></div>
                    </div>
                    <div
                        class="relative rounded-full border border-gray-300 flex justify-center items-center mr-4 p-4 w-6 h-6 cursor-pointer hover:bg-gray-100">
                        <div class="flex space-x-1">
                            <div class="w-1 h-1 rounded-full bg-gray-700"></div>
                            <div class="w-1 h-1 rounded-full bg-gray-700"></div>
                            <div class="w-1 h-1 rounded-full bg-gray-700"></div>
                        </div>
                    </div>
                    <button class="text-gray-600">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </template>
        </div>

        
    </div>

    <div class="border-t border-gray-300 mt-6 mb-6"></div>

</div>

<script>
    const testSeriesId = @json($testSeriesId);
    const teacherId = @json($teacher_id);
    const testSeriesName = @json($testSeriesName);
</script>

<script src="{{ asset('assets/js/teacher/createTest.js') }}"></script>

@endsection
