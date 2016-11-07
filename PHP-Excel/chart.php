<?php 
	$dir=dirname(__FILE__);//查找当前脚本所在路径
	require $dir."/db.php";//引入mysql操作类文件
	require $dir."/PHPExcel/PHPExcel.php";//引入PHPExcel
	$db=new db($phpexcel);//实例化db类 连接数据库
	$objPHPExcel=new PHPExcel();//实例化PHPExcel类， 等同于在桌面上新建一个excel
	$objSheet=$objPHPExcel->getActiveSheet();//获得当前活动sheet
	/**本节课程代码编写开始**/
	$array=array(
			array("","一班","二班","三班"),
			array("不及格",20,30,40),
			array("良好",30,50,55),
			array("优秀",15,17,20)
	);//准备数据
	$objSheet->fromArray($array);//直接加载数组填充进单元格内
	//开始图表代码编写
	$labels=array(
		new PHPExcel_Chart_DataSeriesValues('String','Worksheet!$B$1',null,1),//一班
		new PHPExcel_Chart_DataSeriesValues('String','Worksheet!$C$1',null,1),//二班
		new PHPExcel_Chart_DataSeriesValues('String','Worksheet!$D$1',null,1),//三班
	);//先取得绘制图表的标签
	$xLabels=array(
		new PHPExcel_Chart_DataSeriesValues('String','Worksheet!$A$2:$A$4',null,3)//取得图表X轴的刻度
	);
	$datas=array(
		new PHPExcel_Chart_DataSeriesValues('Number','Worksheet!$B$2:$B$4',null,3),//取一班的数据
		new PHPExcel_Chart_DataSeriesValues('Number','Worksheet!$C$2:$C$4',null,3),//取二班的数据
		new PHPExcel_Chart_DataSeriesValues('Number','Worksheet!$D$2:$D$4',null,3)//取三班的数据
	);//取得绘图所需的数据

	$series=array(
		new PHPExcel_Chart_DataSeries(
			PHPExcel_Chart_DataSeries::TYPE_LINECHART,
			PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
			range(0,count($labels)-1),
			$labels,
			$xLabels,
			$datas
		)
	);//根据取得的东西做出一个图表的框架
	$layout=new PHPExcel_Chart_Layout();
	$layout->setShowVal(true);
	$areas=new PHPExcel_Chart_PlotArea($layout,$series);
	$legend=new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT,$layout,false);
	$title=new PHPExcel_Chart_Title("高一学生成绩分布");
	$ytitle=new PHPExcel_Chart_Title("value(人数)");
	$chart=new PHPExcel_Chart(
		'line_chart',
		$title,
		$legend,
		$areas,
		true,
		false,
		null,
		$ytitle
	);//生成一个图标
	$chart->setTopLeftPosition("A7")->setBottomRightPosition("K25");//给定图表所在表格中的位置

	$objSheet->addChart($chart);//将chart添加到表格中



	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');//生成excel文件
	$objWriter->setIncludeCharts(true);

	//$objWriter->save($dir."/export_1.xls");//保存文件
	browser_export('Excel2007','browser_chart.xlsx');//输出到浏览器
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
?>