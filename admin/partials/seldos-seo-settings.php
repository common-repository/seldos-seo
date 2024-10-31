<?php 

    $post = $this->seldos_postSecurty($_POST); 
	extract($post);
    
    $step1R = ['step'];
	if($this->seldos_postControl($step1R) and  $step == 'general'){
        
        update_option( 'googleAnalyticCode', $googleAnalyticCode,false);
        update_option( 'googleSCCode', $googleSCCode,false);
        update_option( 'yandexMetrica', $yandexMetrica,false);
        update_option( 'wordpressSsl', (isset($wordpressSsl)?$wordpressSsl:''),false);
        update_option( 'wpjson', (isset($wpjson)?$wpjson:''),false);
        
        if(isset($wordpressSsl)){
            update_option( 'siteurl', str_replace(['http'],'https',get_bloginfo('url')) );
            update_option( 'home', str_replace(['http'],'https',get_bloginfo('url')) );
        }else{
            update_option( 'siteurl', str_replace(['https'],'http',get_bloginfo('url')) );
            update_option( 'home', str_replace(['https'],'http',get_bloginfo('url')) );
        }
        
    }else if($this->seldos_postControl($step1R) and  $step == 'titles'){
        
        $post_types = get_post_types('','names');
        unset($post_types['attachment']);
        unset($post_types['revision']);
        unset($post_types['nav_menu_item']);
        unset($post_types['custom_css']);
        unset($post_types['customize_changeset']);
        unset($post_types['oembed_cache']);
        foreach($post_types as $post_type){
            $a = get_post_type_object($post_type);
            $postName = (string) $a->name;
            update_option( $postName.'_title', ${$postName.'_title'},false);
            update_option( $postName.'_desc',  ${$postName.'_desc'},false);
        }
        
    }else if($this->seldos_postControl($step1R) and  $step == 'images'){
        update_option( 'autoAlt', (isset($autoAlt)?$autoAlt:''),false);
        update_option( 'autoSefName', (isset($autoSefName)?$autoSefName:''),false);
    }
    
    $googleAnalyticCode = empty(get_option( 'googleAnalyticCode' ))?'':get_option( 'googleAnalyticCode' );
    $googleSCCode = empty(get_option( 'googleSCCode' ))?'':get_option( 'googleSCCode' );
    $yandexMetrica = empty(get_option( 'yandexMetrica' ))?'':get_option( 'yandexMetrica' );
    $autoAlt = empty(get_option( 'autoAlt' ))?'':get_option( 'autoAlt' );
    $autoSefName = empty(get_option( 'autoSefName' ))?'':get_option( 'autoSefName' );
    $wordpressSsl = empty(get_option( 'wordpressSsl' ))?'':get_option( 'wordpressSsl' );
    $wpjson = empty(get_option( 'wpjson' ))?'':get_option( 'wpjson' );
    $generateSitemap = empty(get_option( 'generateSitemap' ))?'':get_option( 'generateSitemap' );

?><div class="container">
    <div class="seldosseo">
        <div class="pageTitle">
            <h1><?=__('Settings','seldos-seo')?></h1>
        </div>
        
        <div class="pageContent">
            
            <div class="tabs pageHeader">
                <div class="tab active">
                   <?=__('General','seldos-seo')?>
                </div>
                <div class="tab">
                   <?=__('Titles','seldos-seo')?>
                </div>
                <div class="tab">
                   <?=__('Image','seldos-seo')?>
                </div>
            </div>
            
            <!-- GENERAL SETTINGS -->
            <div class="tabsContent">
                
                <div class="tabContent">
                    <form action="" method="post">
                        <input type="hidden" name="step" value="general" />
                        <div class="col-10">
                            
                            <div class="col-10">
                                <div class="tabContentHead">
                                    <?=__('Wordpress Settings','seldos-seo')?>
                                </div>
                                
								<div class="formElement">
									<div class="checkbox">
                                        <input type="checkbox" id="wpjson" name="wpjson" value="1" <?=$wpjson=='1'?'checked':''?>/>
										<label for="wpjson"><?=__('Json Api Disable','seldos-seo')?></label>
									</div>
									<div class="checkbox">
										<input type="checkbox" id="wordpressSsl" name="wordpressSsl" value="1" <?=$wordpressSsl=='1'?'checked':''?>/>
										<label for="wordpressSsl"><?=__('SSL Active','seldos-seo')?></label>
									</div>
									<div class="checkbox">
										<input type="checkbox" id="generateSitemap" name="generateSitemap" value="1" <?=$generateSitemap=='1'?'checked':''?> disabled />
										<label for="generateSitemap"><?=__('Generate Sitemap.xml','seldos-seo')?> (<?=__('Coming Soon','seldos-seo')?>)</label>
									</div>
								</div>
                            </div>
							
                            <div class="col-10">
                                <div class="tabContentHead">
                                    <?=__('Google Settings','seldos-seo')?>
                                </div>
                                
                                <div class="col-5">
                                    <div class="formElement labelUp">
                                        <label for=""><?=__('Google Analytic Code','seldos-seo')?></label>
                                        <input type="text" name="googleAnalyticCode" placeholder="UA-XXXXXXXX-X" value="<?=$googleAnalyticCode?>"/>
                                    </div>
                                </div>
                                
                                <div class="col-5">
                                    <div class="formElement labelUp">
                                        <label for=""><?=__('Google Search Console Code','seldos-seo')?></label>
                                        <input type="text" name="googleSCCode" class="googleSCCode" placeholder="UA-XXXXXXXX-X" value="<?=$googleSCCode?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-10">
                                <div class="tabContentHead">
                                    <?=__('Yandex Settings','seldos-seo')?>
                                </div>
                                
                                <div class="col-10">
                                    <div class="formElement labelUp">
                                        <label for=""><?=__('Yandex Metrica Code','seldos-seo')?></label>
                                        <input type="text" name="yandexMetrica" class="yandexMetrica" placeholder="UA-XXXXXXXX-X" value="<?=$yandexMetrica?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <hr />
                            
                            <div class="buttons">
                                <button><?=__('Save','seldos-seo')?></button>
                            </div>
                            
                        </div>
                    </form>
                </div>
                <!-- GENERAL SETTINGS -->
                
                <!-- TITLE DESC -->
                <div class="tabContent">
                    <form action="" method="post">
                        <input type="hidden" name="step" value="titles" />
                        <div class="col-12">
                        
                            <div class="col-12">
                                <?php 
                                    $post_types = get_post_types('','names');
                                    unset($post_types['attachment']);
                                    unset($post_types['revision']);
                                    unset($post_types['nav_menu_item']);
                                    unset($post_types['custom_css']);
                                    unset($post_types['customize_changeset']);
                                    unset($post_types['oembed_cache']);
                                    foreach ( $post_types as $post_type ) {
                                       $a = get_post_type_object($post_type);
                                ?>
                                <div class="tabContentHead">
                                    <?=$a->label?>
                                </div>
                                <div class="col-10">
                                    <div class="formElement labelUp">
                                        <label for=""><?=$a->label?> <?=__('Title','seldos-seo');?></label>
                                        <input type="text" name="<?=$post_type?>_title" placeholder="%postTitle% %sep% %sitename%" value="<?=get_option( $a->name.'_title' )?get_option( $a->name.'_title' ):'%postTitle% %sep% %sitename%'?>"/>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="formElement labelUp">
                                        <label for=""><?=$a->label?> <?=__('Description','seldos-seo');?></label>
                                        <input type="text" name="<?=$post_type?>_desc" placeholder="%postDesc%" value="<?=get_option( $a->name.'_desc' )?get_option( $a->name.'_desc' ):'%postDesc%'?>"/>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                            
                            <hr />
                            
                            <div class="buttons">
                                <button><?=__('Save','seldos-seo')?></button>
                            </div>
                            
                        </div>
                    </form>
                </div>
                <!-- TITLE DESC -->
				
                <!-- IMAGES -->
                <div class="tabContent"> 
                    
					<form action="" method="post">
                        <input type="hidden" name="step" value="images" />
                        <div class="col-10">
                            
                            <div class="col-10">
                                <div class="tabContentHead">
                                    <?=__('IMAGES SEO SETTINGS','seldos-seo')?>
                                </div>
                                
                                <div class="checkbox">
                                    <div class="formElement">
                                    
                                        <div class="checkbox">
                                            <input type="checkbox" id="autoAlt" name="autoAlt" value="1" <?=$autoAlt=='1'?'checked':''?>/>
                                            <label for="autoAlt"><?=__('Auto Alt Attribute','seldos-seo')?></label>
                                        </div>
                                        
                                        <div class="checkbox">
                                            <input type="checkbox" id="autoSefName" name="autoSefName" value="1" <?=$autoSefName=='1'?'checked':''?>/>
                                            <label for="autoSefName"><?=__('Auto Upload SEO Name','seldos-seo')?></label>
                                        </div>
                                        
                                    </div>
								</div>
                            </div>
                            <hr />
                            
                            <div class="buttons">
                                <button><?=__('Save','seldos-seo')?></button>
                            </div>
                            
                        </div>
                    </form>
					
                </div>
                <!-- IMAGES -->
                
            </div>
            
        </div>
    </div>
</div>