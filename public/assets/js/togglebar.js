function toggleSidebar() {
    // Get the sidebar, close button, and the show sidebar button
    const sidebar = document.querySelector('.sidebar');
    const closeButton = document.getElementById('closeButton');
    const showSidebarButton = document.getElementById('showSidebarButton');

    // Toggle sidebar visibility
    sidebar.classList.toggle('hidden');
    
    // Toggle the visibility of the buttons based on sidebar state
    if (sidebar.classList.contains('hidden')) {
        closeButton.classList.add('hidden');  // Hide the "X" button
        showSidebarButton.classList.remove('hidden');  // Show the "Show Sidebar" button
    } else {
        closeButton.classList.remove('hidden');  // Show the "X" button
        showSidebarButton.classList.add('hidden');  // Hide the "Show Sidebar" button
    }
}