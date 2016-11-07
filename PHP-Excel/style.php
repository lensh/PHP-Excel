<?php 
	$dir=dirname(__FILE__);//查找当前脚本所在路径
	require $dir."/db.php";//引入mysql操作类文件
	require $dir."/PHPExcel/PHPExcel.php";//引入PHPExcel
	$db=new db($phpexcel);//实例化db类 连接数据库
	$objPHPExcel=new PHPExcel();//实例化PHPExcel类， 等同于在桌面上新建一个excel
	$objSheet=$objPHPExcel->getActiveSheet();//获得当前活动单元格
	//开始本节课代码编写
	$objSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置excel文件默认水平垂直方向居中
	$objSheet->getDefaultStyle()->getFont()->setSize(14)->setName("微软雅黑");//设置默认字体大小和格式
	$objSheet->getStyle("A2:Z2")->getFont()->setSize(20)->setBold(true);//设置第二行字体大小和加粗
	$objSheet->getStyle("A3:Z3")->getFont()->setSize(16)->setBold(true);//设置第三行字体大小和加粗
	$objSheet->getDefaultRowDimension()->setRowHeight(30);//设置默认行高
	$objSheet->getRowDimension(2)->setRowHeight(50);//设置第二行行高
	$objSheet->getRowDimension(3)->setRowHeight(40);//设置第三行行高
	$gradeInfo=$db->getAllGrade();//查询所有的年级
	$index=0;
	foreach($gradeInfo as $g_k=>$g_v){
		$gradeIndex=getCells($index*2);//获取年级信息所在列
		$objSheet->setCellValue($gradeIndex."2","高".$g_v['grade']);
		$classInfo=$db->getClassByGrade($g_v['grade']);//查询每个年级所有的班级
		foreach($classInfo as $c_k=>$c_v){
			$nameIndex=getCells($index*2);//获得每个班级学生姓名所在列位置
			$scoreIndex=getCells($index*2+1);//获得每个班级学生分数所在列位置
			$objSheet->mergeCells($nameIndex."3:".$scoreIndex."3");//合并每个班级的单元格
			$objSheet->getStyle($nameIndex."3:".$scoreIndex."3")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6fc144');//填充班级背景颜色
			$classBorder=getBorderStyle("445cc1");//获取班级边框样式代码
			$objSheet->getStyle($nameIndex."3:".$scoreIndex."3")->applyFromArray($classBorder);//设置每个班级的边框
			$info=$db->getDataByClassGrade($c_v['class'],$g_v['grade']);//查询每个班级的学生信息
			$objSheet->setCellValue($nameIndex."3",$c_v['class']."班");//填充班级信息	
			$objSheet->getStyle($nameIndex)->getAlignment()->setWrapText(true);//设置文字自动换行
			$objSheet->setCellValue($nameIndex."4","姓名\n换行")->setCellValue($scoreIndex."4","分数");	
			$objSheet->getStyle($scoreIndex)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);//设置某列单元格格式为文本格式
			$j=5;
			foreach($info as $key=>$val){
				$objSheet->setCellValue($nameIndex.$j,$val['username'])->setCellValue($scoreIndex.$j,$val['score']."21312321321321321321");//填充学生信息
				//$objSheet->setCellValue($nameIndex.$j,$val['username'])->setCellValueExplicit($scoreIndex.$j,$val['score']."12321321321321312",PHPExcel_Cell_DataType::TYPE_STRING);//填充学生信息
				$j++;
			}
			$index++;
		}
		$endGradeIndex=getCells($index*2-1);//获得每个年级的终止单元格
		$objSheet->mergeCells($gradeIndex."2:".$endGradeIndex."2");//合并每个年级的单元格
		$objSheet->getStyle($gradeIndex."2:".$endGradeIndex."2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('c1b644');//填充年级背景颜色
		$gradeBorder=getBorderStyle("c144b1");//获取年级边框样式代码
		$objSheet->getStyle($gradeIndex."2:".$endGradeIndex."2")->applyFromArray($gradeBorder);//设置每个年级的边框
	}


	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');//生成excel文件
	//$objWriter->save($dir."/export_1.xls");//保存文件
	browser_export('Excel5','browser_excel03.xls');//输出到浏览器
	$objWriter->save("php://output");


	function browser_export($type,$filename){
		if($type=="Excel5"){
				header('Content-Type: application/vnd.ms-excel');//告诉浏览器将要输出excel03文件
		}else{
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器数据excel07文件
		}
		header('Content-Disposition: attachment;filename="'.$filename.'"');//告诉浏览器将输出文件的名称
		header('Cache-Control: max-age=0');//禁止缓存
	}

/**
**根据下标获得单元格所在列位置
**/
	function getCells($index){
		$arr=range('A','Z');
		//$arr=array(A,B,C,D,E,F,G,H,I,J,K,L,M,N,....Z);
		return $arr[$index];
	}

/**
**获取边框样式代码
**/
	function getBorderStyle($color){
			$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THICK,
						'color' => array('rgb' => $color),
					),
				),
			);
			return $styleArray;
	}


?>