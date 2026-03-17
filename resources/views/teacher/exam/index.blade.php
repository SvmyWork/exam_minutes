@extends('teacher.exam.layout')
@section('title', 'GATE Exam')
@section('content')

<div class="flex items-center bg-white px-6 py-1.5 text-xl miniwidth">
    <div class="text-lg font-semibold mb-2">Question Type: MCQ</div>
    <div class="ml-auto text-lg mb-1">
        Marks for correct answer: <span class="text-green-500 font-bold">2</span> | Negative Marks: <span
            class="text-red-500 font-bold">-1/4</span>
    </div>
</div>
<div class="flex items-center p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 hidden" role="alert" id = "alerttext">
  <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info</span>
  <div>
    <span class="font-medium">Warning:</span> You are currently offline.
  </div>
</div>
<div class="flex items-center bg-white px-6 py-1.5 text-xl border miniwidth">
    <div class="text-lg font-bold mb-1">Question No. <span id = "QNo">1</span></div>

    <div class="ml-auto text-lg mb-1 ">
        <i class="fa-regular fa-clock"></i>
        <span id="countup">00:00</span>

    </div>

</div>
<div class="items-center bg-white px-6 py-1.5 text-xl miniwidth h-[60%] overflow-y-auto pb-40" id="question-box">
    <!-- <div class="mb-4 text-xl"><img src="{{ asset('assets/images/questions/question1.png')}}" alt="question"></div> -->
    
    <!-- <div class="mb-4 text-xl">Question</div>
    <div class="space-y-2 text-xl">
        <label class="block"><input type="radio" name="q1" class="mr-2">Epidermis</label>
        <label class="block"><input type="radio" name="q1" class="mr-2">Parenchyma</label>
        <label class="block"><input type="radio" name="q1" class="mr-2">Phloem</label>
        <label class="block"><input type="radio" name="q1" class="mr-2">Xylem</label>
    </div> -->

</div>



@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/exam/loadquestions.js') }}"></script>

</script>

<!-- <script src="{{ asset('assets/js/exam/IndexedDB.js') }}"></script> -->