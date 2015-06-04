<?php
/**
 * 
 * @category  phplist
 * @package   submitByMail Plugin
 * @author    Arnold V. Lesikar
 * @copyright 2014 Arnold V. Lesikar
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 *
 * This file is a part of the submitByMailPlugin for Phplist
 *
 * The submitByMailPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 * For more information about how to use this plugin, see
 * http://resources.phplist.com/plugins/submitByMail .
 * 
 */

if (!defined('PHPLISTINIT')) die(); // avoid pages being loaded directly

$sbm = $GLOBALS['plugins']['submitByMailPlugin'];
$dir = $_POST['directory'];

if ($_POST['scriptType']) {
	if ($_POST['scriptType'] == 'pipe') {
		$script = '#!/bin/sh ' . $sbm->makeCliCommand('pipeInMsg') . " -e$1>/dev/null 2>&1";
		$scptname = 'pipeMsg.sh';
	} else {
		$script = '#!/bin/sh ' . $sbm->makeCliCommand('collectMsgs');
		$scptname = 'collectMsgs.sh';
	}
	if (substr($dir, -1) != '/') $dir .= '/';
	file_put_contents($dir . $scptname, $script);
}
$str = <<<EOI
<div style="font-size:14px">
<p>This page generates shell scripts that you can use to pipe messages into phpList and to collect messages
from mailboxes with POP.<p><p>The script to pipe in messages is called "pipeMsg.sh." Store the script
in a convenient location and make it executable with the command <em>chmod 755.</em></p><p>if you store the script in a directory listed in the shell PATH variable, you can pipe from a mailbox, say, <em>mybox@nowhere.com</em>, 
using the following command:
<p style="text-align:center"><em>pipeMsg.sh mybox@nowhere.com</em></p><p>The script to collect messages is called "collectMsgs.sh.
As before, if you store the script in a directory in the shell PATH variable, you can use the following as 
a cron job:</p><p style="text-align:center"><em>collectMsgs.sh > /dev/null 2>&1</em></p><p>The scripts assume that the shell
is located in the usual Unix/Linux location <em>/bin/sh</em>.</div>
EOI;
print($str);
$content = formStart();
$content .= <<<EOD
<h5>Select script to create:</h5>
<p><input type="radio" checked name="scriptType" value="pipe" /><strong>&nbsp;Script to pipe in a message to a list</strong></p>
<p><input type="radio" name="scriptType" value="collect" /><strong>&nbsp;Script to collect messages</input></p>
<p>Directory in which to store the script. The directory should listed in your shell's PATH:<br />
<input type=text name="directory" size=200 value ="$dir" /></form>
<p><input type="submit" value="Create Script" />
<p><input type=text</form>
EOD;

$panel = new UIPanel("Generate Scripts", $content); // Selector .panel .header h2
print($panel->display());