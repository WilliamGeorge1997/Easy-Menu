$(document).ready(function() {
    $(document).on('submit', 'form', function() {
        const $form = $(this);
        const $btn = $form.find('.btn-loader');

        if ($btn.length && !$btn.prop('disabled')) {
            const originalHtml = $btn.data('original-html') || $btn.html();
            $btn.data('original-html', originalHtml);

            $btn.prop('disabled', true)
                .html('<span class="d-inline-flex align-items-center gap-2"><span class="spinner-border spinner-border-sm"></span><span>' + originalHtml + '</span></span>');
        }
    });
});