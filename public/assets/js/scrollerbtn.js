function scrollContainerLeft(button) {
    const container = button.parentElement.querySelector('.scroll-container');
    container.scrollBy({ left: -300, behavior: 'smooth' });
  }
  
  function scrollContainerRight(button) {
    const container = button.parentElement.querySelector('.scroll-container');
    container.scrollBy({ left: 300, behavior: 'smooth' });
  }
  
  // Optional: Auto show/hide buttons based on overflow
  document.querySelectorAll('.group').forEach(group => {
    const container = group.querySelector('.scroll-container');
    const leftBtn = group.querySelector('.scroll-left');
    const rightBtn = group.querySelector('.scroll-right');
  
    function updateButtons() {
      const scrollLeft = container.scrollLeft;
      const scrollWidth = container.scrollWidth;
      const clientWidth = container.clientWidth;
  
      // Check if scrolling is needed
      if (scrollWidth > clientWidth) {
        // Show buttons initially
        leftBtn.classList.remove('hidden');
        rightBtn.classList.remove('hidden');
  
        // Hide left button if at start
        if (scrollLeft <= 0) {
          leftBtn.classList.add('hidden');
        } else {
          leftBtn.classList.remove('hidden');
        }
  
        // Hide right button if at end
        if (scrollLeft + clientWidth >= scrollWidth - 1) {
          rightBtn.classList.add('hidden');
        } else {
          rightBtn.classList.remove('hidden');
        }
      } else {
        // Hide both buttons if no overflow
        leftBtn.classList.add('hidden');
        rightBtn.classList.add('hidden');
      }
    }
  
    container.addEventListener('scroll', updateButtons);
    window.addEventListener('resize', updateButtons);
  
    // Initial check
    updateButtons();
  });
  
  