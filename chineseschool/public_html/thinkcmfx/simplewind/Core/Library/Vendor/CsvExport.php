<?php
/**
 * @Author: Hiber
 * @Date:   2016-11-17 10:12:16
 * @Last Modified time: 2016-11-17 15:04:34
 * 头标题样例,其中name代表头标题,value代表数据的下标,type为数据类型,目前支持string,类型为string的单元格将被添加"\t",以保正他不受EXCEL的影响
 *       $head=array(
 *          array('name'=>'编号','value'=>'pk_id'),
 *          array('name'=>'月份','value'=>'month','type'=>'string'),
 *          array('name'=>'业务单号','value'=>'bus_order_no'),
 *          array('name'=>'收费单号','value'=>'charge_order_no'),
 *          array('name'=>'医院名','value'=>'hname'),
 *          array('name'=>'科室名','value'=>'dname')
 *      );
 */
class CsvExport
{

	protected $base_path = DATA_PATH;    //临时存储文件路径


	public function __construct($base_path = '')
	{
		if (!empty($base_path)) {
			$this->base_path = $base_path;
		}
		if (!file_exists($this->base_path)) {
			mkdir($this->base_path,755,true);
		}
	}

	/**
	 * 导出CSV格式数据
	 *  @param $head array  头标题
	 *  @param $data array  需要导出的数据
	 *  @param $filename string 文件名
	 */
	public function export($head,$data,$filename='') 
	{
		$filename = $this->dealFileName($filename);
		$this->setHeader($filename);
		$path = $this->base_path.$filename;
		$fp = $this->getFileString($path);
		$this->setHead($fp,$head);
		$this->setData($fp,$head,$data);
		fclose($fp);
		$str = file_get_contents($path);
		echo $str;
		@unlink($path);
		exit();
	}

	/**
	 * 设置HTTP头信息
	 */
	private function setHeader($filename='')
	{
		if (empty($filename)) {
			$filename = date('Y-m-d',time());
		}
		header('Content-Type: application/vnd.ms-excel');

		header('Content-Disposition: attachment;filename="'.$filename.'"');

		header('Cache-Control: max-age=0');
		header('Cache-Control: must-revalidate');
	}


	private function getFileString($path)
	{
		if (empty($path)) {
			return false;
		}
		return fopen($path,'a');
	}

	/**
	 * 设置CSV头行导出标题
	 */
	public function setHead($fp,$head)
	{
		$head_title = $this->getHeadTitle($head);
		foreach ($head_title as $i => $v) {
		    // CSV的Excel支持GBK编码，一定要转换，否则乱码
		    $head_title[$i] = iconv('utf-8', 'gbk', $v);
		}
		fputcsv($fp, $head_title);
	}

	/**
	 * 设置CSV数据
	 */
	public function setData($fp,$head,$data)
	{
		$head_key = $this->getHeadKey($head);
		foreach ($data as $k => $list) {
			$rows = array();
		    foreach ($head_key as $v) {
		    	$str = '';
		    	if (isset($v['type']) && $v['type'] == 'string') {
		    		$str = "\t";
		    	}
		    	$rows[$v['value']] = isset($list[$v['value']])?iconv('utf-8', 'gbk', $list[$v['value']]).$str:'';
		    }
		    fputcsv($fp, $rows);
		}
	}
      
	/**
	 * 获取head中的标题
	 */
	private function getHeadTitle($head)
	{
		if (empty($head) || !is_array($head)) {
			return array();
		}
		$head_title = array();
		foreach ($head as $v) {
			$head_title[] = $v['name'];
		}
		return $head_title;
	}


	/**
	 * 获取head中的下标
	 */
	private function getHeadKey($head)
	{
		if (empty($head) || !is_array($head)) {
			return array();
		}
		$head_key = array();
		$i = 0;
		foreach ($head as $v) {
			$head_key[$i]['value'] = $v['value'];
			if (isset($v['type'])) {
				$head_key[$i]['type'] = $v['type'];
			}
			$i++;
		}
		return $head_key;
	}


	private function dealFileName($filename)
	{
		return $filename.'--'.mt_rand(1000,9999).'.csv';
	}

}