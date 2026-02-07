$(document).ready(function() {
    $(document).on('submit', 'form', function() {
        const $form = $(this);
        const $btn = $form.find('.btn-loader');
        
        if ($btn.length && !$btn.prop('disabled')) {
            const originalHtml = $btn.data('original-html') || $btn.html();
            $btn.data('original-html', originalHtml);
            
            $btn.prop('disabled', true)
                .addClass('d-flex align-items-center justify-content-center')
                .html('<span class="spinner-border spinner-border-sm me-2"></span><span>' + originalHtml + '</span>');
        }
    });
});