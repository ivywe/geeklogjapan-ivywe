<?php
// +--------------------------------------------------------------------------+
// | Media Gallery Plugin - Geeklog                                           |
// +--------------------------------------------------------------------------+
// | album_rpc.php                                                            |
// |                                                                          |
// | AJAX component to retrieve album attributes                              |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2015 by the following authors:                             |
// |                                                                          |
// | Yoshinori Tahara       taharaxp AT gmail DOT com                         |
// |                                                                          |
// | Based on the Media Gallery Plugin for glFusion CMS                       |
// | Copyright (C) 2009-2010 by the following authors:                        |
// |                                                                          |
// | Mark A. Howard         mark AT usable-web DOT com                        |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// +--------------------------------------------------------------------------+
// |                                                                          |
// | This program is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU General Public License              |
// | as published by the Free Software Foundation; either version 2           |
// | of the License, or (at your option) any later version.                   |
// |                                                                          |
// | This program is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
// | GNU General Public License for more details.                             |
// |                                                                          |
// | You should have received a copy of the GNU General Public License        |
// | along with this program; if not, write to the Free Software Foundation,  |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.          |
// |                                                                          |
// +--------------------------------------------------------------------------+

require_once '../lib-common.php';

if (COM_isAnonUser() && $_MG_CONF['loginrequired'] == 1) {
    exit;
}

require_once $_CONF['path'] . 'plugins/mediagallery/include/common.php';

if ($_MG_CONF['verbose']) {
    COM_errorLog('album_rpc.php: invocation ------------------------');
}

if (!isset($_REQUEST['aid'])) {
    COM_errorLog('album_rpc.php: invocation with no album parameter');
    exit(0);
}

// retrieve the album_id passed
$album_id = COM_applyFilter($_REQUEST['aid'], true);

$album_data = MG_getAlbumData($album_id, array('album_id'), false);

// check to ensure we have a valid album_id
if (isset($album_data['album_id']) && $album_data['album_id'] == $album_id) {
    // retrieve the upload filesize limit
    $size_limit = MG_getUploadLimit($album_id);

    // retrieve the valid filetypes
    $valid_types = MG_getValidFileTypes($album_id);

    if ($_MG_CONF['verbose']) {
        COM_errorLog('album_id = ' . $album_id);
        COM_errorLog('size_limit = ' . $size_limit);
        COM_errorLog('valid_types = ' . $valid_types);
        COM_errorLog('album_rpc.php: normal termination ----------------');
    }
} else {
    COM_errorLog('album_rpc.php: invalid album id = ' . $album_id);
    $size_limit = 0;
    $valid_types = '';
}

// return the album-specific data
echo $size_limit . '%' . $valid_types;

exit(0);
?>