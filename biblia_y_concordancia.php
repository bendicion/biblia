<?php 
  include('biblia_config.inc.php');
  include('func2.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<head>
<title>Biblia y Concordancia con Audio</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="biblia_archivos/scrolltopcontrol.js"></script>
<link rel="stylesheet" type="text/css" href="biblia_archivos/styles.css" />
<script type="text/javascript" src="biblia_archivos/font-size.js"></script>

<link rel="stylesheet" type="text/css" href="biblia_audio_player/css/page-player.css" />
<link rel="stylesheet" type="text/css" href="biblia_audio_player/flashblock/flashblock.css" />
<script type="text/javascript" src="biblia_audio_player/script/soundmanager2.js"></script>
<script type="text/javascript" src="biblia_audio_player/script/page-player.js"></script>
<script type="text/javascript">soundManager.setup({ url: "biblia_audio_player/swf/"}); </script>

<script type="text/javascript">
$(document).ready(function() {
	$('#wait_1').hide();
	$('#libro').change(function(){
	  $('#wait_1').show();
	  $('#result_1').hide();
      $.get("func2.php", {
		func: "libro",
		drop_var: $('#libro').val()
      }, function(response){
        $('#result_1').fadeOut();
        setTimeout("finishAjax('result_1', '"+escape(response)+"')", 400);
      });
    	return false;
	});
});

function finishAjax(id, response) {
  $('#wait_1').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
}
function finishAjax_tier_three(id, response) {
  $('#wait_2').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
}
</script>

</head>
<body topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0" bgcolor="#fefefc">
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#d9d9d9">
  <tr>
    <td width="15"></td>
    <td height="35" align="left" valign="middle"><img src="http://bendicion.net/images/bendicionlogo.png" width="169" height="15"></td>
  </tr>
  <tr>
    <td height="15" bgcolor="#f4f4f4" colspan="2"></td>
  </tr>  
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4">
  <tr>
   <td>
    <table width="980" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td class="txt_form">
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
    <select name="libro" id="libro" class="txt_form">
      <option value="" selected="selected" disabled="disabled">Seleccionar Libro</option>
      <?php getTierOne(); ?>
    </select> 
    
    <span id="wait_1" style="display: none;">
    <img alt="Please Wait" src="ajax-loader.gif"/>
    </span>
    <span id="result_1" style="display: none;"></span>
    <span id="wait_2" style="display: none;">
    <img alt="Please Wait" src="ajax-loader.gif"/>
    </span>
    <span id="result_2" style="display: none;"></span> 
    </form>
          </td>
        </tr>
      </table>
      </td>
      </tr>
      </table>      
      

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4">
  <tr>
   <td align="center">    
<?php 

    ### Print Concordancia Form
	echo '<table width="980" cellpadding="0" cellspacing="0" border="0">
    <form id="" class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
	<tr><td class="txt_form" colspan="8">
    <b>Concordancia</b> 
	<input type="text" placeholder="Buscar por palabras" autocomplete="on" name="palabras" maxlength="50" class="input_box" style="width:330px;" />
    &nbsp;&nbsp;<b>Versi&oacute;n</b> 
	<select name="version" size="1" class="txt_verse" style="width:300px;">';
	biblia_versiones();
	echo '</select>
	<td align="left"><input type="image" name="Submit" value="Buscar" width="85" height="32" src="http://bendicion.net/images/buscar.png" /></td></tr>
	</td></tr></form></table></br>';
?>

</td>
      </tr>
      </table> 

<table width="980" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="txt_form">
<?
    ####################### Determine if $_GET METHOD is used to get the Bible Book, Chapter, and verse
if (isset($_GET['libro']) && !empty($_GET['libro']) && isset($_GET['capitulo']) && !empty($_GET['capitulo']) && isset($_GET['versiculo']) && !empty($_GET['versiculo']) && isset($_GET['version']) && !empty($_GET['version'])) {
	$libro = $_GET['libro'];
	$capitulo = $_GET['capitulo'];
	$versiculo = $_GET['versiculo'];
	$version   = $_GET['version'];
	
		if ($version == "") {
		$version = 'biblia_1960';
		}

        $table_name    = $version;
		$bible_result1  = @mysql_query("SELECT * FROM $table_name WHERE libro='$libro' AND capitulo='$capitulo' AND versiculo='$versiculo'");
        $bible_result2 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='10' AND versiculo='$libro'");
        $bible_result3 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name
		
		// Get Bible Version Name
		$row3 = mysql_fetch_array($bible_result3); 
		$nombre = $row3["texto"];
		
        // Get Bible Text
		$row1 = mysql_fetch_array($bible_result1); 
		$bible_text = $row1["texto"];

        // Get Bible Book
		$row2 = mysql_fetch_array($bible_result2); 
		$bible_book = $row2["texto"];		
		
        // Display All along with the Bible version name
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td align="left" class="txt_verse">
        </br>'.$bible_text.'&nbsp;<b></br>'.$bible_book.'&nbsp;'.$capitulo.':'.$versiculo.'</b> '.$nombre.'</td></tr>
		<tr><td>';
		
		// Input button to get a whole chapter
		echo '<form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="libro" value="'.$libro.'" />
		<input type="hidden" name="capitulo" value="'.$capitulo.'" />
		<input type="hidden" name="version" value="'.$version.'" />
		<input type="submit" value="Ver '.$bible_book.' '.$capitulo.'" class="submit_button" />
		</form></td></tr><tr><td>';
?>

<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
FB.init({appId: '387055054676186', status: true, cookie: true,
xfbml: true});
};
(function() {
var e = document.createElement('script'); e.async = true;
e.src = document.location.protocol +
'//connect.facebook.net/es_LA/all.js';
document.getElementById('fb-root').appendChild(e);
}());
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
$('#share_button').click(function(e){
e.preventDefault();
FB.ui(
{
method: 'feed',
name: '<? echo $bible_text.' '.$bible_book.' '.$capitulo.':'.$versiculo.' '.$nombre; ?>',
link: 'http://bendicion.net/biblia_y_concordancia.php?libro=<? echo $libro; ?>&capitulo=<? echo $capitulo; ?>&versiculo=<? echo $versiculo; ?>&version=<? echo $version; ?>',
picture: '',
caption: '',
description: 'Biblia y Concordancia con Audio',
message: ''
});
});
});
</script>
<img src = "compartir_fb.png" id = "share_button">
<?
		echo '</td></tr></table></br><ul class="playlist">
  <li><a href="biblia_audio/'.$version.'/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' '.$nombre.'">Escuchar '.$bible_book.' '.$capitulo.' '.$nombre.'</a></li>
 </ul>';
    } // end if statement
	
    ####################### Determine if Bible Book, Chapter, and verse were received with the POST METHOD
if (isset($_POST['libro']) && !empty($_POST['libro']) && isset($_POST['capitulo']) && !empty($_POST['capitulo']) && isset($_POST['versiculo']) && !empty($_POST['versiculo']))

{
	$libro = $_POST['libro'];
	$capitulo = $_POST['capitulo'];
	$versiculo = $_POST['versiculo'];
	$version   = $_POST['version'];
	
		if ($version == "") {
		$version = 'biblia_1960';
		}

        $table_name    = $version;
		$bible_result1  = @mysql_query("SELECT * FROM $table_name WHERE libro='$libro' AND capitulo='$capitulo' AND versiculo='$versiculo'");
        $bible_result2 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='10' AND versiculo='$libro'");
        $bible_result3 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name
		
		// Get Bible Version Name
		$row3 = mysql_fetch_array($bible_result3); 
		$nombre = $row3["texto"];
		
        // Get Bible Text
		$row1 = mysql_fetch_array($bible_result1); 
		$bible_text = $row1["texto"];

        // Get Bible Book
		$row2 = mysql_fetch_array($bible_result2); 
		$bible_book = $row2["texto"];		
		
        // Display All along with the Bible version name
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td align="left" class="txt_verse" colspan="2"><br>'.$bible_text.'</td></tr>
        <tr><td class="txt_verse" colspan="2"><b>'.$bible_book.'&nbsp;'.$capitulo.':'.$versiculo.'</b> Versión '.$nombre.'</td></tr>';		

		// Input button to get a whole chapter
		echo '<tr><td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="libro" value="'.$libro.'" />
		<input type="hidden" name="capitulo" value="'.$capitulo.'" />
		<input type="hidden" name="version" value="'.$version.'" />
		<input type="submit" value="Ver '.$bible_book.' '.$capitulo.'" class="txt_verse" /></form></td>';

        // Display drop down menu to change versions
		echo '<td align="left" width="90%"><form name="version_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="libro" value="'.$libro.'" />
		<input type="hidden" name="capitulo" value="'.$capitulo.'" />
		<input type="hidden" name="versiculo" value="'.$versiculo.'" />		
		&nbsp;<select name="version" size="1" class="txt_verse" onchange="version_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
	    echo '</select></form></td></tr><tr><td colspan="2">';
?>


<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
FB.init({appId: '387055054676186', status: true, cookie: true,
xfbml: true});
};
(function() {
var e = document.createElement('script'); e.async = true;
e.src = document.location.protocol +
'//connect.facebook.net/es_LA/all.js';
document.getElementById('fb-root').appendChild(e);
}());
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
$('#share_button').click(function(e){
e.preventDefault();
FB.ui(
{
method: 'feed',
name: '<? echo $bible_text.' '.$bible_book.' '.$capitulo.':'.$versiculo.' '.$nombre; ?>',
link: 'http://bendicion.net/biblia_y_concordancia.php?libro=<? echo $libro; ?>&capitulo=<? echo $capitulo; ?>&versiculo=<? echo $versiculo; ?>&version=<? echo $version; ?>',
picture: '',
caption: '',
description: 'Biblia y Concordancia con Audio',
message: ''
});
});
});
</script>
<img src = "compartir_fb.png" id = "share_button">
<?
		// echo '</td></tr></table></br><ul class="playlist">
  // <li><a href="biblia_audio/'.$version.'/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' '.$nombre.'">Escuchar '.$bible_book.' '.$capitulo.' '.$nombre.'</a></li>
 // </ul>';
    } // end if statement

	########################### Determine if ONLY the Book and chapter were sent
    else if (isset($_POST['libro']) && !empty($_POST['libro']) && isset($_POST['capitulo']) && !empty($_POST['capitulo']) ) {
		
        // If the user just sent info
        $libro     = $_POST['libro'];
        $capitulo  = $_POST['capitulo'];
		$version   = $_POST['version'];
		if ($version == "") {
		   $version = 'biblia_1960';
		   }
		require ("biblia_config.inc.php"); // connect to database
        //$table_name    = 'biblia_1960';
		$table_name    = $version;
        $bible_result7 = @mysql_query("SELECT * FROM $table_name WHERE libro='$libro' AND capitulo='$capitulo' ORDER BY versiculo ASC"); // Get Text Verse
        //$bible_result8 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='10' AND versiculo='$libro'"); // Get Book Name
		$bible_result9 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name

        // Get Bible Version Name
		$row9 = mysql_fetch_array($bible_result9); 
		$nombre = $row9["texto"];

        // Get Bible Book Name
		$bible_result28 = @mysql_query("SELECT * FROM nombres_de_libros WHERE libro='$libro'");
		$row28 = mysql_fetch_array($bible_result28);
		$bible_book = $row28["nombre"];
		
		//$row8 = mysql_fetch_array($bible_result8);
		//$bible_book = $row8["texto"];
		
		echo '</br>';

			$capitulo_prev = $capitulo - 1;
			$capitulo_next = $capitulo + 1;
			
			// Display Book Name and Chapter
			echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td><h1>'.$bible_book.' - Capítulo '.$capitulo.'</h1></td></tr></table>';
			
			// Display the Bible version name
			echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td class="txt_verse">'.$nombre.'</td></tr></table>';
	
	$libro_next = $libro+1;
	$libro_prev = $libro-1;
	
		// Switch Case Audio Player	depending on what Bible version is used
switch ($version)
{
case "biblia_1909":
		$audio_biblia = '<ul class="playlist">
  <li><a href="biblia_audio/'.$version.'/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' RV 1909" class="txt_verse">Escuchar '.$bible_book.' '.$capitulo.' Reina Valera 1909</a></li>
 </ul>';
  break;

case "biblia_1960":
		$audio_biblia = '<ul class="playlist">
  <li><a href="biblia_audio/'.$version.'/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' RV 1960" class="txt_verse">Escuchar '.$bible_book.' - Capítulo '.$capitulo.' - Reina Valera 1960</a></li>
 </ul>';
  break;
  
case "biblia_kjv":
		$audio_biblia = '<ul class="playlist">
  <li><a href="biblia_audio/'.$version.'/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' KJV"  class="txt_verse">Listen to '.$bible_book.' '.$capitulo.' King James Version</a></li>
 </ul>';
  break;
  
default:
		$audio_biblia = '<ul class="playlist">
  <li><a href="biblia_audio/biblia_1960/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' RV 1960" class="txt_verse">Escuchar '.$bible_book.' - Capítulo '.$capitulo.' - Reina Valera 1960</a></li>
 </ul>';
}		

	echo '<table width="980" border="0" cellspacing="0" cellpadding="0"><tr>';
	
        // Display drop down menu to change versions
		echo '<td align="left"><form name="version_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<select name="version" size="1" class="boton" onchange="version_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
        echo '<input type="hidden" name="capitulo" value="'.$capitulo.'" />
		<input type="hidden" name="libro" value="'.$libro.'" />
	    </select></form></td><td width="5"></td>';	
		
    // Button Cap&iacute;tulo Anterior
	if ($capitulo > 1) {
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		 <input type="hidden" name="libro" value="'.$libro.'" />
		 <input type="hidden" name="capitulo" value="'.$capitulo_prev.'" />
		 <input type="hidden" name="version" value="'.$version.'" />
		 <input type="submit" value="<< Cap&iacute;tulo Anterior" class="boton" /></form></td><td width="5"></td>';
	}	
	
	// Button Capitulo Siguiente
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
    <input type="hidden" name="libro" value="'.$libro.'" />
	<input type="hidden" name="capitulo" value="'.$capitulo_next.'" />
	<input type="hidden" name="version" value="'.$version.'" />
	<input type="submit" value="Cap&iacute;tulo Siguiente >>" class="boton" />
	</form></td><td width="5"></td>';
	
	// Button Libro Anterior
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
	<input type="hidden" name="libro" value="'.$libro_prev.'" />
	<input type="hidden" name="capitulo" value="1" />
	<input type="hidden" name="version" value="'.$version.'" />
	<input type="submit" value="<< Libro Anterior" class="boton" /></form></td><td width="5"></td>';
	
	// Button Libro Siguiente
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
	<input type="hidden" name="libro" value="'.$libro_next.'" />
	<input type="hidden" name="capitulo" value="1" />
	<input type="hidden" name="version" value="'.$version.'" />
	<input type="submit" value="Libro Siguiente >>" class="boton" /></form></td><td width="5"></td>';
	
    // Button Ver en Paralelo
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
    <input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
    <input type="hidden" name="version_left" value="biblia_1960">
    <input type="hidden" name="version_right" value="biblia_1909">
    <input type="hidden" name="paralelo" value="'.$libro.'" />
    <input type="submit" value="Ver en Paralelo" class="boton"></form></td><td width="5"></td>';
	
		// Display Font Size Change /////////////////////////
		echo '<td valign="top" width="30"><a href="javascript:decreaseFontSize();"><img src="biblia_archivos/img/disminuir.png" width="30" height="30" border="0"></a></td><td width="5"></td><td valign="top" width="30"><a href="javascript:increaseFontSize();"><img src="biblia_archivos/img/aumentar.png" width="30" height="30" border="0"></a></td></tr></table>';

		// Display Audio Player
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td align="left" class="txt_verse">'.$audio_biblia.'</td></tr></table>';
		
        // Display Bible Text Verse		
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="hovertable">';
		while ($row7 = mysql_fetch_array($bible_result7))  {
		       echo '<tr><td><p class="txt_verse"><sup class="txt_sup">'.$row7["versiculo"].'</sup> '.$row7["texto"].'</br>';
		       $verso .=  $row7["versiculo"].' '.$row7["texto"].' </p></td></tr>';
		}
		echo '</table>';
		
		// Share button
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="10"></td><td><br>';  ?>
<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
FB.init({appId: '387055054676186', status: true, cookie: true,
xfbml: true});
};
(function() {
var e = document.createElement('script'); e.async = true;
e.src = document.location.protocol +
'//connect.facebook.net/es_LA/all.js';
document.getElementById('fb-root').appendChild(e);
}());
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
$('#share_button').click(function(e){
e.preventDefault();
FB.ui(
{
method: 'feed',
name: '<? echo $bible_book.' '.$capitulo.' '.$nombre; ?>',
link: 'http://bendicion.net/biblia_y_concordancia.php?libro=<? echo $libro; ?>&capitulo=<? echo $capitulo; ?>&version=<? echo $version; ?>',
picture: '',
caption: '',
description: 'Biblia y Concordancia con Audio',
message: ''
});
});
});
</script>
<img src = "compartir_fb.png" id = "share_button">
		
	<?	echo '</td><td width="10"></td></tr></table></br>';
		
		// Display scroll to top button
		echo '<a href="#top"></a>';
    } // end if statement


	########################### Determine if ONLY the Book and chapter were sent with $_GET
    else if (isset($_GET['libro']) && !empty($_GET['libro']) && isset($_GET['capitulo']) && !empty($_GET['capitulo']) && !isset($_GET['versiculo']) && empty($_GET['versiculo']) && isset($_GET['version']) && !empty($_GET['version'])) {
		
        // If the user just sent info
        $libro     = $_GET['libro'];
        $capitulo  = $_GET['capitulo'];
		$version   = $_GET['version'];
		if ($version == "") {
		   $version = 'biblia_1960';
		   }
		require ("biblia_config.inc.php"); // connect to database
        //$table_name    = 'biblia_1960';
		$table_name    = $version;
        $bible_result7 = @mysql_query("SELECT * FROM $table_name WHERE libro='$libro' AND capitulo='$capitulo' ORDER BY versiculo ASC"); // Get Text Verse
        $bible_result8 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='10' AND versiculo='$libro'"); // Get Book Name
		$bible_result9 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name

        // Get Bible Version Name
		$row9 = mysql_fetch_array($bible_result9); 
		$nombre = $row9["texto"];

        // Get Bible Book Name
		$row8 = mysql_fetch_array($bible_result8);
		$bible_book = $row8["texto"];
		
		echo '</br>';

			$capitulo_prev = $capitulo - 1;
			$capitulo_next = $capitulo + 1;
			
			// Display Book Name and Chapter
			echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td><h1>'.$bible_book.' '.$capitulo.'</h1></td></tr></table>';
			
			// Display the Bible version name
			echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td class="txt_verse">'.$nombre.'</td></tr></table>';
	
	$libro_next = $libro+1;
	$libro_prev = $libro-1;
	
		// Switch Case Audio Player	depending on what Bible version is used
switch ($version)
{
case "biblia_1909":
		$audio_biblia = '<ul class="playlist">
  <li><a href="biblia_audio/'.$version.'/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' RV 1909">Escuchar '.$bible_book.' '.$capitulo.' Reina Valera 1909</a></li>
 </ul>';
  break;

case "biblia_1960":
		$audio_biblia = '<ul class="playlist">
  <li><a href="biblia_audio/'.$version.'/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' RV 1960">Escuchar '.$bible_book.' '.$capitulo.' Reina Valera 1960</a></li>
 </ul>';
  break;
  
case "biblia_kjv":
		$audio_biblia = '<ul class="playlist">
  <li><a href="biblia_audio/'.$version.'/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' KJV">Listen to '.$bible_book.' '.$capitulo.' King James Version</a></li>
 </ul>';
  break;
  
default:
		$audio_biblia = '<ul class="playlist">
  <li><a href="biblia_audio/biblia_1960/'.$libro.'/'.$libro.'_'.$capitulo.'.mp3" title="'.$bible_book.' '.$capitulo.' RV 1960">Escuchar '.$bible_book.' '.$capitulo.' Reina Valera 1960</a></li>
 </ul>';
}		
	////////////////////////////////////////////////////

	// Input button to get previous chapter
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
	
        // Display drop down menu to change versions
		echo '<td><form name="version_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<select name="version" size="1" class="boton" onchange="version_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
        echo '<input type="hidden" name="capitulo" value="'.$capitulo.'" />
		<input type="hidden" name="libro" value="'.$libro.'" />
	    </select></form></td><td width="5"></td>';	
		
    // Display previous button
	if ($capitulo > 1) {
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		 <input type="hidden" name="libro" value="'.$libro.'" />
		 <input type="hidden" name="capitulo" value="'.$capitulo_prev.'" />
		 <input type="hidden" name="version" value="'.$version.'" />
		 <input type="submit" value="<< Cap&iacute;tulo Anterior" class="boton" /></form></td><td width="5"></td>';
	}	
	
	// Display next chapter button
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
    <input type="hidden" name="libro" value="'.$libro.'" />
	<input type="hidden" name="capitulo" value="'.$capitulo_next.'" />
	<input type="hidden" name="version" value="'.$version.'" />
	<input type="submit" value="Cap&iacute;tulo Siguiente >>" class="boton" /></form></td><td width="5"></td>';
	
	// Display previous book button
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
	<input type="hidden" name="libro" value="'.$libro_prev.'" />
	<input type="hidden" name="capitulo" value="1" />
	<input type="hidden" name="version" value="'.$version.'" />
	<input type="submit" value="<< Libro Anterior" class="boton" /></form></td><td width="5"></td>';	
	
    // Display next book button
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
	<input type="hidden" name="libro" value="'.$libro_next.'" />
	<input type="hidden" name="capitulo" value="1" />
	<input type="hidden" name="version" value="'.$version.'" />
	<input type="submit" value="Libro Siguiente >>" class="boton" /></form></td><td width="5"></td>';
	
	// Display add paralelo button
	echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
    <input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
    <input type="hidden" name="version_left" value="biblia_1960">
    <input type="hidden" name="version_right" value="biblia_1909">
    <input type="hidden" name="paralelo" value="'.$libro.'" />
    <input type="submit" value="Ver en Paralelo" class="boton"></form></td><td width="5"></td>';
	
	// Display Font Size Change /////////////////////////
	echo '<td valign="top" width="30"><a href="javascript:decreaseFontSize();"><img src="biblia_archivos/img/disminuir.png" width="30" height="30" border="0"></a></td><td width="5"></td><td valign="top" width="30"><a href="javascript:increaseFontSize();"><img src="biblia_archivos/img/aumentar.png" width="30" height="30" border="0"></a></td></tr></table>';
		
		// Display Audio Player	
		echo $audio_biblia;
		
        // Display Bible Text Verse
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td align="left" class="txt_verse"><p class="txt_verse">';
		
		while ($row7 = mysql_fetch_array($bible_result7))  {
		echo '<b>'.$row7["versiculo"].'</b> '.$row7["texto"].'</br>';
		$verso .=  $row7["versiculo"].' '.$row7["texto"].' ';
		}
		echo '</p></td></tr><tr><td><br>';  ?>

<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
FB.init({appId: '387055054676186', status: true, cookie: true,
xfbml: true});
};
(function() {
var e = document.createElement('script'); e.async = true;
e.src = document.location.protocol +
'//connect.facebook.net/es_LA/all.js';
document.getElementById('fb-root').appendChild(e);
}());
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
$('#share_button').click(function(e){
e.preventDefault();
FB.ui(
{
method: 'feed',
name: '<? echo $bible_book.' '.$capitulo.' '.$nombre; ?>',
link: 'http://bendicion.net/biblia_y_concordancia.php?libro=<? echo $libro; ?>&capitulo=<? echo $capitulo; ?>&version=<? echo $version; ?>',
picture: '',
caption: '',
description: 'Biblia y Concordancia con Audio',
message: ''
});
});
});
</script>
<img src = "compartir_fb.png" id = "share_button">
		
	<?	echo '</td></tr></table></br>';
		
		// Display scroll to top button
		echo '<a href="#top"></a>';
    } // end if statement

    ########################### Determine if Paralelo was received
    else if (isset($_POST['paralelo']) && isset($_POST['paralelo_cap']) && isset($_POST['version_left']) && isset($_POST['version_right'])) {
		
        // If the user just sent info
		require ("biblia_config.inc.php"); // connect to database
		$libro    = $_POST['paralelo'];
		$capitulo = $_POST['paralelo_cap'];
		//$version_left_table = 'biblia_1960';
		//$version_right_table = 'biblia_1909';
		$left_table_name  = $_POST['version_left'];
		$right_table_name = $_POST['version_right'];
		
		// Query Results for Left Table
		$bible_result7  = @mysql_query("SELECT * FROM $left_table_name WHERE libro='$libro' AND capitulo='$capitulo' ORDER BY versiculo ASC"); // Get Text Verse
        $bible_result8 = @mysql_query("SELECT * FROM $left_table_name WHERE libro='0' AND capitulo='10' AND versiculo='$libro'"); // Get Book Name
		$bible_result9 = @mysql_query("SELECT * FROM $left_table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name
		
		// Query Results for Right Table
		$bible_result7b  = @mysql_query("SELECT * FROM $right_table_name WHERE libro='$libro' AND capitulo='$capitulo' ORDER BY versiculo ASC"); // Get Text Verse
        $bible_result8b = @mysql_query("SELECT * FROM $right_table_name WHERE libro='0' AND capitulo='10' AND versiculo='$libro'"); // Get Book Name
		$bible_result9b = @mysql_query("SELECT * FROM $right_table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name		
		
		// Get Bible Version Name for Left Table
		$row9 = mysql_fetch_array($bible_result9); 
		$nombre_left = $row9["texto"];

		// Get Bible Version Name for Right Table
		$row9b = mysql_fetch_array($bible_result9b); 
		$nombre_right = $row9b["texto"];

		// Get Bible Book Name
		echo '</br>';
		$bible_result28 = @mysql_query("SELECT * FROM nombres_de_libros WHERE libro='$libro'");
		$row28 = mysql_fetch_array($bible_result28);
		$bible_book = $row28["nombre"];
				
		//$row8 = mysql_fetch_array($bible_result8); 
		//$bible_book = $row8["texto"];		
		
		// Options Top Table
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td class="txt_form" align="center"><h1>'.$bible_book.' - Capítulo '.$capitulo.'</h1></td></tr></table>';
		
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>';
		
		// Button Remover Paralelo
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="libro" value="'.$libro.'" />
		<input type="hidden" name="capitulo" value="'.$capitulo.'" />
		<input type="hidden" name="version" value="'.$left_table_name.'">
		<input type="submit" value="Remover Paralelo" class="boton" /></form></td>
		<td width="5"></td>';

		// Button Agregar Paralelo
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
		<input type="hidden" name="version_izquierda" value="'.$left_table_name.'">
		<input type="hidden" name="version_derecha" value="'.$right_table_name.'">
		<input type="hidden" name="version_third" value="biblia_lbla">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
		<input type="submit" value="Agregar Paralelo" class="boton" /></form></td>
		<td width="5"></td>';
		
		// Button Libro Anterior
		if ($libro > 1) {
		$libro_prev = $libro - 1;
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo" value="'.$libro_prev.'" />
		<input type="hidden" name="paralelo_cap" value="1" />
	    <input type="hidden" name="version_right" value="'.$right_table_name.'">
		<input type="hidden" name="version_left" value="'.$left_table_name.'">
		<input type="submit" value="<< Libro Anterior" class="boton" /></form></td>
		<td width="5"></td>';			
		} // end if

		// Button Libro Siguiente
		$libro_next = $libro + 1;
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo" value="'.$libro_next.'" />
		<input type="hidden" name="paralelo_cap" value="1" />
		<input type="hidden" name="version_right" value="'.$right_table_name.'">
		<input type="hidden" name="version_left" value="'.$left_table_name.'">
		<input type="submit" value="Libro Siguiente >>" class="boton" /></form></td>
		<td width="5"></td>';
		
		// Button Capitulo Anterior	
		if ($capitulo > 1) {
		$capitulo_prev = $capitulo - 1;
		$capitulo_next = $capitulo + 1;
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
		<input type="hidden" name="paralelo_cap" value="'.$capitulo_prev.'" />
	    <input type="hidden" name="version_right" value="'.$right_table_name.'">
		<input type="hidden" name="version_left" value="'.$left_table_name.'">
		<input type="submit" value="<< Cap&iacute;tulo Anterior" class="boton" /></form></td>
		<td width="5"></td>';
		} // end if

		// Button Capitulo Siguiente
		$capitulo_next = $capitulo + 1;
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
		<input type="hidden" name="paralelo_cap" value="'.$capitulo_next.'" />
		<input type="hidden" name="version_right" value="'.$right_table_name.'">
		<input type="hidden" name="version_left" value="'.$left_table_name.'">
		<input type="submit" value="Cap&iacute;tulo Siguiente >>" class="boton" /></form></td>
		<td width="5"></td>';
		
		// Display Font Size Change /////////////////////////
		echo '<td valign="top" width="30"><a href="javascript:decreaseFontSize();"><img src="biblia_archivos/img/disminuir.png" width="30" height="30" border="0"></a></td><td width="5"></td><td valign="top" width="30"><a href="javascript:increaseFontSize();"><img src="biblia_archivos/img/aumentar.png" width="30" height="30" border="0"></a></td></tr></table>';

		// Display Table in half
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td valign="top" class="txt_verse">';
		
		########## Left version here
			
		// Display Book Name and Chapter along with the Bible version name
		//echo '</br><b>'.$bible_book.' '.$capitulo.'</b> - '.$nombre_left.'</br></br>';
		echo $nombre_left;

        // Display drop down menu to change versions on LEFT COLUMN
		echo '<form name="version_left_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<select name="version_left" size="1" class="boton" onchange="version_left_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
        echo '<input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
        <input type="hidden" name="version_right" value="'.$right_table_name.'">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
	    </select></form>';
		 
		// Display Bible Text Verse - Left Column
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="hovertable">';
		while ($row7 = mysql_fetch_array($bible_result7))  {
			echo '<tr><td><p class="txt_verse"><sup class="txt_sup">'.$row7["versiculo"].'</sup> '.$row7["texto"].'</br></p></td></tr>';
		}
		
		echo '</table></td>
		<td width="10"></td>
		<td width="1" bgcolor="#cecece"></td>
		<td width="10"></td>';
		
		########## Right version here
		echo '<td valign="top" class="txt_verse">';

		// Display Book Name and Chapter along with the Bible version name
		echo $nombre_right;
		
		// Display drop down menu to change versions on RIGHT COLUMN
		echo '<form name="version_right_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<select name="version_right" size="1" class="boton" onchange="version_right_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
        echo '<input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
        <input type="hidden" name="version_left" value="'.$left_table_name.'">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
	    </select></form>';

		// Display Bible Text Verse - Right Column
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="hovertable">';
		while ($row7b = mysql_fetch_array($bible_result7b))  {
			echo '<tr><td><p class="txt_verse"><sup class="txt_sup">'.$row7b["versiculo"].'</sup> '.$row7b["texto"].'</br></p></td></tr>';
        }
		echo '</table></td></tr></table></br>';
		
	} // end else if


    ########################### Determine if a Third Paralelo Column was received
    else if (isset($_POST['version_third']) && !empty($_POST['version_third'])) {
		
       // If the user just sent info
		require ("biblia_config.inc.php"); // connect to database
		$libro    = $_POST['paralelo'];
		$capitulo = $_POST['paralelo_cap'];
		$left_table_name  = $_POST['version_izquierda'];
		$right_table_name = $_POST['version_derecha'];
		$third_table_name = $_POST['version_third'];
		//$third_table_name = 'biblia_lbla';
		
		//echo 'libro is: '.$libro.'<br>';
		//echo 'capitulo is: '.$capitulo.'<br>';
		//echo 'left table name is: '.$left_table_name.'<br>';
		//echo 'right table name is: '.$right_table_name.'<br>';
		//echo 'third table name is: '.$third_table_name.'<br>';

		// Get Bible Book Name
		$bible_result28 = @mysql_query("SELECT * FROM nombres_de_libros WHERE libro='$libro'");
		$row28 = mysql_fetch_array($bible_result28);
		$bible_book = $row28["nombre"];
				
		//$bible_result8 = @mysql_query("SELECT * FROM $left_table_name WHERE libro='0' AND capitulo='10' AND versiculo='$libro'"); // Get Book Name
		//$row8 = mysql_fetch_array($bible_result8); 
		//$bible_book = $row8["texto"];
		
        // Display book and chapter name at the very top
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td class="txt_form" align="left"><br><h1>'.$bible_book.' - Capítulo '.$capitulo.'</h1></td></tr></table>';

		##### Display navigation menu
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>';
		
		// Button Remover Tercer Paralelo
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
		<input type="hidden" name="version_left" value="'.$left_table_name.'">
        <input type="hidden" name="version_right" value="'.$right_table_name.'">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
		<input type="submit" value="Remover Paralelo" class="boton" /></form></td>
		<td width="5"></td>';

		// Button Libro Anterior
		if ($libro > 1) {
		$libro_prev = $libro - 1;
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo" value="'.$libro_prev.'" />
		<input type="hidden" name="paralelo_cap" value="1" />
	    <input type="hidden" name="version_derecha" value="'.$right_table_name.'">
		<input type="hidden" name="version_izquierda" value="'.$left_table_name.'">
		<input type="hidden" name="version_third" value="'.$third_table_name.'">
		<input type="submit" value="<< Libro Anterior" class="boton" /></form></td>
		<td width="5"></td>';
		} // end if
		
		// Button Capitulo Anterior	
		if ($capitulo > 1) {
		$capitulo_prev = $capitulo - 1;
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
		<input type="hidden" name="paralelo_cap" value="'.$capitulo_prev.'" />
	    <input type="hidden" name="version_derecha" value="'.$right_table_name.'">
		<input type="hidden" name="version_izquierda" value="'.$left_table_name.'">
		<input type="hidden" name="version_third" value="'.$third_table_name.'">
		<input type="submit" value="<< Cap&iacute;tulo Anterior" class="boton" /></form></td>
		<td width="5"></td>';		
		} // end if
		
		// Button Capitulo Siguiente
		$capitulo_next = $capitulo + 1;
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
		<input type="hidden" name="paralelo_cap" value="'.$capitulo_next.'" />
	    <input type="hidden" name="version_derecha" value="'.$right_table_name.'">
		<input type="hidden" name="version_izquierda" value="'.$left_table_name.'">
		<input type="hidden" name="version_third" value="'.$third_table_name.'">
		<input type="submit" value="Cap&iacute;tulo Siguiente >>" class="boton" /></form></td>		
		<td width="5"></td>';
		
		// Button Libro Siguiente
		$libro_next = $libro + 1;
		echo '<td><form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<input type="hidden" name="paralelo" value="'.$libro_next.'" />
		<input type="hidden" name="paralelo_cap" value="1" />
	    <input type="hidden" name="version_derecha" value="'.$right_table_name.'">
		<input type="hidden" name="version_izquierda" value="'.$left_table_name.'">
		<input type="hidden" name="version_third" value="'.$third_table_name.'">		
		<input type="submit" value="Libro Siguiente >>" class="boton" /></form></td>
		<td width="5"></td>';
		
		// Display Font Size Change /////////////////////////
		echo '<td valign="top" width="30"><a href="javascript:decreaseFontSize();"><img src="biblia_archivos/img/disminuir.png" width="30" height="30" border="0"></a></td><td width="5"></td><td valign="top" width="30"><a href="javascript:increaseFontSize();"><img src="biblia_archivos/img/aumentar.png" width="30" height="30" border="0"></a></td></tr></table>';

		// Display the 3 tables
		echo '<br><table width="980" border="0" cellspacing="0" cellpadding="0"><tr>';

		##### Start Left Column Version Here
		// Get Bible Version Name for Left Column Version
		$bible_result9 = @mysql_query("SELECT * FROM $left_table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'");
		$row9 = mysql_fetch_array($bible_result9); 
		$nombre_left = $row9["texto"];
		echo '<td width="320" valign="top" class="txt_verse">';
		
		echo $nombre_left; // Display version name
		
        // Display drop down menu to change Left Column Version
		echo '<form name="version_left_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<select name="version_izquierda" size="1" class="boton" onchange="version_left_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
        echo '</select>
        <input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
        <input type="hidden" name="version_derecha" value="'.$right_table_name.'">
		<input type="hidden" name="version_third" value="'.$third_table_name.'">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
	    </form>';
		
		// Display Bible Text Verse - Left Column Version
		$bible_result7  = @mysql_query("SELECT * FROM $left_table_name WHERE libro='$libro' AND capitulo='$capitulo' ORDER BY versiculo ASC"); // Get Text Verse
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="hovertable">';
		while ($row7 = mysql_fetch_array($bible_result7))  {
			echo '<tr><td><p class="txt_verse"><sup class="txt_sup">'.$row7["versiculo"].'</sup> '.$row7["texto"].'</br></p></td></tr>';
		}
		echo '</table></td>'; // End Left Column Version
	    echo '<td width="5"></td><td width="1" bgcolor="#cecece"></td><td width="5"></td>';
		
		##### Start Middle Column Version Here
		// Get Bible Version Name for Middle Column Version
		$bible_result9b = @mysql_query("SELECT * FROM $right_table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name
		$row9b = mysql_fetch_array($bible_result9b); 
		$nombre_right = $row9b["texto"];	    
		echo '<td width="319" valign="top" class="txt_verse">';
		echo $nombre_right; // Display version name

        // Display drop down menu to change Middle Column Version
		echo '<form name="version_right_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<select name="version_derecha" size="1" class="boton" onchange="version_right_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
        echo '</select>
        <input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
        <input type="hidden" name="version_izquierda" value="'.$left_table_name.'">
		<input type="hidden" name="version_third" value="'.$third_table_name.'">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
	    </form>';
		
		// Display Bible Text Verse - Middle Column Version
		$bible_result10  = @mysql_query("SELECT * FROM $right_table_name WHERE libro='$libro' AND capitulo='$capitulo' ORDER BY versiculo ASC"); // Get Text Verse
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="hovertable">';
		while ($row10 = mysql_fetch_array($bible_result10))  {
			echo '<tr><td><p class="txt_verse"><sup class="txt_sup">'.$row10["versiculo"].'</sup> '.$row10["texto"].'</br></p></td></tr>';
		}
		echo '</table></td>';  // End Middle Column Version
		echo '<td width="5"></td><td width="1" bgcolor="#cecece"></td><td width="5"></td>';
		
		##### Start Right Column Version Here
		// Get Bible Version Name for Right Column Version
		$bible_result9c = @mysql_query("SELECT * FROM $third_table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name
		$row9c = mysql_fetch_array($bible_result9c); 
		$nombre_third = $row9c["texto"];
	    echo '<td width="319" valign="top" class="txt_verse">';
		
		echo $nombre_third; // Display version name
		
        // Display drop down menu to change versions Right Column Version
		echo '<form name="version_third_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<select name="version_third" size="1" class="boton" onchange="version_third_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
        echo '</select>
        <input type="hidden" name="paralelo_cap" value="'.$capitulo.'" />
        <input type="hidden" name="version_izquierda" value="'.$left_table_name.'">
		<input type="hidden" name="version_derecha" value="'.$right_table_name.'">
		<input type="hidden" name="paralelo" value="'.$libro.'" />
	    </form>';		

		// Display Bible Text Verse - Right Column Version
		$bible_result11  = @mysql_query("SELECT * FROM $third_table_name WHERE libro='$libro' AND capitulo='$capitulo' ORDER BY versiculo ASC"); // Get Text Verse
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="hovertable">';
		while ($row11 = mysql_fetch_array($bible_result11))  {
			echo '<tr><td><p class="txt_verse"><sup class="txt_sup">'.$row11["versiculo"].'</sup> '.$row11["texto"].'</br></p></td></tr>';
		}
		echo '</table></td>'; // End Right Column Version
        echo '</tr></table><br><br>';		

		
	} // end else if

    ########################### Determine if Concordancia was received
    else if (isset($_POST['palabras']) && !empty($_POST['palabras'])) {
        $palabras = stripslashes($_POST['palabras']); // If the user just sent info
		$version   = $_POST['version'];
		
		if ($version == "") {
			$version = 'biblia_1960';
		}
		
        // Save the search term in this varibale to be able to use it in the output
        //$palabras = "%" . $palabras . "%"; // Add wildcard
        require ("biblia_config.inc.php"); // Connect to the database
		$table_name    = $version;
		$search_text = @mysql_query("SELECT * FROM $table_name WHERE texto LIKE '%$palabras%' ORDER BY libro ASC");
        
        // Get Bible Version Name
		$bible_result5 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='0' AND versiculo='0'"); // Get Bible Version Name
		$row5 = mysql_fetch_array($bible_result5); 
	    $nombre = $row5["texto"];		
		
        // Display drop down menu to change versions
		echo '</br><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="10"></td>
		<td class="txt_verse">'.$nombre.'</td>
		<td align="left"><form name="version_column" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		<select name="version" size="1" class="txt_verse" onchange="version_column.submit();">
		<option value="" selected="selected" disabled="disabled">Cambiar Versi&oacute;n</option>';
        biblia_versiones();
        echo '<input type="hidden" name="palabras" value="'.$palabras.'" />
	    </select></form></td><td width="30%">&nbsp;</td>
		<td width="10"></td></tr></table>';

        // Loop for results
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="10"></td><td class="txt_verse">';
		
		// Function to highlight results
		function highlightStr($string, $word, $highlightColorValue) {
			// return $string if there is no highlight color or strings given, nothing to do.
			if (strlen($highlightColorValue) < 1 || strlen($string) < 1 || strlen($word) < 1) {
				return $string;
				}
				preg_match_all("/$word+/i", $string, $matches);
				if (is_array($matches[0]) && count($matches[0]) >= 1) {
					foreach ($matches[0] as $match) {
						$string = str_replace($match, '<span style="background-color:'.$highlightColorValue.';">'.$match.'</span>', $string);
						}
				}
				return $string;
			}		
		
		while ($row_search_text = mysql_fetch_array($search_text))  {
            $output    = $row_search_text["texto"];
            $libro     = $row_search_text["libro"];
            $capitulo  = $row_search_text["capitulo"];
            $versiculo = $row_search_text["versiculo"];
			$highlightColorValue = '#ffff00';
			
			// Call highlightStr function
			$output = highlightStr($output, $palabras, $highlightColorValue);
            
            // Find the name of the book by looking up the number
            $bible_result4 = @mysql_query("SELECT * FROM $table_name WHERE libro='0' AND capitulo='10' AND versiculo='$libro'");
			
            // Get Bible Book
			$row4 = mysql_fetch_array($bible_result4); 
			$bible_book = $row4["texto"];
            
            // Display search results
            echo $output.'&nbsp;<b>'.$bible_book.'&nbsp;'.$capitulo.':'.$versiculo.'</b></br>';

			// Input button to get a whole chapter
		    echo '<form class="bendicion-bible" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post">
		    <input type="hidden" name="libro" value="'.$libro.'" />
		    <input type="hidden" name="capitulo" value="'.$capitulo.'" />
			<input type="hidden" name="version" value="'.$version.'" />
		    <input type="submit" value="Ver '.$bible_book.' '.$capitulo.'" />
		    </form>';

            $count = $count + 1;
        } // end while loop
		
		echo '</td><td width="10"></td></tr>
        <tr><td width="10"></td><td class="txt_verse">Vers&iacute;culos encontrados: <b>' .$count. '</b></br></br></br></td><td width="10"></td></tr></table>';
    } // end else if

?>
</td>
  </tr>
</table>
</body>
</html>
