jQuery(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
     
    //TABS
    $('.tabsContent .tabContent').hide();
    $('.tabsContent .tabContent:eq(0)').show();
    $('.tabs .tab').on('click',function(){
        
        var tabIndex = $(this).index();
        
        $('.tabsContent .tabContent').hide();
        $('.tabsContent .tabContent:eq('+tabIndex+')').show();
        
        $('.tabs .tab').removeClass('active');
        $(this).addClass('active');
    });
    //TABS
    
    //SC META
    $('.googleSCCode').on('keyup',function(){

        const regex = /<meta name="google-site-verification" content="(.*?)" \/>/g;
        const str = $(this).val();
        let m;

        while ((m = regex.exec(str)) !== null) {
            // This is necessary to avoid infinite loops with zero-width matches
            if (m.index === regex.lastIndex) {
                regex.lastIndex++;
            }
            
            // The result can be accessed through the `m`-variable.
            m.forEach((match, groupIndex) => {
                $(this).val(match);
            });
        }
        
    });
    //SC META
    
    //YANDEX METRİCA
    $('.yandexMetrica').on('keyup',function(){
        
        const regex = /id:(.*?),/g;
        const str = $(this).val();
        let m;

        while ((m = regex.exec(str)) !== null) {
            // This is necessary to avoid infinite loops with zero-width matches
            if (m.index === regex.lastIndex) {
                regex.lastIndex++;
            }
            
            // The result can be accessed through the `m`-variable.
            m.forEach((match, groupIndex) => {
                $(this).val(match);
            });
        }
        
    });
    //YANDEX METRİCA
    
    //AUTO PREVIEW
        /* TITLE */
        if($('.searchEnginePreview').length > 0){
            var titlePar = $('.searchEnginePreview #postTitle').attr('placeholder');
            var sep = $('.searchEnginePreview .sep').val();
            var sitename = $('.searchEnginePreview .sitename').val();
            var titleOrj = $('#titlewrap #title').val(); //Post title
            var titlePar = titlePar.replace('%postTitle%',titleOrj);
            var titlePar = titlePar.replace('%sep%',sep);
            var titlePar = titlePar.replace('%sitename%',sitename);
            $('.engines-preview .title').text(titlePar);
            if($('.searchEnginePreview #postTitle').val() == ''){
                $('.searchEnginePreview #postTitle').val(titlePar);
            }
            $('#titlewrap #title').on('keyup',function(){
                var titlePar = $('.searchEnginePreview #postTitle').attr('placeholder');
                var title = $(this).val().substring(0, 120);
                var titlePar = titlePar.replace('%postTitle%',title);
                var titlePar = titlePar.replace('%sep%',sep);
                var titlePar = titlePar.replace('%sitename%',sitename);
                $('.engines-preview .title').text(titlePar);
                $('.searchEnginePreview #postTitle').val(titlePar);
            });
            
            $('.searchEnginePreview #postTitle').on('keyup',function(){
                var title = $(this).val();
                if(title.trim().length > 0){
                    var title = title.replace('%postTitle%',titleOrj);
                    var title = title.replace('%sep%',sep);
                    var title = title.replace('%sitename%',sitename);
                    $('.engines-preview .title').text(title);
                }
            });
        }
        /* TITLE */
        
        /* DESC */
        if($('.searchEnginePreview').length > 0){
            var descPar = $('.searchEnginePreview #postDesc').attr('placeholder');
            var sep = $('.searchEnginePreview .sep').val();
            var sitename = $('.searchEnginePreview .sitename').val();
            if(descPar == ''){
                var descOrj = $('#wp-content-editor-container .wp-editor-area').val(); //Post desc
            }else{
                descOrj = descPar;
            }
            var descPar = descPar.replace('%postDesc%',descOrj);
            var descPar = descPar.replace('%sep%',sep);
            var descPar = descPar.replace('%sitename%',sitename);
            var descPar = descPar.substring(0, 320);
            $('.engines-preview .desc').text(descPar);
            if($('.searchEnginePreview #postDesc').val() == ''){
                $('.searchEnginePreview #postDesc').val(descPar);
            }
            $('#wp-content-editor-container .wp-editor-area').on('keyup',function(){
                var descPar = $('.searchEnginePreview #postDesc').attr('placeholder');
                var desc = $(this).val().substring(0, 320);
                var descPar = descPar.replace('%postDesc%',desc);
                var descPar = descPar.replace('%sep%',sep);
                var descPar = descPar.replace('%sitename%',sitename);
                var descPar = descPar.substring(0, 320);
                $('.engines-preview .desc').text(descPar);
                $('.searchEnginePreview #postDesc').text(descPar);
            });
            
            $('.searchEnginePreview #postDesc').on('keyup',function(){
                var desc = $(this).val().substring(0, 320);
                if(desc.trim().length > 0){
                    var desc = desc.replace('%postDesc%',descOrj);
                    var desc = desc.replace('%sep%',sep);
                    var desc = desc.replace('%sitename%',sitename);
                    $('.engines-preview .desc').text(desc);
                }
            });
        }
        /* DESC */
        
    //AUTO PREVIEW
    
    //404 LINK SAVE
    $('.error-save-btn').on('click',function(){
        var i = $(this).parent().parent();
        
        var ID = $(' .error-ID',i).val();
        var newLink = $(' .error-new-link',i).val();
        
        var data = {
			'action': 'seldos_seo_ajax_post',
			'type': 'error-link-save',
			'errorID': ID,
			'errorNewLink': newLink,
		};
        
        jQuery.post(ajaxurl, data, function(response) {
			alert(response);
		});
        
        return false;
    });
    //404 LINK SAVE
    
    //404 LİNK DELETE
    $('.error-delete-btn').on('click',function(){
        var i = $(this).parent().parent();
        
        var ID = $(' .error-ID',i).val();
        var newLink = $(' .error-new-link',i).val();
        
        var data = {
			'action': 'seldos_seo_ajax_post',
			'type': 'error-link-delete',
			'errorID': ID,
			'errorNewLink': newLink,
		};
        
        jQuery.post(ajaxurl, data, function(response) {
			alert(response);
            location.reload();
		});
        
        return false;
    });
    //404 LİNK DELETE
    
    //404 LINK PASTE CLEAN
    $('.error-new-link').on('keyup',function(){
        
        var domain = $('.error-domain').val();
        var url = $(this).val();
        
        var replace = new RegExp(domain,"g");       
        var r = url.replace(replace,'');
        
        $(this).val(r);
        
    });
    //404 LINK PASTE CLEAN
    
});
