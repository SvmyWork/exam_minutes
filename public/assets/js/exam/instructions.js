let db;
let questionsLoaded = false;
let request = indexedDB.open("MySimpleDB", 2);

request.onupgradeneeded = function(event) {
    db = event.target.result;

    if (!db.objectStoreNames.contains("questions")) {
        db.createObjectStore("questions", { keyPath: "id" });
        console.log("Object store 'questions' created.");
    }
};

request.onsuccess = function(event) {
    db = event.target.result;
    console.log("IndexedDB opened successfully.");

    if (db.objectStoreNames.contains("questions")) {
        loadAllQuestions();
    }
};

request.onerror = function() {
    console.error("Failed to open IndexedDB.");
};

function loadAllQuestions() {
    $.ajax({
        url: `../api/teacher/exam/paper`,
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            paperId: paperId
        },
        success: function (response) {
            if (response.status === "success" && response.paper) {
                console.log("Questions fetched:", response.paper);
                saveAllToIndexedDB(response.paper);
                saveimages(response.paper);
                showPopup("It is ready to start your exam", false);
                questionsLoaded = true;
            } else {
                alert("Failed to fetch questions: " + response.message);
                showPopup("Something went wrong. Please reload your page.", true);
            }
        },
        error: function (xhr) {
            console.error("AJAX Error:", xhr.responseText);
            alert("Request failed.");
            showPopup("Something went wrong. Please reload your page.", true);
        }
    });
}

function saveAllToIndexedDB(paper) {
    let transaction = db.transaction(["questions"], "readwrite");
    let store = transaction.objectStore("questions");

    for (const id in paper) {
        const questionData = {
            id: parseInt(id),  // use numeric id
            section: paper[id].section,
            question: paper[id].Q,
            options: paper[id].options,
            qtype: paper[id].qtype,
            anstype: paper[id].anstype,
            timeremain: '',
            timetaken: '00:00',
            status: 0,  // default status
            answer: 0,  // default answer
            uploadtoserver: 0,  // default upload status
        };

        let request = store.put(questionData);
        request.onsuccess = function () {
            console.log(`Saved question ID ${id} to IndexedDB.`);
        };
        request.onerror = function () {
            console.error(`Failed to save question ID ${id}.`);
        };
    }

    transaction.oncomplete = function () {
        console.log("All questions saved to IndexedDB.");
    };
}


async function cacheImage(url) {
    const cache = await caches.open('image-cache');
    const response = await fetch(url);

    if (response.ok) {
        await cache.put(url, response.clone());
        console.log(`Image cached: ${url}`);
    } else {
        console.error('Failed to fetch image:', response.status);
    }
}


function saveimages(paper) {

    const baseAssetUrl = "/assets/images";

    for (const id in paper) {
        const questionData = paper[id];

        if (questionData.qtype === "image") {
            const imageUrl = `${baseAssetUrl}/questions/${questionData.Q}`;
            cacheImage(imageUrl).catch(console.error);
            console.log(`Caching image for question ID ${id}: ${imageUrl}`);
        }

        for (let i = 0; i < questionData.options.length; i++) {
            if (questionData.anstype[i] === "image") {
                const option = questionData.options[i];
                const optionUrl = `${baseAssetUrl}/options/${option}`;
                cacheImage(optionUrl).catch(console.error);
                console.log(`Caching image for question ID ${id}, option ${i}: ${optionUrl}`);
            }
        }
    }

}

function showPopup(message, isError = false) {
    const popup = document.getElementById("popup");
    const msg = document.getElementById("popupMessage");
    const btn = document.getElementById("popupButton");

    msg.textContent = message;

    if (isError) {
        btn.textContent = "Reload";
        btn.className = "bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-md";
        btn.onclick = () => location.reload();
    } else {
        btn.textContent = "OK";
        btn.className = "bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md";
        btn.onclick = () => popup.classList.add("hidden");
    }

    popup.classList.remove("hidden");
}
