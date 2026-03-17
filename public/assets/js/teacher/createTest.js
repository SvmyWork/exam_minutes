document.addEventListener('alpine:init', () => {
    // Create Test Component
    Alpine.data('createTestComponent', () => ({
        testName: '',
        showNotification: false,
        title: '',
        note: '',
        icon: '',
        createdTestName: '',

        async createTest() {
            if (!this.testName.trim()) {
                this.createdTestName = this.testName;
                this.title = 'Test Name Required';
                this.note = `Please enter a test name.`;
                this.icon = 'error';
                this.showNotification = true;
                this.testName = '';
                setTimeout(() => this.showNotification = false, 3000);
                return;
            }

            try {
                const response = await fetch('/api/create-test', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('teacher_token')}`,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name: this.testName, test_series_id: testSeriesId, teacher_id: teacherId, test_series_name: testSeriesName})
                });

                let data = null;
                const contentType = response.headers.get('content-type') || '';
                if (contentType.includes('application/json')) {
                    try {
                        data = await response.json();
                    } catch (e) {
                        console.error('Failed to parse JSON response', e);
                    }
                } else {
                    const text = await response.text();
                    console.error('Non-JSON response from /api/create-test:', text);
                }

                if (response.ok) {
                    // ✅ Notify success
                    this.title = 'Test Created!';
                    this.note = `${this.testName} was successfully added.`;
                    this.icon = 'success';
                    this.showNotification = true;
                    this.createdTestName = this.testName;
                    this.testName = '';
                    setTimeout(() => this.showNotification = false, 3000);

                    // ✅ Emit Alpine event so testListComponent can refresh
                    window.dispatchEvent(new CustomEvent('test-created'));
                } else {
                    this.title = 'Error!';
                    this.note = (data && data.message) || 'Something went wrong.';
                    this.icon = 'error';
                    this.showNotification = true;
                    setTimeout(() => this.showNotification = false, 3000);
                }
            } catch (error) {
                console.error(error);
                this.title = 'Network Error!';
                this.note = 'Please check your connection and try again.';
                this.icon = 'error';
                this.showNotification = true;
                setTimeout(() => this.showNotification = false, 3000);
            }
        }
    }));

    // Test List Component
    Alpine.data('testListComponent', () => ({
        tests: [],
        loading: true,

        async loadTests() {
            let no_of_tests_text = document.getElementById('Nooftest');
            this.loading = true;
            try {
                const response = await fetch('/api/get-tests', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('teacher_token')}`,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ test_series_id: testSeriesId, teacher_id: teacherId})
                });

                const contentType = response.headers.get('content-type') || '';
                if (contentType.includes('application/json')) {
                    const data = await response.json();
                    console.log('Fetched tests data:', data);
                    this.tests = (Array.isArray(data.tests) ? data.tests : data).map((t, i) => ({
                        id: i + 1,
                        ...t
                    }));
                    no_of_tests_text.innerText = `Number of Tests: ${this.tests.length}`;
                } else {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                }
            } catch (error) {
                console.error('Failed to load tests:', error);
            } finally {
                this.loading = false;
            }
        },

        openTestDetails(testId) {
            window.location = `/teacher/test-details/${testId}`;
        },

        init() {
            // ✅ Listen for global event fired after test creation
            window.addEventListener('test-created', () => {
                this.loadTests();
            });
        }
    }));
});
