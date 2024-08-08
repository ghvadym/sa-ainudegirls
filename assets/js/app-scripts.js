(function ($) {
    $(document).ready(function () {
        const ajax = fvajax.ajaxurl;
        const nonce = fvajax.nonce;
        const burgerOpen = $('.header_burger_icon');
        const burgerClose = $('.header_close_icon');
        const header = $('#header');
        const searchForm = $('#search_form');
        const isDesktop = $(window).width() > 1024;

        if (header.length) {
            if ($(document).scrollTop() > 30) {
                header.addClass('_scrolled');
            }
        }

        if ($(window).width() < 1250) {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 30) {
                    header.addClass('_scrolled');
                } else {
                    header.removeClass('_scrolled');
                }
            });
        }

        if (header.length && burgerOpen.length && burgerClose.length) {
            $(document).on('click', '.header_burger_icon, .header_close_icon', function () {
                $(header).toggleClass('active-menu');
                $('html').toggleClass('no-scroll');
            });
        }

        /* ---------- */

        const articlesLoadBtn = $('#articles_load');
        if (articlesLoadBtn.length) {
            articlesLoadBtn.on('click', function (e) {
                const search = $('.search__input');
                let pageNumber = $(this).attr('data-page');
                pageNumber = parseInt(pageNumber) + 1;

                if (search.length) {
                    ajaxPosts($(search).val(), pageNumber);
                } else {
                    ajaxPosts('', pageNumber);
                }
            });
        }

        function ajaxPosts(search = '', pageNumber = 1)
        {
            let formData = new FormData();

            const posts = $('.articles');
            const wrap = $(posts).closest('.container');
            const loadMoreBtn = $('#articles_load');

            formData.append('action', 'load_posts');
            formData.append('nonce', nonce);
            formData.append('search', search);
            formData.append('page', pageNumber);

            jQuery.ajax({
                type       : 'POST',
                url        : ajax,
                data       : formData,
                dataType   : 'json',
                processData: false,
                contentType: false,
                beforeSend : function () {
                    $(wrap).addClass('_spinner');
                },
                success    : function (response) {
                    if (response.posts) {
                        if (response.append) {
                            $(posts).append(response.posts);
                        } else {
                            $(posts).html(response.posts);
                        }

                        if (response.count === 0 || response.end_posts) {
                            $(loadMoreBtn).hide();
                        } else {
                            $(loadMoreBtn).show();
                        }

                        $(loadMoreBtn).attr('data-page', pageNumber);
                    }

                    $(wrap).removeClass('_spinner');
                },
                error      : function (err) {
                    console.log('error', err);
                }
            });
        }

        /* ---------- */

        if (isDesktop) {
            const footerMenuRows = $('.footer__menu');
            const maxMenuItems = 4;
            if (footerMenuRows.length) {
                $(footerMenuRows).each(function (index, footerMenuRow) {
                    const footerMenus = $(footerMenuRow).find('ul.menu');
                    const menuShowMore = $(footerMenuRow).find('.menu_load_more');
                    let showLoadMoreBtn = false;

                    if (!footerMenus.length || !menuShowMore.length) {
                        return;
                    }

                    $(footerMenus).each(function (index, footerMenu) {
                        const menuItems = $(footerMenu).find('li');

                        if (menuItems.length && menuItems.length > maxMenuItems) {
                            showLoadMoreBtn = true;
                            return false;
                        }
                    });

                    if (showLoadMoreBtn) {
                        $(menuShowMore).show();
                    }
                });
            }

            const menuShowMore = $('.menu_load_more');
            if (menuShowMore.length) {
                $(document).on('click', '.menu_load_more', function () {
                    const menuWrapper = $(this).closest('.footer__menu');
                    const dataTitle = $(this).attr('data-title');
                    const currentTitle = $(this).text();

                    if (!menuWrapper.length) {
                        return false;
                    }

                    const menus = $(menuWrapper).find('ul.menu');

                    if (!menus) {
                        return false;
                    }

                    $(menus).each(function (index, menu) {
                        $(menu).toggleClass('full_content');
                    });

                    $(this).attr('data-title', currentTitle).text(dataTitle);
                });
            }
        }

        if (!isDesktop) {
            const footerTitle = $('.footer__title');
            if (footerTitle.length) {
                $(document).on('click', '.footer__title', function () {
                    $(this).toggleClass('show_menu');
                    $(this).next().slideToggle();
                });
            }
        }

        /* ---------- */

        const pushNotificationsBtn = $('.close_btn');
        if (pushNotificationsBtn.length) {
            $(document).on('click', '.close_btn', function () {
                const wrap = $(this).closest('.push_notification');
                const id = $(wrap).attr('id');

                if (!localStorage.getItem(id)) {
                    localStorage.setItem(id, '1');
                }

                if (wrap.length) {
                    $(wrap).removeClass('show_up');
                }
            });
        }

        /* ---------- */

        const pushNotifications = window.aingSettings.pushNotifications;
        const notifications = [
            'notification-square',
            'notification-wide'
        ];

        $(notifications).each(function (index, item) {
            if (!pushNotifications.hasOwnProperty('display') || pushNotifications.display === '0') {
                return false;
            }

            if (pushNotifications.display === 'each_page') {
                localStorage.removeItem(item);
            } else {
                if (localStorage.getItem(item)) {
                    return;
                }
            }

            const notification = $('#'+item);
            if (notification.length) {
                const delay = $(notification).data('delay');

                if (delay) {
                    setTimeout(function () {
                        $(notification).addClass('show_up');
                    }, delay );
                } else {
                    $(notification).addClass('show_up');
                }
            }
        });

        /* ---------- */

        sessionStorage.setItem('click_under_clicked', '0');

        $(document).on('click', 'a', function (e) {
            const clickUnder = window.aingSettings.clickUnder;
            const clickUnderStatusKey = 'click_under_status';
            const clicked = sessionStorage.getItem('click_under_clicked') === '1';

            if (!clickUnder.hasOwnProperty('activation') || !clickUnder.hasOwnProperty('adv_url') || clickUnder.activation === '0' || !clickUnder.adv_url) {
                return;
            }

            if (clickUnder.activation === 'once' && localStorage.getItem(clickUnderStatusKey)) {
                return;
            }

            if (clickUnder.activation === 'once_a_session' && sessionStorage.getItem(clickUnderStatusKey)) {
                return;
            }

            if ((clickUnder.activation === 'each_1_click' || clickUnder.activation === 'each_2_click' || clickUnder.activation === 'each_3_click') && (!clickUnder.hasOwnProperty('allowed') || clickUnder.allowed === '0' || clicked)) {
                return;
            }

            if (clickUnder.activation === 'by_time' && (!clickUnder.hasOwnProperty('allowed') || clickUnder.allowed === '0' || clicked)) {
                return;
            }

            e.preventDefault();
            clickUnderOpenLink(clickUnder.adv_url);

            if (clickUnder.activation === 'once') {
                localStorage.setItem(clickUnderStatusKey, '1');
            } else {
                localStorage.removeItem(clickUnderStatusKey);
            }

            if (clickUnder.activation === 'once_a_session') {
                sessionStorage.setItem(clickUnderStatusKey, '1');
            } else {
                sessionStorage.removeItem(clickUnderStatusKey);
            }
        });

        function clickUnderOpenLink(url)
        {
            if (!url) {
                return false;
            }

            window.open(url, '_blank');
            window.focus();
            sessionStorage.setItem('click_under_clicked', '1');
        }

        //updateStorageData();
        function updateStorageData()
        {
            const clickUnder = window.aingSettings.clickUnder;

            if (!clickUnder) {
                return false;
            }

            const lastUrl = !clickUnder.hasOwnProperty('url_path') ? clickUnder.url_path : 0;
            const pagesCounter = !clickUnder.hasOwnProperty('pages_counter') ? clickUnder.pages_counter : 0;
            const currentUrl = window.location.href;

            if (lastUrl !== currentUrl) {
                clickUnder.pages_counter = parseInt(pagesCounter) + 1;
                clickUnder.url_path = currentUrl;
            }
        }

    });
})(jQuery);