<?php
/* #############################################################
*	
*					 -- DO NOT REMOVED --
*
* 	  			THIS CODE IS CREATED FOR CORE SIS.
*	  USING IT WITHOUT THE PROPER PERMISSION FROM EGLOBALMD
*			 IS PROHIBITED BUT LIMITED FROM OTHER 
*				  OPEN SOURCE THAT ARE USED.
*
*	------------------------------------------------------------
*	
*	CREATED BY: JBG
*	DATE CREATED: 01 JANUARY 2010
*	FOR ANY ISSUE AND BUG FIXES VISIT http://www.eglobalmd.com
*	OR E-mail support@eglobalmd.com
*
*  ############################################################ */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CORE - Student Information System</title>
<meta name="robots" content="noindex" />
<meta name="robots" content="nofollow" />
<link type="text/css" href="css/style.css" rel="stylesheet" media="all" />	
<link type="text/css" href="css/style_tableList.css" rel="stylesheet" media="all" />	
<link type="text/css" href="css/style_lookup.css" rel="stylesheet" media="all" />	
<link type="text/css" href="css/style_formObjects.css" rel="stylesheet" media="all" />	
<!--[if lte IE 7]>
<link href="css/styles_ie.css" rel="stylesheet" type="text/css" media="all" />
<![endif]-->
<!--[if lte IE 6]>
<link href="css/styles_ie6.css" rel="stylesheet" type="text/css" media="all" />
<![endif]-->
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" media="all" />	
<link rel="stylesheet" type="text/css" href="css/jquery_superfish/superfish.css" media="all">
<link rel="stylesheet" type="text/css" href="css/jquery_datepicker/datePicker.css" media="all">      
<link rel="stylesheet" type="text/css" href="css/jquery_validate/screen.css" media="all" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jscharts/jscharts.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="js/jquery_superfish/hoverIntent.js"></script>
<script type="text/javascript" src="js/jquery_superfish/superfish.js"></script>
<script type="text/javascript" src="js/jquery_superfish/supersubs.js"></script>
<script type="text/javascript" src="js/jquery_datepicker/date.js"></script>
<!--[if IE]><script type="text/javascript" src="js/jquery_datepicker/jquery.bgiframe.min.js"></script><![endif]-->
<script type="text/javascript" src="js/jquery_datepicker/jquery.datePicker.js"></script>    

<script type="text/javascript" src="js/jquery_validate/jquery.metadata.js"></script>
<script type="text/javascript" src="js/jquery_validate/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery_validate/cmxforms.js"></script>

<script type="text/javascript" src="js/default.js"></script>   
<script type="text/javascript">
    $(function(){

        // Accordion
		// change the value of active to change the open drawer. 
		var accordion = $("#accordion");
		var index = $.cookie("accordion");
		var active;
		if (index !== null) {
			active = accordion.find("h3:eq(" + index + ")");
		} else {
			active = 0
		}

        accordion.accordion({
			header: "h3",
			event: "click hoverintent",
			active: active,
			change: function(event, ui) {
				var index = $(this).find("h3").index ( ui.newHeader[0] );
				$.cookie("accordion", index, {
					path: "/"
				});
			},
			autoHeight: false
		});

		$('#schoolCalendar').dialog({
			autoOpen: false,
			width: 600,
			height: 500,
			bgiframe: true,
			modal: true,
			buttons: {
				"Close": function() { 
					$(this).dialog("close"); 
				} 
			}
		});
		
        // Tabs
        $('#tabs').tabs();

        // Datepicker
        $('#datepicker').datepicker({
			 inline: true,
			 dateFormat: 'yy-mm-dd',
			 beforeShowDay: schoolCalendar,
			 onSelect: function(dateText, inst) { 
			 					//$(this).dpDisplay();
								//alert(dateText); 
								var date = dateText;
								$('#schoolCalendar').html(loading);
								$('#schoolCalendar').load('viewer/viewer_school_calendar.php?date='+date, null);
								$('#schoolCalendar').dialog('open');
								return false;
								}

        });
      

        // Slider
        $('#slider').slider({
            range: true,
            values: [17, 67]
        });
        
        // Progressbar
        $("#progressbar").progressbar({
            value: 20 
        });
        
        //hover states on the static widgets
        $('#dialog_link, ul#icons li').hover(
            function() { $(this).addClass('ui-state-hover'); }, 
            function() { $(this).removeClass('ui-state-hover'); }
        );
        
    });
	
	function changeCurrentMenu(parentMenu)
	{
		$.cookie("currentMenu",parentMenu);
	}	
		
	 var  schoolEventDays = <?=generateCalendarDays()?>;

	function schoolCalendar(date) {
		for (i = 0; i < schoolEventDays.length; i++) {
		  if (date.getMonth() == schoolEventDays[i][0] - 1
			  && date.getDate() == schoolEventDays[i][1]) {
			return [true, schoolEventDays[i][2] + '_day'];
		  }
		}
	  return [false, ''];
	}

</script>
</head>
<body>
<div id="wrap">
<?php
	require_once('includes/page_header.php');
?><!-- Header -->

<span class="clear"></span>

<div class="title"><span style="float:right">SEARCH &nbsp;<input class="txt_150" name="search_all" id="search_all" /></span>
<h2><?=$page_title?></h2>

<span><?=$pagination?></span>
</div>   
<div id="wrap">
	<?php
	
        require_once('includes/page_left.php');
    ?>
	<!-- Page Left -->
  
<div id="content">
	
	<?php
	if(SYS_SET_SYSTEM == 'OFF')
	{
	?>
        <div id="system_global_message">
            <span class="icon"></span>
            <span class="txt"><strong>System is currently set to offline, other user will not be able to login. To set it online click <a href="index.php?comp=com_system_settings">here</a> </strong></span>
        </div>
    <?php
	}
	?>
    
	<?php
        require_once($content_template);
    ?>
</div><!-- #content -->
    
</div><!-- #wrap -->
			
<?php
	require_once('includes/page_footer.php');
?><!-- Footer -->
<!-- LIST LOOK UP-->
<div id="schoolCalendar" title="School Calendar">
    This will be replace by the look up contents
</div><!-- #dialog -->
</body>
</html>


