<?php
 2   namespace Think\Model;//自己新建一个model类
 3   use Think\Model;
 4   class ExcelModel extends Model {
 5   protected $tableName = 'products_log';
 6   
 7   public function __construct() {
 8   
 9     /*导入phpExcel核心类 SPAPP_PATH为存放phpexcel路径的定义，在入口文件index.php定义*/
10   require_once SPAPP_PATH.'Core/Library/Vendor/PHPExcel/PHPExcel.php';
11   require_once SPAPP_PATH.'Core/Library/Vendor/PHPExcel/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls 
12   require_once SPAPP_PATH.'Core/Library/Vendor/PHPExcel/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式  
13   }
14 
15 
16 //导入excel内容转换成数组，import方法要用到 
17 public function import($filePath){
18   $this->__construct();
19   $PHPExcel = new \PHPExcel();//实例化，一定要注意命名空间的问题加\ 
20 
21   /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/ 
22   $PHPReader = new \PHPExcel_Reader_Excel2007(); 
23     if(!$PHPReader->canRead($filePath)){ 
24       $PHPReader = new \PHPExcel_Reader_Excel5(); 
25       if(!$PHPReader->canRead($filePath)){ 
26         echo 'no Excel'; 
27         return; 
28       } 
29     } 
30   
31   $PHPExcel = $PHPReader->load($filePath); 
32   $currentSheet = $PHPExcel->getSheet(0);  //读取excel文件中的第一个工作表
33   $allColumn = $currentSheet->getHighestColumn(); //取得最大的列号
34   $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
35   $erp_orders_id = array();  //声明数组
36   
37   /**从第二行开始输出，因为excel表中第一行为列名*/ 
38   for($currentRow = 2;$currentRow <= $allRow;$currentRow++){ 
39   
40       /**从第A列开始输出*/ 
41     for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){ 
42    //这部分注释不要，取出的数据不便于我们处理
43    // $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/
44    //  if($val!=''){
45    //  $erp_orders_id[] = $val;
46    //  }
47    //数据坐标 
48             $address = $currentColumn . $currentRow; 
49             //读取到的数据，保存到数组$arr中 
50             $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
51     /**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/ 
52     //echo iconv('utf-8','gb2312', $val)."\t"; 
53     
54   }  
55   }
56   return $data;
57 }