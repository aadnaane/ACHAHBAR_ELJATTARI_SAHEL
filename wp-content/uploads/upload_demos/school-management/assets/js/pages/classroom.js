jQuery(document).ready(function($){
	"use strict";	
var table =  jQuery('#meeting_list').DataTable({
	responsive: true,
	 'order': [1, 'asc'],
	 "aoColumns":[
	 				  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
	//language:<?php //echo smgt_datatable_multi_language();?>	

       });	

    $('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
	
	 $("#delete_selected").on('click', function()
		{	
			if ($('.select-checkbox:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}
		else{
				var alert_msg=confirm("Are you sure you want to delete this record?");
				if(alert_msg == false)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
	});

	var table =  jQuery('#past_participle_list').DataTable({
	responsive: true,
	 'order': [1, 'asc'],
 	dom: 'lBfrtip',
	buttons: [
	{
		extend: 'print',
		text:'Print',
		title: 'Past Participle List',
	}],
	"aoColumns":[
	        {"bSortable": true},
	    	{"bSortable": true},
	    ],
	//language:<?php //echo smgt_datatable_multi_language();?>		
       });

	$('#meeting_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$("#start_date").datepicker({
        dateFormat: "yy-mm-dd",
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#end_date").datepicker("option", "minDate", dt);
        }
    });
    $("#end_date").datepicker({
       dateFormat: "yy-mm-dd",
	   minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#start_date").datepicker("option", "maxDate", dt);
        }
    });

});