<?php include('includes/header.php') ?>
<link rel="stylesheet" href="./css/stud.css">
<?php include('includes/nav.php') ?>
<?php

if (!logged_in()) {
	set_message("<p class='bg-danger'>Please login again to view that page<p>");
	redirect("login.php");
}
$row = getStudentDetails($_SESSION['email']);
require './imageUpload.php';
require './resumeUpload.php';
if (isset($_POST['dataEnter'])) {
	if (isset($_POST['collegeName']))
		setCollegeName($row['email'], $_POST['collegeName']);
	if (isset($_POST['description']))
		setDescription($row['email'], $_POST['description']);
}
$temp = getAllProfProjects($row['email']);
$error = '';
$projectCount = $_SESSION['projectCount']+1;
if (isset($_POST['submitProj'])) {
	$email = $_SESSION['email'];
	foreach ($temp as $k => $v) {
		if (isset($_POST[$v[0]])) {
			$var = "project" . $projectCount . "_id";
			$query = "UPDATE students SET $var = '$v[0]' WHERE email = '$email';";
			$res = query($query);
			confirm($res);
			$error = "Succesfully submitted your response";
		}
	}
	$projectCount++;
	$_SESSION['projectCount']++;
	if($projectCount === 4)
	{
		$query = "UPDATE students SET projectSelected = 1 WHERE email = '$email';";
		$res = query($query);
		confirm($res);
	}
}

?>
<div class="container-fluid">
	<div class="row">
		<h1>Student Profile</h1>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

			<div class="card" style="width: 18rem;">
				<?php if ($row['imageName'] === '') : ?>
					<img class="card-img-top" src='./assets/images/male.png' alt="Card image cap">
				<?php else : ?>
					<img class="card-img-top" src='./uploads/studentImages/<?php echo $row['imageName'] ?>' alt="Card image cap">
				<?php endif; ?>
				<div class="card-body">
					<h5 class="card-title"><?php echo implode(' ', array($row['first_name'], $row['last_name'])) ?></h5>
					<?php if ($row['college'] != '') : ?>
						<p class="card-text">College : <?php echo $row['college'] ?></p>
					<?php endif; ?>
					<?php if ($row['imageName'] === '') : ?>
						<form action="" method="post" enctype="multipart/form-data">
							<div class="form-group">
								Upload an Image:
								<input type="file" name="myfile" id="fileToUpload" class="form-control">
								<input type="submit" name="submitImage" value="Upload File Now" class="form-control btn btn-primary">
							</div>
						</form>
					<?php else : ?>
						<div class="form-group">
							change uploaded Image:
							<input type="file" name="myfile" id="fileToUpload" class="form-control">
							<input type="submit" name="submitImage" value="Upload File Now" class="form-control btn btn-primary">
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<?php if ($row['college'] === '' || $row['description'] === '') : ?>
				<form action="" method="POST">
					<div class="form-group">
						<?php if ($row['college'] === '') : ?>
							<label for="Enter College">Enter College Name</label>
							<input type="text" name="collegeName" class="form-control">
						<?php endif; ?>
						<?php if ($row['description'] === '') : ?>
							<label for="Enter College">Enter Something about yourself</label>
							<textarea class="form-control" rows="5" id="comment" name="description"></textarea>
						<?php endif; ?>
						<input type="submit" name="dataEnter" class="btn btn-primary form-comtrol">
					</div>
				</form>
			<?php else : ?>
				<?php if ($row['college'] != '') : ?>
					<h3>College: <?php echo $row['college'] ?></h3>
				<?php endif; ?>
				<?php if ($row['college'] != '') : ?>
					<h3>Description</h3>
					<p><?php echo $row['description'] ?></p>
				<?php endif; ?>
			<?php endif; ?>
			<form action="" method="post" enctype="multipart/form-data">
				<div class="form-group">
					Upload the resume:
					<input type="file" name="myfile" id="fileToUpload" class="form-control">
					<input type="submit" name="submitResume" value="Upload File Now" class="form-control btn btn-primary">
				</div>
			</form>
		</div>
	</div>
	<?php if ($row['projectSelected'] === "0") : ?>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<h1>Select Projects</h1>
				<?php if ($error != '') : ?>
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<?php echo $error ?>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<h1>Select your <?php echo $projectCount ?></h1>
				<form action="" method="POST">
					<div class="form-group">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th scope="col">Select</th>
									<th scope="col">Name</th>
									<th scope="col">Description</th>
									<th scope="col">Professor Email</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($temp as $key => $val) : ?>
									<tr>
										<td><input type="checkbox" name=<?php echo $val[0] ?>></td>
										<td><?php echo $val[2] ?></td>
										<td><?php echo $val[3] ?></td>
										<td><?php echo $val[1] ?></td>

									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<input type="submit" name="submitProj" class="btn btn-primary">
				</form>
			</div>
		</div>
	<?php endif; ?>
</div>
<?php include('includes/footer.php') ?>