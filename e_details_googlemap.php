<?php
// ----------------------------------------
// ?????Y?s???Y
// ???~?y?[?WGoogleMap
// e_details_googlemap.php
// ----------------------------------------
// MAKE: 2008/03 Grofield
// ----------------------------------------

    include "../systems/init_estate.php";

    // ?e???v???[?g
    $L_TEMPLATE_LIST = "e_details_googlemap.html";
    
//==================================================================================

    // ?e???v???[?g?f?[?^??????????
    $template = $c_template->load_file($G_SYS_ESTATE_TEMPLATE_DIR.$L_TEMPLATE_LIST);
    
    // ?w?b?_?E?t?b?^???Z?b?g
    //f_adminSetParts($template);
    
//==================================================================================
// ?Z?b?V????????
//==================================================================================
    
    $chkcode = "";
    if(isset($_GET["code"])){
        $chkcode = $_GET["code"];
        
    }
    // ?f?t?H???g?l
    $value["init_lat"] = "34.78180463548781";
    $value["init_lng"] = "135.47962188720703";
    $value["init_zoom"] = 13;
    

    // ?s??????????????
    $cyInfo_list = f_getCityInfo();
    // ??????????????
    $twInfo_list = f_getTownInfo();
        
    // ?f?[?^?x?[?X????
    $c_database->connect();
    
    if($chkcode != ""){
        $head = substr($chkcode, 0, 1);
        $code = substr($chkcode, 1);
        
        // ?V?z
        if($head == "n"){
            $prefix = "nw";
            $L_DB_TABLE_NAME = $G_DB_TB_NEW_HOUSE;
            $typeKey = "n";
            $mbstype = 1;
            $iconmode = $G_SYS_IMG_NEW_HOUSE;
            $mapicon = "icon_new";
            $mode = "new_house";
        // ????
        }elseif($head == "u"){
            $prefix = "us";
            $L_DB_TABLE_NAME = $G_DB_TB_USED_HOUSE;
            $typeKey = "u";
            $mbstype = 2;
            $iconmode = $G_SYS_IMG_USED_HOUSE;
            $mapicon = "icon_used";
            $mode = "used_house";
        // ?}???V????
        }elseif($head == "m"){
            $prefix = "ms";
            $L_DB_TABLE_NAME = $G_DB_TB_MANSION;
            $typeKey = "m";
            $mbstype = 3;
            $iconmode = $G_SYS_IMG_MANSION;
            $mapicon = "icon_mansion";
            $mode = "mansion";
        // ?y?n
        }elseif($head == "l"){
            $prefix = "ld";
            $L_DB_TABLE_NAME = $G_DB_TB_LAND;
            $typeKey = "l";
            $mbstype = 4;
            $iconmode = $G_SYS_IMG_LAND;
            $mapicon = "icon_land";
            $mode = "land";
        }
        
        // SQL
        $sql = "SELECT * FROM ".$L_DB_TABLE_NAME." ";
        $sql .= "WHERE ".$prefix."_delete IS NULL ";
        $sql .= "AND ".$prefix."_code = $code ";
        
        $c_database->query($sql);
        
        if($c_database->cnt_res > 0){
            $list = $c_database->fetch_array();
            
            $value["type"]      = $m;
            $value["type_key"]  = $typeKey;
            $value["code"]      = $list[$prefix.'_code'];
            $value["name"]      = $list[$prefix.'_name'];
            $value["city"]      = f_GetName($list[$prefix.'_city'], $cyInfo_list);
            $value["town"]      = f_GetName($list[$prefix.'_town'], $twInfo_list);
            $value["address"]   = $value["city"].$value["town"].$list[$prefix.'_address'];
            $value["lat"]       = $list[$prefix.'_lat'];
            $value["lng"]       = $list[$prefix.'_lng'];
            $sDate = gmdate("Y/m/d H:i:s",time() + 3600*9 - ($G_SYS_NEW_DISP_DAY * 24 * 60 * 60));
            $chDate             = $list[$prefix.'_change'];
            if($sDate <= $chDate){
                $value["new"]      = $G_SYS_IMG_NEW;
            }else{
                $value["new"]      = "";
            }
            $value["icon_mode"] = $iconmode;
            $value["icon_member"] = "";
            if($list[$prefix.'_public'] == 1){
                $value["icon_member"]  = $G_SYS_IMG_MEMBER;
            }
            $value["map_icon"]  = $mapicon;
            $value["mode"]      = $mode;
            
            // ?????R?[?h????
            $value = $c_string->array_mb_convert_encoding($value, "UTF-8", $G_SYS_HTML_ENCODE);
            
            $value["init_lat"] = $value["lat"];
            $value["init_lng"] = $value["lng"];
        }
    }
    
    
    $template = $c_template->set_value($template, $value);
        
    // ?f?[?^?x?[?X???f
    $c_database->close();
    
    print $template;

?>