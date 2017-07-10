<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
/**********************************************************************************
 *   Instructions for translators:
 *
 *   All gettext key/value pairs are specified as follows:
 *     $trans["key"] = "<php translation code to set the $text variable>";
 *   Allowing translators the ability to execute php code withint the transFunc string
 *   provides the maximum amount of flexibility to format the languange syntax.
 *
 *   Formatting rules:
 *   - Resulting translation string must be stored in a variable called $text.
 *   - Input arguments must be surrounded by % characters (i.e. %pageCount%).
 *   - A backslash ('\') needs to be placed before any special php characters 
 *     (such as $, ", etc.) within the php translation code.
 *
 *   Simple Example:
 *     $trans["homeWelcome"]       = "\$text='Welcome to OpenBiblio';";
 *
 *   Example Containing Argument Substitution:
 *     $trans["searchResult"]      = "\$text='page %page% of %pages%';";
 *
 *   Example Containing a PHP If Statment and Argument Substitution:
 *     $trans["searchResult"]      = 
 *       "if (%items% == 1) {
 *         \$text = '%items% result';
 *       } else {
 *         \$text = '%items% results';
 *       }";
 *
 **********************************************************************************
 */

#****************************************************************************
#*  Translation text used on multiple pages
#****************************************************************************
$trans["reportsCancel"]            = "\$text = 'Cancel';";

#****************************************************************************
#*  Translation text for page index.php
#****************************************************************************
$trans["indexHdr"]                 = "\$text = 'Reports';";
$trans["indexDesc"]                = "\$text = 'Use the report or label list located in the left hand navigation area to produce reports or labels.';";

#****************************************************************************
#*  Translation text for page report_list.php
#****************************************************************************
$trans["reportListHdr"]            = "\$text = 'Report List';";
$trans["reportListDesc"]           = "\$text = 'Choose from one of the following links to run a report.';";
$trans["reportListXmlErr"]         = "\$text = 'Error occurred parsing report definition xml.';";
$trans["reportListCannotRead"]     = "\$text = 'Cannot read label file: %fileName%';";

#****************************************************************************
#*  Translation text for page label_list.php
#****************************************************************************
$trans["labelListHdr"]             = "\$text = 'Label List';";
$trans["labelListDesc"]            = "\$text = 'Choose from one of the following links to produce labels in pdf format.';";
$trans["displayLabelsXmlErr"]      = "\$text = 'Error occurred parsing report definition xml.  Error = ';";

#****************************************************************************
#*  Translation text for page letter_list.php
#****************************************************************************
$trans["letterListHdr"]            = "\$text = 'Letter List';";
$trans["letterListDesc"]           = "\$text = 'Choose from one of the following links to produce letters in pdf format.';";
$trans["displayLettersXmlErr"]      = "\$text = 'Error occurred parsing report definition xml.  Error = ';";

#****************************************************************************
#*  Translation text for page report_criteria.php
#****************************************************************************
$trans["reportCriteriaHead1"]      = "\$text = 'Report Search Criteria (optional)';";
$trans["reportCriteriaHead2"]      = "\$text = 'Report Sort Order (optional)';";
$trans["reportCriteriaHead3"]      = "\$text = 'Report Output Type';";
$trans["reportCriteriaCrit1"]      = "\$text = 'Criteria 1:';";
$trans["reportCriteriaCrit2"]      = "\$text = 'Criteria 2:';";
$trans["reportCriteriaCrit3"]      = "\$text = 'Criteria 3:';";
$trans["reportCriteriaCrit4"]      = "\$text = 'Criteria 4:';";
$trans["reportCriteriaEQ"]         = "\$text = '=';";
$trans["reportCriteriaNE"]         = "\$text = 'not =';";
$trans["reportCriteriaLT"]         = "\$text = '&lt;';";
$trans["reportCriteriaGT"]         = "\$text = '&gt;';";
$trans["reportCriteriaLE"]         = "\$text = '&lt or =';";
$trans["reportCriteriaGE"]         = "\$text = '&gt or =';";
$trans["reportCriteriaBT"]         = "\$text = 'between';";
$trans["reportCriteriaAnd"]        = "\$text = 'and';";
$trans["reportCriteriaRunReport"]  = "\$text = 'Run Report';";
$trans["reportCriteriaSortCrit1"]  = "\$text = 'Sort 1:';";
$trans["reportCriteriaSortCrit2"]  = "\$text = 'Sort 2:';";
$trans["reportCriteriaSortCrit3"]  = "\$text = 'Sort 3:';";
$trans["reportCriteriaAscending"]  = "\$text = 'ascending';";
$trans["reportCriteriaDescending"] = "\$text = 'descending';";
$trans["reportCriteriaStartOnLabel"] = "\$text = 'Start printing on label:';";
$trans["reportCriteriaOutput"]     = "\$text = 'Output Type:';";
$trans["reportCriteriaOutputHTML"] = "\$text = 'HTML';";
$trans["reportCriteriaOutputCSV"]  = "\$text = 'CSV';";

#****************************************************************************
#*  Translation text for page run_report.php
#****************************************************************************
$trans["runReportReturnLink1"]     = "\$text = 'report selection criteria';";
$trans["runReportReturnLink2"]     = "\$text = 'report list';";
$trans["runReportTotal"]           = "\$text = 'Total Rows:';";

#****************************************************************************
#*  Translation text for page display_labels.php
#****************************************************************************
$trans["displayLabelsStartOnLblErr"] = "\$text = 'Field must be numeric.';";
$trans["displayLabelsXmlErr"]      = "\$text = 'Error occurred parsing report definition xml.  Error = ';";
$trans["displayLabelsCannotRead"]  = "\$text = 'Cannot read label file: %fileName%';";

#****************************************************************************
#*  Translation text for page noauth.php
#****************************************************************************
$trans["noauthMsg"]                = "\$text = 'You are not authorized to use the Reports tab.';";

#****************************************************************************
#*  Report Titles
#****************************************************************************
$trans["reportHolds"]              = "\$text = 'Hold Requests Containing Member Contact Info';";
$trans["reportCheckouts"]          = "\$text = 'Bibliography Checkout Listing';";
$trans["Over Due Letters"]           = "\$text = 'Over Due Letters';";
$trans["reportLabels"]             = "\$text = 'Label Printing Query (used by labels)';";
$trans["popularBiblios"]           = "\$text = 'Most Popular Bibliographies';";
$trans["overdueList"]              = "\$text = 'Over Due Member List';";
$trans["balanceDueList"]           = "\$text = 'Balance Due Member List';";

#****************************************************************************
#*  Label Titles
#****************************************************************************
$trans["labelsMulti"]              = "\$text = 'Multi Label Example';";
$trans["labelsSimple"]             = "\$text = 'Simple Label Example';";

#****************************************************************************
#*  Column Text
#****************************************************************************
$trans["ob_biblio.bibid"]             = "\$text = 'Biblio ID';";
$trans["ob_biblio.create_dt"]         = "\$text = 'Date Added';";
$trans["ob_biblio.last_change_dt"]    = "\$text = 'Last Changed';";
$trans["ob_biblio.material_cd"]       = "\$text = 'Material Cd';";
$trans["ob_biblio.collection_cd"]     = "\$text = 'Collection';";
$trans["ob_biblio.call_nmbr1"]        = "\$text = 'Call 1';";
$trans["ob_biblio.call_nmbr2"]        = "\$text = 'Call 2';";
$trans["ob_biblio.call_nmbr3"]        = "\$text = 'Call 3';";
$trans["ob_biblio.title_remainder"]   = "\$text = 'Title Remainder';";
$trans["ob_biblio.responsibility_stmt"] = "\$text = 'Stmt of Resp';";
$trans["ob_biblio.opac_flg"]          = "\$text = 'OPAC Flag';";

$trans["ob_biblio_copy.barcode_nmbr"] = "\$text = 'Barcode';";
$trans["ob_biblio.title"]             = "\$text = 'Title';";
$trans["ob_biblio.author"]            = "\$text = 'Author';";
$trans["ob_biblio_copy.status_begin_dt"]   = "\$text = 'Status Begin Date';";
$trans["ob_biblio_copy.due_back_dt"]       = "\$text = 'Due Back Date';";
$trans["ob_member.student_id"]             = "\$text = 'Member ID';";
$trans["ob_member.barcode_nmbr"]      = "\$text = 'Member Barcode';";
$trans["ob_member.last_name"]         = "\$text = 'Last Name';";
$trans["ob_member.first_name"]        = "\$text = 'First Name';";
$trans["ob_member.address"]          = "\$text = 'Address';";
$trans["ob_biblio_hold.hold_begin_dt"] = "\$text = 'Hold Begin Date';";
$trans["ob_member.home_phone"]        = "\$text = 'Home Phone';";
$trans["ob_member.work_phone"]        = "\$text = 'Work Phone';";
$trans["ob_member.email"]             = "\$text = 'Email';";
$trans["ob_biblio_status_dm.description"] = "\$text = 'Status';";
$trans["ob_settings.library_name"]    = "\$text = 'Library Name';";
$trans["ob_settings.library_hours"]   = "\$text = 'Library Hours';";
$trans["ob_settings.library_phone"]   = "\$text = 'Library Phone';";
$trans["ob_days_late"]                = "\$text = 'Days Late';";
$trans["ob_title"]                    = "\$text = 'Title';";
$trans["ob_author"]                   = "\$text = 'Author';";
$trans["ob_due_back_dt"]              = "\$text = 'Due Back';";
$trans["ob_checkoutCount"]            = "\$text = 'Checkout Count';";

?>
