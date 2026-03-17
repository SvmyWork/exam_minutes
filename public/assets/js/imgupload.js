


function clearPlaceholder(div) {
    const placeholder = div.querySelector('#placeholder');
    if (placeholder) {
        placeholder.remove(); // removes only the placeholder text
    }
    // You can also show the image if needed
    // div.querySelector('#image').classList.remove('hidden');
}


function showAlert(url) {
    Swal.fire({
        title: "Sweet!",
        text: "Modal with a custom image.",
        imageUrl: url,
        imageWidth: 400,
        imageHeight: 200,
        imageAlt: "Custom image"
      });
}


let cropper;

function openCropper() {
  const editor = document.getElementById('editor');
  const input = document.getElementById('fileInput');
  input.click();

  // Remove any existing listeners
  input.onchange = function () {
    const file = this.files[0];
    if (file) {
      Swal.fire({
        title: 'Select and Crop Image',
        html: `
          <div class="crop-container">
            <img id="imagePreview" style="max-width: 100%; display: none;" />
          </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Crop',
        didOpen: () => {
          const image = document.getElementById('imagePreview');
          const reader = new FileReader();
          reader.onload = function (e) {
            image.src = e.target.result;
            image.style.display = 'block';

            if (cropper) cropper.destroy();
            cropper = new Cropper(image, {
              aspectRatio: null, // allows free cropping
              viewMode: 1,
              autoCropArea: 1,
              responsive: true
            });
          };
          reader.readAsDataURL(file);
        },
        preConfirm: () => {
          const placeholder = editor.querySelector('#placeholder');
          if (placeholder) placeholder.remove();

          if (!cropper) {
            Swal.showValidationMessage('Please select and crop an image');
            return false;
          }

          const canvas = cropper.getCroppedCanvas();
          const croppedImageUrl = canvas.toDataURL();
          const imgPreview = document.getElementById('imgPreview');
          imgPreview.src = croppedImageUrl;
          imgPreview.style.display = 'block';
        },
        willClose: () => {
          if (cropper) {
            cropper.destroy();
            cropper = null;
          }
        }
      });
    }
  };
}
