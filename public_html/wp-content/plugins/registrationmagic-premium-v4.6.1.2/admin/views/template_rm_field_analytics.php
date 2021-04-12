<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>
<!--------WP Menu Bar

<div class="wpadminbar">Hi</div>

<div class="adminmenublock">
test</div>-->

<div class="rmagic">

    <!-----Operationsbar Starts-->

    <div class="operationsbar">
        <div class="rmtitle"><?php echo RM_UI_Strings::get('TITLE_FIELD_STAT_PAGE'); ?></div>
        <div class="icons">
            <a href="<?php echo get_admin_url() . "admin.php?page=rm_options_manage"; ?>"><img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . "images/global-settings.png"; ?>">
            </a></div>
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
              
                <li class="rm-form-toggle">
                    <?php
                    if (count($data->forms) !== 0)
                    {
                        echo RM_UI_Strings::get('LABEL_TOGGLE_FORM');
                        ?> 
                        <select id="rm_form_dropdown" name="rm_form_id" onchange="rm_load_page(this, 'analytics_show_field')">
                            <?php
                            foreach ($data->forms as $form_id => $form)
                                if ($data->current_form_id == $form_id)
                                    echo "<option value=$form_id selected>$form</option>";
                                else
                                    echo "<option value=$form_id>$form</option>";
                            ?>
                        </select>
                        </form>
                        <?php
                    }
                    ?>
                </li>
            </ul>
        </div>

    </div>
    <!--------Operationsbar Ends-->

    <!--------Filters
    
    <div class="rmfilters">
    <ul>
    <li>Filters </li>
    <li><a href="#" class="filteron">Time &#x2715;</a></li>
    <li><a href="#">Submissions &#x25BF;</a></li>
    <li><a href="#">Search &#x25BF;</a></li>
    <li class="sort"><a href="#">By Name &#x25BF;</a></li>
    <li class="sort">Sort </li>
    </ul>
    </div> -->

    <!-------Contentarea Starts-->

    <div class="rmagic-analytics rmagic-field-analytics">

        <?php
        if (count($data->field_stat) == 0):
            ?>
            <li class="rows">
                <div class="rmnotice" style="min-height: 45px;"><?php echo RM_UI_Strings::get('ERROR_STAT_INSUFF_DATA'); ?></div>
            </li>
            <?php
            return;
        endif;

//Generate divs for pie charts
        $i = 0;
        foreach ($data->field_stat as $field):

            if ($data->total_entries != 0)
                $sub_rate = round((double) $field->total_sub * 100 / (double) $data->total_entries, 2);
            else
                $sub_rate = 0.00;

            if ($i % 2 == 0):
                ?>

                <div class="rm-left-stats-box">
                    <div class="rm-box-title"><?php echo $field->label; ?></div>
                    <div class="rm-box-graph" id="<?php echo "rm_field_stat_chart_div_" . $i; ?>">
                    </div>
                <!-- <div class="rm-box-info-footer"><?php echo "Submission rate: " . $sub_rate . "% (" . $field->total_sub . "/" . $data->total_entries . ")"; ?></div> -->
                </div>
                <?php
            else:
                ?>
                <div class="rm-right-stats-box">
                    <div class="rm-box-title"><?php echo $field->label; ?></div>
                    <div class="rm-box-graph" id="<?php echo "rm_field_stat_chart_div_" . $i; ?>">
                    </div>
                <!-- <div class="rm-box-info-footer"><?php echo "Submission rate: " . $sub_rate . "% (" . $field->total_sub . "/" . $data->total_entries . ")"; ?></div> -->
                </div>

            <?php
            endif;
            $i++;
        endforeach;
        ?>

    </div>
</div>




<?php
/* * ************************************************************
 * *************     Chart drawing - Field Stats    *************
 * ************************************************************ */
?>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    function drawMultipleFieldCharts()
    {
        // Set chart options
        var options = {/*is3D : true,*/
            /*width:400,*/
            height: 300,
            fontName: 'Titillium Web',
            pieSliceTextStyle: {fontSize: 12},
            colors: ['#00A9DE', '#457B9D', '#8FACBF', '#C8D7E2', '#004F84', '#C2F4FF', '#D2DCE4', '#647A88', '#74DCFC','#00A9DE']};
<?php
$i = 0;

foreach ($data->field_stat as $field):

    $dataset = array();

    if (!$field->total_sub):
        ?>
                document.getElementById("<?php echo "rm_field_stat_chart_div_" . $i; ?>").innerHTML = "<div class='rmnotice'><?php echo RM_UI_Strings::get('MSG_NO_FIELD_STAT_DATA'); ?></div>";
        <?php
        $i++;
        continue;
    endif;

    foreach ($field->sub_stat as $option_value => $option_count)
    {
        $dataset[$option_value] = $option_count;
    }

    $json_table = RM_Utilities::create_json_for_chart('option_value', 'option_count', $dataset);
    ?>
            
            var data = new google.visualization.DataTable(<?php echo $json_table; ?>);

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('<?php echo "rm_field_stat_chart_div_" . $i; ?>'));
            chart.draw(data, options);

    <?php
    $i++;
endforeach;
?>

    }
</script></pre>

