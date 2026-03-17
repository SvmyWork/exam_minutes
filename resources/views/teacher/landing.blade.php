@extends('teacher.layouts.layout')
@push('head')
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/f5ab145519.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endpush
@section('content')
<div class="flex-1 p-4 md:p-10">
    <div class="flex items-center mb-6">
        <button id="hamburgerBtn" class="text-xl md:hidden focus:outline-none mr-4">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="text-xl font-semibold">Create New Test Series</h1>
    </div>

    <!-- Wrapper Alpine Component -->
    <!-- Wrapper Alpine Component -->
    <div 
        x-data="{
            testSeries: [], 
            testSeriesName: '', 
            createdSeriesName: '', 
            showNotification: false, 
            title: '', 
            note: '', 
            icon: '',
            loading: true,
            loadTestSeries() {
                this.loading = true; // start loader
                fetch('../api/test-series', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer {{ $token }}`
                    },
                    body: JSON.stringify({
                        teacher_id: '{{ $teacher_id }}',
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.testSeries = data;
                    this.loading = false; // stop loader
                })
                .catch(err => {
                    console.error('Error loading test series:', err);
                    this.loading = false; // stop loader even on error
                });
            },
            createSeries() {
                createTestSeries(this.testSeriesName, {
                    onSuccess: (name) => {
                        this.createdSeriesName = name;
                        this.title = 'Test Series Created!';
                        this.note = `${name} was successfully added.`;
                        this.icon = 'success';
                        this.testSeriesName = '';
                        this.showNotification = true;
                        setTimeout(() => this.showNotification = false, 3000);

                        // Refresh test series list
                        this.loadTestSeries();
                    },
                    onError: (msg) => {
                        this.title = 'Error!';
                        this.note = msg;
                        this.icon = 'error';
                        this.showNotification = true;
                        setTimeout(() => this.showNotification = false, 3000);
                    }
                });
            }
        }" 
        x-init="loadTestSeries()"
    >
        <!-- Input and Create Button -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center mb-8 gap-4">
            <input 
                type="text" 
                x-model="testSeriesName"
                placeholder="Enter Test Series Name"
                class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" 
            />

            <button @click="createSeries" 
                class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Create
            </button>

            <!-- Toast Notification -->
            <x-notification />
        </div>

        <div class="border-t border-gray-300 mb-2"></div>

        <h1 class="text-xl font-semibold mb-4">Your Test Series</h1>

        <!-- Test Series Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    
            <!-- Skeleton Loader -->
            <template x-if="loading === true">
                <template x-for="n in 6" :key="n">
                    <div class="animate-pulse flex items-center bg-white p-4 rounded-xl border border-l-4 border-gray-200 shadow-sm">
                        <div class="bg-gray-200 p-2 rounded-full mr-4 w-8 h-8"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                            <div class="h-2 bg-gray-200 rounded w-1/3"></div>
                        </div>
                        <div class="w-6 h-6 bg-gray-200 rounded-full ml-4"></div>
                    </div>
                </template>
            </template>

            <!-- Actual Test Series Cards -->
            <template x-if="!loading && testSeries.length > 0">
                <template x-for="series in testSeries" :key="series.id">
                    <div class="flex items-center bg-white p-4 rounded-xl border border-l-4 border-[#007bff] shadow-sm cursor-pointer hover:bg-gray-100"
                        @click="window.location='../teacher/test-series/' + series.test_series_id">
                        <div class="bg-blue-100 p-2 rounded-full mr-4">
                            <img src="{{ asset('assets/images/open-folder.png') }}" class="w-6 h-6" />
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold" x-text="series.name"></div>
                            <div class="text-gray-500 text-sm" x-text="series.no_of_tests + ' Tests'"></div>
                            <div class="text-gray-500 text-xs mt-1 italic" x-text="'Last modified: ' + series.updated_at"></div>
                        </div>
                        <div class="relative rounded-full border border-gray-300 flex justify-center items-center mr-4 p-4 w-6 h-6 cursor-pointer hover:bg-gray-100">
                            <div class="flex space-x-1">
                                <div class="w-1 h-1 rounded-full bg-gray-700"></div>
                                <div class="w-1 h-1 rounded-full bg-gray-700"></div>
                                <div class="w-1 h-1 rounded-full bg-gray-700"></div>
                            </div>
                        </div>
                        <button class="text-gray-600"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </template>
            </template>

        </div>


        {{-- <div class="border-t border-gray-300 mt-6 mb-6"></div> --}}
    </div>

</div>

<script>
    let token = @json($token);
    let teacher_id = @json($teacher_id);
    let email_id = @json($email_id);
    
    console.log('Teacher ID:', teacher_id);
    // save toke local storage
    localStorage.setItem('teacher_token', token);
</script>

<script src="{{ asset('assets/js/teacher/testseriesCreate.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection
