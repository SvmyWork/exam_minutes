
// // Disable Right Click
// document.addEventListener('contextmenu', event => event.preventDefault());

// // Disable Keyboard Shortcuts (F12, Ctrl+Shift+I, Ctrl+U, etc.)
// document.addEventListener('keydown', function(e) {
//     if (
//     e.key === "F12" || 
//     (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key)) || 
//     (e.ctrlKey && e.key === 'U') || 
//     e.key === 'Escape'
//     ) {
//     e.preventDefault();
//     return false;
//     }
// });

// // Disable Mouse Scroll
// window.addEventListener('wheel', function(e) {
//     e.preventDefault();
// }, { passive: false });

// // Disable Copy, Cut, Paste
// ['copy', 'cut', 'paste'].forEach(evt => {
//     document.addEventListener(evt, function(e) {
//     e.preventDefault();
//     });
// });

// // Disable Developer Tools Detection (basic)
// setInterval(function () {
//     const devtools = window.outerWidth - window.innerWidth > 100 || window.outerHeight - window.innerHeight > 100;
//     if (devtools) {
//     document.body.innerHTML = "<h1>Developer tools are disabled</h1>";
//     }
// }, 1000);

// // Disable text selection
// document.addEventListener('selectstart', function(e) {
//     e.preventDefault();
// });

// // Disable drag
// document.addEventListener('dragstart', function(e) {
//     e.preventDefault();
// });

// // Disable keyboard activity (for form fields this may be undesired)
// document.addEventListener('keypress', function(e) {
//     e.preventDefault();
// });
