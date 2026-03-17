// Toggle right panel visibility
const rightPanelBtn = document.getElementById('rightPanel-btn');
const rightPanel = document.getElementById('rightPanel');
const miniwidthElements = document.querySelectorAll('.miniwidth');

rightPanelBtn.addEventListener('click', () => {
    rightPanel.classList.toggle('hidden');

    miniwidthElements.forEach(el => {
        el.classList.add('min-w-[400px]');
    });
});

// Close right panel on outside click
document.addEventListener('click', (event) => {
    if (!rightPanel.contains(event.target) && !rightPanelBtn.contains(event.target)) {
        rightPanel.classList.add('hidden');

        miniwidthElements.forEach(el => {
            el.classList.remove('min-w-[400px]');
        });
    }
});