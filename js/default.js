/*
* [+] AJAX
*/
$.ajaxSetup ({
	cache: false
});

var loading = '<div id="loadingContents"></div><p id="formbottom"></p>';

function redirect(objURL)
{
	window.location = objURL;
}

$(document).ready(function() {

	$('ul.items>li.active>ul').slideDown();
	
	$(function() {
			   
		$('.items').click(clickFn);
		
	});
	
	function clickFn(e) {
		
		var $el = $(e.target);
		if (!$el.parent().children('ul').is(':visible')) {
			
			if ($el.parent().parent().is('ul.items')) {
				
				var $visibles=$('ul.items>li>ul:visible');
				if ($visibles.length>0){
					$visibles.slideUp('medium', function(){
						 $el.parent().children("ul").slideDown('slow');
						}
					);
				}
				else{
					$el.parent().children("ul").slideDown('slow');
				}

			}
			
		}
	
	}	

	function getEventTarget(e) {
		
		e = e || window.event;
		return e.target || e.srcElement;
		
	}

	$('.close').click(function() {
									 
		$(this).parents(".alert").animate({ opacity: 'hide' }, "slow");
		return false;
		
	});
	
	$(document).keyup(function(event) {
		if (event.keyCode == 13) {
			$(this).parents("form").submit();
			return false;
		}
	});
	
	$('.submit').click(function() {
									 
		$(this).parents("form").submit();
		return false;
		
	});

});


function checkbox_checker()
{

	var obj = document.coreForm.id;
	
	var checkbox_choices = 0;
	var objContainer = document.getElementById('temp');
	objContainer.value  = '';
	// Loop from zero to the one minus the number of checkbox button selections
	
	if(obj.length > 0)
	{
		for (counter = 0; counter < obj.length; counter++)
		{
			try
			{
				// If a checkbox has been selected it will return true
				// (If not it will return false)
				if (obj[counter].checked)
				{
					
						checkbox_choices = checkbox_choices + 1; 
						if(objContainer.value != '')
						{
							objContainer.value = objContainer.value + ','
						}
						objContainer.value = objContainer.value + obj[counter].value;
	
					
				}	
			}
			catch(err)
			{
			}
		
		}
	}
	else
	{
			if(document.coreForm.id.checked == false)
			{
				checkbox_choices = 0;
			}
			else
			{
				objContainer.value = document.coreForm.id.value;
				checkbox_choices = 1;
			}
	}

	if(	checkbox_choices > 0 )
	{
		return true;
	}
	else
	{
		return false;
	}
	
	return false;

}
function editItem()
{

	if(checkbox_checker())
	{
		changeAction('update','edit');
		return true;
	}
	else
	{
		alert('No item was selected.');
		return false;
	}
	
}
function checkSelectedItem(itemId)
{
	$('#' + itemId).attr('checked','checked');

}
function doTheAction (formAction, formView, itemId)
{
	checkSelectedItem(itemId);
	changeAction(formAction,formView);
	
	document.coreForm.submit();
	
}
function changeAction(formAction,formView)
{
	$('#action').val(formAction);
	$('#view').val(formView);
}

function changeActionAdd(formAction, formView, itemId)
{
	checkSelectedItem(itemId);
	$('#action').val(formAction);
	$('#view').val(formView);
	document.coreForm.submit();
}

function lnk_deleteItem(itemId)
{
	checkSelectedItem(itemId);
	if(checkbox_checker())
	{
		if(confirm('Are you sure you want to delete the selected item/s?'))
		{
			changeAction('delete','list');
			document.coreForm.submit();
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		alert('No item was selected.');
		return false;
	}
}
function lnk_publishItem(itemId)
{
	checkSelectedItem(itemId);
	if(checkbox_checker())
	{
		
		changeAction('publish','list');
		document.coreForm.submit();
		return true;
	}
	else
	{
		alert('No item was selected.');
		return false;
	}
}

function lnk_unpublishItem(itemId)
{
	checkSelectedItem(itemId);
	if(checkbox_checker())
	{
		
		changeAction('unpublished','list');
		document.coreForm.submit();
		return true;
	}
	else
	{
		alert('No item was selected.');
		return false;
	}
}

function lnk_ResetPasswordItem(itemId)
{
	checkSelectedItem(itemId);
	if(checkbox_checker())
	{
		if(confirm('Are you sure you want to Reset the password of selected item/s?'))
		{
			changeAction('reset','list');
			document.coreForm.submit();
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		alert('No item was selected.');
		return false;
	}
}