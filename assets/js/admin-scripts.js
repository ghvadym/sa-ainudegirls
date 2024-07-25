(function($){
    $(document).ready(function() {
        const ajax = adminajax.ajaxurl;
        const nonce = adminajax.nonce;

        const btn = $('#faq-ai-generate');
        if (btn.length) {
            $(document).on('click', '#faq-ai-generate', function () {
                const btn = $(this);
                const postId = $(this).data('id');

                jQuery.ajax({
                    type       : 'POST',
                    url        : ajax,
                    data       : {
                        'action' : 'faq_by_ai',
                        'nonce'  : nonce,
                        'post_id': postId
                    },
                    beforeSend : function () {
                        $(btn).addClass('_spinner');
                    },
                    success    : function (response) {
                        $(btn).removeClass('_spinner');

                        if (response.success) {
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