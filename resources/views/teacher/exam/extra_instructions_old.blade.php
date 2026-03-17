<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>JAM 2025 Mock Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans bg-white m-0 overflow-hidden h-screen">

    <!-- Popup Overlay -->
    <div id="popup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <!-- Popup Box -->
        <div id="popupBox" class="bg-white p-6 rounded-xl shadow-lg text-center w-80">
            <p id="popupMessage" class="text-lg font-medium mb-4">It is ready to start your exam</p>
            <button id="popupButton" onclick="closePopup()" class="px-5 py-2 rounded-md text-white">
                OK
            </button>
        </div>
    </div>

    <!-- banner -->
    <header class="bg-blue-100 p-2 flex items-center justify-between">
        <h1 class="text-xl text-red-600 mx-2">JOINT ADMISSION TEST FOR MASTERS (JAM) 2025</h1>
    </header>

    <div class="flex "> <!-- Header is ~3.5rem -->
        <div class="flex-1 flex flex-col h-screen" style="height: calc(100vh - 3.5rem);">
            <!-- Instructions section: scrollable content area -->
            <section id="instructions" class="flex-1 overflow-y-auto p-5">
                <h2 class="text-center text-black text-lg font-bold mb-4">JAM 2025: General Instructions during
                    Examination</h2>
                <div class="max-w-3xl mx-auto text-sm">
                    <p class="text-red-600 font-semibold mb-3">
                        This is a mock test for JAM examination pattern familiarity. Candidates are advised to practice
                        with the given calculator as it’s functioning might be different from the physical calculators
                        used.
                    </p>
                    <ol class="list-decimal pl-5 space-y-2">
                        <li>Total duration of examination is <strong>180 minutes</strong>.</li>
                        <li>Calculator is available on top, right hand side of the screen.</li>
                        <li>The clock will be set at the server. The countdown timer at the top right corner of screen
                            will display the remaining time available for you to complete the examination. When the
                            timer
                            reaches zero, the examination will end by itself. You need not terminate the examination or
                            submit
                            your paper.</li>
                        <li>The Question Palette displayed on the right side of screen will show the status of each
                            question
                            using one of the following symbols:</li>
                    </ol>

                    <div class="border border-black mt-4">
                        <div class="flex items-center p-2 border-t first:border-t-0">
                            <div class="w-6 h-6 bg-gray-300 text-black font-bold flex items-center justify-center mr-2">
                                1</div>
                            You have NOT visited the question yet.
                        </div>
                        <div class="flex items-center p-2 border-t">
                            <div class="w-6 h-6 bg-red-600 text-white font-bold flex items-center justify-center mr-2">2
                            </div>
                            You have NOT answered the question.
                        </div>
                        <div class="flex items-center p-2 border-t">
                            <div
                                class="w-6 h-6 bg-green-600 text-white font-bold flex items-center justify-center mr-2">
                                3</div>
                            You have answered the question.
                        </div>
                        <div class="flex items-center p-2 border-t">
                            <div
                                class="w-6 h-6 bg-purple-500 text-white font-bold flex items-center justify-center mr-2">
                                4</div>
                            You have NOT answered the question, but have marked the question for review.
                        </div>
                        <div class="flex items-center p-2 border-t">
                            <div
                                class="w-6 h-6 bg-violet-700 text-white font-bold flex items-center justify-center mr-2">
                                5</div>
                            You have answered the question and marked for review. This will be evaluated.
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div class="flex items-center space-x-2">
                            <span>5. You can click on the</span>
                            <button class="px-2 py-1 border">▶</button>
                            <span>arrow to collapse question palette. Click</span>
                            <button class="px-2 py-1 border">&lt;</button>
                            <span>to expand again.</span>
                        </div>

                    </div>
                    <p class="mt-4 font-bold underline">Navigating to a Question:</p>
                    <div class="mt-4 ml-6">
                        <p class="font-semibold">7. To answer a question, do the following:</p>
                        <ol class="list-[lower-alpha] pl-6 space-y-1">
                            <li>Click on the question number in the Question Palette to go to that question directly.
                            </li>
                            <li>Select the answer for a multiple choice type question and the answer(s) for a multiple
                                select type question. Use the virtual numeric keypad to enter the answer for a numerical
                                type question.</li>
                            <li>Click on <strong>Save & Next</strong> to save your answer for the current question and
                                then
                                go to the next question.</li>
                            <li>Click on <strong>Mark for Review & Next</strong> to save the answer and to mark it for
                                review, and then go to the next question.</li>
                        </ol>
                    </div>
                    <strong>Caution:</strong> Note that your answer for the current question will not be saved, if you
                    navigate to another question directly by clicking on a question number in the Question Palette
                    without saving
                    the answer.
                    <div class="mt-2 ml-6">8. You can view all the questions by clicking on the <strong>Question
                            Paper</strong> button.
                        This
                        feature is provided,
                        should you wish to view the entire str question paper at a glance.</div>

                    <p class="mt-4 font-bold underline">Answering a Question:</p>
                    <div class="mt-4 ml-6">
                        <p class="font-semibold underline">9. Procedure for answering a multiple choice question (MCQ):
                        </p>
                        <ol class="list-[lower-alpha] pl-6 space-y-1">
                            <li>Choose the answer by selecting only one out of the four choices given below the
                                question, by clicking on the radio button placed before the corresponding choice.</li>
                            <li>To deselect your chosen answer, click on the radio button of the selected choice again
                                or click on the <strong>Clear Response</strong> button.</li>
                            <li>To change your chosen answer, click on the radio button corresponding to another choice.
                            </li>
                            <li>To save your answer, you MUST click on the <strong>
                                    Save & Next</strong> button.</li>

                        </ol>
                    </div>
                    <div class="mt-4 ml-6">
                        <p class="font-semibold underline">10. Procedure for answering a multiple select question (MSQ):
                        </p>
                        <ol class="list-[lower-alpha] pl-6 space-y-1">
                            <li>Choose the answer by selecting <strong>one or more than one</strong> out of the four
                                choices given below
                                the question, by clicking on the checkbox(es) placed before each of the corresponding
                                choice
                                (s).</li>
                            <li>To deselect your chosen answer(s), click on the checkbox(es) of the selected choice(s)
                                again. To deselect all the selected choices, click on the <strong>Clear
                                    Response</strong> button.</li>
                            <li>To change a particular selected choice, deselect this choice and click on the checkbox
                                of another choice.
                            </li>
                            <li>To save your answer, you MUST click on the <strong>Save & Next</strong> button.</li>

                        </ol>
                    </div>

                    <div class="mt-4 ml-6">
                        <p class="font-semibold underline">11. Procedure for answering a numerical answer type (NAT)
                            question:
                        </p>
                        <ol class="list-[lower-alpha] pl-6 space-y-1">
                            <li>To enter a number as your answer, use the virtual numerical keypad that appears below
                                the question.</li>
                            <li>A fraction (e.g. -0.3 or -.3) can be entered as an answer with or without '0' before the
                                decimal
                                point.</li>
                            <li>To clear your answer, click on the <strong>Clear Response</strong> button.
                            </li>
                            <li>To save your answer, you MUST click on the <strong>Save & Next</strong> button.</li>

                        </ol>
                    </div>

                    <div class="mt-2 ml-6">
                        12. To mark a question for review, click on the Mark for Review & Next button. If
                        an answer is selected <span class="italic">(for MCQ and MSQ types)</span> or entered <span
                            class="italic">(for NAT)</span>
                        for a
                        question that is Marked for Review, that answer will be considered in the evaluation unless the
                        status is modified by the candidate.
                    </div>




                    <div class="mt-2 ml-6">13. To change your answer to a question that has already been answered, first
                        select that question and
                        then follow the procedure for answering that type of question as described above.</div>




                    <p class="mt-4 font-bold underline">Navigating through Sections:</p>
                    <div class="mt-2 ml-6">
                        14. Sections in this question paper are displayed on the top bar of the screen. All sections are
                        compulsory.</div>
                    <div class="mt-2 ml-6">15. Questions in a section can be viewed by clicking on the name of that
                        section.
                        The section you are
                        currently viewing is highlighted.</div>
                    <div class="mt-2 ml-6">16. To select another section, simply click the name of the section on the
                        top
                        bar. You can shuffle
                        between different sections any number of times.</div>
                    <div class="mt-2 ml-6">17. When you select a section, you will only be able to see questions in that
                        Section.</div>
                    <div class="mt-2 ml-6">18. After clicking the Save & Next button for the last question in a section,
                        you
                        will automatically be
                        taken to the first question of the next section in sequence.</div>
                    <div class="mt-2 ml-6">19. You can click on placed near the section name/subject name, to view the
                        answering status for that
                        section/subject.</div>
                </div>
            </section>

            <!-- Fixed footer -->
            <footer class="bg-white border-t p-4 relative">
                <label for="lang">Choose Language: </label>
                <!-- <select name="lang" id="lang" class="border border-gray-400 bg-gray-100 px-2 py-1 mb-4" disabled> -->
                <select name="lang" id="lang" class="border border-gray-400 bg-gray-100 px-2 py-1 mb-4">
                    <option value="Bengali" selected>Bengali</option>
                    <option value="English">English</option>
                    <option value="Hindi">Hindi</option>
                    <option value="Kannada">Kannada</option>
                    <option value="Malayalam">Malayalam</option>
                    <option value="Marathi">Marathi</option>
                    <option value="Odia">Odia</option>
                    <option value="Punjabi">Punjabi</option>
                    <option value="Tamil">Tamil</option>
                    <option value="Telugu">Telugu</option>
                    <option value="Urdu">Urdu</option>
                    <option value="Assamese">Assamese</option>
                    <option value="Gujarati">Gujarati</option>
                    <option value="Konkani">Konkani</option>
                    <option value="Manipuri">Manipuri</option>
                </select>
                <div class="mb-4 block max-h-24 overflow-y-auto border p-2 rounded">
                    <input type="checkbox" name="agree" class="ml-2 mr-2" id="agree"/>
                    I have read and understood the instructions. All computer hardware allotted to me are in proper
                    working condition. I declare that I am not in possession of / not wearing / not carrying any
                    prohibited gadget like mobile phone, bluetooth devices etc. /any prohibited material with me into
                    the Examination Hall. I agree that in case of not adhering to the instructions, I shall be liable to
                    be debarred from this Test and/or to disciplinary action, which may include ban from future Tests /
                    Examinations
                </div>
                

                <br>

                <!-- Previous button stays on the left -->
                <button class="px-4 py-2 border border-gray-400 bg-gray-100 hover:bg-gray-200" onclick="window.location.href='{{ route('exam.instructions') }}'">
                    &lt; Previous
                </button>

                <!-- Start button centered absolutely -->
                <button
                    id="startBtn"
                    disabled
                    class="px-4 py-2 border border-gray-400 w-[120px] bg-gray-300 text-gray-500 absolute left-1/2 transform -translate-x-1/2 cursor-not-allowed" onclick="window.location.href='{{ route('exam.paper', ['paperid' => 5]) }}'">
                    Start &gt;
                </button>
                
                

            </footer>


        </div>

        <!-- Sidebar (fixed height) -->
        <aside class="w-64 bg-gray-100 border-l p-4 text-center h-full" style="padding-bottom: 0px;">
            <img src="https://img.freepik.com/free-vector/smiling-young-man-illustration_1308-173524.jpg?ga=GA1.1.1157756022.1739728206&semt=ais_items_boosted&w=740"
                alt="User Icon" class="mx-auto w-16">
            <div class="mt-2 font-semibold">John Smith</div>
        </aside>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/exam/instructions.js') }}"></script>
    <script src="{{ asset('assets/js/exam/localstorage.js') }}"></script>
    <script src="{{ asset('assets/js/exam/pageProtection.js') }}"></script>

    <script>clearAllQuestionNos()</script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const langSelect = document.getElementById("lang");
            const agreeCheckbox = document.getElementById("agree");
            const startBtn = document.getElementById("startBtn");

            function validateForm() {
                const langSelected = langSelect.value.trim() !== "";
                const isChecked = agreeCheckbox.checked;

                if (langSelected && isChecked && questionsLoaded) {
                    startBtn.disabled = false;
                    startBtn.classList.remove("bg-gray-300", "text-gray-500", "cursor-not-allowed");
                    startBtn.classList.add("bg-blue-500", "text-white", "hover:bg-blue-600", "cursor-pointer");
                } else {
                    startBtn.disabled = true;
                    startBtn.classList.add("bg-gray-300", "text-gray-500", "cursor-not-allowed");
                    startBtn.classList.remove("bg-blue-500", "text-white", "hover:bg-blue-600", "cursor-pointer");
                }
            }

            langSelect.addEventListener("change", validateForm);
            agreeCheckbox.addEventListener("change", validateForm);
        });
    </script>


</body>

</html>