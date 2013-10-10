<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}" lang="{S_USER_LANG}" xml:lang="{S_USER_LANG}">
<head>

<meta http-equiv="content-type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="content-language" content="{S_USER_LANG}" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="resource-type" content="document" />
<meta name="distribution" content="global" />
<meta name="keywords" content="" />
<meta name="description" content="" />
{META}
<title>{SITENAME} &bull; <!-- IF S_IN_MCP -->{L_MCP} &bull; <!-- ELSEIF S_IN_UCP -->{L_UCP} &bull; <!-- ENDIF -->{PAGE_TITLE}</title>

<!-- IF S_ENABLE_FEEDS -->
	<!-- IF S_ENABLE_FEEDS_OVERALL --><link rel="alternate" type="application/atom+xml" title="{L_FEED} - {SITENAME}" href="{U_FEED}" /><!-- ENDIF -->
	<!-- IF S_ENABLE_FEEDS_NEWS --><link rel="alternate" type="application/atom+xml" title="{L_FEED} - {L_FEED_NEWS}" href="{U_FEED}?mode=news" /><!-- ENDIF -->
	<!-- IF S_ENABLE_FEEDS_FORUMS --><link rel="alternate" type="application/atom+xml" title="{L_FEED} - {L_ALL_FORUMS}" href="{U_FEED}?mode=forums" /><!-- ENDIF -->
	<!-- IF S_ENABLE_FEEDS_TOPICS --><link rel="alternate" type="application/atom+xml" title="{L_FEED} - {L_FEED_TOPICS_NEW}" href="{U_FEED}?mode=topics" /><!-- ENDIF -->
	<!-- IF S_ENABLE_FEEDS_TOPICS_ACTIVE --><link rel="alternate" type="application/atom+xml" title="{L_FEED} - {L_FEED_TOPICS_ACTIVE}" href="{U_FEED}?mode=topics_active" /><!-- ENDIF -->
	<!-- IF S_ENABLE_FEEDS_FORUM and S_FORUM_ID --><link rel="alternate" type="application/atom+xml" title="{L_FEED} - {L_FORUM} - {FORUM_NAME}" href="{U_FEED}?f={S_FORUM_ID}" /><!-- ENDIF -->
	<!-- IF S_ENABLE_FEEDS_TOPIC and S_TOPIC_ID --><link rel="alternate" type="application/atom+xml" title="{L_FEED} - {L_TOPIC} - {TOPIC_TITLE}" href="{U_FEED}?f={S_FORUM_ID}&amp;t={S_TOPIC_ID}" /><!-- ENDIF -->
<!-- ENDIF -->

<link rel="stylesheet" href="{T_STYLESHEET_LINK}" type="text/css" />


<!-- IF SCRIPT_NAME eq 'rules' -->
<script type="text/javascript">
// <![CDATA[
window.onload = function()
{
    var pos = location.href.indexOf("#");
    if(pos != -1)
    {
        highlight(location.href.slice(pos + 1));
    }
};
var oldID = false;
function highlight(id)
{
    if(oldID != false && oldID != id)
    {
        document.getElementById(oldID).className = '';
    }
    document.getElementById(id).className = 'activerule';
    oldID = id;
}
// ]]>
</script>
<!-- ENDIF -->


<script type="text/javascript">
// <![CDATA[
<!-- IF S_USER_PM_POPUP and S_NEW_PM -->
	popup('{UA_POPUP_PM}', 400, 225, '_phpbbprivmsg');
<!-- ENDIF -->

function popup(url, width, height, name)
{
	if (!name)
	{
		name = '_popup';
	}

	window.open(url.replace(/&amp;/g, '&'), name, 'height=' + height + ',resizable=yes,scrollbars=yes,width=' + width);
	return false;
}

function jumpto()
{
	var page = prompt('{LA_JUMP_PAGE}:', '{ON_PAGE}');
	var per_page = '{PER_PAGE}';
	var base_url = '{A_BASE_URL}';

	if (page !== null && !isNaN(page) && page == Math.floor(page) && page > 0)
	{
		if (base_url.indexOf('?') == -1)
		{
			document.location.href = base_url + '?start=' + ((page - 1) * per_page);
		}
		else
		{
			document.location.href = base_url.replace(/&amp;/g, '&') + '&start=' + ((page - 1) * per_page);
		}
	}
}

/**
* Find a member
*/
function find_username(url)
{
	popup(url, 760, 570, '_usersearch');
	return false;
}

/**
* Mark/unmark checklist
* id = ID of parent container, name = name prefix, state = state [true/false]
*/
function marklist(id, name, state)
{
	var parent = document.getElementById(id);
	if (!parent)
	{
		eval('parent = document.' + id);
	}

	if (!parent)
	{
		return;
	}

	var rb = parent.getElementsByTagName('input');
	
	for (var r = 0; r < rb.length; r++)
	{
		if (rb[r].name.substr(0, name.length) == name)
		{
			rb[r].checked = state;
		}
	}
}

<!-- IF ._file -->

	/**
	* Play quicktime file by determining it's width/height
	* from the displayed rectangle area
	*
	* Only defined if there is a file block present.
	*/
	function play_qt_file(obj)
	{
		var rectangle = obj.GetRectangle();

		if (rectangle)
		{
			rectangle = rectangle.split(',')
			var x1 = parseInt(rectangle[0]);
			var x2 = parseInt(rectangle[2]);
			var y1 = parseInt(rectangle[1]);
			var y2 = parseInt(rectangle[3]);

			var width = (x1 < 0) ? (x1 * -1) + x2 : x2 - x1;
			var height = (y1 < 0) ? (y1 * -1) + y2 : y2 - y1;
		}
		else
		{
			var width = 200;
			var height = 0;
		}

		obj.width = width;
		obj.height = height + 16;

		obj.SetControllerVisible(true);

		obj.Play();
	}
<!-- ENDIF -->

// ]]>
</script>
</head>
<body class="{S_CONTENT_DIRECTION}">

<a name="top"></a>

<div id="wrapheader">

	<div id="logodesc">
		<table width="100%" cellspacing="0">
		<tr>
			<td><a href="{U_INDEX}">{SITE_LOGO_IMG}</a></td>
			<td width="100%" align="center"><h1>{SITENAME}</h1><span class="gen">{SITE_DESCRIPTION}</span></td>
		</tr>
		</table>
	</div>

	<div id="menubar">
		<table width="100%" cellspacing="0">
		<tr>
			<td class="genmed">
				<!-- IF not S_IS_BOT --><a href="{U_LOGIN_LOGOUT}"><img src="{T_THEME_PATH}/images/icon_mini_login.gif" width="12" height="13" alt="*" /> {L_LOGIN_LOGOUT}</a>&nbsp;<!-- ENDIF -->
				<!-- IF U_RESTORE_PERMISSIONS --> &nbsp;<a href="{U_RESTORE_PERMISSIONS}"><img src="{T_THEME_PATH}/images/icon_mini_login.gif" width="12" height="13" alt="*" /> {L_RESTORE_PERMISSIONS}</a><!-- ENDIF -->
				<!-- IF S_BOARD_DISABLED and S_USER_LOGGED_IN --> &nbsp;<span style="color: red;">{L_BOARD_DISABLED}</span><!-- ENDIF -->
				<!-- IF not S_IS_BOT -->
					<!-- IF S_USER_LOGGED_IN -->
						<!-- IF S_DISPLAY_PM --> &nbsp;<a href="{U_PRIVATEMSGS}"><img src="{T_THEME_PATH}/images/icon_mini_message.gif" width="12" height="13" alt="*" /> {PRIVATE_MESSAGE_INFO}<!-- IF PRIVATE_MESSAGE_INFO_UNREAD -->, {PRIVATE_MESSAGE_INFO_UNREAD}<!-- ENDIF --></a><!-- ENDIF -->
					<!-- ELSEIF S_REGISTER_ENABLED and not (S_SHOW_COPPA or S_REGISTRATION) --> &nbsp;<a href="{U_REGISTER}"><img src="{T_THEME_PATH}/images/icon_mini_register.gif" width="12" height="13" alt="*" /> {L_REGISTER}</a>
					<!-- ENDIF -->
				<!-- ENDIF -->
			</td>
			<td class="genmed" align="{S_CONTENT_FLOW_END}">
				<a href="{U_FAQ}"><img src="{T_THEME_PATH}/images/icon_mini_faq.gif" width="12" height="13" alt="*" /> {L_FAQ}</a>
				<!-- IF S_DISPLAY_SEARCH -->&nbsp; &nbsp;<a href="{U_SEARCH}"><img src="{T_THEME_PATH}/images/icon_mini_search.gif" width="12" height="13" alt="*" /> {L_SEARCH}</a><!-- ENDIF -->
				<!-- IF not S_IS_BOT -->
					<!-- IF S_DISPLAY_MEMBERLIST -->&nbsp; &nbsp;<a href="{U_MEMBERLIST}"><img src="{T_THEME_PATH}/images/icon_mini_members.gif" width="12" height="13" alt="*" /> {L_MEMBERLIST}</a><!-- ENDIF -->
					<!-- IF S_USER_LOGGED_IN -->&nbsp; &nbsp;<a href="{U_PROFILE}"><img src="{T_THEME_PATH}/images/icon_mini_profile.gif" width="12" height="13" alt="*" /> {L_PROFILE}</a><!-- ENDIF -->
				<!-- ENDIF -->
			</td>
		</tr>
		</table>
	</div>

	<div id="datebar">
		<table width="100%" cellspacing="0">
		<tr>
			<td class="gensmall"><!-- IF S_USER_LOGGED_IN -->{LAST_VISIT_DATE}<!-- ENDIF --></td>
			<td class="gensmall" align="{S_CONTENT_FLOW_END}">{CURRENT_TIME}<br /></td>
		</tr>
		</table>
	</div>

</div>

<div id="wrapcentre">

	<!-- IF S_DISPLAY_SEARCH -->
	<p class="searchbar">
		<span style="float: {S_CONTENT_FLOW_BEGIN};"><a href="{U_SEARCH_UNANSWERED}">{L_SEARCH_UNANSWERED}</a> | <a href="{U_SEARCH_ACTIVE_TOPICS}">{L_SEARCH_ACTIVE_TOPICS}</a></span>
		<!-- IF S_USER_LOGGED_IN or S_LOAD_UNREADS -->
		<span style="float: {S_CONTENT_FLOW_END};"><!-- IF S_LOAD_UNREADS --><a href="{U_SEARCH_UNREAD}">{L_SEARCH_UNREAD}</a><!-- IF S_USER_LOGGED_IN --> | <!-- ENDIF --><!-- ENDIF --><!-- IF S_USER_LOGGED_IN --><a href="{U_SEARCH_NEW}">{L_SEARCH_NEW}</a> | <a href="{U_SEARCH_SELF}">{L_SEARCH_SELF}</a><!-- ENDIF --></span>
		<!-- ENDIF -->
	</p>
	<!-- ENDIF -->

	<br style="clear: both;" />

	<!-- INCLUDE breadcrumbs.html -->

	<br />