function addInput() {
    const container = document.getElementById("input-container");
    const optioncontainer = document.getElementById("option-container");
    const count = container.children.length + 1;

    const div = document.createElement("div");
    div.className = "flex items-center mb-4";
    div.innerHTML = `
        <label class="text-gray-700 mr-2">${count}.</label>
        <div class="relative w-full">
            <input type="text" class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2"
                placeholder="Type your answer...">
            <button type="button"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer"
                onclick="openCropper()">
                <i class="fas fa-upload"></i>
            </button>
        </div>
    `;

    const optionDiv = document.createElement("div");
    optionDiv.className = "flex items-center mb-4";
    optionDiv.innerHTML = `
        <label for="option1" class="text-gray-700">${count}. </label>
        <input type="radio" name="option1" id="option1" class="m-2 text-xl">
    `;
    container.appendChild(div);
    optioncontainer.appendChild(optionDiv);
}



function removeInput() {
    const container = document.getElementById("input-container");
    const optioncontainer = document.getElementById("option-container");
    if (container.children.length > 1) {
        container.removeChild(container.lastChild);
        optioncontainer.removeChild(optioncontainer.lastChild);
    }
}