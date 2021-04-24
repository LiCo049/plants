<?php

namespace Portal\Controller;

use Common\Controller\AdminbaseController;



class DataController extends AdminbaseController

{

	protected $posts_model;

	

	function _initialize() {

		parent::_initialize();

		$this->posts_model =D('Register');

	}



	public function index()

	{

		$list = $this->_lists();	

		$this->display();

	}



	private function _lists(){

		

		

		$start_time=I('request.start_time');

		if(!empty($start_time)){

		    $where['date']=array(

		        array('EGT',$start_time)

		    );

		}

		

		$end_time=I('request.end_time');

		if(!empty($end_time)){

		    if(empty($where['date'])){

		        $where['date']=array();

		    }

		    array_push($where['date'], array('ELT',$end_time));

		}

		

			

		$list = $this->posts_model

		->where($where)->select();



		$this->assign("formget",array_merge($_GET,$_POST));

		$this->assign("list", $list);

		return $list;

	}



	public function onekeyprint()
	{
		$list = $this->_lists();
		vendor('CsvExport');
		$export = new \CsvExport();
		foreach($list as $k=>$v)
		{
			$list[$k]['sex'] = $v['sex']== '男' ? 'male' : 'female';
			$list[$k]['isshow'] = $v['isshow'] == '是' ? 'yes' : 'no';
		}
		$head = array(
			array('name'=>'ID', 'value'=>'id' ),
			array('name'=>'English_Name', 'value'=>'english_name', 'type'=>'string'),
			array('name'=>'Chinese_Name', 'value'=>'chinese_name', 'type'=>'string'),
			array('name'=>'Class', 'value'=>'class', 'type'=>'string'),
			array('name'=>'Gender', 'value'=>'sex', 'type'=>'string'),
			array('name'=>'Birthday', 'value'=>'birth_date', 'type'=>'string'),
			array('name'=>'Sibling', 'value'=>'sibling', 'type'=>'string'),
			array('name'=>'Address', 'value'=>'home_address', 'type'=>'string'),
			array('name'=>'Post_Code', 'value'=>'post_code', 'type'=>'string'),
			array('name'=>'Parents', 'value'=>'parent_name', 'type'=>'string'),
			array('name'=>'Phone_Number', 'value'=>'telphone', 'type'=>'string'),
			array('name'=>'Email', 'value'=>'email', 'type'=>'string'),
			array('name'=>'Emergency', 'value'=>'emergency', 'type'=>'string'),
			array('name'=>'Home_language', 'value'=>'daliy', 'type'=>'string'),
			array('name'=>'Doctor', 'value'=>'doctor', 'type'=>'string'),
			array('name'=>'Demand', 'value'=>'special', 'type'=>'string'),
			array('name'=>'Register_Date', 'value'=>'date', 'type'=>'string'),
			array('name'=>'Propaganda', 'value'=>'isshow', 'type'=>'string'),
		);

		$temp = $export->export($head, $list, '注册信息表');
	}
}