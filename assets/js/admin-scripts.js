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
                        faqAiGenerate(btn, response.step);
                    }

                    if (response.error) {
                        $("<p class='faq_ai_error' '>Something vent wrong, contact with developer.</p>").insertAfter(btn);
                    }

                    if (response.success) {
                        $(btn).removeClass('_spinner');
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