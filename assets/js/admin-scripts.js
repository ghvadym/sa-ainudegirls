(function($){
    $(document).ready(function() {
        const ajax = adminajax.ajaxurl;
        const nonce = adminajax.nonce;

        const faqAiGenerateBtn = $('#faq-ai-generate');
        if (faqAiGenerateBtn.length) {
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
            const errorMessage = $(btn).closest('.metabox_ai__row').find('.faq_ai_error');
            const wrap = $(btn).closest('.metabox_ai');

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
                        $(wrap).addClass('_spinner');
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
                        $(wrap).removeClass('_spinner');
                    }

                    if (response.success) {
                        $(wrap).removeClass('_spinner');
                        $(errorMessage).empty();
                        location.reload();
                    }
                },
                error      : function (err) {
                    console.log('error', err);
                }
            });
        }

        const descAiGenerateBtn = $('#desc-ai-generate');
        if (descAiGenerateBtn.length) {
            $(document).on('click', '#desc-ai-generate', function () {
                const btn = $(this);
                const wrap = $(btn).closest('.metabox_ai');
                const postId = $(btn).data('id');
                const errorMessage = $(btn).closest('.metabox_ai__row').find('.faq_ai_error');

                jQuery.ajax({
                    type       : 'POST',
                    url        : ajax,
                    data       : {
                        'action' : 'desc_by_ai',
                        'nonce'  : nonce,
                        'post_id': postId
                    },
                    beforeSend : function () {
                        $(wrap).addClass('_spinner');
                    },
                    success    : function (response) {
                        if (response.error) {
                            let message = response.message ? response.message : 'Something went wrong';
                            $(errorMessage).html(message);
                            $(wrap).removeClass('_spinner');
                        }

                        if (response.success) {
                            $(wrap).removeClass('_spinner');
                            $(errorMessage).empty();
                            location.reload();
                        }
                    },
                    error      : function (err) {
                        console.log('error', err);
                    }
                });
            });
        }
    });
})(jQuery);