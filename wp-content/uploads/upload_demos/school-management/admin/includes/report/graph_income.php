<div class="panel-body clearfix">
<?php	
        $month =array('1'=>esc_html__('January','school-mgt'),'2'=>esc_html__('February','school-mgt'),'3'=>esc_html__('March','school-mgt'),'4'=>esc_html__('April','school-mgt'),

        '5'=>esc_html__('May','school-mgt'),'6'=>esc_html__('June','school-mgt'),'7'=>esc_html__('July','school-mgt'),'8'=>esc_html__('August','school-mgt'),

        '9'=>esc_html__('September','school-mgt'),'10'=>esc_html__('Octomber','school-mgt'),'11'=>esc_html__('November','school-mgt'),'12'=>esc_html__('December','school-mgt'),);
    
        $year =isset($_POST['year'])?$_POST['year']:date('Y');
    
    global $wpdb;
    $table_name = $wpdb->prefix."smgt_income_expense";
    $report_6 = $wpdb->get_results("SELECT * FROM $table_name where invoice_type='income'");
    foreach($report_6 as $result)
    {
        $all_entry=json_decode($result->entry);
        $total_amount=0;
        foreach($all_entry as $entry)
        {
            $total_amount += $entry->amount;	
            $q="SELECT EXTRACT(MONTH FROM income_create_date) as date, sum($total_amount) as count FROM ".$table_name." WHERE YEAR(income_create_date) =".$year." group by month(income_create_date) ORDER BY income_create_date ASC";
            $result=$wpdb->get_results($q);		
        }
    }
    $sumArray = array(); 
    foreach ($result as $value) 
    { 
        if(isset($sumArray[$value->date]))
        {
            $sumArray[$value->date] = $sumArray[$value->date] + (int)$value->count;
        }
        else
        {
            $sumArray[$value->date] = (int)$value->count; 
        }		
    }
        
        $chart_array = array();
        $chart_array[] = array(esc_html__('Month','school-mgt'),esc_html__('Income','school-mgt'));
        $i=1;
        foreach($sumArray as $month_value=>$count)
        {
            $chart_array[]=array( $month[$month_value],(int)$count);
        }
        
        $options = Array(
                    'title' => esc_html__('Income Payment Report By Month','school-mgt'),
                    'titleTextStyle' => Array('color' => '#66707e'),
                    'legend' =>Array('position' => 'right',
                    'textStyle'=> Array('color' => '#66707e')),
                    'hAxis' => Array(
                        'title' => esc_html__('Month','school-mgt'),
                        'format' => '#',
                        'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                        'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                        'maxAlternation' => 2
                        ),
                    'vAxis' => Array(
                        'title' => esc_html__('Income Payment','school-mgt'),
                        'minValue' => 0,
                        'maxValue' => 6,
                        'format' => '#',
                        'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
                        'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans')
                        ),
                'colors' => array('#22BAA0')
                    );
        require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
        $GoogleCharts = new GoogleCharts;
        $chart = $GoogleCharts->load('column','chart_div')->get( $chart_array , $options );
        ?>
        
        <div id="chart_div" class="chart_div">
        <?php 
        if(empty($result)) 
        {?>
            <div class="clear col-md-12"><h3><?php esc_html_e("There is not enough data to generate report.",'school-mgt');?> </h3></div>
        <?php 
        } ?>
        </div>
        <!-- Javascript --> 
        <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
        <script type="text/javascript">
                <?php if(!empty($result))
                {
                    echo $chart;
                }
                ?>
        </script>
            </div>