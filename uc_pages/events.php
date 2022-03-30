<?php
  // Current page
  $page = 1;
  // Maximum show results
  $max_show = 25;
  // Key results
  $key = '';
  // Check if isset page
  if(isset($_GET["p"])){
    $page = $_GET["p"];
  }  
  // Check if isset page
  if(isset($_GET["k"])){
    $key = $_GET["k"];
  }
  // Check if isset max_show
  if(isset($_GET["c"])){
    $max_show = $_GET["c"];
  }
  // Get events
  $events = $this->ucDatabase->getEvents($page, $max_show, $key);
  // Table data
  $table = "";
  // Add events to table
  if($events["data"] != 0){
    foreach($events["data"] as $data){
      $table .=  '<tr>
              <th scope="row">'.$data["event_id"].'</th>
              <td>'.$data["event_name"].'</td>
              <td>'.$data["event_text"].'</td>
              <td>'.$data["event_timestamp"].'</td>
            </tr>
      ';
    }
  }

?>
<div class="row">
  <div id="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Просмотр последних событий в системе uCrew
    </div>
    <div class="card-body">


      <form class="row g-3" action="/" method="get">
        <input type="hidden" name="page" value="uCrew/events">
         <div class="col-md-6 text-center">
          <input type="text" class="form-control" name="k" placeholder="Ключевые слова" aria-label="Ключевые слова" value="<?php echo $key; ?>">
        </div>
        <div class="col-md-4 text-center">
          <select name="c" id="c" class="form-select form-select" aria-label=".form-select-sm example">
            <option value="25" selected>Отображать 25 событий</option>
            <option value="50">Отображать 50 событий</option>
            <option value="100">Отображать 100 событий</option>
            <option value="200">Отображать 200 событий</option>
          </select>
        </div>
         <div class="col-md-2 text-center">
          <input type="submit" value="Применить" class="btn btn-primary">
        </div>
      </form>

      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">№ </th>
            <th scope="col" width="250px">Заголовок</th>
            <th scope="col">Описание</th>
            <th scope="col">Дата</th>
          </tr>
        </thead>
        <tbody>
<?php
  echo $table;
?>
        </tbody>
      </table>

      <div class="dataTable-bottom">

<?php
  
  echo '<div class="dataTable-info">Всего событий ' . $events["total"] . '</div>';
  // Get pager
  
  $url = "/?page=uCrew/events";
  $max = $this->ucDatabase->getRecordsCount('uc_events');

  if(isset($_GET)){
    foreach ($_GET as $key => $value) {
      if($key != 'page' and $key != 'p' and $key != 'c' ){
        $url .= '&'.$key.'='.$value;
      }
    } 
    if(isset($_GET['k'])){
      $max = $events["total_pages"];
    }
  }

  $pager = $this->uc_CompilatorData->generatePager(
    $page,
    $max_show,
    $max,
    $url
  );

  print_r($pager);
?>
     
      </div>

    </div>
  </div>
</div>