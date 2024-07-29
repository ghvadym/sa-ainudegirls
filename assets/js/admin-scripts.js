(function($){
    $(document).ready(function() {
        const ajax = adminajax.ajaxurl;
        const nonce = adminajax.nonce;

        const btn = $('#faq-ai-generate');
        if (btn.length) {
            $(document).on('click', '#faq-ai-generate', function () {
                faqAiGenerate($(this));
            });
        }

        function faqAiGenerate(btn, step)
        {
            if (!btn) {
                return;
            }

            const postId = $(btn).data('id');
            const errorMessage = $('.faq_ai_error');

            jQuery.ajax({
                type       : 'POST',
                url        : ajax,
                data       : {
                    'action' : 'faq_by_ai',
                    'nonce'  : nonce,
                    'post_id': postId,
                    'step'   : step
                },
                beforeSend : function () {
                    if (!step) {
                        $(btn).addClass('_spinner');
                    }
                },
                success    : function (response) {
                    if (response.step) {
                        $(errorMessage).empty();
                        faqAiGenerate(btn, response.step);
                    }

                    if (response.error) {
                        let message = response.message ? response.message : 'Something went wrong';
                        $(errorMessage).html(message);
                        $(btn).removeClass('_spinner');
                    }

                    if (response.success) {
                        $(btn).removeClass('_spinner');
                        $(errorMessage).empty();
                        location.reload();
                    }
                },
                error      : function (err) {
                    console.log('error', err);
                }
            });
        }
    });
})(jQuery);