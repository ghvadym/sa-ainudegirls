(function(e){e(document).ready(function(){const m=fvajax.ajaxurl,p=fvajax.nonce,w=e(".header_burger_icon"),k=e(".header_close_icon"),l=e("#header");e("#search_form");const f=e(window).width()>1024;l.length&&e(document).scrollTop()>30&&l.addClass("_scrolled"),e(window).width()<1250&&e(window).scroll(function(){e(this).scrollTop()>30?l.addClass("_scrolled"):l.removeClass("_scrolled")}),l.length&&w.length&&k.length&&e(document).on("click",".header_burger_icon, .header_close_icon",function(){e(l).toggleClass("active-menu"),e("html").toggleClass("no-scroll")});const u=e("#articles_load");u.length&&u.on("click",function(o){const t=e(".search__input");let n=e(this).attr("data-page");n=parseInt(n)+1,t.length?h(e(t).val(),n):h("",n)});function h(o="",t=1){let n=new FormData;const a=e(".articles"),c=e(a).closest(".container"),s=e("#articles_load");n.append("action","load_posts"),n.append("nonce",p),n.append("search",o),n.append("page",t),jQuery.ajax({type:"POST",url:m,data:n,dataType:"json",processData:!1,contentType:!1,beforeSend:function(){e(c).addClass("_spinner")},success:function(i){i.posts&&(i.append?e(a).append(i.posts):e(a).html(i.posts),i.count===0||i.end_posts?e(s).hide():e(s).show(),e(s).attr("data-page",t)),e(c).removeClass("_spinner")},error:function(i){console.log("error",i)}})}if(f){const o=e(".footer__menu"),t=4;o.length&&e(o).each(function(a,c){const s=e(c).find("ul.menu"),i=e(c).find(".menu_load_more");let d=!1;!s.length||!i.length||(e(s).each(function(_,S){const g=e(S).find("li");if(g.length&&g.length>t)return d=!0,!1}),d&&e(i).show())}),e(".menu_load_more").length&&e(document).on("click",".menu_load_more",function(){const a=e(this).closest(".footer__menu"),c=e(this).attr("data-title"),s=e(this).text();if(!a.length)return!1;const i=e(a).find("ul.menu");if(!i)return!1;e(i).each(function(d,_){e(_).toggleClass("full_content")}),e(this).attr("data-title",s).text(c)})}f||e(".footer__title").length&&e(document).on("click",".footer__title",function(){e(this).toggleClass("show_menu"),e(this).next().slideToggle()}),e(".close_btn").length&&e(document).on("click",".close_btn",function(){const o=e(this).closest(".push_notification"),t=e(o).attr("id");localStorage.getItem(t)||localStorage.setItem(t,"1"),o.length&&e(o).removeClass("show_up")});const r=window.aingSettings.pushNotifications;e(["notification-square","notification-wide"]).each(function(o,t){if(!r.hasOwnProperty("display")||r.display==="0")return!1;if(r.display==="each_page")localStorage.removeItem(t);else if(localStorage.getItem(t))return;const n=e("#"+t);if(n.length){const a=e(n).data("delay");a?setTimeout(function(){e(n).addClass("show_up")},a):e(n).addClass("show_up")}}),sessionStorage.setItem("click_under_clicked","0"),e(document).on("click","a",function(o){const t=window.aingSettings.clickUnder,n="click_under_status",a=sessionStorage.getItem("click_under_clicked")==="1";!t.hasOwnProperty("activation")||!t.hasOwnProperty("adv_url")||t.activation==="0"||!t.adv_url||t.activation==="once"&&localStorage.getItem(n)||t.activation==="once_a_session"&&sessionStorage.getItem(n)||(t.activation==="each_1_click"||t.activation==="each_2_click"||t.activation==="each_3_click")&&(!t.hasOwnProperty("allowed")||t.allowed==="0"||a)||t.activation==="by_time"&&(!t.hasOwnProperty("allowed")||t.allowed==="0"||a)||(o.preventDefault(),v(t.adv_url),t.activation==="once"?localStorage.setItem(n,"1"):localStorage.removeItem(n),t.activation==="once_a_session"?sessionStorage.setItem(n,"1"):sessionStorage.removeItem(n))});function v(o){if(!o)return!1;window.open(o,"_blank"),window.focus(),sessionStorage.setItem("click_under_clicked","1")}})})(jQuery);
