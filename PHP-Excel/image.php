<?php 
	$dir=dirname(__FILE__);//查找当前脚本所在路径
	require $dir."/db.php";//引入mysql操作类文件
	require $dir."/PHPExcel/PHPExcel.php";//引入PHPExcel
	$objPHPExcel=new PHPExcel();//实例化PHPExcel类， 等同于在桌面上新建一个excel
	$objSheet=$objPHPExcel->getActiveSheet();//获得当前活动sheet
	/**插入图片代码开始**/
	$objDrawing=new PHPExcel_WorkSheet_Drawing();//获得一个图片的操作对象
	$objDrawing->setPath($dir."/image/imooc.jpg");//加载图片路径
	$objDrawing->setCoordinates("F6");//设置图片插入位置的左上角坐标
	$objDrawing->setWidth(500);//设置插入图片的大小
	//$objDrawing->setHeight(100);
	//$objDrawing->setOffsetX(20)->setOffsetY(40);//设定单元格内偏移量
	$objDrawing->setWorkSheet($objSheet);//将图片插入到sheet
	/**代码结束**/
	/**添加丰富的文字块**/
	$objRichText=new PHPExcel_RichText();//获得一个文字块操作对象
	$objRichText->createText("慕课网(imooc)");//添加普通的文字 不能操作样式
	$objStyleFont=$objRichText->createTextRun("是国内最大的IT技能免费培训平台");//生成可以添加样式的文字块
	$objStyleFont->getFont()->setSize(16)->setBold(True)->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_GREEN));//加一些样式
	$objRichText->createText(",课程丰富多样");
	$objSheet->getCell("F4")->setValue($objRichText);//将文字块插入sheet里
	/**代码结束**/
	/**添加批注代码开始**/
	$objSheet->mergeCells("F4:N4");//合并单元格
	$objSheet->getComment("F4")->getText()->createTextRun("Van:\r\n慕课网\n\n时尚时尚最时尚");//添加批注
	/**代码结束**/

	/**超链接代码开始**/
	$objSheet->setCellValue("I3","慕课网");//添加文字
	$objSheet->getStyle("I3")->getFont()->setSize(16)->setBold(true)->setUnderline(true)->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLUE));//添加样式
	$objSheet->getCell("I3")->getHyperlink()->setUrl("http://www.imooc.com");//给文字加上链接地址
	/**代码结束**/

	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');//生成excel文件
	//$objWriter->save($dir."/export_1.xls");//保存文件
	browser_export('Excel2007','browser_excel03.xlsx');//输出到浏览器
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