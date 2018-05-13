<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class rating_model extends CI_Model
{
    
        /**
     * This function is used to get some information of rating
     * @param number $userId : This is id of member
     * @param number $eventId : This is id of event
     * @return number $count : This is information of rating found
     */
    function getRatingContentById($userId,$organizerId)
    {
        $this->db->select("rating.point, rating.comment");
        $this->db->from('rating');
        $this->db->join('event','event.id = rating.event_id');
        $this->db->where('rating.user_id', $userId);
        $this->db->where('event.organizer_id', $organizerId);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to get some information of rating by usign boss id
     * @param number $bossId : This is id of boss
     * @return array $count : This is information of rating found
     */
    function getRatingCountByBoss($bossId)
    {
        $this->db->select("rating.*");
        $this->db->from("rating");
        $this->db->join('event','event.id = rating.event_id');
        $this->db->where("event.organizer_id", $bossId);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to get some information of rating by usign event id
     * @param number $eventId : This is id of event
     * @return array $count : This is information of rating found
     */
    function getRatingByEvent($eventId)
    {
        $this->db->select("rating.point, rating.comment, rating.submit_time");
        $this->db->select("user.name, user.avatar");
        $this->db->from("rating");
        $this->db->join('user', 'user.no = rating.user_id');
        $this->db->where("rating.event_id", $eventId);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to get some information of rating by usign event id
     * @param number $eventId : This is id of event
     * @return array $count : This is information of rating found
     */
    function getRatingByBoss($bossId)
    {
        $this->db->select("rating.point, rating.comment, rating.submit_time");
        $this->db->select("user.name, user.avatar");
        $this->db->from("rating");
        $this->db->join('user', 'user.no = rating.user_id');
        $this->db->join('event', 'event.id = rating.event_id');
        $this->db->where("event.organizer_id", $bossId);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to get some information of rating by usign booking id
     * @param number $bookingId : This is id of booking
     * @param number $userId : This is id of user
     * @return array $count : This is information of rating found
     */
    function getRatingByBooking($bookingId, $userId)
    {
        $this->db->select("rating.point, rating.comment");
        $this->db->from("rating");
        $this->db->join('event', 'event.id = rating.event_id');
        $this->db->join('booking', 'booking.event_id = event.id');
        $this->db->where("booking.id", $bookingId);
        $this->db->where("rating.user_id", $userId);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to get the amount of rating information
     * @param number $searchName : This is id of member or phone
     * @return number $count : This is information of rating found
     */
    function ratingListingCount($searchStatus = null, $searchName = '')
    {
        $query = "select user.name, user.phone, rating.point, rating.comment, booking.id, rating.id as ratingId, rating.submit_time
            from rating, user, booking, event where rating.user_id = user.no and booking.user_id = rating.user_id and booking.event_id = event.id and event.id = rating.event_id";
        if (!empty($searchText)) {
            if(isset($searchStatus)){
                if ($searchStatus == '0') {
                    $query = $query." and (rating.id LIKE '%" . $searchText . "%')";
                } else if ($searchStatus == '1') {
                    $query = $query." and (user.name LIKE '%" . $searchText . "%')";
                } else {
                    $query = $query." and (user.phone LIKE '%" . $searchText . "%')";
                }
            }
        }
        $result = $this->db->query($query);
        return count($result->result());
    }

    /**
     * This function is used to get the amount of rating information
     * @param number $searchName : This is id of member or phone
     * @return number $count : This is information of rating found
     */
    function ratingListing($searchStatus = null, $searchText = '', $page, $segment)
    {
        $query = "select user.name, user.phone, rating.point, rating.comment, booking.id, rating.id as ratingId, rating.submit_time
            from rating, user, booking where rating.user_id = user.no and booking.user_id = rating.user_id and booking.event_id = rating.event_id";
        if (!empty($searchText)) {
            if(isset($searchStatus)){
                if ($searchStatus == '0') {
                    $query = $query." and (rating.id LIKE '%" . $searchText . "%')";
                } else if ($searchStatus == '1') {
                    $query = $query." and (user.name LIKE '%" . $searchText . "%')";
                } else {
                    $query = $query." and (user.phone LIKE '%" . $searchText . "%')";
                }
            }
        }
        $query=$query." order by booking.id desc";
        if($segment!=""){
            $query = $query." limit ".$segment.", ".$page;
        }
        else{
            $query = $query." limit 0, ".$page;
        }
        $result = $this->db->query($query);
        return $result->result();
    }

    /**
    *This function is used to delete rating by id
    *@param number id : id to delete
    *@return boolean $result: state of delete
    **/
    function deleteRating($id)
    {
        $this->db->where("id", $id);
        $this->db->delete("rating");
        return true;
    }

    /**
    *This function is used to add new rating
    *@param array info : all data to insert
    *@return boolean $result: state of insert
    **/
    function addRating($info)
    {
        $rating = $this->db->insert("rating", $info);
        
        return $this->db->affected_rows();
    }

    /**
    *This function is used to get rating for event
    *@param number event_id : id of event to find rating
    *@return boolean $result: state of insert
    **/
    function getRatingDetailByEvent($eventId)
    {
        $this->db->select("rating.point, rating.comment, rating.submit_time");
        $this->db->select("user.name, user.avatar");
        $this->db->from('rating');
        $this->db->join('user', 'user.no = rating.user_id');
        $this->db->where('rating.event_id', $eventId);
        $query = $this->db->get();
        return $query->result();
    }

}

/* End of file rating_model.php */
/* Location: .application/models/rating_model.php */
