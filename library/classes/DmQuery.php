<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 //require_once("../shared/logincheck.php");
require_once("../shared/global_constants.php");
require_once("../classes/Dm.php");
require_once("../classes/Query.php");

class DmQuery extends Query {
  var $_tableNm = "";

  function _get($table, $code = "") {
    $this->_tableNm = $table;
    $sql = $this->mkSQL("select * from %I ", $table);
    if ($code != "") {
      $sql .= $this->mkSQL("where code = %Q ", $code);
    }
    $sql .= "order by description ";
    return $this->exec($sql);
  }
  function get($table) {
    return array_map(array($this, '_mkObj'), $this->_get($table));
  }
  function getAssoc($table, $column="description") {
    $assoc = array();
    foreach ($this->_get($table) as $row) {
      $assoc[$row['code']] = $row[$column];
    }
    return $assoc;
  }
  function get1($table, $code) {
    $rows = $this->_get($table, $code);
    if (count($rows) != 1) {
     Fatal::internalError("Invalid domain table code");
    }
    return $this->_mkObj($rows[0]);
  }

  function getWithStats($table) {
    $this->_tableNm = $table;
    if ($table == "ob_collection_dm") {
      $sql = "select ob_collection_dm.*, count(ob_biblio.bibid) row_count ";
      $sql .= "from ob_collection_dm left join ob_biblio on ob_collection_dm.code = ob_biblio.collection_cd ";
      $sql .= "group by 1, 2, 3, 4, 5 ";
    } elseif ($table == "ob_material_type_dm") {
      $sql = "select ob_material_type_dm.*, count(ob_biblio.bibid) row_count ";
      $sql .= "from ob_material_type_dm left join ob_biblio on ob_material_type_dm.code = ob_biblio.material_cd ";
      $sql .= "group by 1, 2, 3, 4 ";
    } elseif ($table == "ob_mbr_classify_dm") {
      $sql = "select ob_mbr_classify_dm.*, count(stud.id) row_count ";
      $sql .= "from ob_mbr_classify_dm left join tbl_student stud on ob_mbr_classify_dm.code = stud.lib_classification ";
      $sql .= "group by 1, 2, 3, 4 ";
    } else {
      Fatal::internalError("Cannot retrieve stats for that dm table");
    }
    $sql .= "order by description ";
    return array_map(array($this, '_mkObj'), $this->exec($sql));
  }

  function getCheckoutStats($mbrid) {
    $sql = $this->mkSQL("create temporary table mbrout  "
                        . "select b.material_cd, c.bibid, c.copyid "
                        . "from ob_biblio_copy c, ob_biblio b "
                        . "where c.student_id=%N and b.bibid=c.bibid ", $mbrid);
    $this->exec($sql);
    $sql = $this->mkSQL("select mat.*, "
                        . "ifnull(privs.checkout_limit, 0) checkout_limit, "
                        . "ifnull(privs.renewal_limit, 0) renewal_limit, "
                        . "count(mbrout.copyid) row_count "
                        . "from ob_material_type_dm mat join tbl_student stud "
                        . "left join ob_checkout_privs privs "
                        . "on privs.material_cd=mat.code "
                        . "and privs.classification=stud.lib_classification "
                        . "left join mbrout on mbrout.material_cd=mat.code "
                        . "where stud.id=%N "
                        . "group by mat.code, mat.description, mat.default_flg, "
                        . "privs.checkout_limit, privs.renewal_limit ", $mbrid);
    return array_map(array($this, '_mkObj'), $this->exec($sql));
  }

  function _mkObj($array) {
    $dm = new Dm();
    $dm->setCode($array["code"]);
    $dm->setDescription($array["description"]);
    $dm->setDefaultFlg($array["default_flg"]);
    if ($this->_tableNm == "ob_collection_dm") {
      $dm->setDaysDueBack($array["days_due_back"]);
      $dm->setDailyLateFee($array["daily_late_fee"]);
    }
    
    if (isset($array['checkout_limit'])) {
      $dm->setCheckoutLimit($array["checkout_limit"]);
    }
    if (isset($array['renewal_limit'])) {
      $dm->setRenewalLimit($array["renewal_limit"]);
    }
    if (isset($array["image_file"])) {
      $dm->setImageFile($array["image_file"]);
    }
    if (isset($array["max_fines"])) {
      $dm->setMaxFines($array["max_fines"]);
    }
    if (isset($array["row_count"])) {
      $dm->setCount($array["row_count"]);
    }
    return $dm;
  }

  function insert($table, $dm) {
    $sql = $this->mkSQL("insert into %I values ", $table);
    if ($table == "ob_collection_dm"
        or $table == "ob_material_type_dm"
        or $table == "ob_mbr_classify_dm") {
      $sql .= '(null, ';
    } else {
      $sql .= $this->mkSQL('(%Q, ', $dm->getCode());
    }
    $sql .= $this->mkSQL("%Q, 'N' ", $dm->getDescription());
    if ($table == "ob_collection_dm") {
      $sql .= $this->mkSQL(", %N, %N)", $dm->getDaysDueBack(), $dm->getDailyLateFee());
    } elseif ($table == "ob_material_type_dm") {
      $sql .= $this->mkSQL(", %Q)", $dm->getImageFile());
    } elseif ($table == "ob_mbr_classify_dm") {
      $sql .= $this->mkSQL(", %N)", $dm->getMaxFines());
    } else {
      $sql .= ")";
    }

    $this->exec($sql);
  }

  function update($table, $dm) {
    $sql = $this->mkSQL("update %I set description=%Q, default_flg='N' ",
                         $table, $dm->getDescription());
    if ($table == "ob_collection_dm") {
      $sql .= $this->mkSQL(", days_due_back=%N, daily_late_fee=%N ",
                           $dm->getDaysDueBack(), $dm->getDailyLateFee());
    } elseif ($table == "ob_material_type_dm") {
      $sql .= $this->mkSQL(", image_file=%Q ", $dm->getImageFile());
    } elseif ($table == "ob_mbr_classify_dm") {
      $sql .= $this->mkSQL(", max_fines=%N ", $dm->getMaxFines());
    }
    $sql .= $this->mkSQL("where code=%Q ", $dm->getCode());
    $this->exec($sql);
  }

  function delete($table, $code) {
    $sql = $this->mkSQL("delete from %I where code = %Q", $table, $code);
    $this->exec($sql);
  }

}

?>
