<?php
/**
 * Server-side processing class for Fnehousing DataTables
 *
 * @since      1.0.0
 * @package    Fnehousing
 */
 
namespace Fnehousing\Api\DataTable;
 
class SSP {

    /**
     * Create the data output array for the DataTables rows
     * @param  array $columns Column information array
     * @param  array $data    Data from the SQL get
     * @return array          Formatted data in a row-based format
     */
    static function data_output($columns, $data) {
        $out = [];

        foreach ($data as $row) {
            $formattedRow = [];
            foreach ($columns as $column) {
                if (isset($column['formatter'])) {
                    $formattedRow[$column['dt']] = $column['formatter']($row[$column['db']], $row);
                } else {
                    $formattedRow[$column['dt']] = $row[$column['db']];
                }
            }
            $out[] = $formattedRow;
        }

        return $out;
    }

    /**
     * Perform the SQL queries needed for server-side processing
     * @param  array $request Data sent to server by DataTables
     * @param  string $table  Name of the database table
     * @param  string $primaryKey Primary key of the table
     * @param  array $columns Column information array
     * @return array          Server-side processing response array
     */
    static function simple($request, $table, $primaryKey, $columns) {
        global $wpdb;

        $bindings = [];
        $db_columns = array_column($columns, 'db');

        // Build the SQL query string from the request
        $limit = self::limit($request);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        // Main query to actually get the data
        $dataQuery = "SELECT " . implode(', ', $db_columns) . " FROM $table $where $order $limit";
        $data = $wpdb->get_results($dataQuery, ARRAY_A);

        // Data set length after filtering
        $resFilterLength = $wpdb->get_var("SELECT COUNT($primaryKey) FROM $table $where");

        // Total data set length
        $resTotalLength = $wpdb->get_var("SELECT COUNT($primaryKey) FROM $table");

        return [
            "draw" => isset($request['draw']) ? intval($request['draw']) : 0,
            "recordsTotal" => intval($resTotalLength),
            "recordsFiltered" => intval($resFilterLength),
            "data" => self::data_output($columns, $data)
        ];
    }
	
	
     /**
     * Fetch users with the 'fnehousing_user' role and format data for DataTables
     * 
     * @param array $request Data sent to server by DataTables
     * @param array $columns Column information array
     * @return array Server-side processing response array
     */
	 static function getFnehdUsers($request, $columns) {
		$role = 'fnehousing_user';
		$search = isset($request['search']['value']) ? sanitize_text_field($request['search']['value']) : '';
		$order_column = isset($request['order'][0]['column']) ? $columns[$request['order'][0]['column']]['db'] : 'ID';
		$order_dir = isset($request['order'][0]['dir']) ? strtoupper($request['order'][0]['dir']) : 'ASC';
		$number = isset($request['length']) ? intval($request['length']) : 10;
		$offset = isset($request['start']) ? intval($request['start']) : 0;

		// WP_User_Query arguments
		$args = [
			'role'    => $role,
			'orderby' => $order_column,
			'order'   => $order_dir,
			'number'  => $number,
			'offset'  => $offset,
			'search'  => '*' . esc_attr($search) . '*',
			'search_columns' => array_map(function ($column) {
				return $column['db'];
			}, $columns),
		];

		// Query users
		$user_query = new \WP_User_Query($args);
		$users = $user_query->get_results();
		$total_users = count_users()['avail_roles'][$role] ?? 0;
		$filtered_count = $user_query->get_total();

		// Format user data
		$users_with_data = [];
		foreach ($users as $user) {
			$meta = array_map('maybe_unserialize', array_map(function ($item) {
				return is_array($item) ? reset($item) : $item;
			}, get_user_meta($user->ID)));

			$users_with_data[] = array_merge([
				'ID'              => $user->ID,
				'user_login'      => $user->user_login,
				'user_email'      => $user->user_email,
				'first_name'      => get_user_meta($user->ID, 'first_name', true),
				'last_name'       => get_user_meta($user->ID, 'last_name', true),
				'display_name'    => get_user_meta($user->ID, 'display_name', true),
				'user_registered' => $user->user_registered,
				'role'            => $role,
			], $meta);
		}

		return [
			"draw"            => isset($request['draw']) ? intval($request['draw']) : 0,
			"recordsTotal"    => intval($total_users),
			"recordsFiltered" => intval($filtered_count),
			"data"            => self::data_output($columns, $users_with_data),
		];
	}


	

    /**
     * Construct the LIMIT clause for server-side processing SQL query
     * @param  array $request Data sent to server by DataTables
     * @return string SQL limit clause
     */
    static function limit($request) {
        $limit = '';

        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        }

        return $limit;
    }

    /**
     * Construct the ORDER BY clause for server-side processing SQL query
     * @param  array $request Data sent to server by DataTables
     * @param  array $columns Column information array
     * @return string SQL order by clause
     */
    static function order($request, $columns) {
        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = [];
            foreach ($request['order'] as $orderItem) {
                $columnIdx = intval($orderItem['column']);
                $requestColumn = $request['columns'][$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $column = $columns[$columnIdx]['db'];
                    $dir = $orderItem['dir'] === 'asc' ? 'ASC' : 'DESC';
                    $orderBy[] = "$column $dir";
                }
            }

            if (count($orderBy)) {
                $order = 'ORDER BY ' . implode(', ', $orderBy);
            }
        }

        return $order;
    }

    /**
     * Construct the WHERE clause for server-side processing SQL query
     * @param  array $request Data sent to server by DataTables
     * @param  array $columns Column information array
     * @param  array $bindings Array of values for PDO bindings, used for secure SQL execution
     * @return string SQL where clause
     */
		static function filter($request, $columns, &$bindings) {
		global $wpdb; // Use the WordPress database global object

		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = self::pluck($columns, 'dt');

		// Global search
		if (isset($request['search']) && $request['search']['value'] != '') {
			$str = $request['search']['value'];

			foreach ($request['columns'] as $requestColumn) {
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];

				if ($requestColumn['searchable'] === 'true' && !empty($column['db'])) {
					$binding = '%' . $wpdb->esc_like($str) . '%'; // Escape the search term
					$globalSearch[] = $wpdb->prepare("`{$column['db']}` LIKE %s", $binding);
				}
			}
		}

		// Individual column filtering
		if (isset($request['columns'])) {
			foreach ($request['columns'] as $requestColumn) {
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];

				$str = $requestColumn['search']['value'];

				if ($requestColumn['searchable'] === 'true' && $str != '' && !empty($column['db'])) {
					$binding = '%' . $wpdb->esc_like($str) . '%'; // Escape the search term
					$columnSearch[] = $wpdb->prepare("`{$column['db']}` LIKE %s", $binding);
				}
			}
		}

		// Combine the filters into a single string
		$where = '';

		if (!empty($globalSearch)) {
			$where = '(' . implode(' OR ', $globalSearch) . ')';
		}

		if (!empty($columnSearch)) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where . ' AND ' . implode(' AND ', $columnSearch);
		}

		if ($where !== '') {
			$where = 'WHERE ' . $where;
		}

		return $where;
	}


    /**
     * Extended method to allow custom WHERE clause for filtering
     * @param  array $request Data sent to server by DataTables
     * @param  string $table Name of the database table
     * @param  string $primaryKey Primary key of the table
     * @param  array $columns Column information array
     * @param  string|null $customWhere Custom WHERE clause
     * @return array Server-side processing response array
     */
	static function complex($request, $table, $primaryKey, $columns, $customWhere = null) {
		global $wpdb;

		$bindings = [];
		$db_columns = array_column($columns, 'db');

		// Build the SQL query string from the request
		$limit = self::limit($request);
		$order = self::order($request, $columns);
		$where = self::filter($request, $columns, $bindings);

		// Combine custom WHERE clause with the built WHERE clause
		if ($customWhere) {
			if ($where) {
				$where .= " AND ($customWhere)";
			} else {
				$where = "WHERE $customWhere";
			}
		}

		// Main query to actually get the data
		$dataQuery = "SELECT " . implode(', ', $db_columns) . " FROM $table $where $order $limit";
		$data = $wpdb->get_results($dataQuery, ARRAY_A);

		// Data set length after filtering
		$resFilterLengthQuery = "SELECT COUNT($primaryKey) FROM $table $where";
		$resFilterLength = $wpdb->get_var($resFilterLengthQuery);

		// Total data set length
		$resTotalLengthQuery = "SELECT COUNT($primaryKey) FROM $table";
		$resTotalLength = $wpdb->get_var($resTotalLengthQuery);

		return [
			"draw" => isset($request['draw']) ? intval($request['draw']) : 0,
			"recordsTotal" => intval($resTotalLength),
			"recordsFiltered" => intval($resFilterLength),
			"data" => self::data_output($columns, $data)
		];
	}
	
	
	
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Internal methods
	 */

	/**
	 * Throw a fatal error.
	 *
	 * This writes out an error message in a JSON string which DataTables will
	 * see and show to the user in the browser.
	 *
	 * @param  string $msg Message to send to the client
	 */
	static function fatal ( $msg )
	{
		echo json_encode( array( 
			"error" => $msg
		) );

		exit(0);
	}

	/**
	 * Create a PDO binding key which can be used for escaping variables safely
	 * when executing a query with sql_exec()
	 *
	 * @param  array &$a    Array of bindings
	 * @param  *      $val  Value to bind
	 * @param  int    $type PDO field type
	 * @return string       Bound key to be used in the SQL where this parameter
	 *   would be used.
	 */
	static function bind ( &$a, $val, $type )
	{
		$key = ':binding_'.count( $a );

		$a[] = array(
			'key' => $key,
			'val' => $val,
			'type' => $type
		);

		return $key;
	}


	/**
	 * Pull a particular property from each assoc. array in a numeric array, 
	 * returning and array of the property values from each item.
	 *
	 *  @param  array  $a    Array to get data from
	 *  @param  string $prop Property to read
	 *  @return array        Array of property values
	 */
	static function pluck ( $a, $prop )
	{
		$out = array();

		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            if(empty($a[$i][$prop])){
                continue;
			}
			//removing the $out array index confuses the filter method in doing proper binding,
			//adding it ensures that the array data are mapped correctly
			$out[$i] = $a[$i][$prop];
		}

		return $out;
	}


	/**
	 * Return a string from an array or a string
	 *
	 * @param  array|string $a Array to join
	 * @param  string $join Glue for the concatenation
	 * @return string Joined string
	 */
	static function _flatten ( $a, $join = ' AND ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}


}