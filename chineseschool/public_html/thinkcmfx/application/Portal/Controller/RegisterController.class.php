<?php

namespace Portal\Controller;

use Common\Controller\HomebaseController;



class RegisterController extends HomebaseController

{

	public function index()

	{

		$this->display();

	}



	public function add_post()

	{

		$arr = D('Register');

		$data = I("post.");

		if(!sp_check_verify_code()){

    		$this->error("verification code error！");

    	} else {

    		if(!$arr->create($data)) {

				$this->error($arr->getError());

			} else {

				$data['sex'] = $data['sex']  ? '女':'男';

				$data['isshow'] = isset($data['isshow']) ? '否' : '是';

				unset($data['verify']);

				$arr->add($data);

				$this->success("Submitted Successfully！", "http://chinese-school.org.uk");

			}

    	}

		

	}



	// public function datalist()

	// {



	// 	$this->display();

	// }

}