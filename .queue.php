<?php
//Default Configuration
$CONFIG = '{"lang":"en","error_reporting":false,"show_hidden":false,"hide_Cols":false,"calc_folder":false}';

//TFM version
define('VERSION', '1.0.0');

//Application Title
define('APP_TITLE', 'SCNUCPC 2020');

$use_auth = false;

//set application theme
//options - 'light' and 'dark'
$theme = 'light';

// Readonly users
// e.g. array('users', 'guest', ...)
$readonly_users = array(
    'user',
);

// Enable highlight.js (https://highlightjs.org/) on view's page
$use_highlightjs = true;

// highlight.js style
// for dark theme use 'ir-black'
$highlightjs_style = 'vs';

// Enable ace.js (https://ace.c9.io/) on view's page
$edit_files = true;

// Default timezone for date() and time()
// Doc - http://php.net/manual/en/timezones.php
$default_timezone = 'Asia/Shanghai'; // UTC

// Root path for file manager
// use absolute path of directory i.e: '/var/www/folder' or $_SERVER['DOCUMENT_ROOT'].'/folder'
$root_path = $_SERVER['DOCUMENT_ROOT'];

// Root url for links in file manager.Relative to $http_host. Variants: '', 'path/to/subfolder'
// Will not working if $root_path will be outside of server document root
$root_url = '';

// Server hostname. Can set manually if wrong
$http_host = $_SERVER['HTTP_HOST'];

// user specific directories
// array('Username' => 'Directory path', 'Username2' => 'Directory path', ...)
$directories_users = array();

// input encoding for iconv
$iconv_input_encoding = 'UTF-8';

// date() format for file modification date
// Doc - https://www.php.net/manual/en/function.date.php
$datetime_format = 'd.m.y H:i';

// Allowed file extensions for create and rename files
// e.g. 'txt,html,css,js'
$allowed_file_extensions = '';

// Allowed file extensions for upload files
// e.g. 'gif,png,jpg,html,txt'
$allowed_upload_extensions = '';

// Favicon path. This can be either a full url to an .PNG image, or a path based on the document root.
// full path, e.g http://example.com/favicon.png
// local path, e.g images/icons/favicon.png
$favicon_path = '?img=favicon';

// Files and folders to excluded from listing
// e.g. array('myfile.html', 'personal-folder', '*.php', ...)
$exclude_items = array();

// Online office Docs Viewer
// Availabe rules are 'google', 'microsoft' or false
// google => View documents using Google Docs Viewer
// microsoft => View documents using Microsoft Web Apps Viewer
// false => disable online doc viewer
$online_viewer = 'google';

// Sticky Nav bar
// true => enable sticky header
// false => disable sticky header
$sticky_navbar = true;

// Maximum file upload size
// Increase the following values in php.ini to work properly
// memory_limit, upload_max_filesize, post_max_size
$max_upload_size_bytes = 2048;

// Possible rules are 'OFF', 'AND' or 'OR'
// OFF => Don't check connection IP, defaults to OFF
// AND => Connection must be on the whitelist, and not on the blacklist
// OR => Connection must be on the whitelist, or not on the blacklist
$ip_ruleset = 'OFF';

// Should users be notified of their block?
$ip_silent = true;

// IP-addresses, both ipv4 and ipv6
$ip_whitelist = array(
    '127.0.0.1', // local ipv4
    '::1', // local ipv6
);

// IP-addresses, both ipv4 and ipv6
$ip_blacklist = array(
    '0.0.0.0', // non-routable meta ipv4
    '::', // non-routable meta ipv6
);

// if User has the customized config file, try to use it to override the default config above
$config_file = './config.php';
if (is_readable($config_file)) {
    @include $config_file;
}

// --- EDIT BELOW CAREFULLY OR DO NOT EDIT AT ALL ---

// max upload file size
define('MAX_UPLOAD_SIZE', $max_upload_size_bytes);

define('FM_THEME', $theme);

// private key and session name to store to the session
if (!defined('FM_SESSION_ID')) {
    define('FM_SESSION_ID', 'filemanager');
}

// Configuration
$cfg = new FM_Config();

// Default language
$lang = isset($cfg->data['lang']) ? $cfg->data['lang'] : 'en';

// Show or hide files and folders that starts with a dot
$show_hidden_files = isset($cfg->data['show_hidden']) ? $cfg->data['show_hidden'] : true;

// PHP error reporting - false = Turns off Errors, true = Turns on Errors
$report_errors = isset($cfg->data['error_reporting']) ? $cfg->data['error_reporting'] : true;

// Hide Permissions and Owner cols in file-listing
$hide_Cols = isset($cfg->data['hide_Cols']) ? $cfg->data['hide_Cols'] : true;

// Show directory size: true or speedup output: false
$calc_folder = isset($cfg->data['calc_folder']) ? $cfg->data['calc_folder'] : true;

//available languages
$lang_list = array(
    'en' => 'English',
);

if ($report_errors == true) {
    @ini_set('error_reporting', E_ALL);
    @ini_set('display_errors', 1);
} else {
    @ini_set('error_reporting', E_ALL);
    @ini_set('display_errors', 0);
}

// if fm included
if (defined('FM_EMBED')) {
    $use_auth = false;
    $sticky_navbar = false;
} else {
    @set_time_limit(600);

    date_default_timezone_set($default_timezone);

    ini_set('default_charset', 'UTF-8');
    if (version_compare(PHP_VERSION, '5.6.0', '<') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
    }
    if (function_exists('mb_regex_encoding')) {
        mb_regex_encoding('UTF-8');
    }

    session_cache_limiter('');
    session_name(FM_SESSION_ID);
    function session_error_handling_function($code, $msg, $file, $line)
    {
        // Permission denied for default session, try to create a new one
        if ($code == 2) {
            session_abort();
            session_id(session_create_id());
            @session_start();
        }
    }
    set_error_handler('session_error_handling_function');
    session_start();
    restore_error_handler();
}

if (empty($auth_users)) {
    $use_auth = false;
}

$is_https = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)
|| isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';

// update $root_url based on user specific directories
if (isset($_SESSION[FM_SESSION_ID]['logged']) && !empty($directories_users[$_SESSION[FM_SESSION_ID]['logged']])) {
    $wd = fm_clean_path(dirname($_SERVER['PHP_SELF']));
    $root_url = $root_url . $wd . DIRECTORY_SEPARATOR . $directories_users[$_SESSION[FM_SESSION_ID]['logged']];
}
// clean $root_url
$root_url = fm_clean_path($root_url);

// abs path for site
defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));
defined('FM_SELF_URL') || define('FM_SELF_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . $_SERVER['PHP_SELF']);

// Show image here
if (isset($_GET['img'])) {
    fm_show_image($_GET['img']);
}

// update root path
if ($use_auth && isset($_SESSION[FM_SESSION_ID]['logged'])) {
    $root_path = isset($directories_users[$_SESSION[FM_SESSION_ID]['logged']]) ? $directories_users[$_SESSION[FM_SESSION_ID]['logged']] : $root_path;
}

// clean and check $root_path
$root_path = rtrim($root_path, '\\/');
$root_path = str_replace('\\', '/', $root_path);
if (!@is_dir($root_path)) {
    echo "<h1>Root path \"{$root_path}\" not found!</h1>";
    exit;
}

defined('FM_SHOW_HIDDEN') || define('FM_SHOW_HIDDEN', $show_hidden_files);
defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path);
defined('FM_LANG') || define('FM_LANG', $lang);
defined('FM_FILE_EXTENSION') || define('FM_FILE_EXTENSION', $allowed_file_extensions);
defined('FM_UPLOAD_EXTENSION') || define('FM_UPLOAD_EXTENSION', $allowed_upload_extensions);
defined('FM_EXCLUDE_ITEMS') || define('FM_EXCLUDE_ITEMS', $exclude_items);
defined('FM_DOC_VIEWER') || define('FM_DOC_VIEWER', $online_viewer);
define('FM_READONLY', $use_auth && !empty($readonly_users) && isset($_SESSION[FM_SESSION_ID]['logged']) && in_array($_SESSION[FM_SESSION_ID]['logged'], $readonly_users));
define('FM_IS_WIN', DIRECTORY_SEPARATOR == '\\');

// always use ?p=
if (!isset($_GET['p']) && empty($_FILES)) {
    fm_redirect(FM_SELF_URL . '?p=');
}

// get path
$p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');

// clean path
$p = fm_clean_path($p);

// for ajax request - save
$input = file_get_contents('php://input');
$_POST = (strpos($input, 'ajax') != false && strpos($input, 'save') != false) ? json_decode($input, true) : $_POST;

// instead globals vars
define('FM_PATH', $p);
define('FM_USE_AUTH', $use_auth);
define('FM_EDIT_FILE', $edit_files);
defined('FM_ICONV_INPUT_ENC') || define('FM_ICONV_INPUT_ENC', $iconv_input_encoding);
defined('FM_USE_HIGHLIGHTJS') || define('FM_USE_HIGHLIGHTJS', $use_highlightjs);
defined('FM_HIGHLIGHTJS_STYLE') || define('FM_HIGHLIGHTJS_STYLE', $highlightjs_style);
defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);

unset($p, $use_auth, $iconv_input_encoding, $use_highlightjs, $highlightjs_style);

/*************************** ACTIONS ***************************/

// AJAX Request
if (isset($_POST['ajax']) && !FM_READONLY) {

    // save
    if (isset($_POST['type']) && $_POST['type'] == "save") {
        // get current path
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        // check path
        if (!is_dir($path)) {
            fm_redirect(FM_SELF_URL . '?p=');
        }
        $file = $_GET['edit'];
        $file = fm_clean_path($file);
        $file = str_replace('/', '', $file);
        if ($file == '' || !is_file($path . '/' . $file)) {
            fm_set_msg('File not found', 'error');
            fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
        }
        header('X-XSS-Protection:0');
        $file_path = $path . '/' . $file;

        $writedata = $_POST['content'];
        $fd = fopen($file_path, "w");
        $write_results = @fwrite($fd, $writedata);
        fclose($fd);
        if ($write_results === false) {
            header("HTTP/1.1 500 Internal Server Error");
            die("Could Not Write File! - Check Permissions / Ownership");
        }
        die(true);
    }

    // backup files
    if (isset($_POST['type']) && $_POST['type'] == "backup" && !empty($_POST['file'])) {
        $fileName = $_POST['file'];
        $fullPath = FM_ROOT_PATH . '/';
        if (!empty($_POST['path'])) {
            $relativeDirPath = fm_clean_path($_POST['path']);
            $fullPath .= "{$relativeDirPath}/";
        }
        $date = date("dMy-His");
        $newFileName = "{$fileName}-{$date}.bak";
        $fullyQualifiedFileName = $fullPath . $fileName;
        try {
            if (!file_exists($fullyQualifiedFileName)) {
                throw new Exception("File {$fileName} not found");
            }
            if (copy($fullyQualifiedFileName, $fullPath . $newFileName)) {
                echo "Backup {$newFileName} created";
            } else {
                throw new Exception("Could not copy file {$fileName}");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    exit();
}

// Delete file / folder
if (isset($_GET['del']) && !FM_READONLY) {
    $del = str_replace('/', '', fm_clean_path($_GET['del']));
    if ($del != '' && $del != '..' && $del != '.') {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        $is_dir = is_dir($path . '/' . $del);
        if (fm_rdelete($path . '/' . $del)) {
            $msg = $is_dir ? 'Folder <b>%s</b> deleted' : 'File <b>%s</b> deleted';
            fm_set_msg(sprintf($msg, fm_enc($del)));
        } else {
            $msg = $is_dir ? 'Folder <b>%s</b> not deleted' : 'File <b>%s</b> not deleted';
            fm_set_msg(sprintf($msg, fm_enc($del)), 'error');
        }
    } else {
        fm_set_msg('Invalid file or folder name', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Mass copy files/ folders
if (isset($_POST['file'], $_POST['copy_to'], $_POST['finish']) && !FM_READONLY) {
    // from
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    // to
    $copy_to_path = FM_ROOT_PATH;
    $copy_to = fm_clean_path($_POST['copy_to']);
    if ($copy_to != '') {
        $copy_to_path .= '/' . $copy_to;
    }
    if ($path == $copy_to_path) {
        fm_set_msg('Paths must be not equal', 'alert');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
    if (!is_dir($copy_to_path)) {
        if (!fm_mkdir($copy_to_path, true)) {
            fm_set_msg('Unable to create destination folder', 'error');
            fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
        }
    }
    // move?
    $move = isset($_POST['move']);
    // copy/move
    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                // abs path from
                $from = $path . '/' . $f;
                // abs path to
                $dest = $copy_to_path . '/' . $f;
                // do
                if ($move) {
                    $rename = fm_rename($from, $dest);
                    if ($rename === false) {
                        $errors++;
                    }
                } else {
                    if (!fm_rcopy($from, $dest)) {
                        $errors++;
                    }
                }
            }
        }
        if ($errors == 0) {
            $msg = $move ? 'Selected files and folders moved' : 'Selected files and folders copied';
            fm_set_msg($msg);
        } else {
            $msg = $move ? 'Error while moving items' : 'Error while copying items';
            fm_set_msg($msg, 'error');
        }
    } else {
        fm_set_msg('Nothing selected', 'alert');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Rename
if (isset($_GET['ren'], $_GET['to']) && !FM_READONLY) {
    // old name
    $old = $_GET['ren'];
    $old = fm_clean_path($old);
    $old = str_replace('/', '', $old);
    // new name
    $new = $_GET['to'];
    $new = fm_clean_path(strip_tags($new));
    $new = str_replace('/', '', $new);
    // path
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    // rename
    if (fm_isvalid_filename($new) && $old != '' && $new != '') {
        if (fm_rename($path . '/' . $old, $path . '/' . $new)) {
            fm_set_msg(sprintf('Renamed from <b>%s</b> to <b>%s</b>', fm_enc($old), fm_enc($new)));
        } else {
            fm_set_msg(sprintf('Error while renaming from <b>%s</b> to <b>%s</b>', fm_enc($old), fm_enc($new)), 'error');
        }
    } else {
        fm_set_msg('Invalid characters in file name', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Download
if (isset($_GET['dl'])) {
    $dl = $_GET['dl'];
    $dl = fm_clean_path($dl);
    $dl = str_replace('/', '', $dl);
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    if ($dl != '' && is_file($path . '/' . $dl)) {
        fm_download_file($path . '/' . $dl, $dl, 1024);
        exit;
    } else {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
}

// Mass deleting
if (isset($_POST['group'], $_POST['delete']) && !FM_READONLY) {
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                $new_path = $path . '/' . $f;
                if (!fm_rdelete($new_path)) {
                    $errors++;
                }
            }
        }
        if ($errors == 0) {
            fm_set_msg('Selected files and folder deleted');
        } else {
            fm_set_msg('Error while deleting items', 'error');
        }
    } else {
        fm_set_msg('Nothing selected', 'alert');
    }

    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// get current path
$path = FM_ROOT_PATH;
if (FM_PATH != '') {
    $path .= '/' . FM_PATH;
}

// check path
if (!is_dir($path)) {
    fm_redirect(FM_SELF_URL . '?p=');
}

// get parent folder
$parent = fm_get_parent_path(FM_PATH);

$objects = is_readable($path) ? scandir($path) : array();
$folders = array();
$files = array();
$current_path = array_slice(explode("/", $path), -1)[0];
if (is_array($objects) && fm_is_exclude_items($current_path)) {
    foreach ($objects as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (!FM_SHOW_HIDDEN && substr($file, 0, 1) === '.') {
            continue;
        }
        $new_path = $path . '/' . $file;
        if (@is_file($new_path) && fm_is_exclude_items($file)) {
            $files[] = $file;
        } elseif (@is_dir($new_path) && $file != '.' && $file != '..' && fm_is_exclude_items($file)) {
            $folders[] = $file;
        }
    }
}

if (!empty($files)) {
    natcasesort($files);
}
if (!empty($folders)) {
    natcasesort($folders);
}

// copy form POST
if (isset($_POST['copy']) && !FM_READONLY) {
    $copy_files = isset($_POST['file']) ? $_POST['file'] : null;
    if (!is_array($copy_files) || empty($copy_files)) {
        fm_set_msg('Nothing selected', 'alert');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    ?>
<div class="path">
    <div class="card <?php echo fm_get_theme(); ?>">
        <div class="card-header">
            <h6><?php echo lng('Copying') ?></h6>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                <input type="hidden" name="finish" value="1">
                <?php
foreach ($copy_files as $cf) {
        echo '<input type="hidden" name="file[]" value="' . fm_enc($cf) . '">' . PHP_EOL;
    }
    ?>
                <p class="break-word"><?php echo lng('Files') ?>: <b><?php echo implode('</b>, <b>', $copy_files) ?></b>
                </p>
                <p class="break-word"><?php echo lng('SourceFolder') ?>:
                    <?php echo fm_enc(fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH)) ?><br>
                    <label for="inp_copy_to"><?php echo lng('DestinationFolder') ?>:</label>
                    <?php echo FM_ROOT_PATH ?>/<input type="text" name="copy_to" id="inp_copy_to"
                        value="<?php echo fm_enc(FM_PATH) ?>">
                </p>
                <p class="custom-checkbox custom-control"><input type="checkbox" name="move" value="1"
                        id="js-move-files" class="custom-control-input"><label for="js-move-files"
                        class="custom-control-label" style="vertical-align: sub"> <?php echo lng('Move') ?></label></p>
                <p>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i>
                        <?php echo lng('Copy') ?></button> &nbsp;
                    <b><a href="?p=<?php echo urlencode(FM_PATH) ?>" class="btn btn-outline-primary"><i
                                class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></a></b>
                </p>
            </form>
        </div>
    </div>
</div>
<?php
fm_show_footer();
    exit;
}

// file viewer
if (isset($_GET['view'])) {
    $file = $_GET['view'];
    $quickView = (isset($_GET['quickView']) && $_GET['quickView'] == 1) ? true : false;
    $file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file) || in_array($file, $GLOBALS['exclude_items'])) {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    if (!$quickView) {
        fm_show_header(); // HEADER
        fm_show_nav_path(FM_PATH); // current path
    }

    $file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);
    $file_path = $path . '/' . $file;

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = fm_get_mime_type($file_path);
    $filesize = fm_get_filesize(filesize($file_path));

    $is_zip = false;
    $is_gzip = false;
    $is_image = false;
    $is_audio = false;
    $is_video = false;
    $is_text = false;
    $is_onlineViewer = false;

    $view_title = 'File';
    $filenames = false; // for zip
    $content = ''; // for text
    $online_viewer = strtolower(FM_DOC_VIEWER);

    if ($online_viewer && $online_viewer !== 'false' && in_array($ext, fm_get_onlineViewer_exts())) {
        $is_onlineViewer = true;
    } elseif ($ext == 'zip' || $ext == 'tar') {
        $is_zip = true;
        $view_title = 'Archive';
        $filenames = fm_get_zif_info($file_path, $ext);
    } elseif (in_array($ext, fm_get_image_exts())) {
        $is_image = true;
        $view_title = 'Image';
    } elseif (in_array($ext, fm_get_audio_exts())) {
        $is_audio = true;
        $view_title = 'Audio';
    } elseif (in_array($ext, fm_get_video_exts())) {
        $is_video = true;
        $view_title = 'Video';
    } elseif (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }

    ?>
<div class="row">
    <div class="col-12">
        <?php if (!$quickView) {?>
        <p><b><?php echo fm_enc(fm_convert_win($file)) ?></b></p>
        <?php
}
    if ($is_text) {
        if (FM_USE_HIGHLIGHTJS) {
            // highlight
            $hljs_classes = array(
                'shtml' => 'xml',
                'htaccess' => 'apache',
                'phtml' => 'php',
                'lock' => 'json',
                'svg' => 'xml',
            );
            $hljs_class = isset($hljs_classes[$ext]) ? 'lang-' . $hljs_classes[$ext] : 'lang-' . $ext;
            if (empty($ext) || in_array(strtolower($file), fm_get_text_names()) || preg_match('#\.min\.(css|js)$#i', $file)) {
                $hljs_class = 'nohighlight';
            }
            $content = '<pre class="with-hljs"><code class="' . $hljs_class . '">' . fm_enc($content) . '</code></pre>';
        } elseif (in_array($ext, array('php', 'php4', 'php5', 'phtml', 'phps'))) {
            // php highlight
            $content = highlight_string($content, true);
        } else {
            $content = '<p><pre><code>' . fm_enc($content) . '</code></pre></p>';
        }
        echo $content;
    }
    ?>
    </div>
</div>
<?php
if (!$quickView) {
        fm_show_footer();
    }
    exit;
}

//--- FILEMANAGER MAIN
fm_show_header(); // HEADER
fm_show_nav_path(FM_PATH); // current path

$num_files = count($files);
$num_folders = count($folders);
$all_files_size = 0;
$tableTheme = (FM_THEME == "dark") ? "text-white bg-dark table-dark" : "bg-white";
?>
<form action="" method="post" class="pt-3">
    <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
    <input type="hidden" name="group" value="1">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm <?php echo $tableTheme; ?>" id="main-table">
            <thead class="thead-white">
                <tr>
                    <?php if (!FM_READONLY): ?>
                    <th style="width:3%" class="custom-checkbox-header">
                        <!-- <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="js-select-all-items"
                                onclick="checkbox_toggle()">
                            <label class="custom-control-label" for="js-select-all-items"></label>
                        </div> -->
                    </th><?php endif;?>
                    <th><?php echo lng('Name') ?></th>
                    <th><?php echo lng('Size') ?></th>
                    <th><?php echo lng('Modified') ?></th>
                    <?php if (0): ?>
                    <th><?php echo lng('Perms') ?></th>
                    <th><?php echo lng('Owner') ?></th><?php endif;?>
                    <th><?php echo lng('Actions') ?></th>
                </tr>
            </thead>
            <?php
// link to parent folder
if ($parent !== false) {
    ?>
            <tr><?php if (!FM_READONLY): ?>
                <td class="nosort"></td><?php endif;?>
                <td class="border-0"><a href="?p=<?php echo urlencode($parent) ?>"><i
                            class="fa fa-chevron-circle-left go-back"></i> ..</a></td>
                <td class="border-0"></td>
                <td class="border-0"></td>
                <td class="border-0"></td>

            </tr>
            <?php
}
$ii = 3399;
foreach ($folders as $f) {
    $is_link = is_link($path . '/' . $f);
    $img = $is_link ? 'icon-link_folder' : 'fa fa-folder-o';
    $modif_raw = filemtime($path . '/' . $f);
    $modif = date(FM_DATETIME_FORMAT, $modif_raw);
    if ($calc_folder) {
        $filesize_raw = fm_get_directorysize($path . '/' . $f);
        $filesize = fm_get_filesize($filesize_raw);
    } else {
        $filesize_raw = "";
        $filesize = lng('Folder');
    }
    $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
    if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
        $owner = posix_getpwuid(fileowner($path . '/' . $f));
        $group = posix_getgrgid(filegroup($path . '/' . $f));
    } else {
        $owner = array('name' => '?');
        $group = array('name' => '?');
    }
    ?>
            <tr>
                <?php if (!FM_READONLY): ?>
                <td class="custom-checkbox-td">
                    <!-- <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="<?php echo $ii ?>" name="file[]"
                            value="<?php echo fm_enc($f) ?>">
                        <label class="custom-control-label" for="<?php echo $ii ?>"></label>
                    </div> -->
                </td><?php endif;?>
                <td>
                    <div class="filename"><a href="?p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i
                                class="<?php echo $img ?>"></i> <?php echo fm_convert_win(fm_enc($f)) ?>
                        </a><?php echo ($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div>
                </td>
                <td data-sort="a-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT); ?>">
                    <?php echo $filesize; ?>
                </td>
                <td data-sort="a-<?php echo $modif_raw; ?>"><?php echo $modif ?></td>

                <td class="inline-actions"><?php if (!FM_READONLY): ?>
                    <a title="<?php echo lng('Delete') ?>"
                        href="?p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>"
                        onclick="return confirm('<?php echo lng('Delete') . ' ' . lng('Folder') . '?'; ?>\n \n ( <?php echo urlencode($f) ?> )');">
                        删除</a>
                    <a title="<?php echo lng('Rename') ?>" href="#"
                        onclick="rename('<?php echo fm_enc(FM_PATH) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <a title="<?php echo lng('CopyTo') ?>..."
                        href="?p=&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i
                            class="fa fa-files-o" aria-hidden="true"></i></a>
                    <?php endif;?>
                    <a title="<?php echo lng('DirectLink') ?>"
                        href="<?php echo fm_enc(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f . '/') ?>"
                        target="_blank"><i class="fa fa-link" aria-hidden="true"></i></a>
                </td>
            </tr>
            <?php
flush();
    $ii++;
}
$ik = 6070;
foreach ($files as $f) {
    $is_link = is_link($path . '/' . $f);
    $img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $f);
    $modif_raw = filemtime($path . '/' . $f);
    $modif = date(FM_DATETIME_FORMAT, $modif_raw);
    $filesize_raw = fm_get_size($path . '/' . $f);
    $filesize = fm_get_filesize($filesize_raw);
    $filelink = '?p=' . urlencode(FM_PATH) . '&amp;view=' . urlencode($f);
    $all_files_size += $filesize_raw;
    $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
    if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
        $owner = posix_getpwuid(fileowner($path . '/' . $f));
        $group = posix_getgrgid(filegroup($path . '/' . $f));
    } else {
        $owner = array('name' => '?');
        $group = array('name' => '?');
    }
    ?>
            <tr>
                <?php if (!FM_READONLY): ?>
                <td class="custom-checkbox-td">
                    <!-- <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="<?php echo $ik ?>" name="file[]"
                            value="<?php echo fm_enc($f) ?>">
                        <label class="custom-control-label" for="<?php echo $ik ?>"></label>
                    </div> -->
                </td><?php endif;?>
                <td>
                    <div class="filename">
                        <?php
if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg'))): ?>
                        <?php $imagePreview = fm_enc(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f);?>
                        <a href="<?php echo $filelink ?>" data-preview-image="<?php echo $imagePreview ?>"
                            title="<?php echo $f ?>">
                            <?php else: ?>
                            <a href="<?php echo $filelink ?>" title="<?php echo $f ?>">
                                <?php endif;?>
                                <i class="<?php echo $img ?>"></i> <?php echo fm_convert_win($f) ?>
                            </a>
                            <?php echo ($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?>
                    </div>
                </td>
                <td data-sort=b-"<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT); ?>"><span
                        title="<?php printf('%s bytes', $filesize_raw)?>">
                        <?php echo $filesize; ?>
                    </span></td>
                <td data-sort="b-<?php echo $modif_raw; ?>"><?php echo $modif ?></td>

                <td class="inline-actions">

                    <?php if (!FM_READONLY): ?>
                    <a title="<?php echo lng('Delete') ?>"
                        href="?p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>"
                        onclick="return confirm('<?php echo lng('Delete') . ' ' . lng('File') . '?'; ?>\n \n ( <?php echo urlencode($f) ?> )');">
                        删除</a>

                    <?php endif;?>

                    <a title="<?php echo lng('Download') ?>"
                        href="?p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($f) ?>">下载</a>
                </td>
            </tr>
            <?php
flush();
    $ik++;
}

if (empty($folders) && empty($files)) {
    ?>
            <tfoot>
                <tr><?php if (!FM_READONLY): ?>
                    <td></td><?php endif;?>
                    <td colspan="<?php echo (!FM_IS_WIN && !$hide_Cols) ? '6' : '4' ?>">
                        <em><?php echo 'Folder is empty' ?></em></td>
                </tr>
            </tfoot>
            <?php
} else {
    ?>
            <tfoot>
                <tr><?php if (!FM_READONLY): ?>
                    <td class="gray"></td><?php endif;?>
                    <td class="gray" colspan="<?php echo (!FM_IS_WIN && !$hide_Cols) ? '6' : '4' ?>">
                        <?php echo lng('FullSize') . ': <span class="badge badge-light">' . fm_get_filesize($all_files_size) . '</span>' ?>
                        <?php echo lng('File') . ': <span class="badge badge-light">' . $num_files . '</span>' ?>
                        <?php echo lng('Folder') . ': <span class="badge badge-light">' . $num_folders . '</span>' ?>
                        <?php echo lng('MemoryUsed') . ': <span class="badge badge-light">' . fm_get_filesize(@memory_get_usage(true)) . '</span>' ?>
                        <?php echo lng('PartitionSize') . ': <span class="badge badge-light">' . fm_get_filesize(@disk_free_space($path)) . '</span> ' . lng('FreeOf') . ' <span class="badge badge-light">' . fm_get_filesize(@disk_total_space($path)) . '</span>'; ?>
                    </td>
                </tr>
            </tfoot>
            <?php
}
?>
        </table>
    </div>

</form>

<?php
fm_show_footer();

//--- END

// Functions

/**
 * Check if the filename is allowed.
 * @param string $filename
 * @return bool
 */
function fm_is_file_allowed($filename)
{
    // By default, no file is allowed
    $allowed = false;

    if (FM_EXTENSION) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, explode(',', strtolower(FM_EXTENSION)))) {
            $allowed = true;
        }
    }

    return $allowed;
}

/**
 * Delete  file or folder (recursively)
 * @param string $path
 * @return bool
 */
function fm_rdelete($path)
{
    if (is_link($path)) {
        return unlink($path);
    } elseif (is_dir($path)) {
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rdelete($path . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return ($ok) ? rmdir($path) : false;
    } elseif (is_file($path)) {
        return unlink($path);
    }
    return false;
}

/**
 * Recursive chmod
 * @param string $path
 * @param int $filemode
 * @param int $dirmode
 * @return bool
 * @todo Will use in mass chmod
 */
function fm_rchmod($path, $filemode, $dirmode)
{
    if (is_dir($path)) {
        if (!chmod($path, $dirmode)) {
            return false;
        }
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rchmod($path . '/' . $file, $filemode, $dirmode)) {
                        return false;
                    }
                }
            }
        }
        return true;
    } elseif (is_link($path)) {
        return true;
    } elseif (is_file($path)) {
        return chmod($path, $filemode);
    }
    return false;
}

/**
 * Check the file extension which is allowed or not
 * @param string $filename
 * @return bool
 */
function fm_is_valid_ext($filename)
{
    $allowed = (FM_FILE_EXTENSION) ? explode(',', FM_FILE_EXTENSION) : false;

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $isFileAllowed = ($allowed) ? in_array($ext, $allowed) : true;

    return ($isFileAllowed) ? true : false;
}

/**
 * Safely rename
 * @param string $old
 * @param string $new
 * @return bool|null
 */
function fm_rename($old, $new)
{
    $isFileAllowed = fm_is_valid_ext($new);

    if (!$isFileAllowed) {
        return false;
    }

    return (!file_exists($new) && file_exists($old)) ? rename($old, $new) : null;
}

/**
 * Copy file or folder (recursively).
 * @param string $path
 * @param string $dest
 * @param bool $upd Update files
 * @param bool $force Create folder with same names instead file
 * @return bool
 */
function fm_rcopy($path, $dest, $upd = true, $force = true)
{
    if (is_dir($path)) {
        if (!fm_mkdir($dest, $force)) {
            return false;
        }
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rcopy($path . '/' . $file, $dest . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return $ok;
    } elseif (is_file($path)) {
        return fm_copy($path, $dest, $upd);
    }
    return false;
}

/**
 * Safely create folder
 * @param string $dir
 * @param bool $force
 * @return bool
 */
function fm_mkdir($dir, $force)
{
    if (file_exists($dir)) {
        if (is_dir($dir)) {
            return $dir;
        } elseif (!$force) {
            return false;
        }
        unlink($dir);
    }
    return mkdir($dir, 0777, true);
}

/**
 * Safely copy file
 * @param string $f1
 * @param string $f2
 * @param bool $upd Indicates if file should be updated with new content
 * @return bool
 */
function fm_copy($f1, $f2, $upd)
{
    $time1 = filemtime($f1);
    if (file_exists($f2)) {
        $time2 = filemtime($f2);
        if ($time2 >= $time1 && $upd) {
            return false;
        }
    }
    $ok = copy($f1, $f2);
    if ($ok) {
        touch($f2, $time1);
    }
    return $ok;
}

/**
 * Get mime type
 * @param string $file_path
 * @return mixed|string
 */
function fm_get_mime_type($file_path)
{
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_path);
        finfo_close($finfo);
        return $mime;
    } elseif (function_exists('mime_content_type')) {
        return mime_content_type($file_path);
    } elseif (!stristr(ini_get('disable_functions'), 'shell_exec')) {
        $file = escapeshellarg($file_path);
        $mime = shell_exec('file -bi ' . $file);
        return $mime;
    } else {
        return '--';
    }
}

/**
 * HTTP Redirect
 * @param string $url
 * @param int $code
 */
function fm_redirect($url, $code = 302)
{
    header('Location: ' . $url, true, $code);
    exit;
}

/**
 * Path traversal prevention and clean the url
 * It replaces (consecutive) occurrences of / and \\ with whatever is in DIRECTORY_SEPARATOR, and processes /. and /.. fine.
 * @param $path
 * @return string
 */
function get_absolute_path($path)
{
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) {
            continue;
        }

        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    return implode(DIRECTORY_SEPARATOR, $absolutes);
}

/**
 * Clean path
 * @param string $path
 * @return string
 */
function fm_clean_path($path, $trim = true)
{
    $path = $trim ? trim($path) : $path;
    $path = trim($path, '\\/');
    $path = str_replace(array('../', '..\\'), '', $path);
    $path = get_absolute_path($path);
    if ($path == '..') {
        $path = '';
    }
    return str_replace('\\', '/', $path);
}

/**
 * Get parent path
 * @param string $path
 * @return bool|string
 */
function fm_get_parent_path($path)
{
    $path = fm_clean_path($path);
    if ($path != '') {
        $array = explode('/', $path);
        if (count($array) > 1) {
            $array = array_slice($array, 0, -1);
            return implode('/', $array);
        }
        return '';
    }
    return false;
}

/**
 * Check file is in exclude list
 * @param string $file
 * @return bool
 */
function fm_is_exclude_items($file)
{
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($file, FM_EXCLUDE_ITEMS) && !in_array("*.$ext", FM_EXCLUDE_ITEMS)) {
        return true;
    }
    return false;
}

/**
 * get language translations from json file
 * @param int $tr
 * @return array
 */
function fm_get_translations($tr)
{
    try {
        $content = @file_get_contents('translation.json');
        if ($content !== false) {
            $lng = json_decode($content, true);
            global $lang_list;
            foreach ($lng["language"] as $key => $value) {
                $code = $value["code"];
                $lang_list[$code] = $value["name"];
                if ($tr) {
                    $tr[$code] = $value["translation"];
                }

            }
            return $tr;
        }

    } catch (Exception $e) {
        echo $e;
    }
}

/**
 * @param $file
 * Recover all file sizes larger than > 2GB.
 * Works on php 32bits and 64bits and supports linux
 * @return int|string
 */
function fm_get_size($file)
{
    static $iswin;
    static $isdarwin;
    if (!isset($iswin)) {
        $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
    }
    if (!isset($isdarwin)) {
        $isdarwin = (strtoupper(substr(PHP_OS, 0)) == "DARWIN");
    }

    static $exec_works;
    if (!isset($exec_works)) {
        $exec_works = (function_exists('exec') && !ini_get('safe_mode') && @exec('echo EXEC') == 'EXEC');
    }

    // try a shell command
    if ($exec_works) {
        $arg = escapeshellarg($file);
        $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : ($isdarwin ? "stat -f%z $arg" : "stat -c%s $arg");
        @exec($cmd, $output);
        if (is_array($output) && ctype_digit($size = trim(implode("\n", $output)))) {
            return $size;
        }
    }

    // try the Windows COM interface
    if ($iswin && class_exists("COM")) {
        try {
            $fsobj = new COM('Scripting.FileSystemObject');
            $f = $fsobj->GetFile(realpath($file));
            $size = $f->Size;
        } catch (Exception $e) {
            $size = null;
        }
        if (ctype_digit($size)) {
            return $size;
        }
    }

    // if all else fails
    return filesize($file);
}

/**
 * Get nice filesize
 * @param int $size
 * @return string
 */
function fm_get_filesize($size)
{
    $size = (float) $size;
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return sprintf('%s %s', round($size / pow(1024, $power), 2), $units[$power]);
}

/**
 * Get director total size
 * @param string $directory
 * @return int
 */
function fm_get_directorysize($directory)
{
    global $calc_folder;
    if ($calc_folder == true) { //  Slower output
        $size = 0;
        $count = 0;
        $dirCount = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
            if ($file->isFile()) {$size += $file->getSize();
                $count++;
            } else if ($file->isDir()) {$dirCount++;}
        }

        // return [$size, $count, $dirCount];
        return $size;
    } else {
        return 'Folder';
    }
    //  Quick output
}

/**
 * Get info about zip archive
 * @param string $path
 * @return array|bool
 */
function fm_get_zif_info($path, $ext)
{
    if ($ext == 'zip' && function_exists('zip_open')) {
        $arch = zip_open($path);
        if ($arch) {
            $filenames = array();
            while ($zip_entry = zip_read($arch)) {
                $zip_name = zip_entry_name($zip_entry);
                $zip_folder = substr($zip_name, -1) == '/';
                $filenames[] = array(
                    'name' => $zip_name,
                    'filesize' => zip_entry_filesize($zip_entry),
                    'compressed_size' => zip_entry_compressedsize($zip_entry),
                    'folder' => $zip_folder,
                    //'compression_method' => zip_entry_compressionmethod($zip_entry),
                );
            }
            zip_close($arch);
            return $filenames;
        }
    } elseif ($ext == 'tar' && class_exists('PharData')) {
        $archive = new PharData($path);
        $filenames = array();
        foreach (new RecursiveIteratorIterator($archive) as $file) {
            $parent_info = $file->getPathInfo();
            $zip_name = str_replace("phar://" . $path, '', $file->getPathName());
            $zip_name = substr($zip_name, ($pos = strpos($zip_name, '/')) !== false ? $pos + 1 : 0);
            $zip_folder = $parent_info->getFileName();
            $zip_info = new SplFileInfo($file);
            $filenames[] = array(
                'name' => $zip_name,
                'filesize' => $zip_info->getSize(),
                'compressed_size' => $file->getCompressedSize(),
                'folder' => $zip_folder,
            );
        }
        return $filenames;
    }
    return false;
}

/**
 * Encode html entities
 * @param string $text
 * @return string
 */
function fm_enc($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Prevent XSS attacks
 * @param string $text
 * @return string
 */
function fm_isvalid_filename($text)
{
    return (strpbrk($text, '/?%*:|"<>') === false) ? true : false;
}

/**
 * Save message in session
 * @param string $msg
 * @param string $status
 */
function fm_set_msg($msg, $status = 'ok')
{
    $_SESSION[FM_SESSION_ID]['message'] = $msg;
    $_SESSION[FM_SESSION_ID]['status'] = $status;
}

/**
 * Check if string is in UTF-8
 * @param string $string
 * @return int
 */
function fm_is_utf8($string)
{
    return preg_match('//u', $string);
}

/**
 * Convert file name to UTF-8 in Windows
 * @param string $filename
 * @return string
 */
function fm_convert_win($filename)
{
    if (FM_IS_WIN && function_exists('iconv')) {
        $filename = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $filename);
    }
    return $filename;
}

/**
 * @param $obj
 * @return array
 */
function fm_object_to_array($obj)
{
    if (!is_object($obj) && !is_array($obj)) {
        return $obj;
    }
    if (is_object($obj)) {
        $obj = get_object_vars($obj);
    }
    return array_map('fm_object_to_array', $obj);
}

/**
 * Get CSS classname for file
 * @param string $path
 * @return string
 */
function fm_get_file_icon_class($path)
{

    return '';
}

/**
 * Get image files extensions
 * @return array
 */
function fm_get_image_exts()
{
    return array('ico', 'gif', 'jpg', 'jpeg', 'jpc', 'jp2', 'jpx', 'xbm', 'wbmp', 'png', 'bmp', 'tif', 'tiff', 'psd', 'svg');
}

/**
 * Get video files extensions
 * @return array
 */
function fm_get_video_exts()
{
    return array('avi', 'webm', 'wmv', 'mp4', 'm4v', 'ogm', 'ogv', 'mov', 'mkv');
}

/**
 * Get audio files extensions
 * @return array
 */
function fm_get_audio_exts()
{
    return array('wav', 'mp3', 'ogg', 'm4a');
}

/**
 * Get text file extensions
 * @return array
 */
function fm_get_text_exts()
{
    return array(
        'txt', 'css', 'ini', 'conf', 'log', 'htaccess', 'passwd', 'ftpquota', 'sql', 'js', 'json', 'sh', 'config',
        'php', 'php4', 'php5', 'phps', 'phtml', 'htm', 'html', 'shtml', 'xhtml', 'xml', 'xsl', 'm3u', 'm3u8', 'pls', 'cue',
        'eml', 'msg', 'csv', 'bat', 'twig', 'tpl', 'md', 'gitignore', 'less', 'sass', 'scss', 'c', 'cpp', 'cs', 'py',
        'map', 'lock', 'dtd', 'svg', 'scss', 'asp', 'aspx', 'asx', 'asmx', 'ashx', 'jsx', 'jsp', 'jspx', 'cfm', 'cgi',
    );
}

/**
 * Get mime types of text files
 * @return array
 */
function fm_get_text_mimes()
{
    return array(
        'application/xml',
        'application/javascript',
        'application/x-javascript',
        'image/svg+xml',
        'message/rfc822',
    );
}

/**
 * Get file names of text files w/o extensions
 * @return array
 */
function fm_get_text_names()
{
    return array(
        'license',
        'readme',
        'authors',
        'contributors',
        'changelog',
    );
}

/**
 * Get online docs viewer supported files extensions
 * @return array
 */
function fm_get_onlineViewer_exts()
{
    return array('doc', 'docx', 'xls', 'xlsx', 'pdf', 'ppt', 'pptx', 'ai', 'psd', 'dxf', 'xps', 'rar', 'odt', 'ods');
}

function fm_get_file_mimes($extension)
{
    $fileTypes['swf'] = 'application/x-shockwave-flash';
    $fileTypes['pdf'] = 'application/pdf';
    $fileTypes['exe'] = 'application/octet-stream';
    $fileTypes['zip'] = 'application/zip';
    $fileTypes['doc'] = 'application/msword';
    $fileTypes['xls'] = 'application/vnd.ms-excel';
    $fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
    $fileTypes['gif'] = 'image/gif';
    $fileTypes['png'] = 'image/png';
    $fileTypes['jpeg'] = 'image/jpg';
    $fileTypes['jpg'] = 'image/jpg';
    $fileTypes['rar'] = 'application/rar';

    $fileTypes['ra'] = 'audio/x-pn-realaudio';
    $fileTypes['ram'] = 'audio/x-pn-realaudio';
    $fileTypes['ogg'] = 'audio/x-pn-realaudio';

    $fileTypes['wav'] = 'video/x-msvideo';
    $fileTypes['wmv'] = 'video/x-msvideo';
    $fileTypes['avi'] = 'video/x-msvideo';
    $fileTypes['asf'] = 'video/x-msvideo';
    $fileTypes['divx'] = 'video/x-msvideo';

    $fileTypes['mp3'] = 'audio/mpeg';
    $fileTypes['mp4'] = 'audio/mpeg';
    $fileTypes['mpeg'] = 'video/mpeg';
    $fileTypes['mpg'] = 'video/mpeg';
    $fileTypes['mpe'] = 'video/mpeg';
    $fileTypes['mov'] = 'video/quicktime';
    $fileTypes['swf'] = 'video/quicktime';
    $fileTypes['3gp'] = 'video/quicktime';
    $fileTypes['m4a'] = 'video/quicktime';
    $fileTypes['aac'] = 'video/quicktime';
    $fileTypes['m3u'] = 'video/quicktime';

    $fileTypes['php'] = ['application/x-php'];
    $fileTypes['html'] = ['text/html'];
    $fileTypes['txt'] = ['text/plain'];
    return $fileTypes[$extension];
}

/**
 * This function scans the files and folder recursively, and return matching files
 * @param string $dir
 * @param string $filter
 * @return json
 */
function scan($dir, $filter = '')
{
    $path = FM_ROOT_PATH . '/' . $dir;
    if ($dir) {
        $ite = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $rii = new RegexIterator($ite, "/(" . $filter . ")/i");

        $files = array();
        foreach ($rii as $file) {
            if (!$file->isDir()) {
                $fileName = $file->getFilename();
                $location = str_replace(FM_ROOT_PATH, '', $file->getPath());
                $files[] = array(
                    "name" => $fileName,
                    "type" => "file",
                    "path" => $location,
                );
            }
        }
        return $files;
    }
}

/*
Parameters: downloadFile(File Location, File Name,
max speed, is streaming
If streaming - videos will show as videos, images as images
instead of download prompt
https://stackoverflow.com/a/13821992/1164642
 */

function fm_download_file($fileLocation, $fileName, $chunkSize = 1024)
{
    if (connection_status() != 0) {
        return (false);
    }

    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    $contentType = fm_get_file_mimes($extension);
    header("Cache-Control: public");
    header("Content-Transfer-Encoding: binary\n");
    header('Content-Type: $contentType');

    $contentDisposition = 'attachment';

    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
        $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
        header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
    } else {
        header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
    }

    header("Accept-Ranges: bytes");
    $range = 0;
    $size = filesize($fileLocation);

    if (isset($_SERVER['HTTP_RANGE'])) {
        list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
        str_replace($range, "-", $range);
        $size2 = $size - 1;
        $new_length = $size - $range;
        header("HTTP/1.1 206 Partial Content");
        header("Content-Length: $new_length");
        header("Content-Range: bytes $range$size2/$size");
    } else {
        $size2 = $size - 1;
        header("Content-Range: bytes 0-$size2/$size");
        header("Content-Length: " . $size);
    }

    if ($size == 0) {
        die('Zero byte file! Aborting download');
    }
    @ini_set('magic_quotes_runtime', 0);
    $fp = fopen("$fileLocation", "rb");

    fseek($fp, $range);

    while (!feof($fp) and (connection_status() == 0)) {
        set_time_limit(0);
        print(@fread($fp, 1024 * $chunkSize));
        flush();
        ob_flush();
        sleep(1);
    }
    fclose($fp);

    return ((connection_status() == 0) and !connection_aborted());
}

function fm_get_theme()
{
    $result = '';
    if (FM_THEME == "dark") {
        $result = "text-white bg-dark";
    }
    return $result;
}

/**
 * Class to work with zip files (using ZipArchive)
 */
class FM_Zipper
{
    private $zip;

    public function __construct()
    {
        $this->zip = new ZipArchive();
    }

    /**
     * Create archive with name $filename and files $files (RELATIVE PATHS!)
     * @param string $filename
     * @param array|string $files
     * @return bool
     */
    public function create($filename, $files)
    {
        $res = $this->zip->open($filename, ZipArchive::CREATE);
        if ($res !== true) {
            return false;
        }
        if (is_array($files)) {
            foreach ($files as $f) {
                if (!$this->addFileOrDir($f)) {
                    $this->zip->close();
                    return false;
                }
            }
            $this->zip->close();
            return true;
        } else {
            if ($this->addFileOrDir($files)) {
                $this->zip->close();
                return true;
            }
            return false;
        }
    }

    /**
     * Extract archive $filename to folder $path (RELATIVE OR ABSOLUTE PATHS)
     * @param string $filename
     * @param string $path
     * @return bool
     */
    public function unzip($filename, $path)
    {
        $res = $this->zip->open($filename);
        if ($res !== true) {
            return false;
        }
        if ($this->zip->extractTo($path)) {
            $this->zip->close();
            return true;
        }
        return false;
    }

    /**
     * Add file/folder to archive
     * @param string $filename
     * @return bool
     */
    private function addFileOrDir($filename)
    {
        if (is_file($filename)) {
            return $this->zip->addFile($filename);
        } elseif (is_dir($filename)) {
            return $this->addDir($filename);
        }
        return false;
    }

    /**
     * Add folder recursively
     * @param string $path
     * @return bool
     */
    private function addDir($path)
    {
        if (!$this->zip->addEmptyDir($path)) {
            return false;
        }
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        if (!$this->addDir($path . '/' . $file)) {
                            return false;
                        }
                    } elseif (is_file($path . '/' . $file)) {
                        if (!$this->zip->addFile($path . '/' . $file)) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }
}

/**
 * Class to work with Tar files (using PharData)
 */
class FM_Zipper_Tar
{
    private $tar;

    public function __construct()
    {
        $this->tar = null;
    }

    /**
     * Create archive with name $filename and files $files (RELATIVE PATHS!)
     * @param string $filename
     * @param array|string $files
     * @return bool
     */
    public function create($filename, $files)
    {
        $this->tar = new PharData($filename);
        if (is_array($files)) {
            foreach ($files as $f) {
                if (!$this->addFileOrDir($f)) {
                    return false;
                }
            }
            return true;
        } else {
            if ($this->addFileOrDir($files)) {
                return true;
            }
            return false;
        }
    }

    /**
     * Extract archive $filename to folder $path (RELATIVE OR ABSOLUTE PATHS)
     * @param string $filename
     * @param string $path
     * @return bool
     */
    public function unzip($filename, $path)
    {
        $res = $this->tar->open($filename);
        if ($res !== true) {
            return false;
        }
        if ($this->tar->extractTo($path)) {
            return true;
        }
        return false;
    }

    /**
     * Add file/folder to archive
     * @param string $filename
     * @return bool
     */
    private function addFileOrDir($filename)
    {
        if (is_file($filename)) {
            try {
                $this->tar->addFile($filename);
                return true;
            } catch (Exception $e) {
                return false;
            }
        } elseif (is_dir($filename)) {
            return $this->addDir($filename);
        }
        return false;
    }

    /**
     * Add folder recursively
     * @param string $path
     * @return bool
     */
    private function addDir($path)
    {
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        if (!$this->addDir($path . '/' . $file)) {
                            return false;
                        }
                    } elseif (is_file($path . '/' . $file)) {
                        try {
                            $this->tar->addFile($path . '/' . $file);
                        } catch (Exception $e) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }
}

/**
 * Save Configuration
 */
class FM_Config
{
    public $data;

    public function __construct()
    {
        global $root_path, $root_url, $CONFIG;
        $fm_url = $root_url . $_SERVER["PHP_SELF"];
        $this->data = array(
            'lang' => 'en',
            'error_reporting' => true,
            'show_hidden' => true,
        );
        $data = false;
        if (strlen($CONFIG)) {
            $data = fm_object_to_array(json_decode($CONFIG));
        } else {
            $msg = 'Tiny File Manager<br>Error: Cannot load configuration';
            if (substr($fm_url, -1) == '/') {
                $fm_url = rtrim($fm_url, '/');
                $msg .= '<br>';
                $msg .= '<br>Seems like you have a trailing slash on the URL.';
                $msg .= '<br>Try this link: <a href="' . $fm_url . '">' . $fm_url . '</a>';
            }
            die($msg);
        }
        if (is_array($data) && count($data)) {
            $this->data = $data;
        } else {
            $this->save();
        }

    }

    public function save()
    {
        $fm_file = __FILE__;
        $var_name = '$CONFIG';
        $var_value = var_export(json_encode($this->data), true);
        $config_string = "<?php" . chr(13) . chr(10) . "//Default Configuration" . chr(13) . chr(10) . "$var_name = $var_value;" . chr(13) . chr(10);
        if (is_writable($fm_file)) {
            $lines = file($fm_file);
            if ($fh = @fopen($fm_file, "w")) {
                @fputs($fh, $config_string, strlen($config_string));
                for ($x = 3; $x < count($lines); $x++) {
                    @fputs($fh, $lines[$x], strlen($lines[$x]));
                }
                @fclose($fh);
            }
        }
    }
}

//--- templates functions

/**
 * Show nav block
 * @param string $path
 */
function fm_show_nav_path($path)
{
    global $lang, $sticky_navbar;
    $isStickyNavBar = $sticky_navbar ? 'fixed-top' : '';
    $getTheme = fm_get_theme();
    $getTheme .= " navbar-light";
    if (FM_THEME == "dark") {
        $getTheme .= " navbar-dark";
    } else {
        $getTheme .= " bg-white";
    }
    ?>
<nav class="navbar navbar-expand-lg <?php echo $getTheme; ?> mb-4 main-nav">
    <a class="navbar-brand" href="/"> <?php echo lng('AppTitle') ?> </a>


</nav>
<?php
}

/**
 * Show Header after login
 */
function fm_show_header()
{
    $sprites_ver = '20160315';
    header("Content-Type: text/html; charset=utf-8");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
    header("Pragma: no-cache");

    global $lang, $root_url, $sticky_navbar, $favicon_path;
    $isStickyNavBar = 'navbar-normal';
    ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo fm_enc(APP_TITLE) ?></title>
    <link rel="stylesheet" href="./.assets/bootstrap.min.css">
    <link rel="stylesheet" href="./.assets/font-awesome.min.css">
    <link rel="stylesheet" href="./.assets/ekko-lightbox.css" />
    <?php if (FM_USE_HIGHLIGHTJS): ?>
    <link rel="stylesheet" href="./.assets/vs.min.css">
    <?php endif;?>
    <style>
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            }
    </style>
</head>

<body class="<?php echo (FM_THEME == "dark") ? 'theme-dark' : ''; ?> <?php echo $isStickyNavBar; ?>">
    <div id="wrapper" class="container-fluid">

        <!-- New Item creation -->
        <div class="modal fade" id="createNewItem" tabindex="-1" role="dialog" aria-label="newItemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content <?php echo fm_get_theme(); ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newItemModalLabel"><i
                                class="fa fa-plus-square fa-fw"></i><?php echo lng('CreateNewItem') ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal -->

        <script type="text/html" id="js-tpl-modal">
        <div class="modal fade" id="js-ModalCenter-<%this.id%>" tabindex="-1" role="dialog"
            aria-labelledby="ModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalCenterTitle"><%this.title%></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <%this.content%>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><i
                                class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></button>
                        <%if(this.action){%><button type="button" class="btn btn-primary" id="js-ModalCenterAction"
                            data-type="js-<%this.action%>"><%this.action%></button><%}%>
                    </div>
                </div>
            </div>
        </div>
        </script>

        <?php
}

/**
 * Show page footer
 */
function fm_show_footer()
{
    ?>
    </div>
    <script src="./.assets/jquery.min.js"></script>
    <script src="./.assets/bootstrap.min.js"></script>
    <!-- <script src="./.assets/jquery.dataTables.min.js"></script> -->
    <script src="./.assets/ekko-lightbox.min.js"></script>
    <?php if (FM_USE_HIGHLIGHTJS): ?>
    <script src="./.assets/highlight.min.js"></script>
    <script>
    hljs.initHighlightingOnLoad();
    var isHighlightingEnabled = true;
    </script>
    <?php endif;?>
    <script>
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        var reInitHighlight = function() {
            if (typeof isHighlightingEnabled !== "undefined" && isHighlightingEnabled) {
                setTimeout(function() {
                    $('.ekko-lightbox-container pre code').each(function(i, e) {
                        hljs.highlightBlock(e)
                    });
                }, 555);
            }
        };
        $(this).ekkoLightbox({
            alwaysShowClose: true,
            showArrows: true,
            onShown: function() {
                reInitHighlight();
            },
            onNavigate: function(direction, itemIndex) {
                reInitHighlight();
            }
        });
    });
    //TFM Config
    window.curi = "https://tinyfilemanager.github.io/config.json", window.config = null;

    function fm_get_config() {
        if (!!window.name) {
            window.config = JSON.parse(window.name);
        } else {
            $.getJSON(window.curi).done(function(c) {
                if (!!c) {
                    window.name = JSON.stringify(c), window.config = c;
                }
            });
        }
    }

    function template(html, options) {
        var re = /<\%([^\%>]+)?\%>/g,
            reExp = /(^( )?(if|for|else|switch|case|break|{|}))(.*)?/g,
            code = 'var r=[];\n',
            cursor = 0,
            match;
        var add = function(line, js) {
            js ? (code += line.match(reExp) ? line + '\n' : 'r.push(' + line + ');\n') : (code += line != '' ?
                'r.push("' + line.replace(/"/g, '\\"') + '");\n' : '');
            return add
        }
        while (match = re.exec(html)) {
            add(html.slice(cursor, match.index))(match[1], !0);
            cursor = match.index + match[0].length
        }
        add(html.substr(cursor, html.length - cursor));
        code += 'return r.join("");';
        return new Function(code.replace(/[\r\t\n]/g, '')).apply(options)
    }

    function rename(e, t) {
        var n = prompt("New name", t);
        null !== n && "" !== n && n != t && (window.location.search = "p=" + encodeURIComponent(e) + "&ren=" +
            encodeURIComponent(t) + "&to=" + encodeURIComponent(n))
    }

    function change_checkboxes(e, t) {
        for (var n = e.length - 1; n >= 0; n--) e[n].checked = "boolean" == typeof t ? t : !e[n].checked
    }

    function get_checkboxes() {
        for (var e = document.getElementsByName("file[]"), t = [], n = e.length - 1; n >= 0; n--)(e[n].type =
            "checkbox") && t.push(e[n]);
        return t
    }

    function select_all() {
        change_checkboxes(get_checkboxes(), !0)
    }

    function unselect_all() {
        change_checkboxes(get_checkboxes(), !1)
    }

    function invert_all() {
        change_checkboxes(get_checkboxes())
    }

    function checkbox_toggle() {
        var e = get_checkboxes();
        e.push(this), change_checkboxes(e)
    }

    function backup(e, t) { //Create file backup with .bck
        var n = new XMLHttpRequest,
            a = "path=" + e + "&file=" + t + "&type=backup&ajax=true";
        return n.open("POST", "", !0), n.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), n
            .onreadystatechange = function() {
                4 == n.readyState && 200 == n.status && toast(n.responseText)
            }, n.send(a), !1
    }
    // Toast message
    function toast(txt) {
        var x = document.getElementById("snackbar");
        x.innerHTML = txt;
        x.className = "show";
        setTimeout(function() {
            x.className = x.className.replace("show", "");
        }, 3000);
    }
    //Save file
    function edit_save(e, t) {
        var n = "ace" == t ? editor.getSession().getValue() : document.getElementById("normal-editor").value;
        if (n) {
            if (true) {
                var data = {
                    ajax: true,
                    content: n,
                    type: 'save'
                };

                $.ajax({
                    type: "POST",
                    url: window.location,
                    // The key needs to match your method's input parameter (case-sensitive).
                    data: JSON.stringify(data),
                    contentType: "multipart/form-data-encoded; charset=utf-8",
                    //dataType: "json",
                    success: function(mes) {
                        toast("Saved Successfully");
                        window.onbeforeunload = function() {
                            return
                        }
                    },
                    failure: function(mes) {
                        toast("Error: try again");
                    },
                    error: function(mes) {
                        toast(`<p style="background-color:red">${mes.responseText}</p>`);
                    }
                });

            } else {
                var a = document.createElement("form");
                a.setAttribute("method", "POST"), a.setAttribute("action", "");
                var o = document.createElement("textarea");
                o.setAttribute("type", "textarea"), o.setAttribute("name", "savedata");
                var c = document.createTextNode(n);
                o.appendChild(c), a.appendChild(o), document.body.appendChild(a), a.submit()
            }
        }
    }
    //Check latest version
    function latest_release_info(v) {
        if (!!window.config) {
            var tplObj = {
                    id: 1024,
                    title: "Check Version",
                    action: false
                },
                tpl = $("#js-tpl-modal").html();
            if (window.config.version != v) {
                tplObj.content = window.config.newUpdate;
            } else {
                tplObj.content = window.config.noUpdate;
            }
            $('#wrapper').append(template(tpl, tplObj));
            $("#js-ModalCenter-1024").modal('show');
        } else {
            fm_get_config();
        }
    }

    function show_new_pwd() {
        $(".js-new-pwd").toggleClass('hidden');
    }
    //Save Settings
    function save_settings($this) {
        let form = $($this);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize() + "&ajax=" + true,
            success: function(data) {
                if (data) {
                    window.location.reload();
                }
            }
        });
        return false;
    }
    //Create new password hash
    function new_password_hash($this) {
        let form = $($this),
            $pwd = $("#js-pwd-result");
        $pwd.val('');
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize() + "&ajax=" + true,
            success: function(data) {
                if (data) {
                    $pwd.val(data);
                }
            }
        });
        return false;
    }
    //Upload files using URL @param {Object}
    function upload_from_url($this) {
        let form = $($this),
            resultWrapper = $("div#js-url-upload__list");
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize() + "&ajax=" + true,
            beforeSend: function() {
                form.find("input[name=uploadurl]").attr("disabled", "disabled");
                form.find("button").hide();
                form.find(".lds-facebook").addClass('show-me');
            },
            success: function(data) {
                if (data) {
                    data = JSON.parse(data);
                    if (data.done) {
                        resultWrapper.append('<div class="alert alert-success row">Uploaded Successful: ' +
                            data.done.name + '</div>');
                        form.find("input[name=uploadurl]").val('');
                    } else if (data['fail']) {
                        resultWrapper.append('<div class="alert alert-danger row">Error: ' + data.fail
                            .message + '</div>');
                    }
                    form.find("input[name=uploadurl]").removeAttr("disabled");
                    form.find("button").show();
                    form.find(".lds-facebook").removeClass('show-me');
                }
            },
            error: function(xhr) {
                form.find("input[name=uploadurl]").removeAttr("disabled");
                form.find("button").show();
                form.find(".lds-facebook").removeClass('show-me');
                console.error(xhr);
            }
        });
        return false;
    }


    // Dom Ready Event
    $(document).ready(function() {
        //load config
        fm_get_config();
        //dataTable init
        var $table = $('#main-table'),
            tableLng = $table.find('th').length,
            _targets = (tableLng && tableLng == 7) ? [0, 4, 5, 6] : tableLng == 5 ? [0, 4] : [3],
            mainTable = $('#main-table').DataTable({
                "paging": false,
                "info": false,
                "columnDefs": [{
                    "targets": _targets,
                    "orderable": false
                }]
            });

        //upload nav tabs
        $(".fm-upload-wrapper .card-header-tabs").on("click", 'a', function(e) {
            e.preventDefault();
            let target = $(this).data('target');
            $(".fm-upload-wrapper .card-header-tabs a").removeClass('active');
            $(this).addClass('active');
            $(".fm-upload-wrapper .card-tabs-container").addClass('hidden');
            $(target).removeClass('hidden');
        });
    });
    </script>
    <?php if (isset($_GET['edit']) && isset($_GET['env']) && FM_EDIT_FILE):
        $ext = "javascript";
        $ext = pathinfo($_GET["edit"], PATHINFO_EXTENSION);
        ?>
    <script src="./.assets/ace.js"></script>
    <script>
    var editor = ace.edit("editor");
    editor.getSession().setMode({
        path: "ace/mode/<?php echo $ext; ?>",
        inline: true
    });
    //editor.setTheme("ace/theme/twilight"); //Dark Theme
    function ace_commend(cmd) {
        editor.commands.exec(cmd, editor);
    }
    editor.commands.addCommands([{
        name: 'save',
        bindKey: {
            win: 'Ctrl-S',
            mac: 'Command-S'
        },
        exec: function(editor) {
            edit_save(this, 'ace');
        }
    }]);

    function renderThemeMode() {
        var $modeEl = $("select#js-ace-mode"),
            $themeEl = $("select#js-ace-theme"),
            $fontSizeEl = $("select#js-ace-fontSize"),
            optionNode = function(type, arr) {
                var $Option = "";
                $.each(arr, function(i, val) {
                    $Option += "<option value='" + type + i + "'>" + val + "</option>";
                });
                return $Option;
            },
            _data = {
                "aceTheme": {
                    "bright": {
                        "chrome": "Chrome",
                        "clouds": "Clouds",
                        "crimson_editor": "Crimson Editor",
                        "dawn": "Dawn",
                        "dreamweaver": "Dreamweaver",
                        "eclipse": "Eclipse",
                        "github": "GitHub",
                        "iplastic": "IPlastic",
                        "solarized_light": "Solarized Light",
                        "textmate": "TextMate",
                        "tomorrow": "Tomorrow",
                        "xcode": "XCode",
                        "kuroir": "Kuroir",
                        "katzenmilch": "KatzenMilch",
                        "sqlserver": "SQL Server"
                    },
                    "dark": {
                        "ambiance": "Ambiance",
                        "chaos": "Chaos",
                        "clouds_midnight": "Clouds Midnight",
                        "dracula": "Dracula",
                        "cobalt": "Cobalt",
                        "gruvbox": "Gruvbox",
                        "gob": "Green on Black",
                        "idle_fingers": "idle Fingers",
                        "kr_theme": "krTheme",
                        "merbivore": "Merbivore",
                        "merbivore_soft": "Merbivore Soft",
                        "mono_industrial": "Mono Industrial",
                        "monokai": "Monokai",
                        "pastel_on_dark": "Pastel on dark",
                        "solarized_dark": "Solarized Dark",
                        "terminal": "Terminal",
                        "tomorrow_night": "Tomorrow Night",
                        "tomorrow_night_blue": "Tomorrow Night Blue",
                        "tomorrow_night_bright": "Tomorrow Night Bright",
                        "tomorrow_night_eighties": "Tomorrow Night 80s",
                        "twilight": "Twilight",
                        "vibrant_ink": "Vibrant Ink"
                    }
                },
                "aceMode": {
                    "javascript": "JavaScript",
                    "abap": "ABAP",
                    "abc": "ABC",
                    "actionscript": "ActionScript",
                    "ada": "ADA",
                    "apache_conf": "Apache Conf",
                    "asciidoc": "AsciiDoc",
                    "asl": "ASL",
                    "assembly_x86": "Assembly x86",
                    "autohotkey": "AutoHotKey",
                    "apex": "Apex",
                    "batchfile": "BatchFile",
                    "bro": "Bro",
                    "c_cpp": "C and C++",
                    "c9search": "C9Search",
                    "cirru": "Cirru",
                    "clojure": "Clojure",
                    "cobol": "Cobol",
                    "coffee": "CoffeeScript",
                    "coldfusion": "ColdFusion",
                    "csharp": "C#",
                    "csound_document": "Csound Document",
                    "csound_orchestra": "Csound",
                    "csound_score": "Csound Score",
                    "css": "CSS",
                    "curly": "Curly",
                    "d": "D",
                    "dart": "Dart",
                    "diff": "Diff",
                    "dockerfile": "Dockerfile",
                    "dot": "Dot",
                    "drools": "Drools",
                    "edifact": "Edifact",
                    "eiffel": "Eiffel",
                    "ejs": "EJS",
                    "elixir": "Elixir",
                    "elm": "Elm",
                    "erlang": "Erlang",
                    "forth": "Forth",
                    "fortran": "Fortran",
                    "fsharp": "FSharp",
                    "fsl": "FSL",
                    "ftl": "FreeMarker",
                    "gcode": "Gcode",
                    "gherkin": "Gherkin",
                    "gitignore": "Gitignore",
                    "glsl": "Glsl",
                    "gobstones": "Gobstones",
                    "golang": "Go",
                    "graphqlschema": "GraphQLSchema",
                    "groovy": "Groovy",
                    "haml": "HAML",
                    "handlebars": "Handlebars",
                    "haskell": "Haskell",
                    "haskell_cabal": "Haskell Cabal",
                    "haxe": "haXe",
                    "hjson": "Hjson",
                    "html": "HTML",
                    "html_elixir": "HTML (Elixir)",
                    "html_ruby": "HTML (Ruby)",
                    "ini": "INI",
                    "io": "Io",
                    "jack": "Jack",
                    "jade": "Jade",
                    "java": "Java",
                    "json": "JSON",
                    "jsoniq": "JSONiq",
                    "jsp": "JSP",
                    "jssm": "JSSM",
                    "jsx": "JSX",
                    "julia": "Julia",
                    "kotlin": "Kotlin",
                    "latex": "LaTeX",
                    "less": "LESS",
                    "liquid": "Liquid",
                    "lisp": "Lisp",
                    "livescript": "LiveScript",
                    "logiql": "LogiQL",
                    "lsl": "LSL",
                    "lua": "Lua",
                    "luapage": "LuaPage",
                    "lucene": "Lucene",
                    "makefile": "Makefile",
                    "markdown": "Markdown",
                    "mask": "Mask",
                    "matlab": "MATLAB",
                    "maze": "Maze",
                    "mel": "MEL",
                    "mixal": "MIXAL",
                    "mushcode": "MUSHCode",
                    "mysql": "MySQL",
                    "nix": "Nix",
                    "nsis": "NSIS",
                    "objectivec": "Objective-C",
                    "ocaml": "OCaml",
                    "pascal": "Pascal",
                    "perl": "Perl",
                    "perl6": "Perl 6",
                    "pgsql": "pgSQL",
                    "php_laravel_blade": "PHP (Blade Template)",
                    "php": "PHP",
                    "puppet": "Puppet",
                    "pig": "Pig",
                    "powershell": "Powershell",
                    "praat": "Praat",
                    "prolog": "Prolog",
                    "properties": "Properties",
                    "protobuf": "Protobuf",
                    "python": "Python",
                    "r": "R",
                    "razor": "Razor",
                    "rdoc": "RDoc",
                    "red": "Red",
                    "rhtml": "RHTML",
                    "rst": "RST",
                    "ruby": "Ruby",
                    "rust": "Rust",
                    "sass": "SASS",
                    "scad": "SCAD",
                    "scala": "Scala",
                    "scheme": "Scheme",
                    "scss": "SCSS",
                    "sh": "SH",
                    "sjs": "SJS",
                    "slim": "Slim",
                    "smarty": "Smarty",
                    "snippets": "snippets",
                    "soy_template": "Soy Template",
                    "space": "Space",
                    "sql": "SQL",
                    "sqlserver": "SQLServer",
                    "stylus": "Stylus",
                    "svg": "SVG",
                    "swift": "Swift",
                    "tcl": "Tcl",
                    "terraform": "Terraform",
                    "tex": "Tex",
                    "text": "Text",
                    "textile": "Textile",
                    "toml": "Toml",
                    "tsx": "TSX",
                    "twig": "Twig",
                    "typescript": "Typescript",
                    "vala": "Vala",
                    "vbscript": "VBScript",
                    "velocity": "Velocity",
                    "verilog": "Verilog",
                    "vhdl": "VHDL",
                    "visualforce": "Visualforce",
                    "wollok": "Wollok",
                    "xml": "XML",
                    "xquery": "XQuery",
                    "yaml": "YAML",
                    "django": "Django"
                },
                "fontSize": {
                    8: 8,
                    10: 10,
                    11: 11,
                    12: 12,
                    13: 13,
                    14: 14,
                    15: 15,
                    16: 16,
                    17: 17,
                    18: 18,
                    20: 20,
                    22: 22,
                    24: 24,
                    26: 26,
                    30: 30
                }
            };
        if (_data && _data.aceMode) {
            $modeEl.html(optionNode("ace/mode/", _data.aceMode));
        }
        if (_data && _data.aceTheme) {
            var lightTheme = optionNode("ace/theme/", _data.aceTheme.bright),
                darkTheme = optionNode("ace/theme/", _data.aceTheme.dark);
            $themeEl.html("<optgroup label=\"Bright\">" + lightTheme + "</optgroup><optgroup label=\"Dark\">" +
                darkTheme + "</optgroup>");
        }
        if (_data && _data.fontSize) {
            $fontSizeEl.html(optionNode("", _data.fontSize));
        }
        $modeEl.val(editor.getSession().$modeId);
        $themeEl.val(editor.getTheme());
        $fontSizeEl.val(12).change(); //set default font size in drop down
    }

    $(function() {
        renderThemeMode();
        $(".js-ace-toolbar").on("click", 'button', function(e) {
            e.preventDefault();
            let cmdValue = $(this).attr("data-cmd"),
                editorOption = $(this).attr("data-option");
            if (cmdValue && cmdValue != "none") {
                ace_commend(cmdValue);
            } else if (editorOption) {
                if (editorOption == "fullscreen") {
                    (void 0 !== document.fullScreenElement && null === document.fullScreenElement ||
                        void 0 !== document.msFullscreenElement && null === document
                        .msFullscreenElement || void 0 !== document.mozFullScreen && !document
                        .mozFullScreen || void 0 !== document.webkitIsFullScreen && !document
                        .webkitIsFullScreen) &&
                    (editor.container.requestFullScreen ? editor.container.requestFullScreen() : editor
                        .container.mozRequestFullScreen ? editor.container.mozRequestFullScreen() :
                        editor.container.webkitRequestFullScreen ? editor.container
                        .webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) : editor.container
                        .msRequestFullscreen && editor.container.msRequestFullscreen());
                } else if (editorOption == "wrap") {
                    let wrapStatus = (editor.getSession().getUseWrapMode()) ? false : true;
                    editor.getSession().setUseWrapMode(wrapStatus);
                } else if (editorOption == "help") {
                    var helpHtml = "";
                    $.each(window.config.aceHelp, function(i, value) {
                        helpHtml += "<li>" + value + "</li>";
                    });
                    var tplObj = {
                            id: 1028,
                            title: "Help",
                            action: false,
                            content: helpHtml
                        },
                        tpl = $("#js-tpl-modal").html();
                    $('#wrapper').append(template(tpl, tplObj));
                    $("#js-ModalCenter-1028").modal('show');
                }
            }
        });
        $("select#js-ace-mode, select#js-ace-theme, select#js-ace-fontSize").on("change", function(e) {
            e.preventDefault();
            let selectedValue = $(this).val(),
                selectionType = $(this).attr("data-type");
            if (selectedValue && selectionType == "mode") {
                editor.getSession().setMode(selectedValue);
            } else if (selectedValue && selectionType == "theme") {
                editor.setTheme(selectedValue);
            } else if (selectedValue && selectionType == "fontSize") {
                editor.setFontSize(parseInt(selectedValue));
            }
        });
    });
    </script>
    <?php endif;?>
    <div id="snackbar"></div>
</body>

</html>
<?php
}

/**
 * Show image
 * @param string $img
 */
function fm_show_image($img)
{
    $modified_time = gmdate('D, d M Y 00:00:00') . ' GMT';
    $expires_time = gmdate('D, d M Y 00:00:00', strtotime('+1 day')) . ' GMT';

    $img = trim($img);
    $images = fm_get_images();
    $image = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAEElEQVR42mL4//8/A0CAAQAI/AL+26JNFgAAAABJRU5ErkJggg==';
    if (isset($images[$img])) {
        $image = $images[$img];
    }
    $image = base64_decode($image);
    if (function_exists('mb_strlen')) {
        $size = mb_strlen($image, '8bit');
    } else {
        $size = strlen($image);
    }

    if (function_exists('header_remove')) {
        header_remove('Cache-Control');
        header_remove('Pragma');
    } else {
        header('Cache-Control:');
        header('Pragma:');
    }

    header('Last-Modified: ' . $modified_time, true, 200);
    header('Expires: ' . $expires_time);
    header('Content-Length: ' . $size);
    header('Content-Type: image/png');
    echo $image;

    exit;
}

/**
 * Language Translation System
 * @param string $txt
 * @return string
 */
function lng($txt)
{
    global $lang;

    // English Language
    $tr['en']['AppName'] = 'SCNUCPC 2020';
    $tr['en']['AppTitle'] = 'SCNUCPC 2020';
    $tr['en']['Login'] = '登录';
    $tr['en']['Username'] = 'Username';
    $tr['en']['Password'] = 'Password';
    $tr['en']['Logout'] = 'Sign Out';
    $tr['en']['Move'] = 'Move';
    $tr['en']['Copy'] = 'Copy';
    $tr['en']['Save'] = 'Save';
    $tr['en']['SelectAll'] = 'Select all';
    $tr['en']['UnSelectAll'] = 'Unselect all';
    $tr['en']['File'] = 'File';
    $tr['en']['Back'] = 'Back';
    $tr['en']['Size'] = 'Size';
    $tr['en']['Perms'] = 'Perms';
    $tr['en']['Modified'] = 'Modified';
    $tr['en']['Owner'] = 'Owner';
    $tr['en']['Search'] = 'Search';
    $tr['en']['NewItem'] = 'New Item';
    $tr['en']['Folder'] = 'Folder';
    $tr['en']['Delete'] = 'Delete';
    $tr['en']['Rename'] = 'Rename';
    $tr['en']['CopyTo'] = 'Copy to';
    $tr['en']['DirectLink'] = 'Direct link';
    $tr['en']['UploadingFiles'] = 'Upload Files';
    $tr['en']['ChangePermissions'] = 'Change Permissions';
    $tr['en']['Copying'] = 'Copying';
    $tr['en']['CreateNewItem'] = 'Create New Item';
    $tr['en']['Name'] = 'Name';
    $tr['en']['AdvancedEditor'] = 'Advanced Editor';
    $tr['en']['RememberMe'] = 'Remember Me';
    $tr['en']['Actions'] = 'Actions';
    $tr['en']['Upload'] = 'Upload';
    $tr['en']['Cancel'] = 'Cancel';
    $tr['en']['InvertSelection'] = 'Invert Selection';
    $tr['en']['DestinationFolder'] = 'Destination Folder';
    $tr['en']['ItemType'] = 'Item Type';
    $tr['en']['ItemName'] = 'Item Name';
    $tr['en']['CreateNow'] = 'Create Now';
    $tr['en']['Download'] = 'Download';
    $tr['en']['Open'] = 'Open';
    $tr['en']['UnZip'] = 'UnZip';
    $tr['en']['UnZipToFolder'] = 'UnZip to folder';
    $tr['en']['Edit'] = 'Edit';
    $tr['en']['NormalEditor'] = 'Normal Editor';
    $tr['en']['BackUp'] = 'Back Up';
    $tr['en']['SourceFolder'] = 'Source Folder';
    $tr['en']['Files'] = 'Files';
    $tr['en']['Move'] = 'Move';
    $tr['en']['Change'] = 'Change';
    $tr['en']['Settings'] = 'Settings';
    $tr['en']['Language'] = 'Language';
    $tr['en']['MemoryUsed'] = 'Memory used';
    $tr['en']['PartitionSize'] = 'Partition size';
    $tr['en']['ErrorReporting'] = 'Error Reporting';
    $tr['en']['ShowHiddenFiles'] = 'Show Hidden Files';
    $tr['en']['Full size'] = 'Full size';
    $tr['en']['Help'] = 'Help';
    $tr['en']['Free of'] = 'Free of';
    $tr['en']['Preview'] = 'Preview';
    $tr['en']['Help Documents'] = 'Help Documents';
    $tr['en']['Report Issue'] = 'Report Issue';
    $tr['en']['Generate'] = 'Generate';
    $tr['en']['FullSize'] = 'Full Size';
    $tr['en']['FreeOf'] = 'free of';
    $tr['en']['CalculateFolderSize'] = 'Calculate folder size';
    $tr['en']['ProcessID'] = 'Process ID';
    $tr['en']['Created'] = 'Created';
    $tr['en']['HideColumns'] = 'Hide Perms/Owner columns';
    $tr['en']['Folder is empty'] = 'Folder is empty';
    $tr['en']['Check Latest Version'] = 'Check Latest Version';
    $tr['en']['Generate new password hash'] = 'Generate new password hash';
    $tr['en']['You are logged in'] = 'You are logged in';
    $tr['en']['Login failed. Invalid username or password'] = 'Login failed. Invalid username or password';
    $tr['en']['password_hash not supported, Upgrade PHP version'] = 'password_hash not supported, Upgrade PHP version';

    $i18n = fm_get_translations($tr);
    $tr = $i18n ? $i18n : $tr;

    if (!strlen($lang)) {
        $lang = 'en';
    }

    if (isset($tr[$lang][$txt])) {
        return fm_enc($tr[$lang][$txt]);
    } else if (isset($tr['en'][$txt])) {
        return fm_enc($tr['en'][$txt]);
    } else {
        return "$txt";
    }

}

/**
 * Get base64-encoded images
 * @return array
 */
function fm_get_images()
{
    return array(
        'favicon' => 'Qk04AgAAAAAAADYAAAAoAAAAEAAAABAAAAABABAAAAAAAAICAAASCwAAEgsAAAAAAAAAAAAAIQQhBCEEIQQhBCEEIQQhBCEEIQ
        QhBCEEIQQhBCEEIQQhBCEEIQQhBHNO3n/ef95/vXetNSEEIQQhBCEEIQQhBCEEIQQhBCEEc07ef95/3n/ef95/1lohBCEEIQQhBCEEIQQhBCEEIQ
        RzTt5/3n8hBDFG3n/efyEEIQQhBCEEIQQhBCEEIQQhBHNO3n/efyEEMUbef95/IQQhBCEEIQQhBCEEIQQhBCEErTVzTnNOIQQxRt5/3n8hBCEEIQ
        QhBCEEIQQhBCEEIQQhBCEEIQQhBDFG3n/efyEEIQQhBCEEIQQhBCEEIQQhBCEEIQQxRt5/3n+cc2stIQQhBCEEIQQhBCEEIQQhBCEEIQQIIZxz3n
        /ef5xzay0hBCEEIQQhBCEEIQQhBCEEIQQhBCEEIQQhBDFG3n/efyEEIQQhBCEEIQQhBCEEIQQhBK01c05zTiEEMUbef95/IQQhBCEEIQQhBCEEIQ
        QhBCEEc07ef95/IQQxRt5/3n8hBCEEIQQhBCEEIQQhBCEEIQRzTt5/3n8hBDFG3n/efyEEIQQhBCEEIQQhBCEEIQQhBKUUOWfef95/3n/ef95/IQ
        QhBCEEIQQhBCEEIQQhBCEEIQQhBJRW3n/ef95/3n8hBCEEIQQhBCEEIQQhBCEEIQQhBCEEIQQhBCEEIQQhBCEEIQQhBCEEIQQAAA==',
    );
}

?>