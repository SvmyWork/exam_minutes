console.log("IndexedDB.js loaded");

// // Open (or create) the database
// let request = indexedDB.open("MySimpleDB", 1);

// request.onupgradeneeded = function(event) {
//     let db = event.target.result;

//     // Create an object store with a key path
//     let store = db.createObjectStore("people", { keyPath: "id" });
//     console.log("Object store created.");
// };

// request.onsuccess = function(event) {
//     let db = event.target.result;

//     // Start a transaction
//     let transaction = db.transaction(["people"], "readwrite");

//     // Access the object store
//     let store = transaction.objectStore("people");

//     // Add a simple object
//     let addRequest = store.add({ id: 1, name: "John Doe", age: 30 });

//     addRequest.onsuccess = function() {
//     console.log("Data added successfully.");
//     };

//     addRequest.onerror = function() {
//     console.error("Failed to add data.");
//     };
// };

// request.onerror = function() {
//     console.error("Database failed to open.");
// };


let request = indexedDB.open("MySimpleDB", 1);

request.onsuccess = function(event) {
    let db = event.target.result;

    // Start a read-only transaction
    let transaction = db.transaction(["people"], "readonly");

    // Access the object store
    let store = transaction.objectStore("people");

    // Get a specific record by its key (id = 1)
    let getRequest = store.get(1);

    getRequest.onsuccess = function() {
    console.log("Retrieved data:", getRequest.result);
    };

    getRequest.onerror = function() {
    console.error("Failed to retrieve data.");
    };
};

request.onerror = function() {
    console.error("Failed to open database.");
};