<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$
//[RUNTIME]
// 目录设置
define('CACHE_DIR',  'cache');
define('HTML_DIR',    'html');
define('CONF_DIR',    'conf');
define('LIB_DIR',      'lib');
define('LOG_DIR',     'logs');
define('LANG_DIR',    'lang');
define('TEMP_DIR',    'temp');
define('TMPL_DIR',     'template');
// 路径设置
define('TMPL_PATH','./template/');
define('HTML_PATH','./'.RUNTIME_PATH.'/'.HTML_DIR.'/');
//define('TMPL_PATH',APP_PATH.'/'.TMPL_DIR.'/');
//define('HTML_PATH',APP_PATH.'/'.HTML_DIR.'/'); //
define('COMMON_PATH',   APP_PATH.'/common/'); // 项目公共目录
define('LIB_PATH',         APP_PATH.'/'.LIB_DIR.'/'); //
define('CACHE_PATH',   RUNTIME_PATH.CACHE_DIR.'/'); //
define('CONFIG_PATH',  APP_PATH.'/'.CONF_DIR.'/'); //
define('LOG_PATH',       RUNTIME_PATH.LOG_DIR.'/'); //
define('LANG_PATH',     APP_PATH.'/'.LANG_DIR.'/'); //
define('TEMP_PATH',      RUNTIME_PATH.TEMP_DIR.'/'); //
define('DATA_PATH', RUNTIME_PATH.'data/'); //
define('VENDOR_PATH',THINK_PATH.'/vendor/');
//[/RUNTIME]
// 为了方便导入第三方类库 设置Vendor目录到include_path
set_include_path(get_include_path() . PATH_SEPARATOR . VENDOR_PATH);
?>