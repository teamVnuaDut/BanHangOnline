;jQuery(document).ready(function($) {
    "use strict";

    const
            $window = $(window),
            $body = $('body'),

            // Project Search
            etwwptSearchSection = $('#etwwpt-search-section'),
            etwwptDemos = $('#etwwpt-demos'),
            etwwptBuilder = $('#etwwpt-builder'),
            etwwptSearchField = $('#etwwpt-search-field'),
            etwwptType = $('#etwwpt-type'),

            // Project
            etwwptProjectSection = $('#etwwpt-project-section'),
            etwwptProjectGrid = $('#etwwpt-project-grid'),
            etwwptProjectLoadMore = $('#etwwpt-load-more-project'),

            // Project Count
            etwwptInitProjectStartCount = 0,
            etwwptInitProjectEndCount = 8,
            etwwptProjectLoadCount = 4,

            // Project Loading/Load more
            etwwptLoaderHtml = '<span id="etwwpt-loader"></span>',
            etwwptLoaderSelector = '#etwwpt-loader',
            etwwptLoadingText = '<span class="etwwpt-pro-loading"></span>',
            etwwptLoadedText = WLTM.message.allload,
            etwwptNothingFoundText = WLTM.message.notfound,

            // Group Project 
            etwwptGroupProjectSection = $('#etwwpt-group-section'),
            etwwptGroupProjectGrid = $('#etwwpt-group-grid'),
            etwwptGroupProjectBack = $('#etwwpt-group-close'),
            etwwptGroupProjectTitle = $('#etwwpt-group-name');

        let
            // Project Data
            etwwptProjectData = WLTM.alldata,

            // Project Count
            etwwptProjectStartCount = etwwptInitProjectStartCount,
            etwwptProjectEndCount = etwwptInitProjectEndCount,

            // Project Options Value
            etwwptDemosValue = etwwptDemos.val(),
            etwwptBuilderValue = etwwptBuilder.val(),
            etwwptSearchFieldValue = etwwptSearchField.val(),
            etwwptTypeValue = etwwptType.val(),

            // Project Start End Count Fnction for Options
            etwwptProjectStartEndCount,

            // Project Print Function
            etwwptProjectPirnt,

            // Check Image Load Function
            imageLoad,

            // Scroll Magic Infinity & Reveal Function
            etwwptInfinityLoad,
            etwwptElementReveal,

            // Ajax Fail Message
            failMessage,
            msg = '';

        // Project Start End Count Fnction for Options
        etwwptProjectStartEndCount = () => {
            etwwptProjectStartCount = etwwptInitProjectStartCount;
            etwwptProjectEndCount = etwwptInitProjectEndCount;
        }

        // Projects Demo Type Select
        etwwptDemos.selectric({
            onChange: (e) => {
                etwwptDemosValue = $(e).val();
                etwwptSearchFieldValue = '';
                etwwptSearchField.val('');
                etwwptProjectStartEndCount();
                etwwptProjectPirnt(etwwptProjectData);
            },
        });

        // Projects Builder Type Select
        etwwptBuilder.selectric({
            onChange: (e) => {
                etwwptBuilderValue = $(e).val();
                etwwptProjectStartEndCount();
                etwwptProjectPirnt(etwwptProjectData);
            },
        });

        // Projects Pro/Free Type Select
        etwwptType.selectric({
            onChange: (e) => {
                etwwptTypeValue = $(e).val();
                etwwptProjectStartEndCount();
                etwwptProjectPirnt(etwwptProjectData);
            },
        });

        // Projects Search
        etwwptSearchField.on('input', () => {
            if (!etwwptSearchField.val()) {
                etwwptSearchFieldValue = etwwptSearchField.val().toLowerCase();
                etwwptProjectStartEndCount();
                etwwptProjectPirnt(etwwptProjectData);
            }
        });
        etwwptSearchField.on('keyup', (e) => {
            if (e.keyCode == 13) {
                etwwptSearchFieldValue = etwwptSearchField.val().toLowerCase();
                etwwptProjectStartEndCount();
                etwwptProjectPirnt(etwwptProjectData);
            }
        });

        // Check Image Load Function
        imageLoad = () => {
            $('.etwwpt-image img').each((i, e) => $(e).on('load', () => $(e).addClass('finish')));
        };

        // Projects Print/Append on HTML Dom Function
        etwwptProjectPirnt = function (etwwptProjectData, types = 'push') {
            
            // Projects Data Filter for Template/Blocks
            etwwptProjectData = etwwptProjectData.filter(i => i.demoType == etwwptDemosValue)
            // Projects Data Filter for Builder Support
            if (etwwptBuilderValue != "all") {
                etwwptProjectData = etwwptProjectData.filter(i => i.builder.filter(j => j == etwwptBuilderValue)[0])
            }
            // Projects Data Filter for Free/Pro
            if (etwwptTypeValue != "all") {
                // etwwptProjectData = etwwptProjectData.filter(i => i.isPro == etwwptTypeValue)
                etwwptProjectData = etwwptProjectData.filter(i => i.tmpType == etwwptTypeValue)
            }
            // Projects Data Filter by Search
            if (etwwptSearchFieldValue != "") {
                etwwptProjectData = etwwptProjectData.filter(i => i.tags.filter(j => j == etwwptSearchFieldValue)[0])
            }

            let etwwptPrintDataArray = Array.from(new Set(etwwptProjectData.map(i => i.shareId))).map(j => etwwptProjectData.find(a => a.shareId === j)),
                etwwptPrintData = etwwptPrintDataArray.slice(etwwptProjectStartCount, etwwptProjectEndCount),
                html = '';
            for (let i = 0; i < etwwptPrintData.length; i++) {
                let {
                    thumbnail,
                    id,
                    demoUrl,
                    shareId,
                    title
                } = etwwptPrintData[i],
                    totalItem = etwwptProjectData.filter(i => i.shareId == shareId).length,
                    singleItem = totalItem == 1 ? 'etwwpt-project-item-signle' : '';
                html += `<div class="${singleItem} col-xl-4 col-md-6 col-12">
                            <div class="etwwpt-project-item ${singleItem}" data-group="${shareId}">
                                <div class="etwwpt-project-thumb">
                                    <div class="etwwpt-image">
                                        <img src="${WLTM.img_url + thumbnail}" alt="${title}" />
                                        <span class="img-loader"></span>
                                    </div>
                                </div>
                                <div class="etwwpt-project-info">
                                    <h5 class="title">${shareId}</h5>
                                    <h6 class="sub-title">${totalItem} ${etwwpUcfirst(etwwptDemosValue)} ${WLTM.message.packagedesc}</h6>
                                </div>
                            </div>
                        </div>`;
            }
            if (types == "append") {
                etwwptProjectGrid.append(html);
            } else {
                etwwptProjectGrid.html(html);
            }
            if (etwwptPrintDataArray.length == 0) {
                etwwptProjectGrid.html(`<h2 class="etwwpt-project-message text-danger">${etwwptNothingFoundText}</h2>`);
                $(etwwptLoaderSelector).addClass('finish').html('');
            } else {
                if (etwwptPrintDataArray.length <= etwwptProjectEndCount) {
                    $(etwwptLoaderSelector).addClass('finish').html(etwwptLoadedText);
                } else {
                    $(etwwptLoaderSelector).removeClass('finish').html(etwwptLoadingText);
                }
            }
            imageLoad();
        }

        // Scroll Magic for Infinity Load Function
        etwwptInfinityLoad = () => {
            setTimeout(() => {
                let etwwptInfinityController = new ScrollMagic.Controller(),
                    etwwptInfinityscene = new ScrollMagic.Scene({
                        triggerElement: '#etwwpt-loader',
                        triggerHook: 'onEnter',
                        offset: 0
                    })
                    .addTo(etwwptInfinityController)
                    .on('enter', (e) => {
                        if (!$(etwwptLoaderSelector).hasClass('finish')) {
                            etwwptProjectStartCount = etwwptProjectEndCount;
                            etwwptProjectEndCount += etwwptProjectLoadCount;
                            setTimeout(() => {
                                etwwptProjectPirnt(etwwptProjectData, 'append')
                            }, 200);
                        }
                    });
            });
        }

        // Scroll Magic for Reveal Element Function
        etwwptElementReveal = () => {
            let etwwptInfinityController = new ScrollMagic.Controller();
            $('.etwwpt-group-item').each(function () {
                new ScrollMagic.Scene({
                        triggerElement: this,
                        triggerHook: 'onEnter',
                        offset: 50
                    })
                    .setClassToggle(this, "visible")
                    .addTo(etwwptInfinityController);
            })
        }

        if(etwwptProjectData.length) {
            etwwptProjectLoadMore.append(etwwptLoaderHtml);
            etwwptProjectPirnt(etwwptProjectData);
            etwwptInfinityLoad();
        }

        function etwwpUcfirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Group Project Open Function
        etwwptProjectGrid.on('click', '.etwwpt-project-item', function (e) {
            e.preventDefault();
            let etwwptProjectGroupData = etwwptProjectData;
            // Projects Data Filter for Template/Blocks
            etwwptProjectGroupData = etwwptProjectGroupData.filter(i => i.demoType == etwwptDemosValue)
            // Projects Data Filter for Builder Support
            if (etwwptBuilderValue != "all") {
                etwwptProjectGroupData = etwwptProjectGroupData.filter(i => i.builder.filter(j => j == etwwptBuilderValue)[0])
            }
            // Projects Data Filter for Free/Pro
            if (etwwptTypeValue != "all") {
                etwwptProjectGroupData = etwwptProjectGroupData.filter(i => i.tmpType == etwwptTypeValue)
            }
            // Projects Data Filter by Search
            if (etwwptSearchFieldValue != "") {
                etwwptProjectGroupData = etwwptProjectGroupData.filter(i => i.tags.filter(j => j == etwwptSearchFieldValue)[0])
            }
            let $this = $(this),
                $group = $this.data('group'),
                etwwptPrintGroupData = etwwptProjectGroupData.filter(i => i.shareId == $group),
                etwwptGroupHTML = '',
                $impbutton = '',
                $tmptitle = '';
            for (let i = 0; i < etwwptPrintGroupData.length; i++) {
                let {
                    thumbnail,
                    id,
                    demoUrl,
                    shareId,
                    title,
                    isPro,
                    freePlugins,
                    proPlugins,
                    requiredtheme,
                    fullimage,
                    tt
                } = etwwptPrintGroupData[i];
                if( requiredtheme ){ tt = 'rtheme' } else {tt = ''};
                if(isPro == '1' ){
                    $impbutton = `<a href="${WLTM.prolink}" target="_blank">${WLTM.buttontxt.buynow}</a>`;
                    $tmptitle = `<h5 class="title">${title} <span>(${WLTM.prolabel})</span></h5>`;
                }else{
                    $impbutton = `<a href="#" class="etwwpttemplateimp button ${tt}" data-templpateopt='{"parentid":"${shareId}","templpateid":"${id}","templpattitle":"${title}","message":"Successfully ${etwwpUcfirst(shareId)+ ' -> ' + title} has been imported.","thumbnail":"${WLTM.img_url + thumbnail}","freePlugins":"${freePlugins}", "proPlugins":"${proPlugins}","requiredtheme":"${requiredtheme}", "${tt}":"${requiredtheme}" }'>${WLTM.buttontxt.import}</a>`;
                    $tmptitle = `<h5 class="title">${title}</h5>`;
                }
                etwwptGroupHTML += `<div class="etwwpt-group-item col-xl-4 col-md-6 col-12">
                            <div class="etwwpt-project-item">
                                <div class="etwwpt-project-thumb">
                                    <a href="${WLTM.img_url + thumbnail}" class="etwwpt-image etwwpt-image-popup">
                                        <img src="${WLTM.img_url + thumbnail}" data-preview='{"templpateid":"${id}","templpattitle":"${title}","parentid":"${shareId}","fullimage":"${WLTM.img_url + fullimage}"}' alt="${title}" />
                                        <span class="img-loader"></span>
                                    </a>
                                    <div class="etwwpt-actions">
                                        <a href="${demoUrl}" target="_blank">${WLTM.buttontxt.preview}</a>
                                        ${$impbutton}
                                    </div>
                                </div>
                                <div class="etwwpt-project-info">
                                    ${$tmptitle}
                                    <h6 class="sub-title">${shareId}</h6>
                                </div>
                            </div>
                            <div id="etwwpt-popup-prev-${id}" style="display: none;"><img src="${WLTM.img_url + fullimage}" alt="${title}" style="width:100%;"/></div>
                        </div>`;
            }
            if (!$(etwwptLoaderSelector).hasClass('finish')) {
                $(etwwptLoaderSelector).addClass('finish group-loaded');
            }
            etwwptProjectSection.addClass('group-project-open');
            etwwptSearchSection.addClass('group-project-open');
            let topPotision;
            
            etwwptSearchSection.offset().top > 32 && $(window).scrollTop() < etwwptSearchSection.offset().top ? topPotision = etwwptSearchSection.offset().top - $(window).scrollTop() : topPotision = 32;

            etwwptGroupProjectSection.fadeIn().css({
                "top": topPotision + 'px',
                "left": etwwptSearchSection.offset().left + 'px'
            });
            $body.css('overflow-y', 'hidden');
            etwwptGroupProjectTitle.html($group);
            etwwptGroupProjectGrid.html(etwwptGroupHTML);
            etwwptElementReveal();
            imageLoad();
        });

        // Group Project Close Function
        etwwptGroupProjectBack.on('click', function (e) {
            e.preventDefault();
            etwwptGroupProjectSection.fadeOut('fast');
            etwwptGroupProjectTitle.html('');
            etwwptGroupProjectGrid.html('');
            etwwptProjectSection.removeClass('group-project-open');
            etwwptSearchSection.removeClass('group-project-open');
            $body.css('overflow-y', 'auto');
            imageLoad();
            if ($(etwwptLoaderSelector).hasClass('group-loaded')) {
                $(etwwptLoaderSelector).removeClass('finish group-loaded');
            }
        });

        // Scroll To Top
        let $etwwptScrollToTop = $(".etwwpt-scrollToTop"),
            $etwwptGroupScrollToTop = $(".etwwpt-groupScrollToTop");
        $window.on('scroll', function () {
            if ($window.scrollTop() > 100) {
                $etwwptScrollToTop.addClass('show');
            } else {
                $etwwptScrollToTop.removeClass('show');
            }
        });
        $etwwptScrollToTop.on('click', function (e) {
            e.preventDefault();
            $("html, body").animate({
                scrollTop: 0
            });
        });
        etwwptGroupProjectSection.on('scroll', function () {
            if (etwwptGroupProjectSection.scrollTop() > 100) {
                $etwwptGroupScrollToTop.addClass('show');
            } else {
                $etwwptGroupScrollToTop.removeClass('show');
            }
        });
        $etwwptGroupScrollToTop.on('click', function (e) {
            e.preventDefault();
            etwwptGroupProjectSection.animate({
                scrollTop: 0
            });
        });


    /*
    * PopUp button
    * Preview PopUp
    * Data Import Request
    */
    $('body').on('click', 'a.etwwpttemplateimp', function(e) {
        e.preventDefault();

        var $this = $(this),
            template_opt = $this.data('templpateopt');

        $('.etwwpt-edit').html('');
        $('#etwwptpagetitle').val('');
        $(".etwwptpopupcontent").show();
        $(".etwwptmessage").hide();
        $(".etwwptmessage p").html( template_opt.message );

        // dialog header
        $("#etwwpt-popup-area").attr( "title", etwwpUcfirst(template_opt.parentid) + ' → ' +template_opt.templpattitle );

        var htbtnMarkuplibrary = `<a href="#" class="wptemplataimpbtn" data-btnattr='{"templateid":"${template_opt.templpateid}","parentid":"${template_opt.parentid}","templpattitle":"${template_opt.templpattitle}"}'>${WLTM.buttontxt.tmplibrary}</a>`;
        var htbtnMarkuppage = `<a href="#" class="wptemplataimpbtn etwwptdisabled" data-btnattr='{"templateid":"${template_opt.templpateid}","parentid":"${template_opt.parentid}","templpattitle":"${template_opt.templpattitle}"}'>${WLTM.buttontxt.tmppage}</a>`;

        // Enter page title then enable button
        $('#etwwptpagetitle').on('input', function () {
            if( !$('#etwwptpagetitle').val() == '' ){
                $(".etwwptimport-button-dynamic-page .wptemplataimpbtn").removeClass('etwwptdisabled');
            } else {
                $(".etwwptimport-button-dynamic-page .wptemplataimpbtn").addClass('etwwptdisabled');
            }
        });
        //if ( $( '.etwwptemplata-requiredplugins > .etwwptemplate-theme' ).length <= 0 ) {
        //    $(this).closest(".etwwptpopupcontent").hide();   
        //}
        // button Dynamic content
        $( ".etwwptimport-button-dynamic" ).html( htbtnMarkuplibrary );
        $( ".etwwptimport-button-dynamic-page" ).html( htbtnMarkuppage );
        $( ".ui-dialog-title" ).html( etwwpUcfirst( template_opt.parentid ) + ' &#8594; ' +template_opt.templpattitle );

        $this.addClass( 'updating-message' );
        // call dialog
        function OpenPopup(){
            $( "#etwwpt-popup-area" ).dialog({
                modal: true,
                minWidth: 500,
                minHeight:300,
                buttons: {
                    Close: function() {
                      $( this ).dialog( "close" );
                    }
                }
            });
        }

        $.ajax( {
            url: WLTM.ajaxurl,
            type: 'POST',
            data: {
                action: 'etww_ajax_get_required_plugin',
                freeplugins: template_opt.freePlugins,
                proplugins: template_opt.proPlugins,
                requiredtheme: template_opt.requiredtheme,
                security     : WLTM.etww_data_nonce,
            },
            complete: function( data ) {
                $( ".etwwptemplata-requiredplugins" ).html( data.responseText );
                OpenPopup();
                $this.removeClass( 'updating-message' );
            }
        });


    });

    // Preview PopUp
    $('body').on( 'click','.etwwpt-image-popup img', function(e){
        e.preventDefault();

        var $this = $(this),
            preview_opt = $this.data('preview');

        // dialog header
        $( "#etwwpt-popup-prev-"+preview_opt.templpateid ).attr( "title", etwwpUcfirst(preview_opt.parentid) + ' → ' + preview_opt.templpattitle );
        $( ".ui-dialog-title" ).html( etwwpUcfirst( preview_opt.parentid ) + ' &#8594; ' +preview_opt.templpattitle );

        $( "#etwwpt-popup-prev-"+preview_opt.templpateid ).dialog({
            modal: true,
            width: 'auto',
            maxHeight: ( $(window).height()-50 ),
            buttons: {
                Close: function() {
                  $( this ).dialog( "close" );
                }
            }
        });

    });

    // Import data request
    $('body').on('click', 'a.wptemplataimpbtn', function(e) {
        e.preventDefault();

        var $this = $(this),
            pagetitle = ( $('#etwwptpagetitle').val() ) ? ( $('#etwwptpagetitle').val() ) : '',
            pagetemplate = ( document.getElementById("etwwpttemplates").value ) ? ( document.getElementById("etwwpttemplates").value ) : '',
            pagestatus = ( document.getElementById("etwwptstatus").value ) ? ( document.getElementById("etwwptstatus").value ) : '',
            databtnattr = $this.data('btnattr');
        $.ajax({
            url: WLTM.ajaxurl,
            data: {
                'action'       : 'etww_ajax_request',
                'httemplateid' : databtnattr.templateid,
                'htparentid'   : databtnattr.parentid,
                'httitle'      : databtnattr.templpattitle,
                'pagetitle'    : pagetitle,
                'pagetemplate' : pagetemplate,
                'status'       : pagestatus,
                'security'     : WLTM.etww_data_nonce,
            },
            dataType: 'JSON',
            beforeSend: function(){
                $(".etwwptspinner").addClass('loading');
                $(".etwwptpopupcontent").hide();
            },
            success:function(data) {
                $(".etwwptmessage").show();
                var tmediturl = WLTM.adminURL+"post.php?post="+ data.id +"&action=elementor";
                $('.etwwpt-edit').html('<a href="'+ tmediturl +'" target="_blank">'+ data.edittxt +'</a>');
            },
            complete:function(data){
                $(".etwwptspinner").removeClass('loading');
                $(".etwwptmessage").css( "display","block" );
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });

    });


});
