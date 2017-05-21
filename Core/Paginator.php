<?php  

namespace Core;

class Paginator {

	public $page_url='';
	public $url_params = [];
	public $current_page;
	public $per_page;
	public $total_count;
	public $model_namespace = 'App\\Models\\';

	public function __construct($page=1, $per_page=10, $total_count=0) {

		if ( !is_numeric($page) or $page < 1) {
			$this->current_page = 1;
		} else {
			$this->current_page = (int)$page;
		}
		$this->per_page = (int)$per_page;
		$this->total_count = (int)$total_count;
	}

	public function getModelData($model_name, $where_clause){
		$model_name_namespaced = $this->model_namespace . $model_name;
		$model_table = $model_name_namespaced::$table_name;
		// $model_table = "books";
		$sql = "select * " .
				 " from {$model_table} " .
				 $where_clause .
				 " limit {$this->per_page} " .
				 " offset " . $this->offset();
		return $model_name_namespaced::find_by_sql($sql,[]);
	}

	public function offset() {
		//
		return ($this->current_page - 1) * $this->per_page;
	}

	public function total_pages() {
		//
		return ceil($this->total_count/$this->per_page);
	}

	public function previous_page() {
		//
		return $this->current_page-1;
	}

	public function next_page() {
		//
		return $this->current_page+1;
	}

	public function has_previous_page() {
		//
		return $this->previous_page() >= 1 ? true : false;
	}

	public function has_next_page() {
		//
		return $this->next_page() <= $this->totol_pages() ? true : false;
	}

	public function displayPagination() { 
		$page_url = $this->page_url;
		unset( $this->url_params['page'] );
		$url_params = ( empty($this->url_params)  ) ? '' : '&' . http_build_query( $this->url_params );
		
		// moze se malo prepraviti
		$item_per_page = $this->per_page;
		$current_page = $this->current_page;
		$total_records = $this->total_count;
		$total_pages = $this->total_pages();
	   
	    $pagination = '';
	    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
	        $pagination .= '<ul class="pagination">';
	       
	        $right_links   = $current_page + 3;
	        $previous       = $current_page - 3; //previous link
	        $next           = $current_page + 1; //next link
	        $first_link     = true; //boolean var to decide our first link
	       
	        if($current_page > 1){
	            $previous_link = $current_page - 1;
	            $pagination .= '<li class="first"><a href="' . $page_url . '?page=1' . $url_params . '" title="First">&laquo;</a></li>'; //first link
	            $pagination .= '<li><a href="' . $page_url . '?page=' . $previous_link . $url_params . '" title="Previous">&lt;</a></li>'; //previous link
	                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
	                    if($i > 0){
	                        $pagination .= '<li><a href="' . $page_url . '?page='.$i . $url_params . '">' . $i . '</a></li>';
	                    }
	                }  
	            $first_link = false; //set first link to false
	        }
	       
	        if($first_link){ //if current active page is first link
	            $pagination .= '<li class="active"><a>' . $current_page . '</a></li>';
	        }elseif($current_page == $total_pages){ //if it's the last active link
	            $pagination .= '<li class="active"><a>' . $current_page . '</a></li>';
	        }else{ //regular current link
	            $pagination .= '<li class="active"><a>' . $current_page . '</a></li>';
	        }

	        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
	            if($i<=$total_pages){
	                $pagination .= '<li><a href="' . $page_url . '?page=' . $i . $url_params . '">' . $i . '</a></li>';
	            }
	        }
	        if($current_page < $total_pages){
	                $next_link = $current_page + 1;
	                $pagination .= '<li><a href="' . $page_url . '?page=' . $next_link . $url_params . '" >&gt;</a></li>'; //next link
	                $pagination .= '<li class="last"><a href="' . $page_url . '?page=' . $total_pages . $url_params . '" title="Last">&raquo;</a></li>'; //last link
	        }
	       
	        $pagination .= '</ul>';
	    }
	    return $pagination; //return pagination links
	}

}


?>