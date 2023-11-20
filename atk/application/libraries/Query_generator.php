<?php

/**
 * class dbquery
 *
 * @package    	Web Development Frameworks
 * @author     	Gilang <gilang@kresnadi.web.id>
 * @License    	Free GPL
 * @link 	http://www.kresnadi.web.id
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Query_generator {
    

    public function sqlQuery($sql, $type = "query") {
        $field = "";
        $cols = "";
        $vals = "";
        $cond = "";
        $order = "";
        $limit = "";
        $groupby = "";

        //GENERATE FIELD
        if (isset($sql['field']) && !isset($sql['fvals'])) {
            $koma = "";
            for ($i = 0; $i < count($sql['field']); $i++) {
                $field .= $koma . $sql['field'][$i];
                $koma = ", ";
            }
        }

        //GENERATED COLS ONLY
        if (isset($sql['cols'])) {
            $koma = "";
            for ($i = 0; $i < count($sql['cols']); $i++) {
                $cols .= $koma . $sql['cols'][$i];
                $koma = ", ";
            }
        }

        //GENERATED VALS ONLY
        if (isset($sql['vals'])) {
            $koma = "";
            for ($i = 0; $i < count($sql['vals']); $i++) {
                $comparator = "'" . $sql['vals'][$i] . "'";
                if (isset($sql['ctype'])) {
                    switch ($sql['ctype'][$i]) {
                        case "int" :
                            $comparator = $sql['vals'][$i];
                            break;
                        case "fkey" :
                            $comparator = $sql['vals'][$i];
                            break;
                        default:
                            $comparator = "'" . $sql['vals'][$i] . "'";
                            break;
                    }
                }
                $vals .= $koma . $comparator;
                $koma = ", ";
            }
        }

        //GENERATE WHERE CONDITION
        $space = "";
        $colandvals = "";
        if (isset($sql['cols'])) {
            for ($i = 0; $i < count($sql['cols']); $i++) {
                $comparator = "'" . $sql['vals'][$i] . "'";
                if (isset($sql['ctype'])) {
                    switch ($sql['ctype'][$i]) {
                        case "int" :
                            $comparator = $sql['vals'][$i];
                            break;
                        case "fkey" :
                            $comparator = $sql['vals'][$i];
                            break;
                        default:
                            $comparator = "'" . $sql['vals'][$i] . "'";
                            break;
                    }
                }
                $colandvals .= $space . $sql['cols'][$i] . "=" . $comparator;
                $space = " AND ";
            }
            $cond = " WHERE " . $colandvals;
        }
        if (isset($sql['cond'])) {
            $cond .= " " . $sql['cond'];
        }

        //GENERATE LIMIT CONDITION
        if (isset($sql['limit'])) {
            if (isset($sql['offset']) || $sql['offset'] == "0") {
                $limit = " LIMIT " . $sql['offset'] . "," . $sql['limit'];
            } else {
                $limit = " LIMIT " . $sql['limit'];
            }
        }

        //GENERATE ORDER CONDITION
        if (isset($sql['order'])) {
            $order = " ORDER BY " . $sql['order'];
        }

        //GENERATE ORDER CONDITION
        if (isset($sql['group'])) {
            $groupby = " GROUP BY " . $sql['group'];
        }

        switch ($type) {
            case "query" :
                $sql_query = "SELECT " . $field . " FROM " . $sql['table'] . $cond . $order . $limit;
                break;
            case "join" :
                $sql_query = "SELECT " . $field . " FROM " . $sql['table'] . $this->jointable($sql) . $cond . $groupby . $order . $limit;
                break;
            case "numrow" :
                //Bikin kondisi
                $sql_query = "SELECT " . $field . " FROM " . $sql['table'] . $cond;
                break;
            case "insert" :
                //Bikin kondisi
                $sql_query = "INSERT INTO " . $sql['table'] . " (" . $cols . ") VALUES (" . $vals . ")";
                break;
            case "update" :
                //Bikin update parameter
                $updateContent = "";
                $koma = "";
                $colandvals = "";

                if (isset($sql['fvals'])) {
                    for ($i = 0; $i < count($sql['field']); $i++) {
                        $comparator = "'" . $sql['fvals'][$i] . "'";
                        if (isset($sql['ftype'])) {
                            switch ($sql['ftype'][$i]) {
                                case "int" :
                                    $comparator = $sql['fvals'][$i];
                                    break;
                                case "fkey" :
                                    $comparator = $sql['fvals'][$i];
                                    break;
                                default:
                                    $comparator = "'" . $sql['fvals'][$i] . "'";
                                    break;
                            }
                        }
                        $colandvals .= $koma . $sql['field'][$i] . "=" . $comparator;
                        $koma = ", ";
                    }
                    $updateContent = " SET " . $colandvals;
                }

                $sql_query = "UPDATE " . $sql['table'] . $updateContent . $cond;
                break;
            case "delete" :
                //Bikin kondisi
                $sql_query = "DELETE FROM " . $sql['table'] . $cond;
                break;
        }
        return $sql_query;
    }

    //Check Join Table
    private function jointable($sql) {

        $join = " ";
        $jmlJoin = count($sql['join']);
        for ($x = 0; $x < $jmlJoin; $x++) {
            if (isset($sql['join'][$x])) {
                $join .= $sql['join'][$x] . " " . $sql['jtable'][$x];
            }

            if (isset($sql['jcols'][$x])) {
                if (isset($sql['jvals'][$x])) {
                    $join .= " ON " . $sql['jcols'][$x] . "=" . $sql['jvals'][$x];
                } else {
                    $join .= " USING (" . $sql['jcols'][$x] . ")";
                }
            }
            $join .= " ";
        }
        return $join;
    }

}
