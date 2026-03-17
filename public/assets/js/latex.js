const input = document.getElementById('latexInput');
const preview = document.getElementById('preview');
const cc_dropdown = document.querySelector('.cc_dropdown');
let historyStack = [''];  // initialize with empty
let historyIndex = 0;
let isProgrammatic = false;

function updatePreview(callback) {
        preview.innerHTML = '\\begin{flalign*}' + input.value + '\\end{flalign*}';
        // preview.innerHTML = input.value ;
        MathJax.typesetPromise([preview]).then(() => {
          if (typeof callback === 'function') {
            callback();  // Only call if it's a valid function
          }
        });
      }
      
      

input.addEventListener('input', updatePreview);

input.addEventListener('input', () => {
        if (!isProgrammatic) {
          historyStack = historyStack.slice(0, historyIndex + 1);
          historyStack.push(input.value);
          historyIndex++;
        }
        updatePreview(); // No callback here — that's fine
      });
      

window.onload = updatePreview;

function downloadSVG() {
        MathJax.typesetPromise([preview]).then(() => {
          var svgElement = preview.querySelector('svg');
          if (svgElement) {
            var svgData = new XMLSerializer().serializeToString(svgElement);
            var blob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
            var url = URL.createObjectURL(blob);
      
            var a = document.createElement('a');
            a.href = url;
            a.download = 'math-expression.svg';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
          } else {
            console.error("MathJax SVG not found.");
          }
        });
      }
      



function toggleVisibilityOnEvent(triggerId, targetId) {
        const trigger = document.getElementById(triggerId);
        const target = document.getElementById(targetId);

        // Toggle on click
        trigger.addEventListener("click", function () {
                target.classList.toggle("cc_show");
        });

        // Show on hover
        trigger.addEventListener("mouseenter", function () {
                target.classList.add("cc_show");
        });

        // Hide when hover ends
        trigger.addEventListener("mouseleave", function () {
                target.classList.remove("cc_show");
        });

        // Optional: Keep target open if mouse enters target
        target.addEventListener("mouseenter", function () {
                target.classList.add("cc_show");
        });
        target.addEventListener("mouseleave", function () {
                target.classList.remove("cc_show");
        });
}

// Apply to your elements

toggleVisibilityOnEvent("accents", "accents_dropdown");
toggleVisibilityOnEvent("accents_ext", "accents_ext_dropdown");
toggleVisibilityOnEvent("arrows", "arrows_dropdown");
toggleVisibilityOnEvent("operators", "operators_dropdown");
toggleVisibilityOnEvent("brackets", "brackets_dropdown");
toggleVisibilityOnEvent("greeklower", "greeklower_dropdown");
toggleVisibilityOnEvent("greekupper", "greekupper_dropdown");
toggleVisibilityOnEvent("relations", "relations_dropdown");
toggleVisibilityOnEvent("colors", "color_dropdown");
toggleVisibilityOnEvent("matrix-2", "matrix_auto");
toggleVisibilityOnEvent("matrix-3", "matrix_auto");
toggleVisibilityOnEvent("style", "style_dropdown");
toggleVisibilityOnEvent("spaces", "spaces_dropdown");
toggleVisibilityOnEvent("binary", "binary_dropdown");
toggleVisibilityOnEvent("symbols", "symbols_dropdown");
toggleVisibilityOnEvent("foreign", "foreign_dropdown");
toggleVisibilityOnEvent("subsupset", "subsupset_dropdown");


// Select all image elements
const images = document.querySelectorAll('img');

// Add click event listener to each image
images.forEach(img => {
        img.addEventListener('click', () => {
                console.log(img.title);

                const start = input.selectionStart;
                const end = input.selectionEnd;
                const text = input.value;

                const title = img.title;
                const insertText = title;
                console.log("Insert Text:", insertText);
                console.log("Start:", start);
                console.log("End:", end);
                console.log("Text:", text);
                InputText(insertText, text, start, end);
        });
});

function InputText(insertText, text, start, end) {
        // Insert the title at cursor position
        input.value = text.slice(0, start) + insertText + text.slice(end);

        // Calculate position of the first pair of braces `{}` after insertion
        const braceStartInTitle = insertText.indexOf('{');

        if (braceStartInTitle !== -1) {
                // If found, move cursor inside {}
                const TextInBrace = insertText.indexOf('Text');
                if (TextInBrace !== -1) {
                        // If found, move cursor inside {Text}
                        const newCaretPos = start + TextInBrace + 4; // 1 position after '{Text}'
                        input.selectionStart = input.selectionEnd = newCaretPos;
                } else if (braceStartInTitle !== -1) {
                        const newCaretPos = start + braceStartInTitle + 1; // 1 position after '{'
                        input.selectionStart = input.selectionEnd = newCaretPos;
                }
        } else {
                // Else, move cursor to end of inserted text
                input.selectionStart = input.selectionEnd = start + insertText.length;
        }

        // Update history for undo
        historyStack = historyStack.slice(0, historyIndex + 1);
        historyStack.push(input.value);
        historyIndex++;
        updatePreview();
        input.focus();
        
};


document.querySelectorAll('.cc-matrix').forEach(elem => {
        elem.addEventListener("click", function () {
                const brackType = this.dataset.value;
                notify(brackType);
        });
});


function notify(brackType) {
        Swal.fire({
                title: 'Insert Matrix',
                html: `
<label for="swal-rows">Rows:</label>
<input id="swal-rows" type="number" min="1" class="swal2-input" placeholder="Enter rows">
<label for="swal-columns">Columns:</label>
<input id="swal-columns" type="number" min="1" class="swal2-input" placeholder="Enter columns">
`,
                showCancelButton: true,
                confirmButtonText: 'Insert',
                cancelButtonText: 'Back',
                preConfirm: () => {
                        const rows = document.getElementById('swal-rows').value;
                        const columns = document.getElementById('swal-columns').value;

                        if (!rows || !columns || rows <= 0 || columns <= 0) {
                                Swal.showValidationMessage('Please enter valid numbers for rows and columns.');
                                return false;
                        }

                        return {
                                rows: parseInt(rows, 10),
                                columns: parseInt(columns, 10)
                        };
                }
        }).then((result) => {
                if (result.isConfirmed) {
                        const { rows, columns } = result.value;
                        console.log("Rows:", rows);
                        console.log("Columns:", columns);
                        // inputtext = '\\begin{pmatrix} a & b & c \\\\ d & e & f \\\\ g & h & i \\end{pmatrix}';
                        inputtext = generateMatrixLatex(rows, columns, brackType);
                        const start = input.selectionStart;
                        const end = input.selectionEnd;
                        const text = input.value;
                        InputText(inputtext, text, start, end);
                        // Call your function here
                        // insertMatrix(rows, columns);
                }
        });
}

function generateMatrixLatex(rows, columns, brackType) {
        const alphabet = 'abcdefghijklmnopqrstuvwxyz';
        let inputtext = '\\begin{' + brackType + '} ';
        let count = 0;

        for (let i = 0; i < rows; i++) {
                let row = [];
                for (let j = 0; j < columns; j++) {
                        row.push(alphabet[count % 26]);
                        count++;
                }
                inputtext += row.join(' & ');
                if (i < rows - 1) {
                        inputtext += ' \\\\ ';
                }
        }

        inputtext += ' \\end{' + brackType + '}';
        return inputtext;
};

document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.key === 'z') {
        e.preventDefault();
        if (historyIndex > 0) {
            historyIndex--;
            isProgrammatic = true;
            input.value = historyStack[historyIndex];
            isProgrammatic = false;
            updatePreview();
        }
    }

    if (e.ctrlKey && (e.key === 'y' || (e.shiftKey && e.key === 'Z'))) {
        e.preventDefault();
        if (historyIndex < historyStack.length - 1) {
            historyIndex++;
            isProgrammatic = true;
            input.value = historyStack[historyIndex];
            isProgrammatic = false;
            updatePreview();
        }
    }

    // if i press space, insert \\
        if (e.key === ' ') {
                e.preventDefault();
                const start = input.selectionStart;
                const end = input.selectionEnd;
                const text = input.value;
                const insertText = "$$\\,$$";
                InputText(insertText, text, start, end);
        }
        if (e.key === 'Enter') {
                e.preventDefault();
                const start = input.selectionStart;
                const end = input.selectionEnd;
                const text = input.value;
                const insertText = "$$\\$$";
                InputText(insertText, text, start, end);
        }
});
