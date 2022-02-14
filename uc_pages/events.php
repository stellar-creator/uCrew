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
  if(isset($_GET["m"])){
    $max_show = $_GET["m"];
  }
  // Get events
  $events = $this->ucDatabase->getEvents($page, $max_show, $key);
  // Table data
  $table = "";
  // Add events to table
  foreach($events["data"] as $data){
    $table .=  '<tr>
            <th scope="row">'.$data["event_id"].'</th>
            <td>'.$data["event_name"].'</td>
            <td>'.$data["event_text"].'</td>
            <td>'.$data["event_timestamp"].'</td>
          </tr>
    ';
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
          <input type="text" class="form-control" name="k" placeholder="Ключевые слова" aria-label="Ключевые слова">
        </div>
        <div class="col-md-4 text-center">
          <select name="m" id="m" class="form-select form-select" aria-label=".form-select-sm example">
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
?>
        <nav class="dataTable-pagination">
          <ul class="dataTable-pagination-list">
<?php
  if($events["total_pages"] > 1){
    echo '<li class="pager"><a href="#" onClick="setPage(1)">‹</a></li>';
    for ($i = 1; $i < $events["total_pages"] + 1; $i++) { 
      $active = '';
      if($page == $i){
        $active = 'class="active"';
      }
      echo '<li '.$active.'><a href="#" onClick="setPage('.$i.')">'.$i.'</a></li>';
    }
    echo '<li class="pager"><a href="#" onClick="setPage('.($events["total_pages"]).')">›</a></li>';
  }
?>
           
          </ul>
        </nav>
      </div>

    </div>
  </div>
</div>
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(){
      document.getElementById('m').value = <?php if(isset($_GET["m"])){ echo $_GET["m"]; } else {echo "25";}  ?>;
  });

  function setPage(page){
    m = <?php echo $max_show ?>;
    k = <?php echo '"' . $key . '"' ?>;
    document.location.href = "/?page=uCrew/events&m=" + m + "&p=" + page + "&k=" + k;
  } 
</script>