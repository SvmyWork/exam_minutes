// Sample data for the form
const sampleQuestions = [];
// const sampleQuestions = [
//     {
//         id: 'q1',
//         title: 'How would you rate our overall service?',
//         type: 'multiple-choice',
//         required: false,
//         options: [
//             { id: 'q1-opt1', text: 'Excellent' },
//             { id: 'q1-opt2', text: 'Good' },
//             { id: 'q1-opt3', text: 'Fair' },
//             { id: 'q1-opt4', text: 'Poor' }
//         ]
//     },
//     {
//         id: 'q2',
//         title: 'Which features do you use most? (Select all that apply)',
//         type: 'checkboxes',
//         required: true,
//         options: [
//             { id: 'q2-opt1', text: 'Online Booking' },
//             { id: 'q2-opt2', text: 'Mobile App' },
//             { id: 'q2-opt3', text: 'Customer Support' }
//         ]
//     },
//     {
//         id: 'q3',
//         title: 'What additional features would you like to see?',
//         type: 'paragraph',
//         required: false
//     },
//     {
//         id: 'q4',
//         title: 'What is your preferred contact method?',
//         type: 'num-answer',
//         required: false
//     },
//     {
//         id: 'q5',
//         title: 'Which age group do you belong to?',
//         type: 'multiple-choice',
//         required: false,
//         options: [
//             { id: 'q5-opt1', text: '18-24' },
//             { id: 'q5-opt2', text: '25-34' },
//             { id: 'q5-opt3', text: '35-44' },
//             { id: 'q5-opt4', text: '45+' }
//         ]
//     }
// ];

// Sample responses data
const sampleResponses = [
    { 0: 'Excellent', 1: ['Online Booking', 'Mobile App'], 2: 'Very likely (9-10)', 3: 'Email', 4: '25-34' },
    { 0: 'Good', 1: ['Online Booking'], 2: 'Likely (7-8)', 3: 'Phone', 4: '35-44' },
    { 0: 'Excellent', 1: ['Mobile App', 'Customer Support'], 2: 'Very likely (9-10)', 3: 'Email', 4: '18-24' },
    { 0: 'Fair', 1: ['Online Booking'], 2: 'Neutral (5-6)', 3: 'Text Message', 4: '25-34' },
    { 0: 'Good', 1: ['Online Booking', 'Mobile App'], 2: 'Likely (7-8)', 3: 'Email', 4: '45+' },
    { 0: 'Excellent', 1: ['Mobile App'], 2: 'Very likely (9-10)', 3: 'Text Message', 4: '18-24' },
    { 0: 'Good', 1: ['Online Booking', 'Customer Support'], 2: 'Likely (7-8)', 3: 'Email', 4: '35-44' },
    { 0: 'Fair', 1: ['Online Booking', 'Mobile App', 'Customer Support'], 2: 'Neutral (5-6)', 3: 'Phone', 4: '25-34' },
    { 0: 'Poor', 1: ['Customer Support'], 2: 'Unlikely (1-4)', 3: 'Email', 4: '45+' },
    { 0: 'Excellent', 1: ['Online Booking', 'Mobile App'], 2: 'Very likely (9-10)', 3: 'Email', 4: '25-34' },
    { 0: 'Good', 1: ['Mobile App'], 2: 'Likely (7-8)', 3: 'Phone', 4: '35-44' },
    { 0: 'Excellent', 1: ['Online Booking'], 2: 'Very likely (9-10)', 3: 'Text Message', 4: '18-24' },
];

// Generate additional responses to reach 48
while (sampleResponses.length < 48) {
    const baseResponse = sampleResponses[sampleResponses.length % 12];
    sampleResponses.push({ ...baseResponse });
}

// Global state
let currentQuestions = [...sampleQuestions];
let currentQuestionIndex = 0;
let currentIndividualIndex = 0;
let currentImageTarget = null;
let selectedFile = null;
// Focus tracking: keep IDs of the currently and previously focused question-card
let previousFocusedQuestionId = null;
let currentFocusedQuestionId = null;

// Undo/Redo state management
let undoStack = [];
let redoStack = [];
const MAX_HISTORY = 50;

function saveState() {
    // Save current state to undo stack
    const state = JSON.parse(JSON.stringify(currentQuestions));
    undoStack.push(state);
    
    // Limit stack size
    if (undoStack.length > MAX_HISTORY) {
        undoStack.shift();
    }
    
    // Clear redo stack when new action is performed
    redoStack = [];
    
    updateUndoRedoButtons();
}

function undo() {
    if (undoStack.length > 0) {
        // Save current state to redo stack
        redoStack.push(JSON.parse(JSON.stringify(currentQuestions)));
        
        // Restore previous state
        currentQuestions = undoStack.pop();
        renderQuestions();
        updateUndoRedoButtons();
    }
}

function redo() {
    if (redoStack.length > 0) {
        // Save current state to undo stack
        undoStack.push(JSON.parse(JSON.stringify(currentQuestions)));
        
        // Restore next state
        currentQuestions = redoStack.pop();
        renderQuestions();
        updateUndoRedoButtons();
    }
}

function updateUndoRedoButtons() {
    const undoBtn = document.querySelector('[title="Undo"]');
    const redoBtn = document.querySelector('[title="Redo"]');
    
    if (undoBtn) {
        undoBtn.disabled = undoStack.length === 0;
        undoBtn.style.opacity = undoStack.length === 0 ? '0.5' : '1';
    }
    
    if (redoBtn) {
        redoBtn.disabled = redoStack.length === 0;
        redoBtn.style.opacity = redoStack.length === 0 ? '0.5' : '1';
    }
}

// Helper to sort questions by sequence
function sortQuestionsBySequence(questions, sequence) {
    if (!sequence || !Array.isArray(sequence) || sequence.length === 0) {
        console.warn('⚠️ No sequence provided for sorting');
        return;
    }
    
    console.log('🔄 Sorting questions based on sequence...');
    console.log('📋 Sequence IDs:', sequence);
    console.log('📌 Questions before sort:', questions.map(q => ({ id: q.id, title: q.title?.substring(0, 30) })));
    
    // Create a map with sequence position
    const sequenceMap = new Map();
    sequence.forEach((id, index) => {
        sequenceMap.set(String(id), index);
    });
    
    console.log('🔑 Sequence map:', Array.from(sequenceMap.entries()));
    
    // Separate questions into two groups
    const questionsInSequence = [];
    const questionsNotInSequence = [];
    
    questions.forEach(q => {
        const qId = String(q.id);
        console.log(`   Checking question ID: "${qId}" - In sequence: ${sequenceMap.has(qId)}`);
        if (sequenceMap.has(qId)) {
            questionsInSequence.push(q);
        } else {
            questionsNotInSequence.push(q);
        }
    });
    
    console.log(`✓ Found ${questionsInSequence.length} questions in sequence`);
    console.log(`✓ Found ${questionsNotInSequence.length} questions NOT in sequence`);
    
    // Sort questions that are in sequence
    questionsInSequence.sort((a, b) => {
        const indexA = sequenceMap.get(String(a.id));
        const indexB = sequenceMap.get(String(b.id));
        return indexA - indexB;
    });
    
    // Replace original array with sorted result
    questions.length = 0;
    questions.push(...questionsInSequence, ...questionsNotInSequence);
    
    console.log('✓ Questions sorted! Final order:', questions.map(q => ({ id: q.id, title: q.title?.substring(0, 30) })));
}

// Load saved data from IndexedDB
async function loadSavedData() {
    try {
        // Clear all IndexedDB data first to ensure fresh sync
        console.log('Clearing IndexedDB before loading fresh data...');
        try {
            await questionStore.clearAllData();
            console.log('IndexedDB cleared successfully');
        } catch (clearErr) {
            console.warn('Failed to clear IndexedDB:', clearErr);
        }

        // First, try to fetch questions from the API
        console.log('Attempting to fetch questions from API...');
        try {
            const token = localStorage.getItem('teacher_token');
            const res = await fetch('/api/get-questions', {
                method: 'POST',
                headers: {
                    'Authorization': token ? `Bearer ${token}` : '',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    teacherId: teacherId,
                    testId: testId
                })
            });

            if (res.ok) {
                const apiResponse = await res.json();
                let apiQuestions = [];

                // Map API response to internal question format
                if (apiResponse && apiResponse.data && Array.isArray(apiResponse.data)) {
                    apiQuestions = apiResponse.data.map(q => ({
                        id: String(q.question_id || q.id),
                        title: q.questionTitle || q.title || '',
                        type: q.questionType || 'multiple-choice',
                        description: q.description || '',
                        position: 0, // will be set after sorting
                        options: q.options || [],
                        imageUrl: q.imageUrl || null,
                        required: q.required !== undefined ? q.required : false,
                        answer: q.answer || null,
                        is_removed: q.is_removed || 0,
                        created_at: q.created_at,
                        updated_at: q.updated_at
                    }));
                } else if (Array.isArray(apiResponse)) {
                    apiQuestions = apiResponse.map(q => ({
                        id: String(q.question_id || q.id),
                        title: q.questionTitle || q.title || '',
                        type: q.questionType || 'multiple-choice',
                        description: q.description || '',
                        position: 0,
                        options: q.options || [],
                        imageUrl: q.imageUrl || null,
                        required: q.required !== undefined ? q.required : false,
                        answer: q.answer || null,
                        is_removed: q.is_removed || 0,
                        created_at: q.created_at,
                        updated_at: q.updated_at
                    }));
                }

                // 🔥 SORT QUESTIONS ACCORDING TO API SEQUENCE
                if (apiResponse.sequence && Array.isArray(apiResponse.sequence)) {
                    console.log('Received question sequence from API:', apiResponse.sequence);
                    
                    // Update global questionSequence
                    questionSequence = apiResponse.sequence;

                    sortQuestionsBySequence(apiQuestions, questionSequence);

                    apiQuestions = apiQuestions.map((q, index) => ({
                        ...q,
                        position: index // 0-based position
                    }));
                }

                if (apiQuestions.length > 0) {
                    currentQuestions = apiQuestions;
                    console.log('Loaded', apiQuestions.length, 'questions from API');

                    // Save API questions to IndexedDB cache
                    console.log('Saving API questions to IndexedDB...');
                    await questionStore.saveQuestions(apiQuestions);
                    console.log('API questions saved to IndexedDB');

                    // Load form data from IndexedDB
                    const savedFormData = await questionStore.getFormData();
                    if (savedFormData && Object.keys(savedFormData).length > 0) {
                        const formTitleInput = document.getElementById('form-title');
                        if (formTitleInput && savedFormData.title) {
                            formTitleInput.value = savedFormData.title;
                        }

                        const formMainTitleInput = document.getElementById('form-main-title');
                        if (formMainTitleInput && savedFormData.mainTitle) {
                            formMainTitleInput.value = savedFormData.mainTitle;
                        }

                        const formDescriptionInput = document.getElementById('form-description');
                        if (formDescriptionInput && savedFormData.description) {
                            formDescriptionInput.value = savedFormData.description;
                        }

                        console.log('Loaded form data from IndexedDB');
                    }

                    return; // ✅ API path successful, skip IndexedDB fallback
                }
            } else {
                console.warn('API returned status:', res.status, '— falling back to IndexedDB');
            }
        } catch (apiErr) {
            console.warn('Failed to fetch questions from API, falling back to IndexedDB:', apiErr);
        }

        // 🔁 FALLBACK: Load saved questions from IndexedDB
        const savedQuestions = await questionStore.getAllQuestions();
        if (savedQuestions && savedQuestions.length > 0) {
            currentQuestions = savedQuestions;
            console.log('Loaded', savedQuestions.length, 'questions from IndexedDB');

            savedQuestions.forEach(q => {
                console.log('Question HTML content:', q.id, q.title);
                if (q.options) {
                    q.options.forEach(opt => {
                        console.log('Option HTML content:', opt.id, opt.text);
                    });
                }
            });
        }

        // Load saved form data from IndexedDB
        const savedFormData = await questionStore.getFormData();
        if (savedFormData && Object.keys(savedFormData).length > 0) {
            const formTitleInput = document.getElementById('form-title');
            if (formTitleInput && savedFormData.title) {
                formTitleInput.value = savedFormData.title;
            }

            const formMainTitleInput = document.getElementById('form-main-title');
            if (formMainTitleInput && savedFormData.mainTitle) {
                formMainTitleInput.value = savedFormData.mainTitle;
            }

            const formDescriptionInput = document.getElementById('form-description');
            if (formDescriptionInput && savedFormData.description) {
                formDescriptionInput.value = savedFormData.description;
            }

            console.log('Loaded form data from IndexedDB');
        }

    } catch (error) {
        console.error('Failed to load saved data:', error);
    }
}


// Save form data to IndexedDB
async function saveFormData() {
    try {
        const formData = {
            title: document.getElementById('form-title')?.value || '',
            mainTitle: document.getElementById('form-main-title')?.value || '',
            description: document.getElementById('form-description')?.value || ''
        };
        
        await autoSaveManager.debouncedSaveFormData(formData);
    } catch (error) {
        console.error('Failed to save form data:', error);
    }
}

// Re-render MathJax content
function reRenderMathJax(element) {
    if (window.MathJax && window.MathJax.Hub) {
        window.MathJax.Hub.Queue(["Typeset", window.MathJax.Hub, element]);
    }
}

// Initialize the application
document.addEventListener('DOMContentLoaded', async function() {
    // Initialize IndexedDB first
    try {
        await questionStore.init();
        console.log('IndexedDB initialized successfully');
        
        // Load saved data
        await loadSavedData();

        // Sort questions based on questionSequence
        if (typeof questionSequence !== 'undefined' && Array.isArray(questionSequence) && questionSequence.length > 0) {
            sortQuestionsBySequence(currentQuestions, questionSequence);
        }

    } catch (error) {
        console.error('Failed to initialize IndexedDB:', error);
    }
    
    initializeTabs();
    initializeQuestions();
    initializeResponses();
    initializeModal();
    initializeSortable();
    initializeThemeColors();
    // initializeImageUpload();
    
    // Add event listeners
    setupEventListeners();
    
    // Save initial state
    // saveState();
    updateUndoRedoButtons();
});

function initializeTabs() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetTab = tab.dataset.tab;
            
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            tab.classList.add('active');
            document.getElementById(`${targetTab}-content`).classList.add('active');
        });
    });
    
    // Response tabs
    const responseTabs = document.querySelectorAll('.response-tab');
    const responseContents = document.querySelectorAll('.response-content');
    
    responseTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetTab = tab.dataset.tab;
            
            responseTabs.forEach(t => t.classList.remove('active'));
            responseContents.forEach(content => content.classList.remove('active'));
            
            tab.classList.add('active');
            document.getElementById(`${targetTab}-content`).classList.add('active');
            
            // Update content when switching tabs
            if (targetTab === 'summary') {
                renderSummaryCharts();
            } else if (targetTab === 'question') {
                renderCurrentQuestion();
            } else if (targetTab === 'individual') {
                renderCurrentIndividual();
            }
        });
    });
}

function initializeQuestions() {
    renderQuestions();
}

function renderQuestions(activeQuestionId) {
    // Before re-rendering, update each question's title with current HTML from DOM to preserve formatting
    currentQuestions.forEach((question) => {
        const questionCard = document.querySelector(`[data-question-id="${question.id}"]`);
        if (questionCard) {
            // For normal questions, get the HTML content from Trumbowyg editor
            if (question.type !== 'section') {
                const editorContent = questionCard.querySelector('.question-editor');
                if (editorContent) {
                    // Get the HTML content using Trumbowyg API method
                    const $editor = $(editorContent);
                    if ($editor.data('trumbowyg')) {
                        const htmlContent = $editor.trumbowyg('html');
                        question.title = htmlContent;
                        console.log('Captured HTML content for question using Trumbowyg API:', question.id, htmlContent);
                    } else {
                        // Fallback to innerHTML if Trumbowyg is not initialized yet
                        const htmlContent = editorContent.innerHTML;
                        question.title = htmlContent;
                        console.log('Captured HTML content for question using innerHTML:', question.id, htmlContent);
                    }
                }
                
                // Also capture option HTML content
                if (question.options) {
                    question.options.forEach((option) => {
                        const optionElement = questionCard.querySelector(`[data-option-id="${option.id}"] .option-editor`);
                        if (optionElement) {
                            const $optionEditor = $(optionElement);
                            if ($optionEditor.data('trumbowyg')) {
                                option.text = $optionEditor.trumbowyg('html');
                                console.log('Captured HTML content for option using Trumbowyg API:', option.id, option.text);
                            } else {
                                // Fallback to innerHTML if Trumbowyg is not initialized yet
                                option.text = optionElement.innerHTML;
                                console.log('Captured HTML content for option using innerHTML:', option.id, option.text);
                            }
                        }
                    });
                }
            } else {
                // For sections, preserve section-title and description
                const sectionTitle = questionCard.querySelector('.section-title');
                if (sectionTitle) {
                    question.title = sectionTitle.innerHTML;
                }
                const descElement = questionCard.querySelector('.section-description');
                if (descElement) {
                    question.description = descElement.innerHTML;
                }
            }
        }
    });

    // Create question elements and append to container
    const container = document.getElementById('questions-container');
    container.innerHTML = '';

    let q_number = 0; // Question number for display

    currentQuestions.forEach((question, index) => {
        if (question.type !== 'section') {
            q_number = q_number+1; 
        }
        const questionElement = createQuestionElement(question, index, q_number);
        container.appendChild(questionElement);

        const questionCard = document.querySelector(`[data-question-id="${question.id}"]`);

        
        if (question.type !== 'section') { //To Do: Ankan: In future, it should initialize text editor for sections as well
            initializeTextEditor(questionId=question.id, optionId= null, type='question');
            if(question.options && question.options.length > 0){
                question.options.forEach((option) => {
                    initializeTextEditor(questionId=question.id, optionId=option.id, type='option');
                });
            }
            
            // Attach option change listeners for localStorage
            attachOptionChangeListeners(question.id);
            
            // Re-render MathJax after content is loaded
            setTimeout(() => {
                if (window.MathJax && window.MathJax.Hub) {
                    window.MathJax.Hub.Queue(["Typeset", window.MathJax.Hub, questionCard]);
                }
            }, 100);
        }
        

        // questionElement.onfocus = () => {highlightQuestion(question.id); move_cursor_to_end(questionTitle)}
        // questionElement.onblur = () => unhighlightQuestion(question.id);
        
    });
    // If an active question ID is provided, scroll to and focus that question
    if(activeQuestionId) {
        const activeQuestionCard = document.querySelector(`[data-question-id="${activeQuestionId}"]`);
        // const toolbar = activeQuestionCard.closest('.question-card').querySelector('.toolbar');
        // const editor = activeQuestionCard.closest('.question-card').querySelector('.editor-content');

        if(activeQuestionCard) {
            activeQuestionCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Focus the question title
            const questionTitle = activeQuestionCard.closest('.question-card').querySelector('.question-editor, .section-title');
            if (questionTitle) {
                setTimeout(() => {
                    questionTitle.focus();
                    move_cursor_to_end(questionTitle);
                    // toolbar.classList.remove('toolbar-hidden');
                    // editor.classList.remove('toolbar-hidden');
                }, 0); // Use setTimeout to ensure focus after rendering
            }
        }
    }
}

function createQuestionElement(question, index, q_number) {
    const div = document.createElement('div');
    div.className = 'question-card';
    div.dataset.questionId = question.id;
    div.setAttribute('tabindex', '0'); // Make div focusable
    
    // const questionCard = document.querySelector(`[data-question-id="${question.id}"]`);
    // const questionTitle = questionCard.closest('.question-card').querySelector('.question-title');
    // div.onfocus = () => {questionTitle.focus(); move_cursor_to_end(questionTitle)}
    // div.onblur = () => unhighlightQuestion(question.id);


    // Handle sections differently
    if (question.type === 'section') {
        div.innerHTML = `
            <div class="question-content">
                <i class="material-icons drag-handle">drag_indicator</i>
                <div class="question-main">
                    <div class="section-header">
                        <div class="section-title" contenteditable="true" 
                             placeholder="Section title" 
                             onblur="updateQuestionTitle('${question.id}', this.innerHTML); unhighlightQuestion('${question.id}')"
                             onfocus="highlightQuestion('${question.id}')">${question.title}</div>
                        <div class="section-description" contenteditable="true" 
                             placeholder="Add a description (optional)" 
                             onblur="updateSectionDescription('${question.id}', this.innerHTML); unhighlightQuestion('${question.id}')"
                             onfocus="highlightQuestion('${question.id}')">${question.description || ''}</div>
                    </div>
                    
                    <div class="question-footer">
                        <div class="question-actions">
                            <button class="option-btn" onclick="duplicateQuestion('${question.id}')" title="Duplicate">
                                <i class="material-icons">content_copy</i>
                            </button>
                            <button class="option-btn" onclick="deleteQuestion('${question.id}')" title="Delete">
                                <i class="material-icons">delete</i>
                            </button>
                            <div class="divider"></div>
                            <button class="option-btn" title="More options">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="hover-section">
                <button class="hover-btn" onmousedown="addQuestionAfter(${index})" title="Add question">
                    <i class="material-icons" style="font-size: 16px;">add</i>
                    Question
                </button>
                <button class="section-btn" onmousedown="addSectionAfter(${index})" title="Add section">
                    <i class="material-icons" style="font-size: 16px;">add</i>
                    Section
                </button>
            </div>
        `;

        return div;
    }
    
    const optionsHtml = question.type === 'multiple-choice' || question.type === 'checkboxes' 
        ? question.options.map(option => `
            <div class="answer-option">
                ${question.type === 'checkboxes'
                ? `<input type="checkbox" name="option-${question.id}" id="${option.id}" style="zoom: 1.3;" ${option.answer ? 'checked' : ''}>`
                : `<input type="radio" name="option-${question.id}" id="${option.id}" style="zoom: 1.3;" ${option.answer ? 'checked' : ''}>`
                }

                <div class="editor-container" data-option-id="${option.id}">
                    <div class="option-editor" contenteditable="true" 
                     onblur="updateOption('${question.id}', '${option.id}', this.innerHTML); unhighlightQuestion('${question.id}')"
                     onfocus="highlightQuestion('${question.id}')">${option.text || ''}
                    </div>
                </div>
                <button class="option-btn" onmousedown="removeOption('${question.id}', '${option.id}')" title="Remove option">
                    <i class="material-icons">close</i>
                </button>
            </div>
        `).join('') : '';
    
    const addOptionHtml = question.type === 'multiple-choice' || question.type === 'checkboxes'
        ? `<div class="answer-option">
            <i class="material-icons option-icon">${question.type === 'checkboxes' ? 'check_box_outline_blank' : 'radio_button_unchecked'}</i>
            <button class="add-option-btn" onclick="addOption('${question.id}')">Add option</button>
        </div>` : '';
    
    const isRange = question.answer && question.answer.toString().includes(' - ');
    const rangeValues = isRange ? question.answer.split(' - ') : ['', ''];
    const simpleValue = !isRange ? question.answer : '';

    const answerFieldHtml =
        question.type === 'num-answer' || question.type === 'paragraph'
            ? `
            <div class="answer-option">

                <!-- Mode Selector -->
                <select class="answer-mode" onchange="toggleRangeInputs(this, '${question.id}')"
                    style="margin-bottom:8px; padding:6px; font-size:14px;">
                    <option value="simple" ${!isRange ? 'selected' : ''}>Simple</option>
                    <option value="range" ${isRange ? 'selected' : ''}>Range</option>
                </select>

                <!-- Simple Input -->
                <div class="simple-input" style="display: ${!isRange ? 'block' : 'none'};">
                    ${
                    question.type === 'num-answer'
                        ? `<input type="number" placeholder="Value" value="${simpleValue || ''}"
                            class="num-answer-simple"
                            oninput="updateNumericalAnswer('${question.id}')"
                            style="border:none;border-bottom:1px solid #e0e0e0;background:transparent;
                            padding:8px 8px;font-size:14px;color:#5f6368;width:100%;">`
                        : `<textarea placeholder="Paragraph text" 
                            style="border:1px solid #e0e0e0;border-radius:4px;padding:8px;
                            font-size:14px;color:#5f6368;background:transparent;
                            resize:vertical;min-height:80px;width:100%;"></textarea>`
                    }
                </div>

                <!-- Range Inputs -->
                <div class="range-inputs" style="display: ${isRange ? 'flex' : 'none'}; gap:8px;">
                    <input type="number" placeholder="Min" value="${rangeValues[0] || ''}"
                        class="num-answer-min"
                        oninput="updateNumericalAnswer('${question.id}')"
                        style="flex:1;padding:8px;font-size:14px;">
                    <input type="number" placeholder="Max" value="${rangeValues[1] || ''}"
                        class="num-answer-max"
                        oninput="updateNumericalAnswer('${question.id}')"
                        style="flex:1;padding:8px;font-size:14px;">
                </div>

            </div>
            `
            : '';

    
    div.innerHTML = `
        <div class="question-content">
            <i class="material-icons drag-handle">drag_handle</i>
            <div class="question-main">
                <div class="question-header">
                    <div><strong>Q-${q_number}.&nbsp;</strong></div>
                    <div class="question-title" data-question-id="${question.id}">
                        <div class="editor-container">

                            <div class="question-editor" contenteditable="true" placeholder="Type your question here" 
                                onblur="updateQuestionTitle('${question.id}', this.innerHTML); unhighlightQuestion('${question.id}')"
                                onfocus="highlightQuestion('${question.id}')"
                                onkeyup="updateFormatButtons('${question.id}')"
                                onmouseup="updateFormatButtons('${question.id}')"
                            >${question.title || ''}</div>
                        </div>
                    </div>
                    <div class="question-controls">
                        <select class="question-type-select" onchange="updateQuestionType('${question.id}', this.value)">
                            <option value="multiple-choice" ${question.type === 'multiple-choice' ? 'selected' : ''}>Multiple choice</option>
                            <option value="checkboxes" ${question.type === 'checkboxes' ? 'selected' : ''}>Checkboxes</option>
                            <option value="num-answer" ${question.type === 'num-answer' ? 'selected' : ''}>Numerical answer</option>
                            <option value="paragraph" ${question.type === 'paragraph' ? 'selected' : ''}>Paragraph</option>
                        </select>
                    </div>
                </div>
                ${question.imageUrl ? `<div class="question-image"><img src="${question.imageUrl}" alt="Question image"></div>` : ''}
                <div class="answer-options">
                    ${optionsHtml}
                    ${addOptionHtml}
                    ${answerFieldHtml}
                </div>
                <div class="question-footer">
                    <div class="question-actions">
                        <button class="option-btn" onmousedown="duplicateQuestion('${question.id}');" title="Duplicate">
                            <i class="material-icons">content_copy</i>
                        </button>
                        <button class="option-btn" onmousedown="deleteQuestion('${question.id}')" title="Delete">
                            <i class="material-icons">delete</i>
                        </button>
                        <div class="divider"></div>
                        <button class="option-btn" title="More options">
                            <i class="material-icons">more_vert</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="hover-section">
            <button class="hover-btn" onmousedown="addQuestionAfter(${index})" title="Add question">
                <i class="material-icons" style="font-size: 16px;">add</i>
                Question
            </button>
            <button class="section-btn" onmousedown="addSectionAfter(${index})" title="Add section">
                <i class="material-icons" style="font-size: 16px;">add</i>
                Section
            </button>
        </div>
    `;
    
    return div;
}

function toggleRangeInputs(select, questionId) {
    const container = select.closest('.answer-option');
    const simpleInput = container.querySelector('.simple-input');
    const rangeInputs = container.querySelector('.range-inputs');

    if (select.value === 'range') {
        simpleInput.style.display = 'none';
        rangeInputs.style.display = 'flex';
    } else {
        simpleInput.style.display = 'block';
        rangeInputs.style.display = 'none';
    }
    
    // Trigger update to save the change in format (e.g., converting "5" to "5 - " or resetting)
    // We defer this slightly to let the UI settle, or call it directly.
    updateNumericalAnswer(questionId);
}

function updateNumericalAnswer(questionId) {
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    if (!questionCard) return;

    const modeSelect = questionCard.querySelector('.answer-mode');
    const mode = modeSelect ? modeSelect.value : 'simple';
    
    let answer = '';

    if (mode === 'range') {
        const minInput = questionCard.querySelector('.num-answer-min');
        const maxInput = questionCard.querySelector('.num-answer-max');
        const min = minInput ? minInput.value : '';
        const max = maxInput ? maxInput.value : '';
        
        // Save as "Max-Min" as requested by user (actually "Min - Max" logic, but user said "save amsawer as Max-Min")
        // User clarification: "if answer in range then save amsawer as Max-Min" comes from "if answer in range then save amsawer as Max-Min"
        // Standard range is Min-Max. I will stick to Min - Max for logical consistency unless strictly told otherwise.
        // Actually, looking at the user request: "save amsawer as Max-Min". 
        // This could mean "Max minus Min" (a number) OR string "Max - Min".
        // Given it's a "Range", usually you want to know the bounds.
        // I will save as "Min - Max" string to preserve both bounds.
        answer = `${min} - ${max}`;
    } else {
        const simpleInput = questionCard.querySelector('.num-answer-simple');
        answer = simpleInput ? simpleInput.value : '';
    }

    // Update in-memory object
    const question = currentQuestions.find(q => q.id === questionId);
    if (question) {
        question.answer = answer;
        console.log(`Updated answer for Q${questionId}:`, answer);
        
        // Auto-save
        autoSaveManager.debouncedSave(question);
    }
}


// Enhanced text formatting functions
function toggleFormat(command, questionId) {
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    const questionTitle = questionCard.closest('.question-card').querySelector('.question-title');
    
    // Focus the question title if not already focused
    if (document.activeElement !== questionTitle) {
        questionTitle.focus();
    }
    
    // Apply formatting
    document.execCommand(command, false, null);
    
    // Update format button states
    updateFormatButtons(questionId);
    
    // Update question data
    updateQuestionTitle(questionId, questionTitle.innerHTML);
}

function updateFormatButtons(questionId) {
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    if (!questionCard) return;
    
    const formatButtons = questionCard.querySelectorAll('.format-btn');
    
    formatButtons.forEach(button => {
        const format = button.dataset.format;
        const isActive = document.queryCommandState(format);
        
        if (isActive) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}

// Question highlighting functions
function highlightQuestion(questionId) {
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    // const formatControls = questionCard.closest('.question-card').querySelector('.format-controls');

    if (questionCard) {
        // initializeTextEditor(questionId); // Ankan: To Do
        questionCard.classList.add('editing');
        // formatControls.classList.remove('hidden');

    }
}

function unhighlightQuestion(questionId) {
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    // const formatControls = questionCard.closest('.question-card').querySelector('.format-controls');

    if (questionCard) {
        questionCard.classList.remove('editing');
        // formatControls.classList.add('hidden'); // Ankan: To Do 
    }
}

function highlightFormHeader() {
    const formHeader = document.querySelector('.form-header-card');

    if (formHeader) {
        formHeader.classList.add('editing');
        // move_cursor_to_end(formTitle);
    }
}

function unhighlightFormHeader() {
    const formHeader = document.querySelector('.form-header-card');
    if (formHeader) {
        formHeader.classList.remove('editing');
    }
}

function move_cursor_to_end(element) {
    const range = document.createRange();
    const sel = window.getSelection();
    range.selectNodeContents(element);
    range.collapse(false);
    sel.removeAllRanges();
    sel.addRange(range);
    element.focus();
    // console.log("Cursor moved to end");
}

// Attach event listeners to option checkboxes/radios to update localStorage
function attachOptionChangeListeners(questionId) {
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    if (!questionCard) return;
    
    // Get all radio buttons and checkboxes for this question
    const optionInputs = questionCard.querySelectorAll(`input[name="option-${questionId}"]`);
    
    optionInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const optionId = input.id;
            const isChecked = input.checked;
            const answer = isChecked ? 1 : 0;
            
            // Update localStorage with the answer value
            const storageKey = `option-${questionId}-${optionId}`;
            localStorage.setItem(storageKey, JSON.stringify({
                questionId: questionId,
                optionId: optionId,
                answer: answer,
                timestamp: new Date().toISOString()
            }));
            
            console.log(`Option ${optionId} checked state changed to ${isChecked}, answer = ${answer}`);
            
            // Also update the question object in memory
            const question = currentQuestions.find(q => q.id === questionId);
            if (question) {
                if (question.options) {
                    const option = question.options.find(opt => opt.id === optionId);
                    if (option) {
                        option.answer = answer; // Keep this for legacy/other logic
                    }
                }

                // NEW: Save answer as list of option IDs
                const checkedInputs = questionCard.querySelectorAll(`input[name="option-${questionId}"]:checked`);
                const selectedIds = Array.from(checkedInputs).map(inp => inp.id);
                
                question.answer = selectedIds;
                console.log(`Updated question.answer to list of IDs for Q${questionId}:`, question.answer);
                
                autoSaveManager.debouncedSave(question);
            }
        });
    });
}

// Small helpers to expose current/previous focused question-card IDs
function getCurrentFocusedQuestionId() {
    return currentFocusedQuestionId;
}

function getPreviousFocusedQuestionId() {
    return previousFocusedQuestionId;
}

// Attach to window for quick external access if needed
window.getCurrentFocusedQuestionId = getCurrentFocusedQuestionId;
window.getPreviousFocusedQuestionId = getPreviousFocusedQuestionId;

// Collect a question's current data from DOM (falls back to in-memory data)
function getQuestionDataFromDOM(questionId) {
    // Normalize questionId: it may be an index (number or numeric-string) or an actual id string.
    let resolvedQuestion = null;
    let resolvedId = questionId;

    // If a numeric index was passed (e.g. 0), resolve it to the question id from currentQuestions
    if (typeof questionId === 'number' || (/^\d+$/.test(String(questionId)))) {
        const idx = Number(questionId);
        resolvedQuestion = currentQuestions[idx] || null;
        if (resolvedQuestion) resolvedId = resolvedQuestion.id;
    }

    // If not resolved yet, try to find by id
    if (!resolvedQuestion) {
        resolvedQuestion = currentQuestions.find(q => q.id === questionId) || null;
    }

    // Fallback snapshot if we didn't find a matching question object
    const question = resolvedQuestion || { id: resolvedId };
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);

    // Compute position if not provided on the question object.
    // position is 0-based index (from API or IndexedDB)
    let positionIndex = question.position;
    if (positionIndex == null) {
        const foundIndex = currentQuestions.findIndex(q => q.id === questionId);
        positionIndex = foundIndex !== -1 ? foundIndex : null;
    }

    
    const result = {
        teacherId: teacherId,
        testSeriesId: testSeriesId,
        testId: testId, 
        questionId: String(question.id),
        position: positionIndex, // 0-based index for server
        type: question.type || null,
        title: question.title || null,
        description: question.description || null,
        options: question.options ? question.options.map(opt => ({ id: opt.id, text: opt.text, imageUrl: opt.imageUrl, answer: opt.answer})) : undefined,
        imageUrl: question.imageUrl || null,
        answer: question.answer !== undefined ? question.answer : null,
        // attach timestamp for server-side auditing
        _sentAt: Date.now()
    };

    if (!questionCard) {
        // No DOM element available — return the best-known in-memory snapshot
        return result;
    }

    // Get title/section content (prefer  editor HTML if present)
    const titleElement = questionCard.querySelector('.question-editor, .section-title');
    if (titleElement) {
        try {
            const $editor = $(titleElement);
            if ($editor.data && $editor.data('trumbowyg')) {
                result.title = $editor.trumbowyg('html');
            } else {
                result.title = titleElement.innerHTML;
            }
        } catch (err) {
            // If jQuery not available or trumbowyg call fails, fallback
            result.title = titleElement.innerHTML;
        }
    }

    // Section description
    const descElement = questionCard.querySelector('.section-description');
    if (descElement) {
        result.description = descElement.innerHTML;
    }

    // Options (if present): capture current DOM HTML for each option and compute `answer` (0/1)
    if (result.options && Array.isArray(result.options)) {
        result.options = result.options.map(opt => {
            const optEditorEl = questionCard.querySelector(`[data-option-id="${opt.id}"] .option-editor`);
            let text = opt.text;
            if (optEditorEl) {
                try {
                    const $opt = $(optEditorEl);
                    if ($opt.data && $opt.data('trumbowyg')) {
                        text = $opt.trumbowyg('html');
                    } else {
                        text = optEditorEl.innerHTML;
                    }
                } catch (err) {
                    text = optEditorEl.innerHTML;
                }
            }

            // Determine checked state from the input element (radio/checkbox) inside the question card
            let answer = 0;
            try {
                const inputEl = questionCard.querySelector(`#${opt.id}`);
                if (inputEl && (inputEl.type === 'radio' || inputEl.type === 'checkbox')) {
                    answer = inputEl.checked ? 1 : 0;
                } else if (opt.answer !== undefined) {
                    answer = opt.answer ? 1 : 0;
                } else {
                    answer = 0;
                }
            } catch (err) {
                answer = opt.answer ? 1 : 0;
            }

            return { id: opt.id, text: text, imageUrl: opt.imageUrl, answer: answer };
        });
    }

    // Answer for numerical/paragraph questions
    if (question.type === 'num-answer') {
        const modeSelect = questionCard.querySelector('.answer-mode');
        const mode = modeSelect ? modeSelect.value : 'simple';
        
        if (mode === 'range') {
            const minInput = questionCard.querySelector('.num-answer-min');
            const maxInput = questionCard.querySelector('.num-answer-max');
            const min = minInput ? minInput.value : '';
            const max = maxInput ? maxInput.value : '';
            result.answer = `${min} - ${max}`;
        } else {
            const simpleInput = questionCard.querySelector('.num-answer-simple');
            result.answer = simpleInput ? simpleInput.value : '';
        }
    } else if (question.type === 'paragraph') {
         // for paragraph, we might not have a specific 'answer' field to save as correct answer, 
         // but if we did, we would capture it here. 
         // For now, leaving it as per existing logic (or lack thereof if it wasn't handled).
         // The original code passed 'answer' from the object if it existed.
         result.answer = question.answer || null;
    } else if (question.type === 'multiple-choice' || question.type === 'checkboxes') {
        const checkedInputs = questionCard.querySelectorAll(`input[name="option-${question.id}"]:checked`);
        result.answer = Array.from(checkedInputs).map(inp => inp.id);
    }
    // For multiple-choice and checkboxes, answer is already set from listofAnswers above
    // Do not overwrite it with null

    // Image on question card
    const imgEl = questionCard.querySelector('.question-image img');
    if (imgEl) {
        result.imageUrl = imgEl.src;
    }

    return result;
}

// Send a question snapshot to server endpoint ./test-post
async function sendQuestionToServer(questionId) {
    if (!questionId) return;

    const payload = getQuestionDataFromDOM(questionId);

    try {
        const res = await fetch('/api/save-question', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('teacher_token')}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (res.ok) {
            console.log(`Successfully posted question ${questionId} to .api`);
        } else {
            console.error(`Failed to post question ${questionId} to .api:`, res.status, res.statusText);
        }
    } catch (err) {
        console.error(`Error posting question ${questionId} to .api:`, err);
    }
}

// Question management functions
function updateQuestionTitle(questionId, title) {
    const question = currentQuestions.find(q => q.id === questionId);
    if (question) {
        // Get the HTML content using Trumbowyg API
        const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
        if (questionCard) {
            const editorContent = questionCard.querySelector('.question-editor');
            if (editorContent) {
                const $editor = $(editorContent);
                if ($editor.data('trumbowyg')) {
                    const htmlContent = $editor.trumbowyg('html');
                    question.title = htmlContent;
                    console.log('Updated question title with Trumbowyg API:', questionId, htmlContent);
                } else {
                    // Fallback to the passed title if Trumbowyg is not initialized
                    question.title = title;
                    console.log('Updated question title with HTML:', questionId, title);
                }
            } else {
                question.title = title;
                console.log('Updated question title with HTML:', questionId, title);
            }
        } else {
            // Store the HTML content (including MathJax) as-is
            question.title = title;
            console.log('Updated question title with HTML:', questionId, title);
        }
        // Auto-save to IndexedDB
        autoSaveManager.debouncedSave(question);
    }
}

function updateSectionDescription(questionId, description) {
    const question = currentQuestions.find(q => q.id === questionId);
    if (question) {
        question.description = description;
        // Auto-save to IndexedDB
        autoSaveManager.debouncedSave(question);
    }
}

function updateQuestionType(questionId, type) {
    const question = currentQuestions.find(q => q.id === questionId);
    if (question) {
        question.type = type;
        
        // Add default options for multiple choice and checkboxes
        if ((type === 'multiple-choice' || type === 'checkboxes') && !question.options) {
            question.options = [
                { id: `${questionId}-opt1`, text: 'Option 1' }
            ];
        }
        
        // Auto-save to IndexedDB
        autoSaveManager.debouncedSave(question);
        
        // Re-render questions to update the UI
        renderQuestions(activeQuestionId=questionId);
    }
}

function updateQuestionRequired(questionId, required) {
    const question = currentQuestions.find(q => q.id === questionId);
    if (question) {
        question.required = required;
        // Auto-save to IndexedDB
        autoSaveManager.debouncedSave(question);
    }
}

function addOption(questionId) {
    const question = currentQuestions.find(q => q.id === questionId);
    if (question && question.options) {
        const newOptionId = `${questionId}-opt${question.options.length + 1}`;
        question.options.push({
            id: newOptionId,
            text: `Option ${question.options.length + 1}`
        });
        
        // Auto-save to IndexedDB
        autoSaveManager.debouncedSave(question);
        
        renderQuestions();
    }
}

function updateOption(questionId, optionId, text) {
    const question = currentQuestions.find(q => q.id === questionId);
    if (question && question.options) {
        const option = question.options.find(opt => opt.id === optionId);
        if (option) {
            // Get the HTML content using Trumbowyg API
            const optionElement = document.querySelector(`[data-option-id="${optionId}"] .option-editor`);
            if (optionElement) {
                const $optionEditor = $(optionElement);
                if ($optionEditor.data('trumbowyg')) {
                    const htmlContent = $optionEditor.trumbowyg('html');
                    option.text = htmlContent;
                    console.log('Updated option text with Trumbowyg API:', optionId, htmlContent);
                } else {
                    // Fallback to the passed text if Trumbowyg is not initialized
                    option.text = text;
                    console.log('Updated option text with HTML:', optionId, text);
                }
            } else {
                // Store the HTML content (including MathJax) as-is
                option.text = text;
                console.log('Updated option text with HTML:', optionId, text);
            }
            // Auto-save to IndexedDB
            autoSaveManager.debouncedSave(question);
        }
    }
}

function removeOption(questionId, optionId) {
    const question = currentQuestions.find(q => q.id === questionId);
    if (question && question.options) {
        question.options = question.options.filter(opt => opt.id !== optionId);
        
        // Auto-save to IndexedDB
        autoSaveManager.debouncedSave(question);
        
        renderQuestions();
    }
}

// Save a question object directly to server (bypassing DOM)
async function saveQuestionToServerFromObject(question) {
    if (!question) return;

    const payload = {
        teacherId: teacherId,
        testSeriesId: testSeriesId,
        testId: testId,
        questionId: String(question.id),
        position: currentQuestions.findIndex(q => q.id === question.id),
        type: question.type || null,
        title: question.title || null,
        description: question.description || null,
        options: question.options ? question.options.map(opt => ({ 
            id: opt.id, 
            text: opt.text, 
            imageUrl: opt.imageUrl, 
            answer: opt.answer 
        })) : undefined,
        imageUrl: question.imageUrl || null,
        answer: question.answer !== undefined ? question.answer : null,
        _sentAt: Date.now()
    };

    try {
        const res = await fetch('/api/save-question', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('teacher_token')}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (res.ok) {
            console.log(`Successfully posted new question ${question.id} to .api`);
        } else {
            console.error(`Failed to post new question ${question.id} to .api:`, res.status, res.statusText);
        }
    } catch (err) {
        console.error(`Error posting new question ${question.id} to .api:`, err);
    }
}

function duplicateQuestion(questionId) {
    console.log("Duplicating question:", questionId);
    const question = currentQuestions.find(q => q.id === questionId);
    if (question) {
        // saveState();
        
        // Get the actual HTML content from the DOM element to preserve formatting
        const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
        let actualTitle = question.title;
        let actualDescription = question.description;
        
        if (questionCard) {
            // const titleElement = questionCard.querySelector('.question-title, .section-title');
            const titleElement = questionCard.querySelector('.question-editor, .section-title');
            const descriptionElement = questionCard.querySelector('.section-description');
            
            if (titleElement) {
                actualTitle = titleElement.innerHTML;
                console.log("Actual Title from DOM:", actualTitle);
                // Update the original question's title to preserve formatting
                question.title = actualTitle;
            }
            if (descriptionElement) {
                actualDescription = descriptionElement.innerHTML;
                // Update the original question's description to preserve formatting
                question.description = actualDescription;
            }
        }
        
        const newQuestion = {
            ...question,
            id: `q${Date.now()}`,
            title: actualTitle, // Preserve HTML formatting from DOM
            description: actualDescription, // Preserve description formatting for sections
            options: question.options ? question.options.map(opt => ({
                ...opt,
                id: `q${Date.now()}-${opt.id.split('-').pop()}`
            })) : undefined
        };
        
        const index = currentQuestions.findIndex(q => q.id === questionId);
        currentQuestions.splice(index + 1, 0, newQuestion);
        
        // Auto-save all questions to IndexedDB
        autoSaveManager.immediateSave(currentQuestions);
        
        // Save to backend immediately
        saveQuestionToServerFromObject(newQuestion);
        updateQuestionSequenceAPI();

        renderQuestions(activeQuestionId=newQuestion.id);
    }
}

function deleteQuestion(questionId) {
    // saveState();

    // Fire-and-forget: inform backend to delete this question
    try {
        const payload = {
            teacherId: teacherId,
            testSeriesId: testSeriesId,
            testId: testId,
            questionId: String(questionId)
        };

        fetch('/api/delete-question', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('teacher_token')}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        }).then(res => {
            if (res.ok) {
                console.log('Server deleted question:', questionId);
                // After delete, update sequence too? 
                // Since deleteQuestion endpoint handles logic, we might not strictly need to send sequence update 
                // unless we want to remove the ID from the sequence array on server.
                // It is safer to update sequence after local removal.
                updateQuestionSequenceAPI();
            } else {
                console.warn('Server failed to delete question:', res.status, res.statusText);
            }
        }).catch(err => {
            console.error('Error calling /api/delete-question:', err);
        });
    } catch (err) {
        console.error('Failed to prepare delete request:', err);
    }

    // Remove locally and save state regardless of server response
    currentQuestions = currentQuestions.filter(q => q.id !== questionId);

    // Auto-save all questions to IndexedDB
    autoSaveManager.immediateSave(currentQuestions);

    renderQuestions();
}

function addQuestionAfter(index) {
    console.log("Adding question after index:", index);
    const newQuestion = {
        id: `q${Date.now()}`,
        title: `Question`,
        type: 'multiple-choice',
        required: false,
        options: [
            { id: `q${Date.now()}-opt1`, text: 'Option 1' }
        ]
    };
    
    currentQuestions.splice(index + 1, 0, newQuestion);
    
    // Auto-save all questions to IndexedDB
    autoSaveManager.immediateSave(currentQuestions);

    // Save to backend immediately
    saveQuestionToServerFromObject(newQuestion);
    updateQuestionSequenceAPI();
    
    renderQuestions(activeQuestionId=newQuestion.id);
}

function addSectionAfter(index) {
    const newSection = {
        id: `sec${Date.now()}`,
        title: 'Untitled Section',
        description: 'Add a description (optional)',
        type: 'section',
        required: false
    };
    
    currentQuestions.splice(index + 1, 0, newSection);
    
    // Auto-save all questions to IndexedDB
    autoSaveManager.immediateSave(currentQuestions);

    // Save to backend immediately
    saveQuestionToServerFromObject(newSection);
    updateQuestionSequenceAPI();
    
    renderQuestions(activeQuestionId=newSection.id);
    // renderQuestions();
}

function addQuestionAtIndex(index) {
    // saveState();
    
    const newQuestion = {
        id: `q${Date.now()}`,
        title: `Question`,
        type: 'multiple-choice',
        required: false,
        options: [
            { id: `q${Date.now()}-opt1`, text: 'Option 1' }
        ]
    };

    currentQuestions.splice(index, 0, newQuestion);
    
    // Auto-save all questions to IndexedDB
    autoSaveManager.immediateSave(currentQuestions);
    
    // Save to backend immediately
    saveQuestionToServerFromObject(newQuestion);
    updateQuestionSequenceAPI();

    renderQuestions(activeQuestionId=newQuestion.id);
}

function addSectionAtIndex(index) {
    // saveState();
    
    const newSection = {
        id: `section${Date.now()}`,
        title: 'Untitled Section',
        description: 'Add a description (optional)',
        type: 'section',
        required: false
    };
    
    currentQuestions.splice(index, 0, newSection);
    
    // Auto-save all questions to IndexedDB
    autoSaveManager.immediateSave(currentQuestions);
    
    // Save to backend immediately
    saveQuestionToServerFromObject(newSection);
    updateQuestionSequenceAPI();

    renderQuestions(activeQuestionId=newSection.id);
}

// Event listeners setup
function setupEventListeners() {
    // Form header input listeners for auto-save
    const formTitleInput = document.getElementById('form-title');
    const formMainTitleInput = document.getElementById('form-main-title');
    const formDescriptionInput = document.getElementById('form-description');
    
    if (formTitleInput) {
        formTitleInput.addEventListener('input', saveFormData);
    }
    
    if (formMainTitleInput) {
        formMainTitleInput.addEventListener('input', saveFormData);
    }
    
    if (formDescriptionInput) {
        formDescriptionInput.addEventListener('input', saveFormData);
    }
    
    // // Add question button
    // document.getElementById('add-question-btn').addEventListener('click', () => {
    //     const newQuestion = {
    //         id: `q${Date.now()}`,
    //         title: '',
    //         type: 'multiple-choice',
    //         required: false,
    //         options: [
    //             { id: `q${Date.now()}-opt1`, text: 'Option 1' }
    //         ]
    //     };
        
    //     saveState();
    //     currentQuestions.push(newQuestion);
    //     renderQuestions();
    // });
    
    // // Add section button
    // document.getElementById('add-section-btn').addEventListener('click', () => {
    //     const newSection = {
    //         id: `section${Date.now()}`,
    //         title: 'Untitled Section',
    //         description: 'Add a description (optional)',
    //         type: 'section',
    //         required: false
    //     };
        
    //     saveState();
    //     currentQuestions.push(newSection);
    //     renderQuestions();
    // });
    
    // Response navigation - prev/next buttons
    document.getElementById('prev-question')?.addEventListener('click', () => {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            renderCurrentQuestion();
            document.getElementById('question-input').value = currentQuestionIndex + 1;
        }
    });
    
    document.getElementById('next-question')?.addEventListener('click', () => {
        if (currentQuestionIndex < currentQuestions.length - 1) {
            currentQuestionIndex++;
            renderCurrentQuestion();
            document.getElementById('question-input').value = currentQuestionIndex + 1;
        }
    });
    
    document.getElementById('prev-individual')?.addEventListener('click', () => {
        if (currentIndividualIndex > 0) {
            currentIndividualIndex--;
            renderCurrentIndividual();
            document.getElementById('individual-input').value = currentIndividualIndex + 1;
        }
    });
    
    document.getElementById('next-individual')?.addEventListener('click', () => {
        if (currentIndividualIndex < sampleResponses.length - 1) {
            currentIndividualIndex++;
            renderCurrentIndividual();
            document.getElementById('individual-input').value = currentIndividualIndex + 1;
        }
    });
    
    // Add event listeners for input fields with Enter key support and change events
    document.getElementById('question-input')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            const value = parseInt(e.target.value) - 1;
            if (value >= 0 && value < currentQuestions.length) {
                currentQuestionIndex = value;
                renderCurrentQuestion();
            }
        }
    });
    
    document.getElementById('question-input')?.addEventListener('change', (e) => {
        const value = parseInt(e.target.value) - 1;
        if (value >= 0 && value < currentQuestions.length) {
            currentQuestionIndex = value;
            renderCurrentQuestion();
        }
    });
    
    document.getElementById('individual-input')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            const value = parseInt(e.target.value) - 1;
            if (value >= 0 && value < sampleResponses.length) {
                currentIndividualIndex = value;
                renderCurrentIndividual();
            }
        }
    });
    
    document.getElementById('individual-input')?.addEventListener('change', (e) => {
        const value = parseInt(e.target.value) - 1;
        if (value >= 0 && value < sampleResponses.length) {
            currentIndividualIndex = value;
            renderCurrentIndividual();
        }
    });

    // Focus tracking for .question-card elements (keeps previous and current IDs)
    const questionsContainer = document.getElementById('questions-container');
    if (questionsContainer) {
        // Use focusin so we catch focus on contenteditable children and inputs
        questionsContainer.addEventListener('focusin', (e) => {
            const card = e.target.closest('.question-card');
            if (card) {
                previousFocusedQuestionId = currentFocusedQuestionId;
                currentFocusedQuestionId = card.dataset.questionId || null;
                console.log('question focus changed: previous=', previousFocusedQuestionId, 'current=', currentFocusedQuestionId);

                // If we had a previous focused question, send its current snapshot to server
                if (previousFocusedQuestionId) {
                    // fire-and-forget; server sync of the previous question
                    sendQuestionToServer(previousFocusedQuestionId);
                }

                // Dispatch a custom event so other parts of the app can react
                document.dispatchEvent(new CustomEvent('questionFocusChanged', {
                    detail: { previous: previousFocusedQuestionId, current: currentFocusedQuestionId }
                }));
            }
        });

        // Ensure clicking a card focuses it (useful when clicking non-focusable parts)
        // But avoid stealing focus when clicking inside interactive elements
        questionsContainer.addEventListener('click', (e) => {
            const card = e.target.closest('.question-card');
            if (!card) return;

            // If the click happened inside an editor, toolbar, or other interactive element,
            // don't move focus to the card itself — this prevents the editor losing focus on mouseup.
            const interactive = e.target.closest(
                '.question-editor, .option-editor, .section-title, .section-description, .question-title, .section-header, .editor-container, .trumbowyg-box, .trumbowyg-button-pane, .trumbowyg-editor, input, textarea, select, option, button, a, label'
            );
            if (interactive) {
                return;
            }

            if (document.activeElement !== card) {
                card.focus();
            }
        });
    }
}

// Response rendering functions
function initializeResponses() {
    renderSummaryCharts();
    renderCurrentQuestion();
    renderCurrentIndividual();
}

function renderSummaryCharts() {
    const container = document.getElementById('summary-charts');
    container.innerHTML = '';
    
    currentQuestions.forEach((question, index) => {
        if (question.type === 'multiple-choice' || question.type === 'checkboxes') {
            const chartDiv = document.createElement('div');
            chartDiv.className = 'chart-container';
            
            const responses = sampleResponses.map(response => response[index]).filter(Boolean);
            const optionCounts = {};
            
            // Count responses for each option
            question.options.forEach(option => {
                optionCounts[option.text] = 0;
            });
            
            responses.forEach(response => {
                if (Array.isArray(response)) {
                    response.forEach(item => {
                        if (optionCounts[item] !== undefined) {
                            optionCounts[item]++;
                        }
                    });
                } else {
                    if (optionCounts[response] !== undefined) {
                        optionCounts[response]++;
                    }
                }
            });
            
            const maxCount = Math.max(...Object.values(optionCounts));
            
            chartDiv.innerHTML = `
                <div class="chart-title">${question.title}</div>
                <div class="chart-bars">
                    ${Object.entries(optionCounts).map(([option, count]) => `
                        <div class="chart-bar">
                            <div class="bar-label">${option}</div>
                            <div class="bar-visual">
                                <div class="bar-fill" style="width: ${maxCount > 0 ? (count / maxCount) * 100 : 0}%"></div>
                            </div>
                            <div class="bar-count">${count}</div>
                        </div>
                    `).join('')}
                </div>
            `;
            
            container.appendChild(chartDiv);
        }
    });
}

function renderCurrentQuestion() {
    const container = document.getElementById('current-question-data');
    const question = currentQuestions[currentQuestionIndex];
    
    if (!question) return;
    
    const responses = sampleResponses.map(response => response[currentQuestionIndex]).filter(Boolean);
    
    // Only show question response breakdown for multiple choice and checkbox questions
    if (question.type === 'multiple-choice' || question.type === 'checkboxes') {
        const optionCounts = {};
        
        // Initialize option counts
        if (question.options) {
            question.options.forEach(option => {
                optionCounts[option.text] = 0;
            });
        }
        
        // Count responses
        responses.forEach(response => {
            if (Array.isArray(response)) {
                response.forEach(item => {
                    if (optionCounts[item] !== undefined) {
                        optionCounts[item]++;
                    }
                });
            } else {
                if (optionCounts[response] !== undefined) {
                    optionCounts[response]++;
                }
            }
        });
        
        container.innerHTML = `
            <div class="question-response-container">
                <div class="question-response-title">${question.title}</div>
                <div class="response-options-list">
                    ${Object.entries(optionCounts).map(([option, count]) => `
                        <div class="response-option-item">
                            <span class="option-text">${option}</span>
                            <span class="response-count">${count} response${count !== 1 ? 's' : ''}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    } else {
        // For text-based questions (paragraph, num-answer), show response counts for unique answers
        const responseCounts = {};
        
        // Count unique responses
        responses.forEach(response => {
            const responseText = Array.isArray(response) ? response.join(', ') : response;
            if (responseText && responseText.trim()) {
                responseCounts[responseText] = (responseCounts[responseText] || 0) + 1;
            }
        });
        
        // Sort by count (descending) and show top responses
        const sortedResponses = Object.entries(responseCounts)
            .sort(([,a], [,b]) => b - a)
            .slice(0, 10);
        
        container.innerHTML = `
            <div class="question-response-container">
                <div class="question-response-title">${question.title}</div>
                <div class="response-options-list">
                    ${sortedResponses.map(([response, count]) => `
                        <div class="response-option-item">
                            <span class="option-text">${response}</span>
                            <span class="response-count">${count} response${count !== 1 ? 's' : ''}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
}

function renderCurrentIndividual() {
    const container = document.getElementById('current-individual-data');
    const response = sampleResponses[currentIndividualIndex];
    
    if (!response) return;
    
    container.innerHTML = `
        <div class="individual-response">
            ${currentQuestions.map((question, index) => `
                <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #f0f0f0;">
                    <div style="font-weight: 500; margin-bottom: 8px;">${question.title}</div>
                    <div style="color: #5f6368;">
                        ${response[index] ? (Array.isArray(response[index]) ? response[index].join(', ') : response[index]) : 'No response'}
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Modal functions
function initializeModal() {
    const modal = document.getElementById('image-modal');
    const closeBtn = modal.querySelector('.modal-close');
    const cancelBtn = document.getElementById('cancel-upload');
    const uploadBtn = document.getElementById('upload-image');
    const fileInput = document.getElementById('file-input');
    const chooseFileBtn = document.getElementById('choose-file-btn');
    const uploadZone = document.getElementById('upload-zone');
    
    // closeBtn.addEventListener('click', closeImageModal);
    // cancelBtn.addEventListener('click', closeImageModal);
    
    chooseFileBtn.addEventListener('click', () => fileInput.click());
    
    // fileInput.addEventListener('change', handleFileSelect);
    
    // Drag and drop
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });
    
    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('dragover');
    });
    
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });
    
    // uploadBtn.addEventListener('click', uploadImageToQuestion);
    
    // Close modal when clicking outside
    // modal.addEventListener('click', (e) => {
    //     if (e.target === modal) {
    //         closeImageModal();
    //     }
    // });
}

// function openImageModal(questionId, optionId = null) {
//     console.log('Opening image modal for:', questionId, optionId);
//     currentImageTarget = { questionId, optionId };
//     document.getElementById('image-modal').style.display = 'block';
//     resetImageModal();
// }

// function closeImageModal() {
//     document.getElementById('image-modal').style.display = 'none';
//     const fileInput = document.getElementById('file-input');
//     if (fileInput) fileInput.value = '';
//     resetImageModal();
// }

// function handleFileSelect(e) {
//     const file = e.target.files[0];
//     if (file) {
//         handleFile(file);
//     }
// }

// function handleFile(file) {
//     if (file.type.startsWith('image/')) {
//         selectedFile = file;
//         const reader = new FileReader();
//         reader.onload = (e) => {
//             document.getElementById('preview-img').src = e.target.result;
//             document.getElementById('image-preview').style.display = 'block';
//             document.getElementById('upload-zone').style.display = 'none';
//             document.getElementById('upload-image').disabled = false;
//         };
//         reader.readAsDataURL(file);
//     }
// }

// Sortable initialization
function initializeSortable() {
    const container = document.getElementById('questions-container');
    console.log("Initializing Sortable on container:", container);
    
    new Sortable(container, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            // Update the questions array order
            const movedQuestion = currentQuestions.splice(evt.oldIndex, 1)[0];
            currentQuestions.splice(evt.newIndex, 0, movedQuestion);
            
            // Auto-save question positions to IndexedDB
            autoSaveManager.immediateSave(currentQuestions);
            
            renderQuestions(activeQuestionId=movedQuestion.id);

            // Sync new sequence with backend
            updateQuestionSequenceAPI();
        }
    });
}

// Function to update question sequence on the server
async function updateQuestionSequenceAPI() {
    try {
        const sequence = currentQuestions.map(q => q.id);
        console.log('Updating question sequence with positions:', sequence);
        console.log('Current questions order:', currentQuestions.map((q, idx) => ({ idx, id: q.id, title: q.title })));
        
        const token = localStorage.getItem('teacher_token');
        const response = await fetch('/api/update-sequence', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': token ? `Bearer ${token}` : ''
            },
            body: JSON.stringify({
                teacherId: teacherId,
                testSeriesId: testSeriesId,
                testId: testId,
                sequence: sequence
            })
        });

        if (response.ok) {
            console.log('✓ Question sequence updated successfully at positions:', sequence);
            const result = await response.json();
            if (result.message) {
                console.log('Server response:', result.message);
            }
        } else {
            console.warn('✗ Failed to update question sequence:', response.status, response.statusText);
            const errorData = await response.json();
            console.error('Error details:', errorData);
        }
    } catch (error) {
        console.error('Error updating question sequence:', error);
    }
}

// Theme color functionality
function initializeThemeColors() {
    const colorOptions = document.querySelectorAll('.color-option');
    
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            colorOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Apply theme color
            const color = this.dataset.color;
            applyThemeColor(color);
        });
    });
}

function applyThemeColor(color) {
    // Update CSS custom properties
    document.documentElement.style.setProperty('--primary-color', color);
    document.documentElement.style.setProperty('--primary-light', color + '20');
    
    // Store the selected color in localStorage
    localStorage.setItem('themeColor', color);
}

// Image upload functionality

// function initializeImageUpload() {
//     // Load saved theme color
//     const savedColor = localStorage.getItem('themeColor');
//     if (savedColor) {
//         applyThemeColor(savedColor);
//         // Update the active color option
//         const colorOption = document.querySelector(`[data-color="${savedColor}"]`);
//         if (colorOption) {
//             document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('active'));
//             colorOption.classList.add('active');
//         }
//     }
// }

// function openImageModal(questionId) {
//     currentImageTarget = questionId;
//     document.getElementById('image-modal').style.display = 'block';
//     resetImageModal();
// }

// function resetImageModal() {
//     document.getElementById('upload-zone').style.display = 'block';
//     document.getElementById('image-preview').style.display = 'none';
//     document.getElementById('upload-image').disabled = true;
//     selectedFile = null;
// }

// function uploadImageToQuestion() {
//     console.log('Upload button clicked. Selected file:', selectedFile, 'Target:', currentImageTarget);
    
//     if (selectedFile && currentImageTarget) {
//         const reader = new FileReader();
//         reader.onload = function(e) {
//             const imageUrl = e.target.result;
//             console.log('Image loaded, URL length:', imageUrl.length);
            
//             if (currentImageTarget.optionId) {
//                 console.log('here1');
//                 // Update option image
//                 const question = currentQuestions.find(q => q.id === currentImageTarget.questionId);
//                 if (question && question.options) {
//                     const option = question.options.find(opt => opt.id === currentImageTarget.optionId);
//                     if (option) {
//                         option.imageUrl = imageUrl;
//                         console.log('Updated option image for:', currentImageTarget.optionId);
//                     }
//                 }
//             } else {
//                 // Update question image
//                 console.log('here2');
//                 const question = currentQuestions.find(q => q.id === currentImageTarget.questionId);
//                 console.log(currentImageTarget);
//                 if (question) {
//                     console.log('here3');
//                     question.imageUrl = imageUrl;
//                     console.log('Updated question image for:', currentImageTarget.questionId);
//                 }
//             }
            
//             renderQuestions();
//             saveState();
            
//             // Close modal
//             closeImageModal();
//             currentImageTarget = null;
//         };
//         reader.readAsDataURL(selectedFile);
//     } else {
//         console.log('Missing file or target:', { selectedFile, currentImageTarget });
//     }
// }


