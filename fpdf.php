<?php

namespace aw2\fpdf;

use \setasign\Fpdi;
use \setasign\fpdf;

set_time_limit(0);
date_default_timezone_set('UTC');
/**
$files = ['a.pdf','b.pdf'];
$savePath = '/path/to/file'
*/

\aw2_library::add_service('fpdf.files_merge','PDF file merge activity',['namespace'=>__NAMESPACE__]);
function files_merge($atts,$content=null,$shortcode=null){

	if(\aw2_library::pre_actions('all',$atts,$content,$shortcode)==false)return;
	extract( \aw2_library::shortcode_atts( array(
		'files'=>'',
		'output_file_path'=>''
		), $atts) );	

	$error=array('status'=>'error','message'=>'files array is empty');
	if(empty($files) || !(is_array($files)) ){
		return $error;
	}
	$error=array('status'=>'error','message'=>'output_file_path is missing');
	if($output_file_path==''){
		return $error;
	}

	$pdf = new Fpdi\Fpdi();

	foreach ($files as $file) {
		$path_info = pathinfo($file);
		if($path_info['extension'] == 'pdf'){
		    $pageCount = $pdf->setSourceFile($file);
		    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
		        $pdf->AddPage();
		        $pageId = $pdf->importPage($pageNo, '/MediaBox');
		        $s = $pdf->useTemplate($pageId, 10, 10, 200);
		    }
		}else if($path_info['extension'] == 'jpg' || $path_info['extension'] == 'jpeg' || $path_info['extension'] == 'png'){
			$pdf->AddPage();
			$pdf->Image($file,20,40,170,170);
		}
	}
	$pdf->Output($output_file_path, 'F');
}
