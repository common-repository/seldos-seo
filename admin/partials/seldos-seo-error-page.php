<?php 
    global $wpdb;
    
    $errorPage = $wpdb->get_results("SELECT * FROM seldos_seo_404 ORDER BY new_link ASC");
    
    $links = [];
    foreach($errorPage as $l){
        $links[] = [
            'id' => '<input type="hidden" class="error-ID" value="'.$l->id.'" />'.$l->id,
            'domain' => '<input type="hidden" class="error-domain" value="'.str_replace('/','\/',get_bloginfo('url')).'" />'.get_bloginfo('url'),
            'link' => $l->link,
            'new_link' => '<input type="text" class="error-new-link" value="'.$l->new_link.'"/>',
            'edit' => '<input type="submit" class="btn button error-save-btn button-primary" value="'.__('Save','seldos-seo').'"/><button class="btn button error-delete-btn button-link-delete" onclick="return confirm('.__('Are You SURE?','seldos-seo').');">'.__('DELETE','seldos-seo').'</button>',
        ]; 
    }
    
    $table = new seldos_seo_table_generator();
    $table->table_thead = [
        'id'        => 'ID',
        'domain'     => 'Domain',
        'link'     => 'Link',
        'new_link'     => 'New Link',
        'edit'    => 'Edit',
    ];
    
    $table->table_data = $links;
    
  //$table->table_data = [
  //    [
  //        'id'        => 'ID',
  //        'link'     => 'Link',
  //        'new_link'     => 'New Link',
  //        //'edit'    => 'Edit',
  //    ],
  //    [
  //        'id'        => 'ID',
  //        'link'     => 'Link',
  //        'new_link'     => 'New Link',
  //        //'edit'    => 'Edit',
  //    ]
  //];

    
    $table->prepare_items();

?>
<div class="wrap">
    
    <div id="icon-users" class="icon32"><br/></div>
    <h2><?=__('ALL 404 Page','seldos-seo');?></h2>
    
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="error-filter" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
        <?php $table->display() ?>
    </form>
    
</div>
