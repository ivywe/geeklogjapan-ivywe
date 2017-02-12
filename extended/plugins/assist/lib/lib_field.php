<?php

if (strpos ($_SERVER['PHP_SELF'], 'lib_field.php') !== false) {
    die ('This file can not be used on its own.');
}


// +---------------------------------------------------------------------------+
// | 機能  一覧表示
// | 書式 LIB_List($pi_name)
// +---------------------------------------------------------------------------+
// | 引数 $pi_name:plugin name 'databox' 'userbox' 'formbox'
// +---------------------------------------------------------------------------+
// | 戻値 nomal:一覧
// +---------------------------------------------------------------------------+
function LIB_List(
    $pi_name
)
{
    global $_CONF;
    global $_TABLES;
    global $LANG_ADMIN;
    global $LANG09;

    $lang_box_admin="LANG_".strtoupper($pi_name)."_ADMIN";
    global $$lang_box_admin;
    $lang_box_admin=$$lang_box_admin;

    $lang_box="LANG_".strtoupper($pi_name);
    global $$lang_box;
    $lang_box=$$lang_box;

    $table=$_TABLES[strtoupper($pi_name).'_def_field'];

    require_once( $_CONF['path_system'] . 'lib-admin.php' );

    $retval = '';

    //MENU1:管理画面
    $url1=$_CONF['site_admin_url'] . '/plugins/'.THIS_SCRIPT.'?mode=new';
    $url2=$_CONF['site_url'] . '/'.$pi_name.'/index.php';

    $url3=$_CONF['site_url'] . '/'.$pi_name.'/field.php';

    $url5=$_CONF['site_admin_url'] . '/plugins/'.THIS_SCRIPT.'?mode=export';
    $url6=$_CONF['site_admin_url'] . '/plugins/'.THIS_SCRIPT.'?mode=import';

    $menu_arr[]=array('url' => $url1,'text' => $lang_box_admin['new']);
    $menu_arr[]=array('url' => $url2,'text' => $lang_box['list']);
    $menu_arr[]=array('url' => $url3,'text' => $lang_box['field_top']);

    $menu_arr[]=array('url' => $url5,'text' => $lang_box_admin['export']);
    //$menu_arr[]=array('url' => $url6,'text' => $lang_box['export']);
    $menu_arr[]=array('url' => $_CONF['site_admin_url'],'text' => $LANG_ADMIN['admin_home']);

    $retval .= COM_startBlock($lang_box_admin['admin_list'], '',
                              COM_getBlockTemplate('_admin_block', 'header'));

    $function="plugin_geticon_".$pi_name;
    $icon=$function();
    $retval .= ADMIN_createMenu(
        $menu_arr,
        $lang_box_admin['instructions'],
        $icon
    );


    //ヘッダ：編集～
    $header_arr[]=array('text' => $lang_box_admin['orderno'], 'field' => 'orderno', 'sort' => true);
    $header_arr[]=array('text' => $LANG_ADMIN['edit'], 'field' => 'editid', 'sort' => false);
    $header_arr[]=array('text' => $LANG_ADMIN['copy'], 'field' => 'copy', 'sort' => false);
    $header_arr[]=array('text' => $lang_box_admin['field_id'], 'field' => 'field_id', 'sort' => true);
    $header_arr[]=array('text' => $lang_box_admin['name'], 'field' => 'name', 'sort' => true);
    $header_arr[]=array('text' => $lang_box_admin['templatesetvar'], 'field' => 'templatesetvar', 'sort' => true);

    //
    $text_arr = array('has_menu' =>  true,
      'has_extras'   => true,
      'form_url' => $_CONF['site_admin_url'] . "/plugins/".THIS_SCRIPT);

    //Query

    $sql = "SELECT ";
    $sql .= " field_id";
    $sql .= " ,name";
    $sql .= " ,templatesetvar";
    $sql .= " ,orderno";

    $sql .= " ,type";
    $sql .= " ,allow_display";

    $sql .= " FROM ";
    $sql .= " {$table} AS t";
    $sql .= " WHERE ";
    $sql .= " 1=1";
    //

    $query_arr = array(
        'table' => $table,
        'sql' => $sql,
        'query_fields' => array('field_id','name','orderno','templatesetvar'),
        'default_filter' => $exclude);
    //デフォルトソート項目:
    $defsort_arr = array('field' => 'orderno', 'direction' => 'ASC');
    //List 取得
    //ADMIN_list($component, $fieldfunction, $header_arr, $text_arr,
    //       $query_arr, $menu_arr, $defsort_arr, $filter = '', $extra = '', $options = '')
    $retval .= ADMIN_list(
        $pi_name
        , "LIB_GetListField"
        , $header_arr
        , $text_arr
        , $query_arr
        , $defsort_arr
        );

    $retval .= COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));

    return $retval;
}

// +---------------------------------------------------------------------------+
// | 一覧取得 ADMIN_list で使用
// +---------------------------------------------------------------------------+
function LIB_GetListField($fieldname, $fieldvalue, $A, $icon_arr)
{
    global $_CONF;
    $LANG_ACCESS;

    $retval = '';

    $type=COM_applyFilter($A['type'],true);
    $allow_display=COM_applyFilter($A['allow_display'],true);
    $allow_type = array(0,2,3,7,8);
	
    switch($fieldname) {
        //編集アイコン
        case 'editid':
            $retval = "<a href=\"{$_CONF['site_admin_url']}";
            $retval .= "/plugins/".THIS_SCRIPT;
            $retval .= "?mode=edit";
            $retval .= "&amp;id={$A['field_id']}\">";
            $retval .= "{$icon_arr['edit']}</a>";
            break;
        case 'copy':
            $url=$_CONF['site_admin_url'] . "/plugins/".THIS_SCRIPT;
            $url.="?";
            $url.="mode=copy";
            $url.="&amp;id={$A['field_id']}";
            $retval = COM_createLink($icon_arr['copy'],$url);
            break;
        //名
        case 'name':
            if (in_array ($type,$allow_type)){
                if ($allow_display<2){
                    $name=COM_applyFilter($A['name']);
                    $url=$_CONF['site_url'] . "/".THIS_SCRIPT;
                    $url.="?";
                    $url.="m=id";
                    $url.="&id=".$A['field_id'];
                    $url = COM_buildUrl( $url );
                    $retval= COM_createLink($name, $url);
                    break;
                }
            }
        case 'templatesetvar':
            if (in_array ($type,$allow_type)){
                if ($allow_display<2){
                    $name=COM_applyFilter($A['templatesetvar']);
                    $url=$_CONF['site_url'] . "/".THIS_SCRIPT;
                    $url.="?";
                    $url.="m=code";
                    $url.="&code=".$A['templatesetvar'];
                    $url = COM_buildUrl( $url );
                    $retval= COM_createLink($name, $url);
                    break;
                }
            }


        //各項目
        default:
            $retval = $fieldvalue;
            break;
    }

    return $retval;

}
// +---------------------------------------------------------------------------+
// | 機能  編集画面表示
// | 書式 LIB_Edit($pi_name,$id , $edt_flg,$msg,$errmsg)
// +---------------------------------------------------------------------------+
// | 引数 $pi_name:plugin name 'databox' 'userbox' 'formbox'
// | 引数 $id:
// | 引数 $edt_flg:
// | 引数 $msg:メッセージ番号
// +---------------------------------------------------------------------------+
// | 戻値 nomal:編集画面
// +---------------------------------------------------------------------------+
function LIB_Edit(
    $pi_name
    ,$id
    ,$edt_flg
    ,$msg = ''
    ,$errmsg=""
    ,$mode="edit"
)
{
    global $_CONF;
    global $_TABLES;
    global $LANG_ADMIN;
    global $MESSAGE;
    global $LANG_ACCESS;
    global $_USER;

    $box_conf="_".strtoupper($pi_name)."_CONF";
    global $$box_conf;
    $box_conf=$$box_conf;

    $lang_box_admin="LANG_".strtoupper($pi_name)."_ADMIN";
    global $$lang_box_admin;
    $lang_box_admin=$$lang_box_admin;

    $lang_box="LANG_".strtoupper($pi_name);
    global $$lang_box;
    $lang_box=$$lang_box;

    $lang_box_noyes="LANG_".strtoupper($pi_name)."_NOYES";
    global $$lang_box_noyes;
    $lang_box_noyes=$$lang_box_noyes;

    $lang_box_type="LANG_".strtoupper($pi_name)."_TYPE";
    global $$lang_box_type;
    $lang_box_type=$$lang_box_type;

    $lang_box_allow_display="LANG_".strtoupper($pi_name)."_ALLOW_DISPLAY";
    global $$lang_box_allow_display;
    $lang_box_allow_display=$$lang_box_allow_display;

    $lang_box_allow_edit="LANG_".strtoupper($pi_name)."_ALLOW_EDIT";
    global $$lang_box_allow_edit;
    $lang_box_allow_edit=$$lang_box_allow_edit;


    $table=$_TABLES[strtoupper($pi_name).'_def_field'];

//        $cur_year = date( 'Y' );
//        $year_startoffset=1990 - $cur_year +1;
//        $year_endoffset=0;

    $retval = '';


    $delflg=false;

    //メッセージ表示
    if (!empty ($msg)) {
        $retval .= COM_showMessage ($msg,$pi_name);
        $retval .= $errmsg;

        // clean 'em up
        $name = COM_applyFilter($_POST['name']);
        $templatesetvar = COM_applyFilter($_POST['templatesetvar']);
        $fieldgroup_id = COM_applyFilter($_POST['group'],true);
        $type = COM_applyFilter($_POST['type']);
        $description = COM_applyFilter($_POST['description']);

        $allow_display = COM_applyFilter($_POST['allow_display'],true);
        $allow_edit = COM_applyFilter($_POST['allow_edit'],true);

        $selection = COM_applyFilter($_POST['selection']);
        $selectlist = COM_applyFilter($_POST['selectlist']);
        $checkrequried = COM_applyFilter($_POST['checkrequried']);

        $size = COM_applyFilter($_POST['size'],true);
        $maxlength = COM_applyFilter($_POST['maxlength'],true);
        $rows = COM_applyFilter($_POST['rows'],true);

        $orderno = COM_applyFilter ($_POST['orderno']);

        $uuid=$_USER['uid'];

    }else{
        if (empty($id)) {

            $id=0;

            $name ="";
            $templatesetvar ="";
            $description ="";
            $allow_display="";
            $allow_edit="";

            $type ="";

            $selection ="";
            $selectlist ="";
            $checkrequried ="";

            $size = 60;
            $maxlength = 160;
            $rows = 2;
            $br = 0;

            $fieldgroup_id ="";

            $orderno ="";

            $uuid=0;
            $udatetime="";//"";

        }else{
            $sql = "SELECT ";

            $sql .= " *";

            $sql .= " FROM ";
            $sql .= $table;
            $sql .= " WHERE ";
            $sql .= " field_id = $id";
            $result = DB_query($sql);

            $A = DB_fetchArray($result);

            $name = COM_stripslashes($A['name']);
            $templatesetvar = COM_stripslashes($A['templatesetvar']);
            $description = $A['description'];//COM_stripslashes($A['description']);

            $allow_edit = COM_stripslashes($A['allow_edit']);
            $allow_display = COM_stripslashes($A['allow_display']);

            $type = COM_stripslashes($A['type']);
            $fieldgroup_id = COM_stripslashes($A['fieldgroup_id']);

            $selection = COM_stripslashes($A['selection']);
            $selectlist = COM_stripslashes($A['selectlist']);
            $checkrequried = COM_stripslashes($A['checkrequried']);
            $br = COM_stripslashes($A['br']);

            $size = COM_stripslashes($A['size']);
            $maxlength = COM_stripslashes($A['maxlength']);
            $rows = COM_stripslashes($A['rows']);
            $br = COM_stripslashes($A['br']);

            $orderno=COM_stripslashes($A['orderno']);

            $uuid = COM_stripslashes($A['uuid']);
            $udatetime=COM_stripslashes($A['udatetime']);

            if ($edt_flg==FALSE) {
                $delflg=true;
            }
        }
    }
    if ($mode==="copy"){
        $id=0;
        //作成日付
        $created=0;
        $created_month=0;
        $created_day = 0;
        $created_year = 0;
        $created_hour = 0;
        $created_minute = 0;
        //
        $delflg=false;

    }
    $retval .= COM_startBlock ($lang_box_admin['edit'], '',
                               COM_getBlockTemplate ('_admin_block', 'header'));

    $tmplfld=DATABOX_templatePath('admin','default',$pi_name);
    $templates = new Template($tmplfld);
    $templates->set_file('editor',"field_editor.thtml");
    //--
    $templates->set_var('about_thispage', $lang_box_admin['about_admin_field']);
    $templates->set_var('lang_must', $lang_box_admin['must']);
    $templates->set_var('site_url', $_CONF['site_url']);
    $templates->set_var('site_admin_url', $_CONF['site_admin_url']);

    $token = SEC_createToken();
    $retval .= SEC_getTokenExpiryNotice($token);
    $templates->set_var('gltoken_name', CSRF_TOKEN);
    $templates->set_var('gltoken', $token);
    $templates->set_var ( 'xhtml', XHTML );

    $templates->set_var('script', THIS_SCRIPT);

//
    $templates->set_var('lang_link_admin', $lang_box_admin['link_admin']);
    $templates->set_var('lang_link_admin_top', $lang_box_admin['link_admin_top']);

    //id
    $templates->set_var('lang_field_id', $lang_box_admin['field_id']);
    $templates->set_var('id', $id);

    //名前＆テンプレート変数＆説明
    $templates->set_var('lang_name', $lang_box_admin['name']);
    $templates->set_var ('name', $name);
    $templates->set_var('lang_templatesetvar', $lang_box_admin['templatesetvar']);
    $templates->set_var ('templatesetvar', $templatesetvar);
    $templates->set_var('lang_description', $lang_box_admin['description']);
    $templates->set_var ('description', $description);

    $templates->set_var('lang_allow_display', $lang_box_admin['allow_display']);
    $list_allow_display=DATABOX_getradiolist ($lang_box_allow_display,"allow_display",$allow_display,"<br/>");
    $templates->set_var( 'list_allow_display', $list_allow_display);

    $templates->set_var('lang_allow_edit', $lang_box_admin['allow_edit']);
    $list_allow_edit=DATABOX_getradiolist ($lang_box_allow_edit,"allow_edit",$allow_edit,"<br/>");
    $templates->set_var( 'list_allow_edit', $list_allow_edit);

    //type
    $templates->set_var('lang_type', $lang_box_admin['type']);
    $list_type=DATABOX_getradiolist ($lang_box_type,"type",$type,"<br/>");
    $templates->set_var( 'list_type', $list_type);

    //checkrequried
    $templates->set_var('lang_checkrequried', $lang_box_admin['checkrequried']);
    $list_checkrequried=DATABOX_getradiolist ($lang_box_noyes,"checkrequried",$checkrequried);
    $templates->set_var( 'list_checkrequried', $list_checkrequried);

    //size maxlength rows br
    $templates->set_var('lang_size', $lang_box_admin['size']);
    $templates->set_var ('size', $size);
    $templates->set_var('lang_maxlength', $lang_box_admin['maxlength']);
    $templates->set_var ('maxlength', $maxlength);
    $templates->set_var('lang_rows', $lang_box_admin['rows']);
    $templates->set_var ('rows', $rows);
    $templates->set_var('lang_br', $lang_box_admin['br']);
    $list_br=DATABOX_getradiolist ($lang_box_noyes,"br",$br);
    $templates->set_var( 'list_br', $list_br);



    //selection
    $templates->set_var('lang_selection', $lang_box_admin['selection']);
    $templates->set_var ('selection', $selection);

    //selectlist
    $templates->set_var('lang_selectlist', $lang_box_admin['selectlist']);
    $list_selectlist=DATABOX_getoptionlist("selectlist",$selectlist,0,$pi_name);
    $templates->set_var ('list_selectlist', $list_selectlist);


    //group
    $templates->set_var('lang_group', $lang_box_admin['group']);
    $list_group=DATABOX_getoptionlist("group",$fieldgroup_id,0,$pi_name ,"",0);
    $templates->set_var ('list_group', $list_group);


    //順序
    $templates->set_var('lang_orderno', $lang_box_admin['orderno']);
    $templates->set_var ('orderno', $orderno);

    //保存日時
    $templates->set_var ('lang_udatetime', $lang_box_admin['udatetime']);
    $templates->set_var ('udatetime', $udatetime);
    $templates->set_var ('lang_uuid', $lang_box_admin['uuid']);
    $templates->set_var ('uuid', $uuid);


    // SAVE、CANCEL ボタン
    $templates->set_var('lang_save', $LANG_ADMIN['save']);
    $templates->set_var('lang_cancel', $LANG_ADMIN['cancel']);
    $templates->set_var('lang_preview', $LANG_ADMIN['preview']);

    //delete_option
    if ($delflg){
        $delbutton = '<input type="submit" value="' . $LANG_ADMIN['delete']
                   . '" name="mode"%s>';
        $jsconfirm = ' onclick="return confirm(\'' . $MESSAGE[76] . '\');"';
        $templates->set_var ('delete_option',
        sprintf ($delbutton, $jsconfirm));
        //
        $templates->set_var('lang_delete_help', $lang_box_admin['delete_help_field']);
    }


    //
    $templates->parse('output', 'editor');
    $retval .= $templates->finish($templates->get_var('output'));
    $retval .= COM_endBlock (COM_getBlockTemplate ('_admin_block', 'footer'));

    return $retval;
}


/// +---------------------------------------------------------------------------+
// | 機能  保存
// | 書式 fncSave ($pi_name,$edt_flg)
// +---------------------------------------------------------------------------+
// | 引数 $pi_name:plugin name 'databox' 'userbox' 'formbox'
// | 引数 $edt_flg
// +---------------------------------------------------------------------------+
// | 戻値 nomal:戻り画面＆メッセージ
// +---------------------------------------------------------------------------+
function LIB_Save (
    $pi_name
    ,$edt_flg
    ,$navbarMenu
    ,$menuno

)
{
    global $_CONF;
    global $_TABLES;
    global $_USER;

    $box_conf="_".strtoupper($pi_name)."_CONF";
    global $$box_conf;
    $box_conf=$$box_conf;

    $lang_box_admin="LANG_".strtoupper($pi_name)."_ADMIN";
    global $$lang_box_admin;
    $lang_box_admin=$$lang_box_admin;

    $lang_box_admin_menu="LANG_".strtoupper($pi_name)."_admin_menu";
    global $$lang_box_admin_menu;
    $lang_box_admin_menu=$$lang_box_admin_menu;

    $table=$_TABLES[strtoupper($pi_name).'_def_field'];
    $table1=$_TABLES[strtoupper($pi_name).'_base'];
    $table2=$_TABLES[strtoupper($pi_name).'_addition'];

    $retval = '';

    // clean 'em up
    $id = COM_applyFilter($_POST['id'],true);
    if ($id==0){
        $new_flg=true;
    }else{
        $new_flg=false;
    }

    $name=COM_applyFilter($_POST['name']);
    $name=addslashes (COM_checkHTML (COM_checkWords ($name)));
    $templatesetvar=COM_applyFilter($_POST['templatesetvar']);
    $templatesetvar=addslashes (COM_checkHTML (COM_checkWords ($templatesetvar)));

    $description=$_POST['description'];//COM_applyFilter($_POST['description']);
    $description=addslashes (COM_checkHTML (COM_checkWords ($description)));

    $allow_display=COM_applyFilter($_POST['allow_display']);
    $allow_display=addslashes (COM_checkHTML (COM_checkWords ($allow_display)));
    $allow_edit=COM_applyFilter($_POST['allow_edit']);
    $allow_edit=addslashes (COM_checkHTML (COM_checkWords ($allow_edit)));

    $type=COM_applyFilter($_POST['type']);
    $type=addslashes (COM_checkHTML (COM_checkWords ($type)));
    $selection=COM_applyFilter($_POST['selection']);
    $selection=addslashes (COM_checkHTML (COM_checkWords ($selection)));
    $selectlist=COM_applyFilter($_POST['selectlist']);
    $selectlist=addslashes (COM_checkHTML (COM_checkWords ($selectlist)));
    $checkrequried=COM_applyFilter($_POST['checkrequried']);
    $checkrequried=addslashes (COM_checkHTML (COM_checkWords ($checkrequried)));

    $size=COM_applyFilter($_POST['size'],true);
    $size=addslashes (COM_checkHTML (COM_checkWords ($size)));
    $maxlength=COM_applyFilter($_POST['maxlength']);
    $maxlength=addslashes (COM_checkHTML (COM_checkWords ($maxlength)));
    $rows=COM_applyFilter($_POST['rows']);
    $rows=addslashes (COM_checkHTML (COM_checkWords ($rows)));
    $br=COM_applyFilter($_POST['br']);
    $br=addslashes (COM_checkHTML (COM_checkWords ($br)));

    $fieldgroup_id=COM_applyFilter($_POST['group']);
    $fieldgroup_id=addslashes (COM_checkHTML (COM_checkWords ($fieldgroup_id)));

    $orderno = mb_convert_kana($_POST['orderno'],"a");//全角英数字を半角英数字に変換する
    $orderno=COM_applyFilter($orderno,true);

    //$name = mb_convert_kana($name,"AKV");
    //A:半角英数字を全角英数字に変換する
    //K:半角カタカナを全角カタカナに変換する
    //V:濁点つきの文字を１文字に変換する (K、H と共に利用する）
    //$name = str_replace ("'", "’",$name);
    //$code = mb_convert_kana($code,"a");//全角英数字を半角英数字に変換する

    //-----
    $uuid=$_USER['uid'];


    // CHECK　はじめ
    $err="";
    //ID
    if ($id==0 ){
        //$err.=$lang_box_admin['err_id']."<br/>".LB;
    }else{
        if (!is_numeric($id) ){
            $err.=$lang_box_admin['err_id']."<br/>".LB;
        }
    }
    //名称必須
    if (empty($name)){
        $err.=$lang_box_admin['err_name']."<br/>".LB;
    }

    //テンプレート必須,二重チェック
    if (empty($templatesetvar)){
        $err.=$lang_box_admin['err_templatesetvar']."<br/>".LB;
    }else{
        $cntsql="SELECT field_id FROM {$table} ";
        $cntsql.=" WHERE ";
        $cntsql.=" templatesetvar='{$templatesetvar}' ";
        $cntsql.=" AND field_id<>{$id}";
//echo "cntsql=".$cntsql."<br>";
        $result = DB_query ($cntsql);
        $numrows = DB_numRows ($result);
        if ($numrows<>0 ) {
            $err.=$lang_box_admin['err_templatesetvar_w']."<br/>".LB;
        }
    }
    //7 = 'オプションリスト';
    //8 = 'ラジオボタンリスト';

    if ($type==7 OR $type==8) {
        if  ($selection==""){
            $err.=$lang_box_admin['err_selection']."<br/>".LB;
        }
    }

    //errorのあるとき
    if ($err<>"") {
        $page_title=$lang_box_admin['piname'].$lang_box_admin['edit'];
        $retval .= DATABOX_siteHeader($pi_name,'_admin',$page_title);
        $retval .=ppNavbarjp($navbarMenu,$lang_box_admin_menu[$menuno]);
        $retval .= LIB_Edit($pi_name,$id, $edt_flg,3,$err);
        $retval .= DATABOX_siteFooter($pi_name,'_admin');

        return $retval;

    }
    // CHECK　おわり

    if ($id==0){
        $w=DB_getItem($table,"max(field_id)","1=1");
        if ($w=="") {
            $w=0;
        }
        $id=$w+1;
    }

    $fields="field_id";
    $values="$id";

    $fields.=",name";
    $values.=",'$name'";

    $fields.=",templatesetvar";
    $values.=",'$templatesetvar'";

    $fields.=",description";
    $values.=",'$description'";

    $fields.=",type";
    $values.=",$type";

    $fields.=",selection";
    $values.=",'$selection'";

    $fields.=",selectlist";
    $values.=",$selectlist";

    $fields.=",checkrequried";
    $values.=",$checkrequried";


    $fields.=",size";
    $values.=",$size";

    $fields.=",maxlength";
    $values.=",$maxlength";

    $fields.=",rows";
    $values.=",$rows";

    $fields.=",br";
    $values.=",$br";

    $fields.=",fieldgroup_id";
    $values.=",'$fieldgroup_id'";

    $fields.=",orderno";//
    $values.=",'$orderno'";

    $fields.=",allow_display";
    $values.=",$allow_display";

    $fields.=",allow_edit";
    $values.=",$allow_edit";

    $fields.=",uuid";
    $values.=",$uuid";

    $fields.=",udatetime";
    $values.=",NOW( )";
    //


    DB_save($table,$fields,$values);

//    if ($new_flg){
        $sql="INSERT INTO ".$table2.LB;
        $sql.=" (`id`,`field_id`,`value`)".LB;

        $sql.=" SELECT id";
        $sql.=" ,".$id;
        //7 = 'オプションリスト';
        //8 = 'ラジオボタンリスト';
        if (($type==7 OR $type==8) AND ($selection<>"")){
            $sql.=",'0' ";
        }else{
            $sql.=",'' ";
        }
        $sql.=" FROM " .$table1.LB;


        $sql.=" where id NOT IN (select id from ".$table2.LB;
        $sql.=" where field_id=".$id.")".LB;
//COM_errorLog( "sql= " . $sql, 1 );

        DB_query($sql);



//    }



//    $rt=fncsendmail ($id);

//    if ($edt_flg){
//        $return_page=$_CONF['site_url'] . "/".THIS_SCRIPT;
//        $return_page.="?id=".$id;
//    }else{
//        $return_page=$_CONF['site_admin_url'] . '/plugins/'.THIS_SCRIPT.'?msg=1';
//    }

//$return_page="";//@@@@@debug 用

	$message="";
    if ($box_conf['aftersave_admin']==='no') {
        $page_title=$lang_box_admin['piname'].$lang_box_admin['edit'];
        $retval .= DATABOX_siteHeader($pi_name,'_admin',$page_title);
        $retval .=ppNavbarjp($navbarMenu,$lang_box_admin_menu[$menuno]);
        $retval .= LIB_Edit($pi_name,$id, $edt_flg,1,"");
        $retval .= DATABOX_siteFooter($pi_name,'_admin');
        return $retval;

    }else if ($box_conf['aftersave_admin']==='list'){
            $url = $_CONF['site_admin_url'] . "/plugins/$pi_name/field.php";
            $item_url=COM_buildURL($url);
            $target='item';
			$message=1;
    }else if ($box_conf['aftersave_admin']==='admin'){
			$target=$box_conf['aftersave_admin'];
			$message=1;
    }else{
            $item_url=COM_buildURL($_CONF['site_url'] . $box_conf['top']);
			$target=$box_conf['aftersave_admin'];
    }

    $return_page = PLG_afterSaveSwitch(
                    $target
                    ,$item_url
                    ,$pi_name
                    , $message);
	
    echo $return_page;



    //exit;

}
// +---------------------------------------------------------------------------+
// | 機能  削除
// | 書式 fncdelete ($pi_name)
// +---------------------------------------------------------------------------+
// | 戻値 nomal:戻り画面＆メッセージ
// +---------------------------------------------------------------------------+
function LIB_delete (
    $pi_name
)
{
    global $_CONF;
    global $_TABLES;

    $lang_box_admin="LANG_".strtoupper($pi_name)."_ADMIN";
    global $$lang_box_admin;
    $lang_box_admin=$$lang_box_admin;

    $table=$_TABLES[strtoupper($pi_name).'_def_field'];
    $table2=$_TABLES[strtoupper($pi_name).'_addition'];

    $id = COM_applyFilter($_POST['id'],true);
    $type=COM_applyFilter($_POST['type']);

    // CHECK
    $err="";
    if ($err<>"") {
        $page_title= $lang_box_admin['err'];
        $retval .= DATABOX_siteHeader($pi_name,'_admin',$page_title);

        $retval .= COM_startBlock ($lang_box_admin['err'], '',
                            COM_getBlockTemplate ('_msg_block', 'header'));
        $retval .= $err;
        $retval .= COM_endBlock (COM_getBlockTemplate ('_msg_block', 'footer'));
        $retval .= DATABOX_siteFooter($pi_name,'_admin');
        return $retval;
    }

	//
	if ($type=12){
		$rt=DATABOX_deleteaddtionfiles_def($id,$pi_name);//外部ファイルの削除
	}
    DB_delete($table2,"field_id",$id);
    DB_delete ($table, 'field_id', $id);

    return COM_refresh ($_CONF['site_admin_url']
                        . '/plugins/'.THIS_SCRIPT.'?msg=2');
}



// +---------------------------------------------------------------------------+
// | 機能  エキスポート
// | 書式 fncexport ($pi_name)
// +---------------------------------------------------------------------------+
// | 戻値 nomal:
// +---------------------------------------------------------------------------+
function LIB_export (
    $pi_name
)
{
    global $_CONF;
    global $_TABLES;

    $lang_box_admin="LANG_".strtoupper($pi_name)."_ADMIN";
    global $$lang_box_admin;
    $lang_box_admin=$$lang_box_admin;

    $table=$_TABLES[strtoupper($pi_name).'_def_field'];

    require_once ($_CONF['path'].'plugins/'.$pi_name.'/lib/comj_dltbldt.php');

// 項目の見出リスト
$fld = array ();


$fld['field_id'] = $lang_box_admin['field_id'];
$fld['name'] = $lang_box_admin['name'];
$fld['orderno'] = $lang_box_admin['orderno'];

$fld['templatesetvar'] = $lang_box_admin['templatesetvar'];
$fld['description'] = $lang_box_admin['description'];
$fld['allow_display'] = $lang_box_admin['allow_display'];
$fld['allow_edit'] = $lang_box_admin['allow_edit'];
$fld['type'] = $lang_box_admin['type'];
$fld['selection'] = $lang_box_admin['selection'];
$fld['selectlist'] = $lang_box_admin['selectlist'];
$fld['checkrequried'] = $lang_box_admin['checkrequried'];
$fld['size'] = $lang_box_admin['size'];
$fld['maxlength'] = $lang_box_admin['maxlength'];
$fld['rows'] = $lang_box_admin['rows'];
$fld['br'] = $lang_box_admin['br'];
$fld['fieldgroup_id'] = $lang_box_admin['group_id'];
$fld['orderno'] = $lang_box_admin['orderno'];

$fld['udatetime'] = $lang_box_admin['udatetime'];
$fld['uuid'] = $lang_box_admin['uuid'];


//----------------------
$filenm=$pi_name."_field";
$tbl ="{$table}";
$where = "";
$order = "field_id";
$addition=false;


$rt= DATABOX_dltbldt($filenm,$fld,$tbl,$where,$order,$pi_name,$addition);


return;
}
// +---------------------------------------------------------------------------+
// | 機能  インポート画面表示
// | 書式 fncimport ($pi_name)
// +---------------------------------------------------------------------------+
// | 戻値 nomal:
// +---------------------------------------------------------------------------+
function LIB_import (
    $pi_name
    )
{
    global $_CONF;

    $lang_box_admin="LANG_".strtoupper($pi_name)."_ADMIN";
    global $$lang_box_admin;
    $lang_box_admin=$$lang_box_admin;

    $tmpl = new Template ($_CONF['path'] . "plugins/".THIS_PLUGIN."/templates/admin/");
    $tmpl->set_file(array('import' => 'import.thtml'));

    $tmpl->set_var('site_admin_url', $_CONF['site_admin_url']);

    $tmpl->set_var('gltoken_name', CSRF_TOKEN);
    $tmpl->set_var('gltoken', SEC_createToken());
    $tmpl->set_var ( 'xhtml', XHTML );

    $tmpl->set_var('script', THIS_SCRIPT);

    $tmpl->set_var('importmsg', $lang_box_admin['importmsg']);
    $tmpl->set_var('importfile', $lang_box_admin['importfile']);
    $tmpl->set_var('submit', $lang_box_admin['submit']);

    $tmpl->parse ('output', 'import');
    $import = $tmpl->finish ($tmpl->get_var ('output'));

    $retval="";
    $retval .= COM_startBlock ($lang_box_admin['import'], '',
                            COM_getBlockTemplate ('_admin_block', 'header'));
    $retval .= $import;
    $retval .= COM_endBlock (COM_getBlockTemplate ('_admin_block', 'footer'));


    return $retval;
}
// +---------------------------------------------------------------------------+
// | 機能  メール送信
// | 書式 LIB_sendmail ($pi_name,$id)
// +---------------------------------------------------------------------------+
// | 戻値 nomal:
// +---------------------------------------------------------------------------+
function LIB_sendmail (
    $pi_name
    ,$id
)
{
    global $_CONF;
    global $_TABLES;
    global $_USER ;

    $box_conf="_".strtoupper($pi_name)."_CONF";
    global $$box_conf;
    $box_conf=$$box_conf;

    $lang_box_admin="LANG_".strtoupper($pi_name)."_ADMIN";
    global $$lang_box_admin;
    $lang_box_admin=$$lang_box_admin;

    $lang_box_mail="LANG_".strtoupper($pi_name)."_MAIL";
    global $$lang_box_mail;
    $lang_box=$$lang_box_mail;

    $table=$_TABLES[strtoupper($pi_name).'_def_field'];

    $retval = '';


    $sql = "SELECT ";

    $sql .= " *";

    $sql .= " FROM ";
    $sql .= $table;
    $sql .= " WHERE ";
    $sql .= " field_id = $id";



//ECHO "sql={$sql}<br>";

    $result = DB_query ($sql);
    $numrows = DB_numRows ($result);

    if ($numrows > 0) {

        //
        $A = DB_fetchArray ($result);


        //保存日時
        $msg.=$lang_box_admin['udatetime'].":".$A['udatetime'].LB;

        //コード
        $msg.= $lang_box_admin['field_id'].":".$A['field_id'].LB;

        //名称
        $msg.= $lang_box_admin['name'].":".$A['name'].LB;
        //順序
        $msg.= $lang_box_admin['orderno'].":".$A['orderno'].LB;

        $msg.= $lang_box_MAIL['sig'] .LB;
        //
        $msg.= $_CONF['site_url'] . '/'.THIS_SCRIPT.'?id=' .$A['field_id'].LB;

        //
        //
        $to=$_USER['email'];
        //
        $subject = $lang_box_MAIL['subject'];
        //
        $message=$lang_box_MAIL['message'];
        $message.=$msg;

        $html = false;
        $priority = 0;
        $cc = '' ;
        //COM_mail ($to, $subject, $message, $from,$html,$priority,$cc);
        COM_mail ($to, $subject, $message);
        $to=$box_conf['adminmail'];
        COM_mail ($to, $subject, $message);

    }

    return $retval;
}

?>