<?php
	$page_title = "Töövarjupäev";
	$page_file = "shadow.php";
?>
<?php
	require_once("../header.php");
	require_once ("../inc/functions.php");
?>
<?php

	$keyword = "";
	if (isset($_GET["keyword"])) {
		$keyword = cleanInput($_GET["keyword"]);

		//otsime
		$job_array = $Shadow->getAllData($keyword);
	} else {
		//Naitame koiki tulemus
		$job_array = $Shadow->getAllData();
	}
?>
<h3 class="col-sm-12">Töövarjupäev</h3>
<?php if(isset($_SESSION['response']->success)): ?>

<div class="alert alert-success alert-dismissible fade in" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
	<p><?=$_SESSION['response']->success->message;?></p>
</div>

<?php elseif(isset($_SESSION['response']->error)): ?>

<div class="alert alert-danger alert-dismissible fade in" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
	<p><?=$_SESSION['response']->error->message;?></p>
</div>

<?php
	endif;
	unset($_SESSION['response']);
?>


<div class="col-xs-12 col-sm-10 col-sm-offset-1">
	<form action="shadow.php" method="get" class="col-xs-12 col-sm-12">
		<div class="input-group">
		<input name="keyword" type="text" class="form-control" placeholder="Otsi..." value="<?=$keyword?>">
		<span class="input-group-btn">
			<button class="btn btn-default" style="z-index: 1;" type="submit" value="otsi">Otsi!</button>
		</span>
		</div>
	</form>
	<div class="col-xs-12 col-sm-12">
	<br>

		<div class="list-group">
		<?php
			for($i = 0; $i < count($job_array); $i++) {
				echo '<div class="list-group-item" role="button" data-toggle="collapse" href="#'.$job_array[$i]->id.'" aria-expanded="false" aria-controls="'.$job_array[$i]->id.'">';
				echo '<h4 class="list-group-item-heading">'.$job_array[$i]->name.'</h4>';
				echo '<p class="list-group-item-text">'.$job_array[$i]->company.", ".$job_array[$i]->county.", ".$job_array[$i]->parish.'</p>';
				echo '</div>';
				echo '<div class="collapse" id="'.$job_array[$i]->id.'">';
				echo '<div class="well">';
				echo '<h4 style="margin-bottom: 5px;">Kirjeldus:</h4>';
				echo '<p class="list-group-item-text">'.$job_array[$i]->description.'</p><br>';

				echo '<h4 style="margin-bottom: 5px;">Kontakt:</h4>';
				echo '<p class="list-group-item-text">'.$job_array[$i]->email.'</p>';
				echo '<p class="list-group-item-text">'.$job_array[$i]->number.'</p>';
				echo '<p class="list-group-item-text">'.$job_array[$i]->county.', '.$job_array[$i]->parish.', '.$job_array[$i]->location.', '.$job_array[$i]->address.'</p><br>';
				echo '<ul class="list-inline">';
				echo '<li>Sisestatud: '.$job_array[$i]->inserted.'</li>';

				if($_SESSION['logged_in_user_group'] == 1) {
				echo '<li class="pull-right"><a href="../job/'.$job_array[$i]->link.'.php" class="btn btn-success btn-sm">
												Saada CV	<span class="glyphicon glyphicon-share-alt"></span>
							</a></li>';
				}

				echo '</ul>';
				#echo '<p class="list-group-item-text">Sisestatud: '.$job_array[$i]->inserted.'</p>';



				echo '</div>';
				echo '</div>';
			}

			?>
		</div>


	</div>
</div>
<?php require_once("../footer.php"); ?>
