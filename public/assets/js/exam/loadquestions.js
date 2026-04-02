$(document).ready(function () {
    const metaDataRaw = localStorage.getItem('examMetaData');
    const metaData = JSON.parse(metaDataRaw);
    let secondLast = getLastTwoQuestionNos().secondLast;
    let currentQuestion = (secondLast === 0 || secondLast == null) ? 1 : secondLast;
    let currentSectionIndex = 1;
    let maxQuestions = metaData.SectionWiseTime ? metaData.CurrentSectionTotalQuestion : metaData.TotalQuestion; // Set this to the maximum number of questions you have
    let currentQuestionStatus = 0; // 0: Not Visited, 1: Visited, 2: Answered, 3: Marked for Review, 4: Reviewed
    let QuestionNumber = document.getElementById("QNo");
    const countUpElement = document.getElementById("countup");
    const timerElement = document.getElementById("timer");
    const alertText = document.getElementById("alerttext");
    const currentSection = document.getElementById("currentSection");
    const finalSubmitButton = document.getElementById("submitAnswerFinal");
    const preview = document.getElementById('preview');
    const sectionButtonsContainer = document.getElementById("section-buttons");
    const activeSectionClasses = ["bg-[#007bff]", "text-white"];
    const inactiveSectionClasses = ["bg-[#e7e7e7]", "text-black"];
    const sectionBaseClasses = ["px-4", "py-2", "m-1", "rounded", "transition-colors", "duration-150"];

    // Create section buttons based on metadata
    metaData.SectionName.forEach((sectionName, index) => {
        console.log(`Creating button for section: ${sectionName} with index: ${index + 1}`);
        const button = document.createElement("button");
        button.textContent = sectionName;
        button.classList.add(...sectionBaseClasses);
        if (index === 0) {
            button.classList.add(...activeSectionClasses);
        } else {
            button.classList.add(...inactiveSectionClasses);
        }
        button.setAttribute("data-section", index + 1);
        sectionButtonsContainer.appendChild(button);
    });
    



    console.log(`Total No of question: ${maxQuestions}`);

    function loadQuestion(questionNo, MarkedForReview = false, UpdateStatus = true, SelectedAnswer = false) {
        let request = indexedDB.open("MySimpleDB", 2);

        addQuestionNo(questionNo);

        request.onsuccess = function (event) {
            let db = event.target.result;
            let transaction = db.transaction(["questions"], "readwrite");
            let store = transaction.objectStore("questions");

            const { last, secondLast } = getLastTwoQuestionNos();
            // console.log("Last two question numbers:", last, secondLast);
            let getRequest = store.get(questionNo);

            let PgetRequest = store.get(secondLast);

            getRequest.onsuccess = function () {
                if (!getRequest.result) {
                    console.warn("No more questions found.");
                    $("#question-box").html(`<div class="text-xl">No more questions.</div>`);
                    $("#submitAnswer").prop("disabled", true);
                    return;
                }

                let data = getRequest.result;

                PgetRequest.onsuccess = function () {
                    let Pdata = PgetRequest.result;

                    if (UpdateStatus) {
                        // console.log("UpdateStatus:", UpdateStatus);
                        // console.log("MarkedForReview:", MarkedForReview);
                        // console.log("SelectedAnswer:", SelectedAnswer);
                        try {
                            if (MarkedForReview && Pdata) {
                                Pdata.status = 3; // Marked for review
                                store.put(Pdata); // Optional: save it if you want to persist
                            } else if (!SelectedAnswer) {
                                Pdata.status = 1; // Not visited
                                store.put(Pdata);
                                // } else{
                                //     data.status = 1; // Answered
                                // }
                                // check previously status updated or not 
                            } else if (0 == data.status) {
                                data.status = 1;
                            }
                        } catch (e) {
                            console.error(e);
                        };
                        // console.log("Selected Answer:", SelectedAnswer);
                        // } else {
                        //     data.status = 1; // Not visited
                        // }
                    }

                    renderQuestion(data);
                    QuestionNumber.textContent = questionNo;
                    currentSection.textContent = data.section;

                    // console.log(`metadata Section names: ${metaData.SectionName} and data.section value: ${data.section}`);
                    currentSectionIndex = parseInt(metaData.SectionName.indexOf(data.section)) + 1;
                    // console.log(`section Index: ${currentSectionIndex}`);
                    handleSectionClick(currentSectionIndex);

                    // let updateRequest = store.put(data);

                    // If the question has already been answered, pre-select the answer
                    if (data.answer && data.answer !== "") {
                        // console.log(`Question ${questionNo} already answered with:`, data.answer);
                        const radios = document.querySelectorAll(`input[name="q${questionNo}"]`);

                        // Loop through them and check the one with label "Phloem"
                        radios.forEach(radio => {
                            if (radio.parentElement.textContent.trim() === data.answer) {
                                radio.checked = true;
                            }
                        });
                    }

                    // console.log('Time Remaining:', timerElement.textContent);
                    if (Pdata) {
                        Pdata.timeremain = String(timerElement.textContent); // Save the time remaining for this question as a string
                        // console.log('Time Taken:', countUpElement.textContent);
                        Pdata.timetaken = String(countUpElement.textContent); // Save the time taken for this question
                        store.put(Pdata);
                    }
                    // Update timer if needed
                    // clearInterval(countUpInterval);    // Stop the current timer
                    resetCountUpTimer(data.timetaken);
                    // startCountUp(); // Start a new timer with the previous time taken

                    let updateRequest = store.put(data);


                    updateRequest.onsuccess = function () {
                        console.log("Question status updated successfully.");
                    };
                    updateRequest.onerror = function () {
                        console.error("Failed to update question status.");
                    };
                };

                PgetRequest.onerror = function () {
                    console.warn("Previous question not found.");
                    // You can still render current question even if previous is missing
                    renderQuestion(data);
                    QuestionNumber.textContent = questionNo;
                };

                currentQuestionStatus = data.status; // Update current question status
            };

            getRequest.onerror = function () {
                console.error("Failed to retrieve question data.");
            };
        };

        request.onerror = function () {
            console.error("Failed to open database.");
        };
    }


    async function loadCachedImage(url, elementId) {
        const cache = await caches.open('image-cache');
        const cachedResponse = await cache.match(url);

        if (cachedResponse) {
            const blob = await cachedResponse.blob();
            const imageURL = URL.createObjectURL(blob);

            const imgElement = document.getElementById(elementId);
            if (imgElement) {
                imgElement.src = imageURL;
            } else {
                console.warn(`Image element with ID '${elementId}' not found.`);
            }
        } else {
            console.warn(`Image not found in cache: ${url}`);
        }
    }

    // Initial load
    loadQuestion(currentQuestion);
    updateQuestionButtonStatus(currentQuestion);

    // Select all buttons inside the grid
    const buttons = document.querySelectorAll(".grid button");

    // Add click event to each button
    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const buttonValue = this.textContent.trim(); // Get the button's text
            // console.log("Button clicked:", buttonValue);
            currentQuestion = parseInt(buttonValue); // Convert to integer
            loadQuestion(parseInt(buttonValue), false, false); // Load the question with the clicked button value


            // You can use `buttonValue` as needed here
        });
    });

    // Handle answer submission and load next question
    $(document).on("click", "#submitAnswer", function () {
        const selected = $(`input[name="q${currentQuestion}"]:checked`).val();
        let selectedAnswer = false;
        // Always log the answer, even if none is selected
        // console.log(`Question ${currentQuestion} - Selected answer:`, selected ? selected : "");

        // Optional: warn if nothing is selected
        if (!selected) {
            console.warn(`No answer selected for question ${currentQuestion}`);
            sendAnswertoServer(currentQuestion, "", currentQuestionStatus);
            selectedAnswer = false;
        }
        if (selected) {
            saveAnswer(currentQuestion, selected);
            sendAnswertoServer(currentQuestion, selected, currentQuestionStatus);
            selectedAnswer = true;
        }
        // Load next question
        currentQuestion++;

        if (currentQuestion > maxQuestions) {
            console.warn("No more questions available.", maxQuestions);

            currentQuestion = metaData.SectionWiseTime ? metaData.SectionInitialQuestion[currentSectionIndex - 1] : 1; // Reset to first question or handle as needed

            loadQuestion(currentQuestion, false, true, selectedAnswer);

            updateQuestionButtonStatus(currentQuestion);
            updateQuestionButtonStatus(maxQuestions);
        } else {
            loadQuestion(currentQuestion, false, true, selectedAnswer);

            updateQuestionButtonStatus(currentQuestion);
            updateQuestionButtonStatus(currentQuestion - 1);
        }
    });


    // Handle Mark answer submission and load next question
    $(document).on("click", "#markForReview", function () {
        const selected = $(`input[name="q${currentQuestion}"]:checked`).val();

        // Always log the answer, even if none is selected
        console.log(`Question ${currentQuestion} - Selected answer:`, selected ? selected : "");

        // Optional: warn if nothing is selected
        if (!selected) {
            console.warn(`No answer selected for question ${currentQuestion}`);
            // Load next question
            currentQuestion++;

            if (currentQuestion > maxQuestions) {
                console.warn("No more questions available.");
                currentQuestion = metaData.SectionWiseTime ? metaData.SectionInitialQuestion[currentSectionIndex - 1] : 1; // Reset to first question or handle as needed

                loadQuestion(currentQuestion, true, true, false); // Marked for review

                updateQuestionButtonStatus(currentQuestion);
                updateQuestionButtonStatus(maxQuestions);
            } else {
                loadQuestion(currentQuestion, true, true, false); // Marked for review

                updateQuestionButtonStatus(currentQuestion);
                updateQuestionButtonStatus(currentQuestion - 1);
            }

            sendAnswertoServer(currentQuestion, "", currentQuestionStatus);

        }
        if (selected) {
            saveAnswer(currentQuestion, selected, true); // Marked for review
            sendAnswertoServer(currentQuestion, selected, currentQuestionStatus);

            // Load next question
            currentQuestion++;

            loadQuestion(currentQuestion, false, true, true); // Marked for review

            updateQuestionButtonStatus(currentQuestion);
            updateQuestionButtonStatus(currentQuestion - 1);
        }


    });


    function renderQuestion(data) {
        let html = "";

        if (data.qtype === "text") {
            html += `<div class="mb-4 text-xl">${data.question}</div>`;
        } else if (data.qtype === "image") {
            html += `<div class="mb-4"><img id="questionImage" src="" alt="Question Image" class="w-full max-w-md"></div>`;
            loadCachedImage(`/assets/images/questions/${data.question}`, "questionImage");
        } else if (data.qtype === "markdown") {
            const parsedMarkdownQ = marked.parse(data.question);
            html += `<div class="mb-4 text-xl preview">${parsedMarkdownQ}</div>`;
        } else {
            console.error("Unknown question type:", data.type);
            html += `<div class="text-xl">Unknown question type.</div>`;
            document.getElementById("question-box").innerHTML = html;
            return;
        }

        html += `<div class="space-y-2 text-xl">`;

        data.options.forEach((option, index) => {
            let optionType = "text";
            if (data.anstype && data.anstype[index] === "image") {
                optionType = "image";
            } else if (data.anstype && data.anstype[index] === "markdown") {
                optionType = "markdown";
            }

            const optionId = `optionImage_${data.id}_${index}`;

            if (optionType === "text") {
                html += `<label class="block">
                    <input type="radio" name="q${data.id}" class="mr-2" value="${option}">${option}
                </label>`;
            } else if (optionType === "image") {
                html += `<label class="block flex items-center space-x-2">
                    <input type="radio" name="q${data.id}" class="mr-2" value="${option}">
                    <img id="${optionId}" src="" alt="Option Image" class="h-16 max-w-xs">
                </label>`;
                loadCachedImage(`/assets/images/options/${option}`, optionId);
            } else if (optionType === "markdown") {
                const parsedMarkdownO = marked.parse(option);
                html += `<label class="block flex">
                    <input type="radio" name="q${data.id}" class="mr-2" value="${option}">
                    <span class="preview">${parsedMarkdownO}</span>
                </label>`;
            }
        });

        html += `</div>`;
        document.getElementById("question-box").innerHTML = html;

        // Delay to ensure DOM is updated before MathJax processes
        requestAnimationFrame(() => {
            if (window.MathJax) {
                const previews = document.querySelectorAll(".preview");
                if (previews.length > 0) {
                    MathJax.typesetPromise([...previews]);
                }
            }
        });
    }


    function saveAnswer(questionNo, answer, markedForReview = false) {
        let request = indexedDB.open("MySimpleDB", 2);


        request.onsuccess = function (event) {
            let db = event.target.result;

            // Start a read-write transaction
            let transaction = db.transaction(["questions"], "readwrite");
            let store = transaction.objectStore("questions");


            let getRequest = store.get(questionNo);

            getRequest.onsuccess = function () {
                let result = getRequest.result;
                if (result) {
                    // console.log("Retrieved data for saving answer:", result);
                    result.answer = answer; // Update the answer

                    if (markedForReview) {
                        result.status = 4; // Mark as reviewed
                    } else {
                        result.status = 2; // Mark as answered
                    }

                    result.uploadtoserver = 1; // Mark for upload

                    result.timeremain = String(timerElement.textContent); // Save the time remaining for this question as a string
                    result.timetaken = String(countUpElement.textContent);

                    let updateRequest = store.put(result);

                    updateRequest.onsuccess = function () {
                        console.log(`Answer for question ${questionNo} saved successfully.`);
                    };
                    updateRequest.onerror = function () {
                        console.error(`Failed to save answer for question ${questionNo}.`);
                    };
                } else {
                    console.warn(`No data found for question ${questionNo}.`);
                }
            }
        };
        request.onerror = function () {
            console.error("Failed to open database.");
        };
    }

    async function sendAnswertoServer(questionNo, answer, currentQuestionStatus) {
        let request = indexedDB.open("MySimpleDB", 2);

        request.onsuccess = function (event) {
            let db = event.target.result;

            let transaction = db.transaction(["questions"], "readwrite");
            let store = transaction.objectStore("questions");

            // Fetch current question data
            let getRequest = store.get(questionNo);
            getRequest.onsuccess = async function () {
                let result = getRequest.result;

                if (result) {
                    // console.log("Retrieved data for saving answer:", result);
                    // console.log(`Sending answer for question ${questionNo} to server:`, answer);
                    // console.log('Time Remaining:', timerElement.textContent);
                    // console.log('Time Taken:', countUpElement.textContent);

                    const RemainingTime = String(timerElement.textContent);
                    const TimeTaken = String(countUpElement.textContent);

                    result.answer = answer; // Update the answer

                    result.uploadtoserver = 1; // Mark for upload

                    result.timeremain = String(timerElement.textContent); // Save the time remaining for this question as a string
                    result.timetaken = String(countUpElement.textContent);

                    let updateRequest = store.put(result);

                    updateRequest.onsuccess = function () {
                        console.log(`Answer for question ${questionNo} saved successfully.`);
                    };
                    updateRequest.onerror = function () {
                        console.error(`Failed to save answer for question ${questionNo}.`);
                    };
                    // Fetch all entries where server == 1
                    let serverOnRecords = [];

                    store.openCursor().onsuccess = async function (event) {
                        let cursor = event.target.result;
                        if (cursor) {
                            let record = cursor.value;
                            if (record.uploadtoserver === 1) {
                                serverOnRecords.push(record);
                            }
                            cursor.continue();
                        } else {
                            // All records collected
                            try {
                                const response = await fetch('/api/server', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        questionNo,
                                        syncedRecords: serverOnRecords // added list of all server=1 records
                                    })
                                });

                                if (!response.ok) {
                                    throw new Error(`Server responded with status ${response.status}`);
                                }

                                const data = await response.json();
                                // console.log('Server response:', data);
                                alertText.classList.add("hidden");

                                let updateTransaction = db.transaction(["questions"], "readwrite");
                                let updateStore = updateTransaction.objectStore("questions");

                                serverOnRecords.forEach((record) => {
                                    record.uploadtoserver = 0; // update field
                                    updateStore.put(record); // save updated record
                                });
                            } catch (error) {
                                alertText.classList.remove("hidden");
                                console.error('Fetch error:', error);
                            }
                        }
                    };
                }
            };
        };
    }


    // Attach section button click event
    $("#section-buttons button").on("click", function () {
        const sectionNumber = parseInt($(this).data("section"));
        // Only allow section change if not already on that section
        if (!metaData.SectionWiseTime) {
            handleSectionClick(sectionNumber);
            const SectionIndex = metaData.SectionInitialQuestion[sectionNumber - 1];
            currentQuestion = parseInt(SectionIndex);

            // Load first question of selected section
            loadQuestion(currentQuestion, false, false);

            updateQuestionButtonStatus(currentQuestion);
        }
    });

    function handleSectionClick(sectionNumber) {
        // console.log("Section Number:", sectionNumber);
        showBatch(sectionNumber);

        // Reset all buttons
        $("#section-buttons button")
            .removeClass(`${activeSectionClasses.join(" ")} ${inactiveSectionClasses.join(" ")}`)
            .addClass(inactiveSectionClasses.join(" "));

        // Highlight the selected button
        $(`#section-buttons button[data-section="${sectionNumber}"]`)
            .removeClass(inactiveSectionClasses.join(" "))
            .addClass(activeSectionClasses.join(" "));

    }

    finalSubmitButton.addEventListener("click", function () {
        console.log("Final Submit button clicked.");
        if (confirm("Are you sure you want to submit the exam?")) {
            endExam();
        }
    });

    window.endExam = function () {
        console.log(" Current Section Index:", currentSectionIndex);

        if (metaData.SectionWiseTime) {
            currentSectionIndex++;

            if (currentSectionIndex > metaData.TotalSection) {
                console.log(" All sections completed. Exam fully ended.");
                // You can redirect, submit exam, or show result here
                window.location.href = studentExamEndRoute;


            }

            //  Update section metadata
            metaData.CurrentSectionTotalQuestion = metaData.SectionTotalQuestion[currentSectionIndex - 1];
            metaData.CurrentSectionName = metaData.SectionName[currentSectionIndex - 1];

            // Make sure your browser console is open and not filtering logs
            console.log("Switched to Section:", metaData.CurrentSectionName);
            console.log(" Total Questions in Section:", metaData.CurrentSectionTotalQuestion);

            //  Save updated metaData to localStorage
            localStorage.setItem("examMetaData", JSON.stringify(metaData));

            maxQuestions = metaData.SectionWiseTime ? (metaData.CurrentSectionTotalQuestion + maxQuestions) : metaData.TotalQuestion;

            console.warn(" maxQuestions now:", maxQuestions, currentQuestion);

            //  Update current question
            currentQuestion = metaData.SectionInitialQuestion[currentSectionIndex - 1];
            console.log(" Starting from question:", currentQuestion);

            //  Reset and restart timer
            const savedTime = metaData.SectionWiseTotalTime[currentSectionIndex - 1];
            timerElement.textContent = savedTime;
            localStorage.setItem("examTimer", savedTime);

            const [minStr, secStr] = savedTime.split(":");
            const seconds = parseInt(minStr) * 60 + parseInt(secStr);
            console.log("⏱ Timer restarted with:", seconds, "seconds");

            startTimer(seconds);

            //  Load the question for new section
            loadQuestion(currentQuestion);
            updateQuestionButtonStatus(currentQuestion);
        } else {
            console.log(" All sections completed. Exam fully ended.");

            window.location.href = studentExamEndRoute;

        }
    }

});


function updateQuestionButtonStatus(questionNo) {

    let request = indexedDB.open("MySimpleDB", 2);

    request.onsuccess = function (event) {

        let db = event.target.result;
        let transaction = db.transaction(["questions"], "readonly");
        let store = transaction.objectStore("questions");

        let getRequest = store.get(questionNo);

        getRequest.onsuccess = function (e) {

            let result = e.target.result;
            console.log("Question data for button status update:", result);
            if (result && typeof result.status !== "undefined") {
                // console.log(`Updat for question ${questionNo}`);
                let status = result.status;

                let button = document.querySelector(`.question-btn[data-question='${questionNo}']`);
                if (button) {
                    // console.log(`Updating button for question ${questionNo} with status ${status}`);
                    if (status == 2) {
                        button.style.backgroundImage = "url('../../assets/images/Logo3.png')";
                        button.title = "Answered";
                    } else if (status == 3) {
                        button.style.backgroundImage = "url('../../assets/images/Logo4.png')";
                        button.style.color = "white";
                        button.title = "Marked for Review";
                    } else if (status == 4) {
                        button.style.backgroundImage = "url('../../assets/images/Logo5.png')";
                        button.style.color = "white";
                        button.title = "Answered and Marked for Review";
                    }
                    else {
                        button.style.backgroundImage = "url('../../assets/images/Logo2.png')";
                        button.title = "Not Visited or Not Answered but Visited";
                    }
                }
            }
        };
    };
}


function clearSelectedOptions() {
    const selected = document.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
    selected.forEach(input => input.checked = false);
    // console.log("Cleared selected options.");
}



