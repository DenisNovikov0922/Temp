<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>

        <div class="rmagic-table" id="rm_inbox_tab">
            <div class="rm-user-row rm-icon dbfl">
                <i class="material-icons rm-bg" data-rm_apply_acc_bgcolor="true"><?php  _e('mail', 'registrationmagic-gold') ?></i>
            </div>
            <div class="rm-user-row dbfl">
                <h2><?php echo RM_UI_Strings::get('LABEL_INBOX'); ?></h2>
            </div>         
            
            <?php 
             if (!is_array($data->inbox->mails) || count($data->inbox->mails) < 1):
             
                echo RM_UI_Strings::get('LABEL_NO_MSG_USER_INBOX');
             
             else:
            ?>
            <table class="rm-user-data rm-inbox-data">
                <thead>
                    <th class="rm-bg-lt"><?php echo RM_UI_Strings::get('LABEL_DATE'); ?></th>
                    <th class="rm-bg-lt"><?php echo RM_UI_Strings::get("LABEL_EMAIL_SUB"); ?></th>
                    <th class="rm-bg-lt"><?php echo RM_UI_Strings::get("LABEL_EMAIL_BODY"); ?></th>
                    <th class="rm-bg-lt"><?php echo RM_UI_Strings::get("ACTION"); ?></th></tr>
                </thead>
                <?php
                
                
                //New Inbox
                if (is_array($data->inbox->mails)):
                    $i = 0 ;
                    foreach ($data->inbox->mails as $mail):
                        if($mail->is_read_by_user == '1')
                            $row_class = 'rm_inbox_email_read';
                        else
                            $row_class = 'rm_inbox_email_unread';
                ?>
                    <tr class="<?php echo $row_class; ?>">                        
                        <td class="rm_data" title="<?php echo $mail->sent_on; ?>">
                            <?php
                                $date_only = explode(" ",$mail->sent_on);
                                $date_only = $date_only[0];
                                echo $date_only;
                            ?>
                        </td>
                        <td class="rm_data">
                            <?php
                                echo strip_tags(htmlspecialchars_decode($mail->sub));
                            ?>
                        </td>
                        <td class="rm_data">
                            <?php
                                echo strip_tags(htmlspecialchars_decode($mail->body));
                            ?>
                        </td>
                        <td>
                            <a href="javascript:void(0)" onclick="rm_show_email_detail(<?php echo $i; ?>, this)" data-rminboxmailid="<?php echo $mail->mail_id; ?>"><?php echo RM_UI_Strings::get("VIEW"); ?></a>
                        </td>
                    </tr>
                    <tr class="rm_email_detail_container" id="rm_email_detail_view_<?php echo $i; ?>" style="display: none">
                        <td colspan="4">
                            <div class="rm_email_detail dbfl">
                                <div class="rm_email_detail_label difl"><?php  _e('Date', 'registrationmagic-gold') ?></div> 
                                <div class="rm_email_detail_body difl">   
                                    <?php echo $mail->sent_on; ?>
                                </div>
                            </div>
                            <div class="rm_email_detail dbfl">
                                <div class="rm_email_detail_label difl"><?php  _e('Subject', 'registrationmagic-gold') ?></div>
                                <div class="rm_email_detail_body difl">
                                    <?php echo htmlspecialchars_decode($mail->sub);?>
                                </div>
                            </div>
                            <div class="rm_email_detail dbfl">
                                <div class="rm_email_detail_label difl"><?php  _e('Message', 'registrationmagic-gold') ?></div>
                                <div class="rm_email_detail_body difl">
                                    <?php echo htmlspecialchars_decode($mail->body); ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php
                    $i++;
                    endforeach;                    
                endif;
                ////////////
                ?>
            </table>

            <?php
            endif;
            /*             * ********** Pagination Logic ************** */
            $max_pages_without_abb = 10;
            $max_visible_pages_near_current_page = 3; //This many pages will be shown on both sides of current page number.

            if ($data->inbox->total_pages_inbox > 1):
                ?>
                <ul class="rmpagination">
                    <?php
                    if ($data->inbox->curr_page_inbox > 1):
                        ?>
                        <li onclick="get_tab_and_redirect('rm_reqpage_inbox=1')"><a><?php echo RM_UI_Strings::get('LABEL_FIRST'); ?></a></li>
                        <li onclick="get_tab_and_redirect('rm_reqpage_inbox=<?php echo $data->inbox->curr_page_inbox - 1; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_PREVIOUS'); ?></a></li>
                        <?php
                    endif;
                    if ($data->inbox->total_pages_inbox > $max_pages_without_abb):
                        if ($data->inbox->curr_page_inbox > $max_visible_pages_near_current_page + 1):
                            ?>
                            <li><a> ... </a></li>
                            <?php
                            $first_visible_page = $data->inbox->curr_page_inbox - $max_visible_pages_near_current_page;
                        else:
                            $first_visible_page = 1;
                        endif;

                        if ($data->inbox->curr_page_inbox < $data->inbox->total_pages_inbox - $max_visible_pages_near_current_page):
                            $last_visible_page = $data->inbox->curr_page_inbox + $max_visible_pages_near_current_page;
                        else:
                            $last_visible_page = $data->inbox->total_pages_inbox;
                        endif;
                    else:
                        $first_visible_page = 1;
                        $last_visible_page = $data->inbox->total_pages_inbox;
                    endif;
                    for ($i = $first_visible_page; $i <= $last_visible_page; $i++):
                        if ($i != $data->inbox->curr_page_inbox):
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_inbox=<?php echo $i; ?>')"><a><?php echo $i; ?></a></li>
                        <?php else:
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_inbox=<?php echo $i; ?>')"><a class="active"><?php echo $i; ?></a></li>
                            <?php
                            endif;
                        endfor;
                        if ($data->inbox->total_pages_inbox > $max_pages_without_abb):
                            if ($data->inbox->curr_page_inbox < $data->inbox->total_pages_inbox - $max_visible_pages_near_current_page):
                                ?>
                            <li><a> ... </a></li>
                            <?php
                        endif;
                    endif;
                    ?>
                    <?php
                    if ($data->inbox->curr_page_inbox < $data->inbox->total_pages_inbox):
                        ?>
                        <li onclick="get_tab_and_redirect('rm_reqpage_inbox=<?php echo $data->inbox->curr_page_inbox + 1; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_NEXT'); ?></a></li>
                        <li onclick="get_tab_and_redirect('rm_reqpage_inbox=<?php echo $data->inbox->total_pages_inbox; ?>')"><a><?php echo RM_UI_Strings::get('LABEL_LAST'); ?></a></li>
                        <?php
                    endif;
                    ?>
                </ul>
    <?php endif; ?>
        </div>   

<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    function rm_show_email_detail(index, email_action_ele) {
        var detail_jqele = jQuery('#rm_email_detail_view_'+index);
        
        if(typeof detail_jqele != 'undefined') {
            
            if(detail_jqele.hasClass('rm_email_detail_container_expanded')) {
                detail_jqele.removeClass('rm_email_detail_container_expanded');
                detail_jqele.hide();
                return;
            }
                
            
            var exp_details = jQuery('.rm_email_detail_container_expanded');
            if(exp_details.length>0) {                
                exp_details.removeClass('rm_email_detail_container_expanded');
                exp_details.hide();
            }
            detail_jqele.addClass('rm_email_detail_container_expanded').show();
        }
        
        //mark as read
        var action_jqele = jQuery(email_action_ele);
        var email_row_jqele = action_jqele.closest('tr');
        if(email_row_jqele.hasClass('rm_inbox_email_unread')) {
            var email_id = action_jqele.data('rminboxmailid');
            rm_mark_email_read(email_id);
            email_row_jqele.switchClass('rm_inbox_email_unread', 'rm_inbox_email_read');           
            //update unread count on the tab title
            var tabhead_jqele = jQuery("#rminbox_tab_head");
            var localized_title = tabhead_jqele.data('rmtabloctitle');
            var unread_count = parseInt(tabhead_jqele.data('rminboxunreadcount'));
            unread_count--;
            if(unread_count > 0)
                tabhead_jqele.html('<i class="material-icons">mail</i> '+localized_title+" ("+unread_count+")");
            else
                tabhead_jqele.html('<i class="material-icons">mail</i> '+localized_title);
            tabhead_jqele.data('rminboxunreadcount',unread_count);
        }
    }
    
    function rm_mark_email_read(email_id) {
        
        if(typeof email_id === 'undefined')
            return;
        
        var data = {
                        action: 'rm_mark_email_read',
                        rm_email_id: email_id
                };
        
        jQuery.post(rm_ajax_url, data);
    }
</script></pre>