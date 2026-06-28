(function() {
    'use strict';

    // Disable right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable text selection
    document.addEventListener('selectstart', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable copy
    document.addEventListener('copy', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable cut
    document.addEventListener('cut', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable keyboard shortcuts for copy (Ctrl+C, Cmd+C)
    document.addEventListener('keydown', function(e) {
        // Ctrl+C or Cmd+C
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 67) {
            e.preventDefault();
            return false;
        }
        // Ctrl+X or Cmd+X (cut)
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 88) {
            e.preventDefault();
            return false;
        }
        // Ctrl+U or Cmd+U (view source)
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 85) {
            e.preventDefault();
            return false;
        }
        // Ctrl+S or Cmd+S (save)
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
            e.preventDefault();
            return false;
        }
        // F12 (developer tools)
        if (e.keyCode === 123) {
            e.preventDefault();
            return false;
        }
        // Ctrl+Shift+I or Cmd+Shift+I (inspect element)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 73) {
            e.preventDefault();
            return false;
        }
        // Ctrl+Shift+J or Cmd+Shift+J (console)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 74) {
            e.preventDefault();
            return false;
        }
        // Ctrl+Shift+C or Cmd+Shift+C (inspect)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 67) {
            e.preventDefault();
            return false;
        }
    });

    // Add CSS to prevent text selection
    var style = document.createElement('style');
    style.textContent = `
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Allow selection in input fields and textareas */
        input, textarea, [contenteditable="true"] {
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
            user-select: text !important;
        }
    `;
    document.head.appendChild(style);

})();
