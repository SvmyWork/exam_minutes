// Your JSON data
// Initialize storage if not already set
if (!localStorage.getItem("trackActivity")) {
  localStorage.setItem("trackActivity", JSON.stringify({ questionNo: [] }));
}

// Function to add a value to the array
function addQuestionNo(value) {
  const data = JSON.parse(localStorage.getItem("trackActivity"));

  data.questionNo.push(value);

  localStorage.setItem("trackActivity", JSON.stringify(data));
  console.log("all question numbers:", data.questionNo);
}


function getLastTwoQuestionNos() {
  const data = JSON.parse(localStorage.getItem("trackActivity"));
  const arr = data?.questionNo || [];

  const last = arr.length >= 1 ? arr[arr.length - 1] : 0;
  const secondLast = arr.length >= 2 ? arr[arr.length - 2] : 0;

  return { last, secondLast };
}

// Function to get all question numbers
function getAllQuestionNos() {
  const data = JSON.parse(localStorage.getItem("trackActivity"));
  const all = data?.questionNo || [];

  // Return unique values only
  const unique = [...new Set(all)];
  return unique;
}



function clearAllQuestionNos() {
  const data = JSON.parse(localStorage.getItem("trackActivity")) || { questionNo: [] };
  data.questionNo = [];
  localStorage.setItem("trackActivity", JSON.stringify(data));
  console.log("All question numbers cleared.");
}



