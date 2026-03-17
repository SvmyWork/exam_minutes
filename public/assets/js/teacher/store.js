// IndexedDB Store for Question Management
class QuestionStore {
    constructor() {
        this.dbName = 'QuestionFormDB';
        this.dbVersion = 1;
        this.db = null;
        this.storeName = 'questions';
        this.formStoreName = 'formData';
    }

    // Initialize IndexedDB
    async init() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onerror = () => {
                console.error('Failed to open IndexedDB:', request.error);
                reject(request.error);
            };

            request.onsuccess = () => {
                this.db = request.result;
                console.log('IndexedDB initialized successfully');
                resolve(this.db);
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;

                // Create questions store
                if (!db.objectStoreNames.contains(this.storeName)) {
                    const questionStore = db.createObjectStore(this.storeName, { keyPath: 'id' });
                    questionStore.createIndex('position', 'position', { unique: false });
                    questionStore.createIndex('type', 'type', { unique: false });
                }

                // Create form data store
                if (!db.objectStoreNames.contains(this.formStoreName)) {
                    const formStore = db.createObjectStore(this.formStoreName, { keyPath: 'id' });
                }
            };
        });
    }

    // Save a single question
    async saveQuestion(question) {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readwrite');
            const store = transaction.objectStore(this.storeName);

            // Add timestamp for tracking changes and ensure HTML content is preserved
            const questionWithTimestamp = {
                ...question,
                lastModified: new Date().toISOString(),
                position: question.position || 0,
                // Ensure HTML content is stored as-is (including MathJax)
                title: question.title || '',
                description: question.description || '',
                // Store options with their HTML content
                options: question.options ? question.options.map(opt => ({
                    ...opt,
                    text: opt.text || '' // This will contain HTML with MathJax
                })) : undefined
            };

            const request = store.put(questionWithTimestamp);

            request.onsuccess = () => {
                console.log('Question saved with HTML content:', question.id);
                resolve(questionWithTimestamp);
            };

            request.onerror = () => {
                console.error('Failed to save question:', request.error);
                reject(request.error);
            };
        });
    }

    // Save multiple questions (for bulk operations)
    async saveQuestions(questions) {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readwrite');
            const store = transaction.objectStore(this.storeName);

            let completed = 0;
            const total = questions.length;

            if (total === 0) {
                resolve([]);
                return;
            }

            questions.forEach((question, index) => {
                const questionWithTimestamp = {
                    ...question,
                    lastModified: new Date().toISOString(),
                    position: index
                };

                const request = store.put(questionWithTimestamp);

                request.onsuccess = () => {
                    completed++;
                    if (completed === total) {
                        console.log('All questions saved successfully');
                        resolve(questions);
                    }
                };

                request.onerror = () => {
                    console.error('Failed to save question:', question.id, request.error);
                    reject(request.error);
                };
            });
        });
    }

    // Get all questions
    async getAllQuestions() {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readonly');
            const store = transaction.objectStore(this.storeName);
            const request = store.getAll();

            request.onsuccess = () => {
                // Sort by position to maintain order
                const questions = request.result.sort((a, b) => (a.position || 0) - (b.position || 0));
                console.log('Retrieved questions:', questions.length);
                resolve(questions);
            };

            request.onerror = () => {
                console.error('Failed to get questions:', request.error);
                reject(request.error);
            };
        });
    }

    // Get a single question by ID
    async getQuestion(id) {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readonly');
            const store = transaction.objectStore(this.storeName);
            const request = store.get(id);

            request.onsuccess = () => {
                resolve(request.result);
            };

            request.onerror = () => {
                console.error('Failed to get question:', request.error);
                reject(request.error);
            };
        });
    }

    // Delete a question
    async deleteQuestion(id) {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readwrite');
            const store = transaction.objectStore(this.storeName);
            const request = store.delete(id);

            request.onsuccess = () => {
                console.log('Question deleted:', id);
                resolve(true);
            };

            request.onerror = () => {
                console.error('Failed to delete question:', request.error);
                reject(request.error);
            };
        });
    }

    // Update question positions after drag and drop
    async updateQuestionPositions(questions) {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readwrite');
            const store = transaction.objectStore(this.storeName);

            let completed = 0;
            const total = questions.length;

            if (total === 0) {
                resolve([]);
                return;
            }

            questions.forEach((question, index) => {
                const updatedQuestion = {
                    ...question,
                    position: index,
                    lastModified: new Date().toISOString()
                };

                const request = store.put(updatedQuestion);

                request.onsuccess = () => {
                    completed++;
                    if (completed === total) {
                        console.log('Question positions updated successfully');
                        resolve(questions);
                    }
                };

                request.onerror = () => {
                    console.error('Failed to update question position:', question.id, request.error);
                    reject(request.error);
                };
            });
        });
    }

    // Save form header data (title, description)
    async saveFormData(formData) {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.formStoreName], 'readwrite');
            const store = transaction.objectStore(this.formStoreName);

            const formDataWithTimestamp = {
                id: 'form-header',
                ...formData,
                lastModified: new Date().toISOString()
            };

            const request = store.put(formDataWithTimestamp);

            request.onsuccess = () => {
                console.log('Form data saved');
                resolve(formDataWithTimestamp);
            };

            request.onerror = () => {
                console.error('Failed to save form data:', request.error);
                reject(request.error);
            };
        });
    }

    // Get form header data
    async getFormData() {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.formStoreName], 'readonly');
            const store = transaction.objectStore(this.formStoreName);
            const request = store.get('form-header');

            request.onsuccess = () => {
                resolve(request.result || {});
            };

            request.onerror = () => {
                console.error('Failed to get form data:', request.error);
                reject(request.error);
            };
        });
    }

    // Clear all data (for testing/reset)
    async clearAllData() {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName, this.formStoreName], 'readwrite');
            
            const questionStore = transaction.objectStore(this.storeName);
            const formStore = transaction.objectStore(this.formStoreName);

            const questionRequest = questionStore.clear();
            const formRequest = formStore.clear();

            let completed = 0;
            const total = 2;

            const checkComplete = () => {
                completed++;
                if (completed === total) {
                    console.log('All data cleared');
                    resolve(true);
                }
            };

            questionRequest.onsuccess = checkComplete;
            formRequest.onsuccess = checkComplete;

            questionRequest.onerror = () => reject(questionRequest.error);
            formRequest.onerror = () => reject(formRequest.error);
        });
    }

    // Get database statistics
    async getStats() {
        if (!this.db) {
            await this.init();
        }

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName, this.formStoreName], 'readonly');
            const questionStore = transaction.objectStore(this.storeName);
            const formStore = transaction.objectStore(this.formStoreName);

            const questionCountRequest = questionStore.count();
            const formDataRequest = formStore.get('form-header');

            let completed = 0;
            const total = 2;
            const stats = {};

            const checkComplete = () => {
                completed++;
                if (completed === total) {
                    resolve(stats);
                }
            };

            questionCountRequest.onsuccess = () => {
                stats.questionCount = questionCountRequest.result;
                checkComplete();
            };

            formDataRequest.onsuccess = () => {
                stats.hasFormData = !!formDataRequest.result;
                checkComplete();
            };

            questionCountRequest.onerror = () => reject(questionCountRequest.error);
            formDataRequest.onerror = () => reject(formDataRequest.error);
        });
    }
}

// Create global instance
const questionStore = new QuestionStore();

// Auto-save functionality with debouncing
class AutoSaveManager {
    constructor(store) {
        this.store = store;
        this.saveTimeout = null;
        this.debounceDelay = 1000; // 1 second delay
    }

    // Debounced save for question changes
    debouncedSave(question) {
        clearTimeout(this.saveTimeout);
        this.saveTimeout = setTimeout(() => {
            this.store.saveQuestion(question);
        }, this.debounceDelay);
    }

    // Immediate save for critical operations (delete, reorder)
    immediateSave(questions) {
        clearTimeout(this.saveTimeout);
        this.store.saveQuestions(questions);
    }

    // Save form data with debouncing
    debouncedSaveFormData(formData) {
        clearTimeout(this.saveTimeout);
        this.saveTimeout = setTimeout(() => {
            this.store.saveFormData(formData);
        }, this.debounceDelay);
    }
}

// Create auto-save manager instance
const autoSaveManager = new AutoSaveManager(questionStore);

// Export for use in other files
window.questionStore = questionStore;
window.autoSaveManager = autoSaveManager;
