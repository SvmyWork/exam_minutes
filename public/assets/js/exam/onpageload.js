window.onload = onPageLoad;

function onPageLoad() {
    console.log("Page loaded. Initializing exam...");

    const allQuestionNos = getAllQuestionNos();
    console.log("All question numbers:", allQuestionNos);

    for (const questionNo of allQuestionNos) {
        updateQuestionButtonStatus(questionNo);
    }
    
}

