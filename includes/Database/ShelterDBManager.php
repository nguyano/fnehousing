<?php
/**
 * Shelters Database Interaction class of the plugin.
 * Handles all DB interactions for Shelter actions.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Database;

defined('ABSPATH') || exit;

class ShelterDBManager {

    protected $tables;
    private   $db;

    public function __construct() {

        $this->db = new DBHelper();

        // Initialize table names
        $this->tables = (object) [
            'shelters'          => $this->db->tbl_pfx . 'fnehousing_shelters',
            'activity_log' 	    => $this->db->tbl_pfx . 'fnehousing_activity_log',
            'notifications'     => $this->db->tbl_pfx . 'fnehousing_notifications',
            'invoices'          => $this->db->tbl_pfx . 'fnehousing_invoices',
        ];
    }
	

    /**
     * Insert data into a database table.
     */
    public function insertData($table, $data) {
        return $this->db->fnehdInsert($table, $data);
    }
	
	/**
     * Get the ID of the last inserted shelter.
     * 
     * @return int The last inserted shelter ID.
     */
    public function getLastInsertedShelterID() {
        $sql = "SELECT shelter_id FROM {$this->tables->shelters} ORDER BY shelter_id DESC LIMIT 1";
		return $this->db->fnehdQuery($sql, [], 'var');
    }

    /**
     * Update data in a database table.
     */
    public function updateData($table, $data, $where) {
        return $this->db->fnehdUpdate($table, $data, $where);
    }

    /**
     * Fetch all rows from the Shelters table.
     */
    public function fetchAllShelters() {
        $sql = "SELECT * FROM {$this->tables->shelters} ORDER BY shelter_id DESC";
        return $this->db->fnehdQuery($sql);
    }
	
	/**
     * Fetch shelter by availability.
     */
    public function fetchSheltersByAvailability($availability) {
        $sql = "SELECT * FROM {$this->tables->shelters} WHERE availability = %s";
        return $this->db->fnehdQuery($sql, [$availability]);
    }

    /**
     * Fetch recent shelters (limit 7).
     */
    public function fetchRecentShelters() {
        $sql = "SELECT * FROM {$this->tables->shelters} ORDER BY shelter_id DESC LIMIT 7";
        return $this->db->fnehdQuery($sql);
    }

    /**
     * Fetch a single shelter by its ID.
     */
    public function getShelterById($id) {
        $sql = "SELECT * FROM {$this->tables->shelters} WHERE shelter_id = %d";
        return $this->db->fnehdQuery($sql, [$id], 'row');
    }

    /**
     * Count total shelters.
     */
    public function getTotalShelterCount() {
        $sql = "SELECT COUNT(*) FROM {$this->tables->shelters}";
        return $this->db->fnehdQuery($sql, [], 'var');
    }


    /**
     * Check if an shelter exists by its ID.
     */
    public function shelterExists($id) {
        $sql = "SELECT COUNT(*) FROM {$this->tables->shelters} WHERE shelter_id = %d";
        return $this->db->fnehdQuery($sql, [$id], 'var') > 0;
    }

    /**
     * Delete an shelter and its associated data.
     */
    public function deleteShelter($shelter_id) {
        $this->db->fnehdDelete($this->tables->shelters, ['shelter_id' => $shelter_id]);
        $this->db->fnehdDelete($this->tables->shelter_meta, ['shelter_id' => $shelter_id]);
        $this->db->fnehdDelete($this->tables->notifications, ['subject_id' => $shelter_id]);
    }

    /**
     * Delete multiple shelters and their associated data.
     */
    public function deleteShelters($ids) {
        foreach (explode(',', $ids) as $id) {
            $this->deleteShelter($id);
        }
    }

	 
	/**
	 * Count Total Shelters Per User
	 */
	public function getUserSheltersCount($username) {
		$sql = "SELECT COUNT(*) FROM {$this->tables->shelters} WHERE user_id = %d";
		return $this->db->fnehdQuery($sql, [$user_id], 'var');
	}
	
	
	/**
	 * Shelter Search Count backend
	 */
	public function shelterSearchCount($text) {
		$sql = "SELECT COUNT(*) FROM {$this->tables->shelters} WHERE shelter_name LIKE %s OR shelter_organization LIKE %s";
		return $this->db->fnehdQuery($sql, ["%$text%", "%$text%"], 'var');
	}

	/**
	 * Shelter Search Data backend
	 */
	public function shelterSearchData($text) {
		$sql = "SELECT * FROM {$this->tables->shelters} WHERE shelter_name LIKE %s OR shelter_organization LIKE %s";
		return $this->db->fnehdQuery($sql, ["%$text%", "%$text%"]);
	}
	
	
	/**
	 * Shelter Search Data backend
	 */
	public function shelterSearchListings($conditions, $params) {
		 $sql = "SELECT * FROM {$this->tables->shelters} WHERE 1=1"; 

		if (!empty($conditions)) {
			$sql .= " AND " . implode(" AND ", $conditions);
		}
		return $this->db->fnehdQuery($sql, $params, 'results');
	}
	
	
	/**
	 * Count Shelter Search Results backend
	 */
	public function countShelterSearchListings($conditions, $params) {
		 $sql = "SELECT COUNT(*) FROM {$this->tables->shelters} WHERE 1=1"; 

		if (!empty($conditions)) {
			$sql .= " AND " . implode(" AND ", $conditions);
		}
		return $this->db->fnehdQuery($sql, $params, 'var');
	}


    /**
     * Add a activity log entry.
     */
    public function logActivity($data) {
        return $this->insertData($this->tables->activity_log, $data);
    }

    /**
     * Fetch all activity logs for admin (user_id = 0).
     */
    public function fetchAdminActivityLogs() {
        $sql = "SELECT * FROM {$this->tables->activity_log} WHERE user_id = 0 ORDER BY log_id DESC";
        return $this->db->fnehdQuery($sql);
    }

    /**
     * Count all admin activity logs (user_id = 0).
     */
    public function getAdminActivityLogCount() {
        $sql = "SELECT COUNT(*) FROM {$this->tables->activity_log} WHERE user_id = 0";
        return $this->db->fnehdQuery($sql, [], 'var');
    }
	
	/**
	 * Delete log
	 */
	
	public function deleteLog($log_id) {
		$this->db->fnehdDelete($this->tables->shelter_meta, ['meta_id' => $meta_id]);
	}

	/**
	 * Delete multiple log records
	 */
	public function deleteLogs($multid) {
		$ids = explode(',', $multid);
		foreach ($ids as $id) {
			$this->deleteShelterMeta($id);
		}
	}

	
	
}
