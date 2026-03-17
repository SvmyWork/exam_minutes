// MathJax inline configuration
MathJax.Hub.Config({
    tex2jax: {
        inlineMath: [
            ['$', '$'],
            ['\\(', '\\)']
        ]
    }
});

// Custom button function for Trumbowyg - Make it globally accessible
window.customButtonFunction = function() {
    console.log('Custom button clicked!');
    
    // Store reference to current editor for later use
    window.currentEditor = $(this);
    
    // Open the text input popup
    openTextInputPopup();
};

// Function to open the text input popup
function openTextInputPopup() {
    const modal = document.getElementById('textInputModal');
    const textArea = document.getElementById('textInputArea');
    
    // Clear any existing text
    textArea.value = '';
    
    // Show the modal
    modal.style.display = 'flex';
    
    // Focus on the text area
    setTimeout(() => {
        textArea.focus();
    }, 100);
}

// Function to close the text input popup
function closeTextInputPopup() {
    const modal = document.getElementById('textInputModal');
    modal.style.display = 'none';
}

// Function to save text to editor
function saveTextToEditor() {
    const textArea = document.getElementById('textInputArea');
    const text = textArea.value.trim();
    
    if (!text) {
        alert('Please enter some text before saving.');
        return;
    }
    
    if (!window.currentEditor || window.currentEditor.length === 0) {
        console.error('No current editor reference found');
        return;
    }
    
    const $editor = window.currentEditor;
    
    // Get current HTML content from Trumbowyg
    const currentHtml = $editor.trumbowyg('html') || '';
    
    // Append the new text to the current content
    const newHtml = currentHtml + (currentHtml ? '<br>' : '') + text;
    
    // Set the new HTML content using Trumbowyg API
    $editor.trumbowyg('html', newHtml);
    
    // Focus the editor after inserting text
    $editor.focus();
    
    // Close the popup
    closeTextInputPopup();
    
    // Show success message
    console.log('Text saved to editor:', text);
    alert('Text has been added to the editor!');
}


function initializeTextEditor(questionId, optionId, type) {
    // $.trumbowyg.svgPath = 'trumbowyg/dist/ui/icons.svg';
    console.log("Initializing text editor for", type, questionId, optionId);
    let $editor, $buttons, htmlContent;

    if (type === 'question'){
        // It's a question
        let $questionCard = $(`[data-question-id="${questionId}"]`);
        // toolbar = $questionCard.closest('.question-card').find('.toolbar');
        $editor = $questionCard.closest('.question-card').find('.question-editor');
        
        // Get the HTML content to set in the editor
        htmlContent = $editor.html() || '';
        
        $buttons = [
                ['viewHTML'],
                ['undo', 'redo'], // Only supported in Blink browsers
                ['formatting'],
                ['strong', 'em'],
                ['superscript', 'subscript'],
                ['removeformat'],
                ['link'],
                ['image'], // Our fresh created dropdown
                ['customButton'], // Custom button
                ['indent', 'outdent', 'lineheight'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['speechrecognition'],,
                ['mathml'],
                ['fullscreen']
            ];
    }
    else if (type === 'option'){
        // It's an option
        let $optionCard = $(`[data-option-id="${optionId}"]`);
        // let $toolbar = $optionCard.closest('.answer-option').querySelector('.toolbar');
        $editor = $optionCard.closest('.answer-option').find('.option-editor');
        
        // Get the HTML content to set in the editor
        htmlContent = $editor.html() || '';
        
        $buttons = [
                ['viewHTML'],
                ['undo', 'redo'], // Only supported in Blink browsers
                ['formatting'],
                ['strong', 'em'],
                ['superscript', 'subscript'],
                ['removeformat'],
                // ['link'],
                ['image'], // Our fresh created dropdown
                ['customButton'], // Custom button
                ['indent', 'outdent', 'lineheight'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                // ['horizontalRule'],
                ['speechrecognition'],,
                ['mathml'],
                ['fullscreen']
            ];
    }
    else {
        console.log("Unsupported type for text editor initialization:", type);
        return; // Unsupported type
    }


    $(document).ready(function () {
        
        // Initialize Trumbowyg
        $editor.trumbowyg({
            btnsDef: {
                // Create a new dropdown
                image: {
                    dropdown: ['upload', 'insertImage'],
                    title: 'Upload or Insert image URL',
                    ico: 'insertImage'
                },
                // Custom button definition
                customButton: {
                    fn: function() {
                        return window.customButtonFunction.call(this);
                    },
                    title: 'Custom Action',
                    text: 'Code',
                    isSupported: function () { return true; },
                    key: 'C',
                    ico: 'equation', // Use Trumbowyg's built-in icon system
                    class: 'custom-btn',
                    hasIcon: false ,
                    svgPath: 'trumbowyg/dist/ui/icons.svg'
                },
                
            },
            btns: $buttons,
            plugins: {
                // Add imgur parameters to upload plugin for demo purposes
                upload: {
                    serverPath: 'https://api.imgur.com/3/image',
                    fileFieldName: 'image',
                    headers: {
                        'Authorization': 'Client-ID xxxxxxxxxxxx'
                    },
                    urlPropertyName: 'data.link'
                },
                resizimg: {
                    minSize: 64,
                    step: 1,
                }
            },
            // semantic: {
            //     'div': 'div', // Editor does nothing on div tags now
            // },

            // imageWidthModalEdit: true,
            // autogrow: true,
            // autogrowOnEnter: true,
            // removeformatPasted: true,

            // });
            // $('#editor').trumbowyg('disable');
            // $('#editor').trumbowyg('toggle');
            // $('#editor').trumbowyg('destroy');
            // var $modal = $('#editor').trumbowyg('openModal', {
            //     title: 'A title for modal box',
            //     content: '<p>Content in HTML which you want include in created modal box</p>'
            // });
        // $editor.trumbowyg('destroy');
        });

        // Set the HTML content using Trumbowyg API after initialization
        if (htmlContent && htmlContent.trim() !== '') {
            $editor.trumbowyg('html', htmlContent);
            console.log('Set HTML content using Trumbowyg API:', htmlContent);
        }

        // Get the toolbar
        const $toolbar = $editor.closest('.trumbowyg-box').find('.trumbowyg-button-pane');

        // Initially hide the toolbar
        $toolbar.hide();

        // Focus → show toolbar
        $editor.on('tbwfocus', function () {
            $toolbar.stop(true, true).slideDown(0);
        });

        // When editor is blurred → hide toolbar
        $editor.on('tbwblur', function () {
            setTimeout(function () {
                if(!$toolbar.hasClass('trumbowyg-disable')) {
                    $toolbar.stop(true, true).slideUp(0);
                }
            }, 0);
        });

        $editor.on('focus blur', function () {
            if ($toolbar.is(':hidden')) {
            // If toolbar is hidden → remove the class
            $editor.addClass('trumbowyg-box-focus');
            } else {
            // If toolbar is visible → add the class
            $editor.removeClass('trumbowyg-box-focus');
            }
        });
    });

}

// Add event listeners for the text input popup
$(document).ready(function() {
    // Close popup when clicking the X button
    $('#closeTextModal').on('click', function() {
        closeTextInputPopup();
    });
    
    // Close popup when clicking Cancel button
    $('#cancelTextInput').on('click', function() {
        closeTextInputPopup();
    });
    
    // Save text when clicking Save button
    $('#saveTextInput').on('click', function() {
        saveTextToEditor();
    });
    
    // Close popup when clicking outside the modal
    $('#textInputModal').on('click', function(e) {
        if (e.target === this) {
            closeTextInputPopup();
        }
    });
    
    // Handle Enter key in textarea (Ctrl+Enter to save)
    $('#textInputArea').on('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            saveTextToEditor();
        }
    });
});