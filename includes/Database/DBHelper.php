<?php
/**
 * Database Controller Class.
 * For all Database Interactions using WordPress Database API.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Database;

defined('ABSPATH') || exit; 

class DBHelper
{
    private $wpdb;
    public $tbl_pfx;

    /**
     * Constructor to initialize the database manager with $wpdb.
     * @global wpdb $wpdb WordPress database object.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tbl_pfx = $wpdb->prefix; // WordPress table prefix
    }

    /**
     * Perform a raw query with optional parameters and dynamic return types.
     * @param string $sql The SQL query (use placeholders for parameters).
     * @param array $params Parameters for the query (use prepare placeholders).
     * @param string $returnType The type of result to return: 'results', 'row', 'var', or 'col'.
     * @return mixed Query result based on the specified return type or false on failure.
     */
    public function fnehdQuery($sql, $params = [], $returnType = 'results'){
        $prepared_sql = empty($params) ? $sql : $this->wpdb->prepare($sql, ...$params);

        switch ($returnType) {
            case 'var': // Return a single variable
                return $this->wpdb->get_var($prepared_sql);
            case 'row': // Return a single row as an associative array
                return $this->wpdb->get_row($prepared_sql, ARRAY_A);
            case 'col': // Return a single column from all rows
                return $this->wpdb->get_col($prepared_sql);
            case 'results': // Return all results as an array of associative arrays
            default:
                return $this->wpdb->get_results($prepared_sql, ARRAY_A);
        }
    }

    /**
     * Insert data into a table.
     * @param string $table The table name (without prefix).
     * @param array $data Associative array of column => value.
     * @return int|false Inserted row ID or false on failure.
     */
    public function fnehdInsert($table, $data){
        $result = $this->wpdb->insert($table, $data);
        return $result ? $this->wpdb->insert_id : false;
    }

    /**
     * Update data in a table.
     * @param string $table The table name (without prefix).
     * @param array $data Associative array of column => value.
     * @param array $where Associative array for WHERE condition.
     * @return int|false Number of rows affected or false on failure.
     */
    public function fnehdUpdate($table, $data, $where){
        return $this->wpdb->update($table, $data, $where);
    }

    /**
     * Delete data from a table.
     * @param string $table The table name (without prefix).
     * @param array $where Associative array for WHERE condition.
     * @return int|false Number of rows affected or false on failure.
     */
    public function fnehdDelete($table, $where){
        return $this->wpdb->delete($table, $where);
    }

	
}
