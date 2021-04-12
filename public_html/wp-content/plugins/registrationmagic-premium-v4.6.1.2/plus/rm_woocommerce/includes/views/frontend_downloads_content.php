<div class="rmagic-table" id="rmwc_downloads_tab">
        <table class="user-content ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="false" style="display: table;">
        <tbody>
            <tr>
                <th>&#35;</th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_NAME'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_REMAINING_DOWNLOADS'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_ACCESS_EXPIRES'); ?></th>
                <th>&nbsp;</th>
            </tr>
            <?php 
            $i =1;
            foreach($downloads as $dl)
            {
              
                $rem_dls = (strlen($dl['downloads_remaining']) === 0) ? RM_WC_UI_Strings::get('LABEL_REMAINING_DLS_UNLIMITED') : $dl['downloads_remaining'];                
                $acc_exp = (!$dl['access_expires']) ? RM_WC_UI_Strings::get('LABEL_ACCESS_EXPIRES_NEVER') : strtok($dl['access_expires'],' ');
                echo "<tr><td>$i</td><td>{$dl['download_name']}</td><td>{$rem_dls}</td><td>{$acc_exp}</td><td><a href='{$dl['download_url']}'>".RM_WC_UI_Strings::get('LABEL_DOWNLOAD')."</a></td></tr>";
                $i++;
            }
            
            ?>
            
            <?php if(empty($downloads)): ?>
                    <tr><td colspan="5"><?php _e('There are no downloads yet.','registrationmagic-gold') ?></td></tr>
            <?php endif; ?>

        </tbody>
    </table>
</div>