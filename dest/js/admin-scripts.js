(function(n){n(document).ready(function(){const i=adminajax.ajaxurl,c=adminajax.nonce;n("#faq-ai-generate").length&&n(document).on("click","#faq-ai-generate",function(){o(n(this))});function o(e,t){if(!e)return;const r=n(e).data("id");jQuery.ajax({type:"POST",url:i,data:{action:"faq_by_ai",nonce:c,post_id:r,step:t},beforeSend:function(){t||n(e).addClass("_spinner")},success:function(a){a.step&&o(e,a.step),a.error&&n("<p class='faq_ai_error' '>Something vent wrong, contact with developer.</p>").insertAfter(e),a.success&&(n(e).removeClass("_spinner"),location.reload())},error:function(a){console.log("error",a)}})}})})(jQuery);
