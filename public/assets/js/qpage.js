const input = document.getElementById('imageInput');
const gallery = document.getElementById('gallery');

input.addEventListener('change', () => {
    const files = Array.from(input.files);

    files.forEach((file) => {
        const reader = new FileReader();
        const imageId = `img-${Math.random().toString(36).substr(2, 9)}`;

        // Create container for image with loading indicator
        const container = document.createElement('div');
        container.className = 'relative w-10 h-10 rounded overflow-hidden shadow';

        container.innerHTML = `
            <img id="${imageId}" class="w-full h-full object-cover filter blur-sm transition duration-300 ease-in-out border" />
            <div class="absolute inset-0 flex items-center justify-center bg-black/30" id="overlay-${imageId}">
                <svg class="w-8 h-8 transform -rotate-90">
                    <circle cx="16" cy="16" r="14" class="stroke-gray-300" stroke-width="3" fill="none" />
                    <circle id="progress-${imageId}" cx="16" cy="16" r="14"
                            class="stroke-cyan-500 transition-all duration-300 ease-out"
                            stroke-width="3" fill="none"
                            style="stroke-dasharray: 565; stroke-dashoffset: 565;" />
                </svg>
            </div>
        `;

        gallery.appendChild(container);

        reader.onload = (e) => {
            const img = document.getElementById(imageId);
            img.src = e.target.result;

            const circle = document.getElementById(`progress-${imageId}`);
            const overlay = document.getElementById(`overlay-${imageId}`);

            // 🔴 Upload via XMLHttpRequest with real progress
            const formData = new FormData();
            formData.append('image', file);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/teacher/upload-image', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

            xhr.upload.onprogress = function (event) {
                if (event.lengthComputable) {
                    const percentComplete = (event.loaded / event.total) * 100;
                    const offset = 565 - (percentComplete / 100) * 565;
                    circle.style.strokeDashoffset = offset;
                }
            };

            xhr.onload = function () {
                if (xhr.status === 200) {
                    overlay.classList.add('hidden');
                    img.classList.remove('blur-sm');

                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Upload success:', response);
                    } catch (err) {
                        console.error('Invalid JSON response');
                    }
                } else {
                    console.error('Upload failed:', xhr.responseText);
                }
            };

            xhr.onerror = function () {
                console.error('Upload failed due to network error.');
            };

            xhr.send(formData);
        };

        reader.readAsDataURL(file);
    });
});
