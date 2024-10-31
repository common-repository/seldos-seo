<div class="searchEnginePreview">

    <input type="hidden" class="sep" value="-"/>
    <input type="hidden" class="sitename" value="<?=get_bloginfo('name')?>"/>
    <?php 
        global $post;
        $seldos_title = get_post_meta($post->ID,'seldos_seo_title',true);
        $seldos_desc = get_post_meta($post->ID,'seldos_seo_description',true);
    ?>
    
    <div class="formElement labelUp">
        <label for="postTitle"><?=__('Title','seldos-seo');?></label>
        <input type="text" id="postTitle" name="seldos_seo_title" placeholder="<?=get_option( 'post_title' );?>" value="<?=!empty($seldos_title)?$seldos_title:''?>"/>
    </div>
    
    <div class="formElement labelUp">
        <label for="postDesc"><?=__('Description','seldos-seo');?></label>
        <textarea name="seldos_seo_description" id="postDesc" cols="30" rows="10" placeholder="<?=get_option('post_desc');?>" maxlength="320"><?=!empty($seldos_desc)?$seldos_desc:''?></textarea>
    </div>

    <div class="col-10">
        <h2 class="preview-title"><?=__('Search Engines Preview')?></h2>
        <hr />
        <div class="tabs">
            <div class="tab active"><?=__('Google')?></div>
            <div class="tab"><?=__('Yandex')?></div>
        </div>
        
        <div class="tabsContent engines-preview">
            <div class="tabContent google-preview">
                <div class="title"></div>
                <div class="url"></div>
                <div class="desc"></div>
            </div>
            <div class="tabContent yandex-preview">
                <div class="title"><div class="favicon"></div></div>
                <div class="url"></div>
                <div class="desc"></div>
            </div>
        </div>
    
        
    </div>

</div>