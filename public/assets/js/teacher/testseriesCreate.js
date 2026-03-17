// public/js/testseries.js

async function createTestSeries(testSeriesName, callbacks = {}) {
    console.log("Creating test series:", testSeriesName);
    // callbacks = { onSuccess: fn, onError: fn }

    if (!testSeriesName || testSeriesName.trim() === "") {
        if (callbacks.onError) callbacks.onError("Please enter a test series name.");
        return;
    }

    try {
        let response = await axios.post(
            "../api/create-test-series",
            { 
                name: testSeriesName, 
                teacher_id: teacher_id, 
                no_of_tests: 0 
            },
            {
                headers: {
                    'Authorization': `Bearer ${token}`, // <-- Add your token here
                    'Content-Type': 'application/json'
                }
            }
        );

        if (callbacks.onSuccess) {
            callbacks.onSuccess(testSeriesName, response);
        }
    } catch (error) {
        if (callbacks.onError) {
            let message = error.response?.data?.message || "Something went wrong.";
            callbacks.onError(message);
        }
    }
}
