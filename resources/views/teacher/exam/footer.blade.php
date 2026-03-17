<div class="fixed bottom-0 w-full bg-white border-t border-gray-300 flex flex-row md:flex-row text-sm">
    <!-- Left div -->
    <div
        class="flex-1 bg-gray-200 flex flex-row md:flex-row justify-between items-center text-center text-lg py-3 border-t border-gray-300">
        <div
            class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 md:ml-4  md:w-auto px-4 md:px-0">
            <button id="markForReview"
                class="w-[100px] text-xs md:w-auto px-1 py-1 md:px-4 md:py-2 bg-[#007bff] text-white rounded text-sm">Mark
                for
                Review &
                Next</button>
            <button class=" md:w-auto px-1 py-1 md:px-4 md:py-2 border bg-white rounded text-sm" onclick="clearSelectedOptions()">Clear
                Response</button>
        </div>
        <div class="mt-2 md:mt-0 md:mr-4 px-4 md:px-0 w-full md:w-auto">
            <button
                class="w-[100px] md:w-auto bg-[#007bff] text-white rounded md:px-4 md:py-2 px-1 py-2 text-sm" id="submitAnswer">Save
                &
                Next</button>
        </div>
    </div>

    <!-- Right div -->
    <div
        class="w-full md:w-[280px] bg-gray-200 flex justify-center items-center text-center text-lg py-3 border-t md:border-t-0 border-gray-300">
        <button id="submitAnswerFinal"
            class=" md:w-auto bg-[#d2e6ff] border border-[#8bb7f0] rounded px-4 py-2 text-sm" >Submit</button>
    </div>
</div>