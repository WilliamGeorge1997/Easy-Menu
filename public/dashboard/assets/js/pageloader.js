(function () {
    // Create the progress bar immediately (no jQuery, runs in <head>)
    var bar = document.createElement('div');
    bar.id = 'page-progress-bar';
    bar.style.cssText = [
        'position:fixed',
        'top:0',
        'left:0',
        'width:0%',
        'height:3px',
        'background:#696cff',
        'z-index:99999',
        'transition:width 0.2s ease',
        'pointer-events:none',
    ].join(';');

    // Overlay to block all interactions until fully loaded
    var overlay = document.createElement('div');
    overlay.id = 'page-load-overlay';
    overlay.style.cssText = [
        'position:fixed',
        'top:0',
        'left:0',
        'width:100%',
        'height:100%',
        'z-index:99998',
        'background:transparent',
        'pointer-events:all',
        'cursor:wait',
    ].join(';');

    // Insert into <html> immediately — before <body> even exists
    document.documentElement.appendChild(bar);
    document.documentElement.appendChild(overlay);

    // Animate 0 → 85% while loading
    var progress = 0;
    var interval = setInterval(function () {
        if (progress < 85) {
            progress += Math.random() * 8;
            if (progress > 85) progress = 85;
            bar.style.width = progress + '%';
        } else {
            clearInterval(interval);
        }
    }, 150);

    // On full page load: complete to 100% then fade out
    window.addEventListener('load', function () {
        clearInterval(interval);
        bar.style.transition = 'width 0.3s ease';
        bar.style.width = '100%';

        // Remove overlay immediately so user can interact
        overlay.parentNode && overlay.parentNode.removeChild(overlay);

        setTimeout(function () {
            bar.style.transition = 'opacity 0.4s ease';
            bar.style.opacity = '0';
            setTimeout(function () {
                bar.parentNode && bar.parentNode.removeChild(bar);
            }, 400);
        }, 300);
    });
}());
