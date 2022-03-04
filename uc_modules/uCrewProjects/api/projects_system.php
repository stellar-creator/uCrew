<?php
	/**
	 * uCrew Projects Class
	 */
	class uCrewProjects	{

		private $ucs_Database ;

		function __construct(){
			$this->ucs_Database = new uCrewDatabase('ucrew_projects');
		}

		public function getMechanicsPager($page, $count){
			$sql = "SELECT COUNT(*) FROM `ucp_mechanics`";
			$data = $this->ucs_Database->getAllData($sql)[0]['COUNT(*)'];
			$number = $data / $count;

			$total_pages = floor($number);     
			$fraction = $number - $total_pages;

			if($fraction > 0){
				$total_pages++;
			}

			$pages = '';

			for ($i = 1; $i < $total_pages + 1; $i++) { 
				$class = '';

				if($page == $i){
					$class = 'active';
				}

				$pages .= '<li class="page-item '.$class.'"><a class="page-link" href="/?page=uCrewProjects/mechanics&p='.$i.'&c='.$count.'">'.$i.'</a></li>';
			}

			return '
			<nav aria-label="Page navigation example">
			  <ul class="pagination justify-content-end">
			    <li class="page-item">
			      <a class="page-link" href="/?page=uCrewProjects/mechanics&p=1&c='.$count.'" aria-label="Начало">
			        <span aria-hidden="true">&laquo;</span>
			      </a>
			    </li>
			   	'.$pages.'
			    <li class="page-item">
			      <a class="page-link" href="/?page=uCrewProjects/mechanics&p='.$total_pages.'&c='.$count.'" aria-label="Конец">
			        <span aria-hidden="true">&raquo;</span>
			      </a>
			    </li>
			  </ul>
			</nav>
			';
		}

		public function getMechanicsList($page, $count){
			
			$start = ($page * $count) - $count;
			$end = $count;

			$sql = "SELECT * FROM `ucp_mechanics` ORDER BY `ucp_mechanics`.`mechanic_codename` DESC LIMIT $start,$end";
			$list = $this->ucs_Database->getAllData($sql);
			
			foreach ($list as $key => &$value) {
				$value['mechanic_data'] = json_decode($value['mechanic_data'], true);
			}

			return $list;
		}

		public function getMechanicsLastCodeName(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'mechanics_codename'";
			$data = $this->ucs_Database->getAllData($sql)[0]['data_value'];
			$sufix = "TBM";
			if($data < 1000){
				$data = $sufix . '0' . $data;
			}
			if($data >= 1000 and $data < 10000){
				$data = $sufix . '00' . $data;
			}
			if($data >= 10000 and $data < 100000){
				$data = $sufix . '000' . $data;
			}
			return $data;
		}

	}
?>