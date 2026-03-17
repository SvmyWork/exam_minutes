{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Forms Clone</title>
    
    <!-- <link rel="stylesheet" href="textinput.css"> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/ui/trumbowyg.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/mathml/ui/trumbowyg.mathml.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/teacher/test-editor.css') }}">
    <link rel="stylesheet" href="textinput.css">
</head>
<body>
    <div class="app">
        <!-- Header -->
        <header class="header">
            <div class="header-container">
                <div class="header-left">
                    <i class="material-icons header-icon">description</i>
                    <div class="header-title-section">
                        <input type="text" id="form-title" class="header-title-input" value="Class 11 Test Series" placeholder="Untitled form">
                        <!-- <div class="header-subtitle">Description (optional)</div> -->
                    </div>
                </div>
                <div class="header-right">
                    <button class="icon-btn" title="Undo" onclick="undo()">
                        <i class="material-icons">undo</i>
                    </button>
                    <button class="icon-btn" title="Redo" onclick="redo()">
                        <i class="material-icons">redo</i>
                    </button>
                    <!-- <button class="send-btn" id="send-btn">
                        <i class="material-icons">send</i>
                        Send
                    </button> -->
                    <!-- <button class="icon-btn" title="More">
                        <i class="material-icons">more_vert</i>
                    </button> -->
                </div>
            </div>
        </header> --}}
@extends('teacher.layouts.layout')
@push('head')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/ui/trumbowyg.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/mathml/ui/trumbowyg.mathml.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/teacher/test-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/teacher/textinput.css') }}">
@endpush
@section('content')
<div class="app h-screen w-full  overflow-y-auto max-h-screen">
        <!-- Header -->
        <header class="header">
            <div class="header-container">
                <div class="header-left">
                    <i class="material-icons header-icon">description</i>
                    <div class="header-title-section">
                        <input type="text" id="form-title" class="header-title-input" value="{{ $test_name }}" placeholder="Untitled form">
                        <!-- <div class="header-subtitle">Description (optional)</div> -->
                    </div>
                </div>
                <div class="header-right">
                    <button class="icon-btn" title="Undo" onclick="undo()">
                        <i class="material-icons">undo</i>
                    </button>
                    <button class="icon-btn" title="Redo" onclick="redo()">
                        <i class="material-icons">redo</i>
                    </button>
                    <!-- <button class="send-btn" id="send-btn">
                        <i class="material-icons">send</i>
                        Send
                    </button> -->
                    <!-- <button class="icon-btn" title="More">
                        <i class="material-icons">more_vert</i>
                    </button> -->
                </div>
            </div>
        </header>
        <!-- Main Content -->
        <div class="main-container">
            <!-- Tabs -->
            <div class="tabs-container">
                <nav class="tabs">
                    <button class="tab active" data-tab="questions">Questions</button>
                    <button class="tab" data-tab="responses">Responses</button>
                    <button class="tab" data-tab="settings">Settings</button>
                </nav>
            </div>

            <!-- Questions Tab -->
            <div id="questions-content" class="tab-content active">
                <!-- Form Header -->
                <div class="form-header-card" tabindex="0" onfocus="highlightFormHeader();document.querySelector('.form-main-title').focus();" onblur="unhighlightFormHeader()">
                    <div class="form-header-border">
                        <input type="text" id="form-main-title" class="form-main-title" value="{{ $test_name }}" placeholder="Form title" 
                               onfocus="highlightFormHeader()" onblur="unhighlightFormHeader()">
                        <textarea id="form-description" class="form-description" placeholder="Form description" 
                                  onfocus="highlightFormHeader()" onblur="unhighlightFormHeader()">Test Series Name: {{ $test_series_name }}</textarea>
                    </div>
                </div>

                <!-- Add buttons between header and questions -->
                <div class="header-add-controls" tabindex="0" onfocus="highlightFormHeader();document.querySelector('.form-main-title').focus();" onblur="unhighlightFormHeader()">
                    <button class="hover-btn" onclick="addQuestionAtIndex(0)" title="Add question">
                        <i class="material-icons" style="font-size: 16px;">add</i>
                        Question
                    </button>
                    <button class="section-btn" onclick="addSectionAtIndex(0)" title="Add section">
                        <i class="material-icons" style="font-size: 16px;">add</i>
                        Section
                    </button>
                </div>

                <!-- Questions Container -->
                <div id="questions-container" class="questions-container">
                    <!-- Questions will be dynamically added here -->
                </div>

                <!-- Add Controls -->
                <!-- <div class="add-controls">
                    <button id="add-question-btn" class="add-question-btn" title="Add question">
                        <i class="material-icons">add</i>
                    </button>
                    <button id="add-section-btn" class="add-section-btn">
                        <i class="material-icons">add</i>
                        Add Section
                    </button>
                </div> -->
            </div>

            <!-- Responses Tab -->
            <div id="responses-content" class="tab-content">
                <div class="responses-card">
                    <!-- Responses Header -->
                    <div class="responses-header">
                        <div class="responses-header-left">
                            <h2 class="responses-count">48 responses</h2>
                            <label class="switch">
                                <input type="checkbox" id="accepting-responses" checked>
                                <span class="slider"></span>
                            </label>
                            <span class="switch-label">Accepting responses</span>
                        </div>
                        <div class="responses-header-right">
                            <button class="view-sheets-btn">
                                <i class="material-icons">table_chart</i>
                                View in Sheets
                            </button>
                            <button class="icon-btn">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </div>
                    </div>

                    <!-- Response Sub-tabs -->
                    <div class="response-tabs-container">
                        <nav class="response-tabs">
                            <button class="response-tab active" data-tab="summary">Summary</button>
                            <button class="response-tab" data-tab="question">Question</button>
                            <button class="response-tab" data-tab="individual">Individual</button>
                        </nav>
                    </div>

                    <!-- Summary Content -->
                    <div id="summary-content" class="response-content active">
                        <div id="summary-charts"></div>
                    </div>

                    <!-- Question Content -->
                    <div id="question-content" class="response-content">
                        <div class="question-nav">
                            <h3 class="question-nav-title">
                                Question 
                                <input type="number" id="question-input" class="nav-input" value="1" min="1" max="5">
                                of 5
                            </h3>
                            <div class="nav-controls">
                                <button id="prev-question" class="nav-btn">
                                    <i class="material-icons">chevron_left</i>
                                </button>
                                <button id="next-question" class="nav-btn">
                                    <i class="material-icons">chevron_right</i>
                                </button>
                            </div>
                        </div>
                        <div id="current-question-data" class="current-question-data"></div>
                    </div>

                    <!-- Individual Content -->
                    <div id="individual-content" class="response-content">
                        <div class="individual-nav">
                            <h3 class="individual-nav-title">
                                Response 
                                <input type="number" id="individual-input" class="nav-input" value="1" min="1" max="48">
                                of 48
                            </h3>
                            <div class="nav-controls">
                                <button id="prev-individual" class="nav-btn">
                                    <i class="material-icons">chevron_left</i>
                                </button>
                                <button id="next-individual" class="nav-btn">
                                    <i class="material-icons">chevron_right</i>
                                </button>
                            </div>
                        </div>
                        <div id="current-individual-data" class="current-individual-data"></div>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div id="settings-content" class="tab-content">
                <div class="settings-card">
                    <h2 class="settings-title">Settings</h2>
                    
                    <div class="settings-section">
                        <h3 class="settings-section-title">Access</h3>
                        <div class="settings-options">
                            <!-- <label class="setting-item">
                                <span>Collect email addresses</span>
                                <label class="switch">
                                    <input type="checkbox" id="collect-email">
                                    <span class="slider"></span>
                                </label>
                            </label>
                            <label class="setting-item">
                                <span>Limit to 1 response</span>
                                <label class="switch">
                                    <input type="checkbox" id="limit-response" checked>
                                    <span class="slider"></span>
                                </label>
                            </label> -->
                        </div>
                    </div>

                    <div class="settings-section">
                        <h3 class="settings-section-title">General</h3>
                        <div class="settings-options">
                            <label class="setting-item">
                                <span>Collect email addresses</span>
                                <label class="switch">
                                    <input type="checkbox" id="collect-email">
                                    <span class="slider"></span>
                                </label>
                            </label>
                            <label class="setting-item">
                                <span>Limit to 1 response</span>
                                <label class="switch">
                                    <input type="checkbox" id="limit-response" checked>
                                    <span class="slider"></span>
                                </label>
                            </label>
                        </div>
                    </div>
                    
                    <div class="settings-section">
                        <h3 class="settings-section-title">Presentation</h3>
                        <div class="settings-options">
                            <label class="setting-item">
                                <span>Show progress bar</span>
                                <label class="switch">
                                    <input type="checkbox" id="show-progress" checked>
                                    <span class="slider"></span>
                                </label>
                            </label>
                            <label class="setting-item">
                                <span>Shuffle question order</span>
                                <label class="switch">
                                    <input type="checkbox" id="shuffle-questions">
                                    <span class="slider"></span>
                                </label>
                            </label>
                        </div>
                    </div>
                    
                    <div class="settings-section">
                        <h3 class="settings-section-title">Theme Color</h3>
                        <p class="setting-description">Choose a theme color for your form</p>
                        <div class="theme-colors">
                            <div class="color-option active" data-color="#1a73e8" style="background: #1a73e8;" title="Blue (Default)"></div>
                            <div class="color-option" data-color="#34a853" style="background: #34a853;" title="Green"></div>
                            <div class="color-option" data-color="#ea4335" style="background: #ea4335;" title="Red"></div>
                            <div class="color-option" data-color="#fbbc04" style="background: #fbbc04;" title="Yellow"></div>
                            <div class="color-option" data-color="#9c27b0" style="background: #9c27b0;" title="Purple"></div>
                            <div class="color-option" data-color="#ff6d01" style="background: #ff6d01;" title="Orange"></div>
                            <div class="color-option" data-color="#0f9d58" style="background: #0f9d58;" title="Teal"></div>
                            <div class="color-option" data-color="#795548" style="background: #795548;" title="Brown"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Upload Modal -->
        <div id="image-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Upload Image</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="upload-zone" class="upload-zone">
                        <i class="material-icons upload-icon">cloud_upload</i>
                        <p>Drag and drop an image here, or click to select</p>
                        <input type="file" id="file-input" accept="image/*" style="display: none;">
                        <button id="choose-file-btn" class="choose-file-btn">Choose file</button>
                    </div>
                    <div id="image-preview" class="image-preview" style="display: none;">
                        <img id="preview-img" src="" alt="Preview">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="cancel-upload" class="cancel-btn">Cancel</button>
                    <button id="upload-image" class="upload-btn" disabled>Upload</button>
                </div>
            </div>
        </div>
    </div>

    <div id="boxModal" class="modal">
        <div class="modal-content">
          <h3>Edit Box Content</h3>
          <textarea id="boxEditor"></textarea>
          <div id="preview" class="preview"></div>
          <button id="saveBox">Save</button>
          <button id="deleteBox" style="background:#f87171; border-color:#dc2626; color:white;">Delete</button>
          <button id="closeModal">Cancel</button>
        </div>
      </div>

    <!-- Text Input Popup Modal -->
    <div id="textInputModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Text Content</h3>
                <button class="modal-close" id="closeTextModal">&times;</button>
            </div>
            <div class="modal-body">
                <textarea id="textInputArea" placeholder="Type your text here..." rows="6"></textarea>
            </div>
            <div class="modal-footer">
                <button id="cancelTextInput" class="cancel-btn">Cancel</button>
                <button id="saveTextInput" class="upload-btn">Save to Editor</button>
            </div>
        </div>
    </div>
    
    <script>
        let teacherId = @json($teacher_id);
        let testSeriesId = @json($test_series_id);
        let testId = @json($test_id);
        console.log(testSeriesId, testId, teacherId);
        let questionSequence = @json($question_sequence);
        console.log(questionSequence);
    </script>

    <!-- Load jQuery and Trumbowyg scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/trumbowyg.js"></script>
    <!-- <script src="trumbowyg/dist/trumbowyg_custom.js"></script> -->
    <!-- <script src="trumbowyg/dist/trumbowyg.min.js"></script> -->
    <!-- <script src="https://xcceedai.com/test1/trumbowyg_custom.js"></script> -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/autogrow/trumbowyg.autogrow.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/pasteimage/trumbowyg.pasteimage.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/upload/trumbowyg.upload.min.js"></script>
    <script src="https://rawcdn.githack.com/RickStrahl/jquery-resizable/0.35/dist/jquery-resizable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/resizimg/trumbowyg.resizimg.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/indent/trumbowyg.indent.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/lineheight/trumbowyg.lineheight.min.js"></script>



    <!-- Import Trumbowyg MathJax at the end of <body>... -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML"></script>
    <!-- Import Trumbowyg MathML plugin at the end of <body>... -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/mathml/trumbowyg.mathml.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/mathml/trumbowyg.mathml.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.31.0/dist/plugins/speechrecognition/trumbowyg.speechrecognition.min.js"></script>
        
    <script src="{{ asset('assets/js/teacher/store.js') }}"></script>
    <script src="{{ asset('assets/js/teacher/script.js') }}"></script>
    <script src="{{ asset('assets/js/teacher/textinput.js') }}"></script>

@endsection
