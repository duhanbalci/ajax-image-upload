<?php
if(isset($_FILES["imgs"])){
	$count = count($_FILES["imgs"]["name"]);
	$url = "http://127.0.0.1/imgup/";
	for($i = 0; $i < $count; $i++){
		$verifyimg = getimagesize($_FILES['imgs']['tmp_name'][$i]);
		$mimeTypes = ['image/png','image/jpeg','image/gif'];
		if(!in_array($verifyimg['mime'],$mimeTypes)) {
		    echo "Only images are allowed!";
		    exit;
		}

		$uploaddir = 'i/';

		$uploadfile = $uploaddir . basename($_FILES['imgs']['name'][$i]);

		if (move_uploaded_file($_FILES['imgs']['tmp_name'][$i], $uploadfile)) {
		    $result[] = $url.$uploadfile;
		} else {
		    echo "Image uploading failed.";
		}
	}
	echo json_encode($result);
}else{


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Image Upload</title>
	<link rel="stylesheet" href="">
	<style type="text/css" media="screen">
		#uploadInput {
			width: 0.1px;
			height: 0.1px;
			opacity: 0;
			overflow: hidden;
			position: absolute;
			z-index: -1;
		}
		#uploadInput + label {
			-webkit-border-radius: 9;
			-moz-border-radius: 9;
			border-radius: 9px;
			text-shadow: 1px 1px 3px #666666;
			-webkit-box-shadow: 1px 1px 9px #666666;
			-moz-box-shadow: 1px 1px 9px #666666;
			box-shadow: 1px 1px 9px #666666;
			font-family: Georgia;
			color: #ffffff;
			font-size: 37px;
			background: #1fa5ff;
			padding: 10px 20px 10px 20px;
			text-decoration: none;
			width:350px;
			margin: 100px 0;
			cursor:pointer;
		}
		#uploadButton:hover {
			background: #0075bd;
			text-decoration: none;
		}
		#container {
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			text-align: center;
		}
		#content {
			margin:auto;
		}
		.meter { 
			height: 20px;  /* Can be anything */
			position: relative;
			background: #0075bd;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;
			padding: 3px;
			box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
		}
		.meter > span {
			-webkit-transition: width 0.3s; /* Safari */
			transition: width 0.3s;
			display: block;
			height: 100%;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;
			background-color: rgb(31, 165, 255);
			background-image: linear-gradient(
				center bottom,
				rgb(43,194,83) 37%,
				rgb(84,240,84) 69%
			);
			box-shadow: 
			inset 0 2px 9px  rgba(255,255,255,0.3),
			inset 0 -2px 6px rgba(0,0,0,0.4);
			position: relative;
			overflow: hidden;
		}
		#uploads {
			margin:100px auto;
			width:70%;
		}
		input{
			padding:10px;
			font-size: 20px;
			width:79%;
			border-radius: 4px;
			margin:3px;
		}

	</style>
	<script type="text/javascript">
		function toggleProgressBar(x) {
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
		}
		window.onload = () => {
			var form = document.getElementById('form');
			var fileSelect = document.getElementById('uploadInput');
			var progressBar = document.getElementById('progress');
			var progressBarW = document.getElementById('progressW');
			fileSelect.onchange = () => {
				var files = fileSelect.files;
				var formData = new FormData();

				for (var i = 0; i < files.length; i++) {
					var file = files[i];
					if (!file.type.match('image.*')) {
						//continue;
					}
					formData.append('imgs[]', file, file.name);
				}
				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'index.php', true);
				xhr.onloadstart = () => {
					toggleProgressBar(progressBarW);
				}
				xhr.onreadystatechange = function() {
					if (xhr.readyState === 4) {
						var result = JSON.parse(xhr.response);
						var htmlData = "";

						setTimeout(() => {
							toggleProgressBar(progressBarW);
							for(i=0;i<result.length;i++){
								document.getElementById("uploads").innerHTML += '<input onClick="this.select();" type="text" value="'+result[i]+'">'; 
							}
						},500);
						
					}
				}
				xhr.upload.onprogress = function (e) {
				    if (e.lengthComputable) {
				    	var t = e.total / 1024 / 1024;
				    	var l = e.loaded / 1024 / 1024;
				    	var p = (100 * l) / t ;
				    	progressBar.style.width = p+"%" ;
				    	progressBar.innerHTML = Math.floor(p)+"%";
				    }
				}
				xhr.send(formData);
			}
		}
	</script>
</head>
<body>
	<div id="container">
		<div id="content">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<input type="file" id="uploadInput" name="img" multiple>
				<label for="uploadInput">Browse</label>
			</form>
			<div id="uploads">
				
				<div class="meter" style="display: none" id="progressW">
				  <span style="width: 1%" id="progress"></span>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php } ?>