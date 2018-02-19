<?php
 
class Paginator {
 
     private $_conn;
     private $_limit;
     private $_page;
     private $_query;
     private $_total;
	 
	 private $_dataArray;
	 
	 public function __construct( $conn, $query, $dataArray = null ) {
     
	    $this->_conn = $conn;
	    $this->_query = $query;
	 
	    $rs = mysqli_query($conn, $this->_query);
	    $this->_total = mysqli_num_rows($rs);
	    
		if($dataArray || $dataArray != null){
			$this->_dataArray = $dataArray;
		}
	}
	 
	public function getData( $limit = 10, $page = 1 ) {
		
		if($this->_dataArray || $this->_dataArray != null){
			$result         = new stdClass();
		    $result->page   = $this->_page;
		    $result->limit  = $this->_limit;
		    $result->total  = $this->_total;
		    $result->data   = $this->_dataArray;
			
			return $this->_dataArray;
		}
	     
	    $this->_limit   = $limit;
	    $this->_page    = $page;
	 
	    if ( $this->_limit == 'all' ) {
	        $query      = $this->_query;
	    } else {
	        $query      = $this->_query . " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
	    }
	    $rs             = mysqli_query($this->_conn, $query);
	 
	    while ( $row = mysqli_fetch_assoc($rs) ) {
	        $results[]  = $row;
	    }
	 
	    $result         = new stdClass();
	    $result->page   = $this->_page;
	    $result->limit  = $this->_limit;
	    $result->total  = $this->_total;
	    if(isset($results)){
			$result->data   = $results;
		} else {
			$result->data = array();
		}
	 
	    return $result;
	}
	
	public function createLinks( $links, $list_class ) {
	    if ( $this->_limit == 'all' ) {
	        return '';
	    }
	 
	    $last       = ceil( $this->_total / $this->_limit );
	 
	    $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
	    $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;
	 
	    $html       = '<nav aria-label="Pagination" style="width: 100%"><ul class="' . $list_class . '">';
	 
	    $class      = ( $this->_page == 1 ) ? "disabled" : "";
	    $html       .= '<li class="page-item ' . $class . '"><a class="page-link" href="?page=' . ( $this->_page - 1 ) . '">&laquo;</a></li>';
	 
	    if ( $start > 1 ) {
	        $html   .= '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
	        $html   .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
	    }
	 
	    for ( $i = $start ; $i <= $end; $i++ ) {
	        $class  = ( $this->_page == $i ) ? "active" : "";
	        $html   .= '<li class="page-item ' . $class . '"><a class="page-link" href="?page=' . $i . '">' . number_format($i, 0, '', '.') . '</a></li>';
	    }
	 
	    if ( $end < $last ) {
	        $html   .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
	        $html   .= '<li class="page-item"><a class="page-link" href="?page=' . $last . '">' . number_format($last, 0, '', '.') . '</a></li>';
	    }
	 
	    $class      = ( $this->_page == $last ) ? "disabled" : "";
	    $html       .= '<li class="page-item ' . $class . '"><a class="page-link" href="?page=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';
	 
	    $html       .= '</ul></nav>';
	 
	    return $html;
	}
 
}

?>