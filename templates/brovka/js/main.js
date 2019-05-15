jQuery(document).ready(function ($) {

    // window.onload = function () {
    //     $('.airSticky').airStickyBlock({debug: false});
    // };

    $(".fancybox").fancybox({
        'type':'ajax'
    });

    /*$('.search-div').bind('click', function () {
        $('.search').animate({width: "show"}, 600);
    });*/

    /*$(document).mouseup(function (e) {
        var container = $(".search");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('.search').animate({width: "hide"}, 600);
        } else {

        }
    });*/


    var headerh = $('.headcontainer').outerHeight();
    var asideh = $('.aside-column').outerHeight();
    var aside_header = headerh + asideh;

    function addHandler(object, event, handler) {
        if (object.addEventListener) {
            object.addEventListener(event, handler, false);
        } else if (object.attachEvent) {
            object.attachEvent('on' + event, handler);
        } else alert("РћР±СЂР°Р±РѕС‚С‡РёРє РЅРµ РїРѕРґРґРµСЂР¶РёРІР°РµС‚СЃСЏ");
    }

    addHandler(window, 'DOMMouseScroll', wheel);
    addHandler(window, 'mousewheel', wheel);
    addHandler(document, 'mousewheel', wheel);

    function wheel(event) {
        var delta;
        event = event || window.event;
        if (event.wheelDelta) {
            delta = event.wheelDelta / 120;
            if (window.opera) delta = -delta;
        } else if (event.detail) {
            delta = -event.detail / 3;
        }
/*
        if (delta > 0) {
            //Вверх
            if ($(window).scrollTop() < headerh) {

                $('.fixed-menu').hide();
                $('.headcontainer').css('opacity', '1');
            }

        } else {
            //Вниз
            if ($(window).scrollTop() >= headerh) {
                $('.fixed-menu').show();
                $('.headcontainer').css('opacity', '0');
            }

            // if($(window).scrollTop() > asideh) {
            //     $('.aside-column').addClass('fixed-aside');
            // }
        }
        */

    };


    function close_accordion_section() {
        $('.accordion .accordion-section-title').removeClass('active');
        $('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }

    $('.accordion-section-title').click(function (e) {
        // Grab current anchor value
        var currentAttrValue = $(this).attr('href');

        if ($(e.target).is('.active')) {
            close_accordion_section();
        } else {
            close_accordion_section();

            // Add active class to section title
            $(this).addClass('active');
            // Open up the hidden content panel
            $('.accordion ' + currentAttrValue).slideDown(300).addClass('open');
        }

        e.preventDefault();
    });

    $('a.page-tab').bind('click', function () {
        $('a.page-tab').removeClass('active-page');
        $(this).toggleClass('active-page');
    })
/*
    $('.content-block-footer button.plus-button').bind('click', function () {
        $(this).toggleClass('plus-active');
        $('.content-block-footer button.minus-button').removeClass('minus-active');
    });

    $('.content-block-footer button.minus-button').bind('click', function () {
        $(this).toggleClass('minus-active');
        $('.content-block-footer button.plus-button').removeClass('plus-active');
    });*/

    /*$('button.favorite-button').bind('click', function () {
        $(this).hide();
        $(this).siblings('button.favorite-active').show();
    });*/

    $(".load_more_posts").click(function(){
        if(postList != 'undefined') {
            var target = $(this).prev('div'),
                bxajaxid = target.attr('id').replace('comp_', ''),
                linkShowMore = $(this),
                nextpage = parseInt(postList.NavPageNomer) + 1,
                url = location.pathname + '?page=' + nextpage + '&bxajaxid=' + bxajaxid + '&' + postList.NavQueryString;

            $.get(url, function (data) {
                target.append(data);
                console.log('nextpage:',nextpage+1,parseInt(postList.NavPageMaxpages));
                if((nextpage + 1) > parseInt(postList.NavPageMaxpages))
                    linkShowMore.remove();
            });
        }
    });


//Инициализация плагина Select2
    $('.selectpicker').select2({
        width: '150px',
    });
    $('.select2guides').select2({
        width: '100%',
    });
    $('.select2guidesCities').select2({
        width: '100%',
        //minimumInputLength: 2
    });


//Имитация клика по Input type file в профиле
    $('.bx-auth-profile .inputs span.note ').bind('click', function(){
        $(this).parents(".inputs").find("input[type=file]").trigger("click");
        //$('.bx-auth-profile .inputs input[type=file]').trigger('click');
    });
//Маски на инпуты
    $('.bx-auth-profile input.profile-phone').mask('+7 (000) 000 00 00', {placeholder: "+7 (___) ___ __ __"});
    $('.bx-auth-profile input.profile-birthdate').mask('00.00.0000', {placeholder: "__/__/____"});


//Липкий хэдер
    $(window).scroll(function(){
            if($(this).scrollTop()>249){
                $('.headcontainer').addClass('fixed');
                $('.airSticky_stop-block').css('padding-top', '330px');
            }
            else if ($(this).scrollTop()<270){
                $('.headcontainer').removeClass('fixed');
                $('.airSticky_stop-block').css('padding-top', '0');
            }
        });
});

//Отключаем липкий баннер если страничка небольшая
//console.log($(window).height());

function editThemes(id,type){
    var themes = [];
    $(".edit-themes-form .edit-themes-inputs").each(function(){
        if($(this).prop("checked") === true)
            themes.push($(this).val());
    });
    $.post("/local/ajax/ajax.php",{"action":"editThemes","id":id,"themes":themes,"type":type},function(data){
        console.log(data);
        if(data.error == 0)
        {
            $.fancybox.close();
            location.reload();
        }
    },"json");
}
function guideShowContacts(guide) {
    $.post("/local/ajax/ajax.php",{"action":"guideShowContacts","guide":guide},function(data){
        if(data.error == 0)
        {
            $(".guid-show-contacts").html("");
            if(data.contacts.email)
                $(".guid-show-contacts").append("<div class='email'><b>E-mail:</b> "+data.contacts.email+"</div>");
            if(data.contacts.phones)
                $(".guid-show-contacts").append("<div class='phones'><b>Телефон:</b> "+data.contacts.phones+"</div>");
            if(!data.contacts.email && !data.contacts.phones)
                $(".guid-show-contacts").append("<div class='phones'>Контакты скрыты от общего доступа</div>");
        }else{
            $.fancybox.open('<div class="message"><h2>Ошибка!</h2><p>'+data.message+'</p></div>');
        }
    },"json");
}
function upvote(topic,type){
    $.post("/local/ajax/ajax.php",{"action":"addlike","topic":topic,"type":type},function(data){
        if(data.error == 0)
        {
            $('[data-id="'+topic+'"] .plus-button span').text(data.votes.TOTAL_POSITIVE_VOTES);
            $('[data-id="'+topic+'"] .minus-button span').text(data.votes.TOTAL_NEGATIVE_VOTES);
        }else{
            $.fancybox.open('<div class="message"><h2>Ошибка!</h2><p>'+data.message+'</p></div>');
        }
    },"json");
}
function downvote(topic,type){
    $.post("/local/ajax/ajax.php",{"action":"deletelike","topic":topic,"type":type},function(data){
        if(data.error == 0)
        {
            $('[data-id="'+topic+'"] .plus-button span').text(data.votes.TOTAL_POSITIVE_VOTES);
            $('[data-id="'+topic+'"] .minus-button span').text(data.votes.TOTAL_NEGATIVE_VOTES);
        }else{
            $.fancybox.open('<div class="message"><h2>Ошибка!</h2><p>'+data.message+'</p></div>');
        }
    },"json");
}
function favorites_add(topic,type,target){
    $.post("/local/ajax/ajax.php",{"action":"favorites_add","topic":topic,"type":type},function(data){
        if(data.error == 0)
        {
            target.parent().find(".favorite-button").toggle();
        }else{
            $.fancybox.open('<div class="message"><h2>Ошибка!</h2><p>'+data.message+'</p></div>');
        }
    },"json");
}
function favorites_del(topic,type,target){
    $.post("/local/ajax/ajax.php",{"action":"favorites_del","topic":topic,"type":type},function(data){
        if(data.error == 0)
        {
            target.parent().find(".favorite-button").toggle();
        }else{
            $.fancybox.open('<div class="message"><h2>Ошибка!</h2><p>'+data.message+'</p></div>');
        }
    },"json");
}
function topic_hide_index(topic,type,target){
    $.post("/local/ajax/ajax.php",{"action":"topic_hide_index","topic":topic,"type":type},function(data){
        /*if(data.error == 0)
        {
            target.parent().find(".hide-button").toggle();
        }else{
            $.fancybox.open('<div class="message"><h2>Ошибка!</h2><p>'+data.message+'</p></div>');
        }*/
        $.fancybox.open('<div class="message"><p>'+data.message+'</p></div>');
    },"json");
    return false;
}
function topic_unhide_index(topic,type,target){
    $.post("/local/ajax/ajax.php",{"action":"topic_unhide_index","topic":topic,"type":type},function(data){
        /*if(data.error == 0)
        {
            target.parent().find(".hide-button").toggle();
        }else{
            $.fancybox.open('<div class="message"><h2>Ошибка!</h2><p>'+data.message+'</p></div>');
        }*/
        $.fancybox.open('<div class="message"><p>'+data.message+'</p></div>');
    },"json");
    return false;
}