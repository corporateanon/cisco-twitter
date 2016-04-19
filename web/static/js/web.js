(function ($) {
    var cb = new Clipboard('.nt-action-copy');
    
    function onCopyResult(message, e) {
        var $target = $(e.trigger);
        $target.tooltip({
            title: message,
            trigger: 'manual'
        });
        $target.tooltip('show');
        setTimeout(function () { $target.tooltip('destroy'); }, 3000);
    }
    
    cb.on('success', onCopyResult.bind(null, 'Copied to clipboard'));
    cb.on('error', onCopyResult.bind(null, 'Press Ctrl+C (or ⌘+C) to copy'));
})(jQuery);
